<?php

namespace App\Http\Controllers;

use App\Models\tb_categoria;
use App\Models\tb_categoria_produto;
use App\Models\tb_fornecedor;
use App\Models\tb_lote;
use App\Models\tb_marca;
use App\Models\tb_marca_produto;
use App\Models\tb_produto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class MainController extends Controller
{
    public function registroitens(Request $request): View
    {
        $pesquisarCategoria = $request->query('pesquisarCategoria');
        $pesquisarMarca = $request->query('pesquisarMarca');
        $pesquisarFornecedor = $request->query('pesquisarFornecedor');
        $pesquisarProduto = $request->query('pesquisarProduto');

        $categorias = tb_categoria::select('idCategorias', 'nomeCategoria')
            ->whereNull('deleted_at')
            ->when($pesquisarCategoria, function ($query) use ($pesquisarCategoria) {
                $query->where('nomeCategoria', 'like', "%{$pesquisarCategoria}%");
            })
            ->get()
            ->toArray();

        $marcas = tb_marca::select('idMarca', 'nomeMarca', 'descricao')
            ->whereNull('deleted_at')
            ->when($pesquisarMarca, function ($query) use ($pesquisarMarca) {
                $query->where(function ($q) use ($pesquisarMarca) {
                    $q->where('nomeMarca', 'like', "%{$pesquisarMarca}%")
                        ->orWhere('descricao', 'like', "%{$pesquisarMarca}%");
                });
            })
            ->get()
            ->toArray();

        $produtos = tb_produto::with(['marcas', 'categorias'])
            ->whereNull('deleted_at')
            ->when($pesquisarProduto, function ($query) use ($pesquisarProduto) {
                $query->where(function ($q) use ($pesquisarProduto) {

                    $q->where('nomeProduto', 'like', "%{$pesquisarProduto}%")

                        ->orWhereHas('categorias', function ($catQuery) use ($pesquisarProduto) {
                            $catQuery->where('nomeCategoria', 'like', "%{$pesquisarProduto}%");
                        })

                        ->orWhereHas('marcas', function ($marcaQuery) use ($pesquisarProduto) {
                            $marcaQuery->where('nomeMarca', 'like', "%{$pesquisarProduto}%");
                        });
                });
            })
            ->get()
            ->toArray();

        $fornecedores = tb_fornecedor::whereNull('deleted_at')
            ->when($pesquisarFornecedor, function ($query) use ($pesquisarFornecedor) {
                $query->where(function ($q) use ($pesquisarFornecedor) {
                    $q->where('nomeFornecedor', 'like', "%{$pesquisarFornecedor}%")
                        ->orWhere('cnpj', 'like', "%{$pesquisarFornecedor}%");
                });
            })
            ->get()
            ->toArray();

        return view('home', [
            'dados' => [
                'categorias' => $categorias,
                'marcas' => $marcas,
                'produtos' => $produtos,
                'fornecedores' => $fornecedores,
            ],
            'pesquisarCategoria' => $pesquisarCategoria,
            'pesquisarMarca' => $pesquisarMarca,
            'pesquisarProduto' => $pesquisarProduto,
            'pesquisarFornecedor' => $pesquisarFornecedor,
        ]);
    }

    public function estoque(): View
    {
        $produtos = DB::table('tb_produtos')
            ->leftJoin('tb_marca_produto', 'tb_produtos.idProdutos', '=', 'tb_marca_produto.produto_id')
            ->leftJoin('tb_marca', 'tb_marca_produto.marca_id', '=', 'tb_marca.idMarca')
            ->leftJoin('tb_categoria_produto', 'tb_produtos.idProdutos', '=', 'tb_categoria_produto.produto_id')
            ->leftJoin('tb_categorias', 'tb_categoria_produto.categoria_id', '=', 'tb_categorias.idCategorias')
            ->select(
                'tb_produtos.idProdutos',
                'tb_produtos.nomeProduto',
                'tb_produtos.qtdEstoque',
                'tb_produtos.tamanho',
                'tb_produtos.pesoLiquido',
                'tb_produtos.UnidadeMedida',
                'tb_marca.nomeMarca',
                'tb_categorias.nomeCategoria'
            )
            ->whereNull('tb_produtos.deleted_at')
            ->orderBy('tb_produtos.nomeProduto', 'asc')
            ->get()
            ->map(fn($item) => (array) $item)
            ->toArray();

        $lotes = DB::table('tb_lotes')
            ->select(
                'tb_produtos.idProdutos',
                'tb_lotes.idLotes',
                'tb_lotes.qtdLote',
                'tb_lotes.dataValidade'
            )
            ->join('tb_produtos', 'tb_lotes.idProdutos', '=', 'tb_produtos.idProdutos')
            ->whereNull('tb_produtos.deleted_at')
            ->where('tb_lotes.qtdLote', '>', 0)
            ->get()
            ->map(fn($item) => (array) $item)
            ->toArray();

        $lotesPorProduto = [];
        foreach ($lotes as $lote) {
            $lotesPorProduto[$lote['idProdutos']][] = $lote;
        }

        foreach ($produtos as &$produto) {
            $produto['lotes'] = $lotesPorProduto[$produto['idProdutos']] ?? [];
        }
        unset($produto);

        return view('estoque', [
            'dados' => [
                'produtos' => $produtos
            ]
        ]);
    }


    public function movimentacao(Request $request): View
    {
        $pesquisarEntrada = $request->input('pesquisarEntrada');
        $pesquisarSaida = $request->input('pesquisarSaida');

        $entradas = DB::table('tb_entrada')
            ->join('tb_lotes', 'tb_entrada.idLotes', '=', 'tb_lotes.idLotes')
            ->join('tb_produtos', 'tb_lotes.idProdutos', '=', 'tb_produtos.idProdutos')
            ->join('tb_fornecedor', 'tb_entrada.idFornecedor', '=', 'tb_fornecedor.idFornecedor')
            ->leftJoin('tb_marca_produto', 'tb_produtos.idProdutos', '=', 'tb_marca_produto.produto_id')
            ->leftJoin('tb_marca', 'tb_marca_produto.marca_id', '=', 'tb_marca.idMarca')
            ->select(
                'tb_produtos.nomeProduto',
                'tb_produtos.tamanho',
                'tb_produtos.UnidadeMedida',
                'tb_produtos.pesoLiquido',
                'tb_fornecedor.nomeFornecedor',
                'tb_entrada.dataEntrada',
                'tb_entrada.qtdEntrada',
                'tb_entrada.numeroNotaFiscal',
                'tb_lotes.dataValidade',
                'tb_marca.nomeMarca'
            )
            ->when($pesquisarEntrada, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('tb_produtos.nomeProduto', 'like', "%{$search}%")
                        ->orWhere('tb_fornecedor.nomeFornecedor', 'like', "%{$search}%")
                        ->orWhere('tb_marca.nomeMarca', 'like', "%{$search}%")
                        ->orWhere('tb_entrada.numeroNotaFiscal', 'like', "%{$search}%");
                });
            })->whereNull('tb_produtos.deleted_at')
            ->get()
            ->map(fn($item) => (array) $item)
            ->toArray();

        $saidas = DB::table('tb_saidas')
            ->join('tb_lotes', 'tb_saidas.idLotes', '=', 'tb_lotes.idLotes')
            ->join('tb_produtos', 'tb_lotes.idProdutos', '=', 'tb_produtos.idProdutos')
            ->leftJoin('tb_marca_produto', 'tb_produtos.idProdutos', '=', 'tb_marca_produto.produto_id')
            ->leftJoin('tb_marca', 'tb_marca_produto.marca_id', '=', 'tb_marca.idMarca')
            ->select(
                'tb_produtos.nomeProduto',
                'tb_produtos.tamanho',
                'tb_produtos.pesoLiquido',
                'tb_produtos.UnidadeMedida',
                'tb_saidas.destino',
                'tb_saidas.dataSaida',
                'tb_saidas.qtdSaida',
                'tb_marca.nomeMarca'
            )->whereNull('tb_produtos.deleted_at')
            ->when($pesquisarSaida, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('tb_produtos.nomeProduto', 'like', "%{$search}%")
                        ->orWhere('tb_marca.nomeMarca', 'like', "%{$search}%")
                        ->orWhere('tb_saidas.qtdSaida', 'like', "%{$search}%");
                });
            })
            ->get()
            ->map(fn($item) => (array) $item)
            ->toArray();

        return view('movimentacao', [
            'dados' => [
                'entradas' => $entradas,
                'saidas' => $saidas
            ]
        ]);
    }

    public function relatorios(): View
    {
        return view('relatorios');
    }

    public function exibirCategoria($id): View
    {
        $idCategoria = $this->descriptografar($id);
        $categoria = tb_categoria::find($idCategoria)->toArray();
        $produtos = DB::table('tb_produtos')
            ->join('tb_categoria_produto', 'tb_produtos.idProdutos', '=', 'tb_categoria_produto.produto_id')
            ->where('tb_categoria_produto.categoria_id', $idCategoria)
            ->whereNull('tb_produtos.deleted_at')
            ->get()
            ->map(fn($item) => (array) $item)
            ->toArray();

        return view('associar.exibir-categoria-produto', ['dados' =>
        [
            'categoria' => $categoria,
            'produtos' => $produtos
        ]]);
    }

    public function exibirMarca($id): View
    {
        $idMarca = $this->descriptografar($id);
        $marca = tb_marca::find($idMarca)->toArray();
        $produtos = DB::table('tb_produtos')
            ->join('tb_marca_produto', 'tb_produtos.idProdutos', '=', 'tb_marca_produto.produto_id')
            ->where('tb_marca_produto.marca_id', $idMarca)
            ->whereNull('tb_produtos.deleted_at')
            ->get()
            ->map(fn($item) => (array) $item)
            ->toArray();

        return view('associar.exibir-marca-produto', ['dados' =>
        [
            'marca' => $marca,
            'produtos' => $produtos
        ]]);
    }

    public function exibirCategoriaSubmit(Request $request)
    {
        $idCategoria = $this->descriptografar($request->query('categoria'));
        $idProduto = $this->descriptografar($request->query('produto'));
        $categoria_produto = tb_categoria_produto::where('categoria_id', $idCategoria)
            ->where('produto_id', $idProduto)
            ->first();

        if ($categoria_produto) {
            $categoria_produto->delete();
            session()->flash('criacao_valida', 'Produto removido da categoria com sucesso!');
        } else {
            session()->flash('erro', 'Associação não encontrada.');
        }
        $produto = tb_produto::find($idProduto);
        $produto->ativoCategoria = 0;
        $produto->save();
        return redirect()->route('ExibirCategoria',  ['id' => Crypt::encrypt($idCategoria)]);
    }

    public function exibirMarcaSubmit(Request $request)
    {
        $idMarca = $this->descriptografar($request->query('marca'));
        $idProduto = $this->descriptografar($request->query('produto'));
        $marca_produto = tb_marca_produto::where('marca_id', $idMarca)
            ->where('produto_id', $idProduto)
            ->first();

        if ($marca_produto) {
            $marca_produto->delete();
            session()->flash('criacao_valida', 'Produto removido da marca com sucesso!');
        } else {
            session()->flash('erro', 'Associação não encontrada.');
        }

        $produto = tb_produto::find($idProduto);
        $produto->ativoMarca = 0;
        $produto->save();
        return redirect()->route('ExibirMarca',  ['id' => Crypt::encrypt($idMarca)]);
    }

    public function associarCategoria(): View
    {
        $categorias = tb_categoria::whereNull('deleted_at')->get()->toArray();
        $produtos = DB::table('tb_produtos')->whereNull('deleted_at')->where('ativoCategoria', '=', 0)->get()->map(fn($item) => (array) $item)->toArray();

        return view('associar.associar-categoria', ['dados' =>
        [
            'categorias' => $categorias,
            'produtos' => $produtos,
        ]]);
    }

    public function associarMarca(): View
    {
        $marcas = tb_marca::whereNull('deleted_at')->get()->toArray();
        $produtos = DB::table('tb_produtos')->whereNull('deleted_at')->where('ativoMarca', '=', 0)->get()->map(fn($item) => (array) $item)->toArray();
        return view('associar.associar-marca', ['dados' =>
        [
            'marcas' => $marcas,
            'produtos' => $produtos,
        ]]);
    }

    public function associarCategoriaSubmit(Request $request)
    {
        $data = $request->validate(
            //validação
            [
                'id_categoria' => 'required',
                'id_produto' => 'required'
            ],
            //mensagens de erro
            [
                'id_categoria.required' => 'Selecionar uma categoria é obrigatório',
                'id_produto.required' => 'Selecionar um produto é obrigatório',
            ]
        );

        $idCategoria = $this->descriptografar($request->id_categoria);
        $idProduto = $this->descriptografar($request->id_produto);

        $categoria_produto = new tb_categoria_produto();
        $categoria_produto->produto_id = $idProduto;
        $categoria_produto->categoria_id = $idCategoria;
        $categoria_produto->save();

        $produto = tb_produto::find($idProduto);
        $produto->ativoCategoria = 1;
        $produto->save();
        session()->flash('criacao_valida', 'O produto foi associado sucesso a categoria!');

        return redirect()->route('AssociarCategoria');
    }

    public function associarMarcaSubmit(Request $request)
    {
        $data = $request->validate(
            //validação
            [
                'id_marca' => 'required',
                'id_produto' => 'required'
            ],
            //mensagens de erro
            [
                'id_marcas.required' => 'Selecionar uma marca é obrigatório',
                'id_produto.required' => 'Selecionar um produto é obrigatório',
            ]
        );

        $idMarca = $this->descriptografar($request->id_marca);
        $idProduto = $this->descriptografar($request->id_produto);

        $marca_produto = new tb_marca_produto();
        $marca_produto->produto_id = $idProduto;
        $marca_produto->marca_id = $idMarca;
        $marca_produto->save();

        $produto = tb_produto::find($idProduto);
        $produto->ativoMarca = 1;
        $produto->save();

        session()->flash('criacao_valida', 'O produto foi associado sucesso a marca!');

        return redirect()->route('AssociarMarca');
    }

    private function descriptografar($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->route('home');
        }
        return $id;
    }

    private function descriptografarPesquisa($id)
    {
        try {
            $id = Crypt::decryptString($id);
        } catch (DecryptException $e) {
            return null;
        }
        return $id;
    }

    public function restaurarCategoria()
    {
        $categoria = tb_categoria::onlyTrashed()->get()->toArray();
        return view('associar.restaurarCategoria', ['dados' => ['categorias' => $categoria]]);
    }

    public function restaurarCategoriaSubmit(Request $request)
    {
        $idCategoria = $request->input('restaurarCategoria', []);
        if (empty($idCategoria)) {
            return redirect()->route('home');
        }

        foreach ($idCategoria as $id) {
            $categoria = tb_categoria::withTrashed()->find($id);
            if ($categoria) {
                $categoria->restore();
            }
        }

        return redirect()->route('restaurarCategoria');
    }
}
