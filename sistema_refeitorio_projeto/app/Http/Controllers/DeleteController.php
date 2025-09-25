<?php

namespace App\Http\Controllers;

use App\Models\tb_categoria;
use App\Models\tb_fornecedor;
use App\Models\tb_marca;
use App\Models\tb_produto;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class DeleteController extends Controller
{

    public function deletarCategoriaConfirmar(Request $request)
    {
        $idCategoriaCripto = $request->input('itensCategoria', []);

        if (empty($idCategoriaCripto)) {
            return redirect()->route('home');
        }

        $idCategoria = array_map(function ($idCripto) {
            return $this->descriptografar($idCripto);
        }, $idCategoriaCripto);

        $categorias = tb_categoria::whereIn('idCategorias', $idCategoria)->get()->toArray();
        return view('delete.deletar-categoria', [
            'dados' => [
                'categorias' => $categorias,
                'idsSelecionados' => $idCategoriaCripto
            ]
        ]);
    }

    public function deletarProdutoConfirmar(Request $request)
    {
        $idProdutoCripto = $request->input('itensProduto', []);

        if (empty($idProdutoCripto)) {
            return redirect()->route('home');
        }

        $idProduto = array_map(function ($idCripto) {
            return $this->descriptografar($idCripto);
        }, $idProdutoCripto);
        $produtos = DB::table('tb_produtos')
            ->whereIn('idProdutos', $idProduto)
            ->leftJoin('tb_marca_produto', 'tb_produtos.idProdutos', '=', 'tb_marca_produto.produto_id')
            ->leftJoin('tb_marca', 'tb_marca_produto.marca_id', '=', 'tb_marca.idMarca')
            ->select('tb_produtos.idProdutos', 'tb_produtos.nomeProduto', 'tb_produtos.tamanho', 'tb_produtos.UnidadeMedida', 'tb_marca.nomeMarca')
            ->whereNull('tb_produtos.deleted_at')
            ->get()
            ->map(fn($item) => (array) $item)
            ->toArray();
        return view('delete.deletar-produto', [
            'dados' => [
                'produtos' => $produtos,
                'idsSelecionados' => $idProdutoCripto
            ]
        ]);
    }

    public function deletarMarcaConfirmar(Request $request)
    {
        $idMarcaCripto = $request->input('itensMarca', []);

        if (empty($idMarcaCripto)) {
            return redirect()->route('home');
        }

        $idMarca = array_map(function ($idCripto) {
            return $this->descriptografar($idCripto);
        }, $idMarcaCripto);

        $marcas = tb_marca::whereIn('idMarca', $idMarca)->get()->toArray();
        return view('delete.deletar-marca', [
            'dados' => [
                'marcas' => $marcas,
                'idsSelecionados' => $idMarcaCripto
            ]
        ]);
    }

    public function deletarFornecedorConfirmar(Request $request)
    {
        $idFornecedorCripto = $request->input('itensFornecedor', []);

        if (empty($idFornecedorCripto)) {
            return redirect()->route('home');
        }

        $idFornecedor = array_map(function ($idCripto) {
            return $this->descriptografar($idCripto);
        }, $idFornecedorCripto);

        $fornecedor = tb_fornecedor::whereIn('idFornecedor', $idFornecedor)->get()->toArray();
        return view('delete.deletar-fornecedor', [
            'dados' => [
                'fornecedores' => $fornecedor,
                'idsSelecionados' => $idFornecedorCripto
            ]
        ]);
    }

    public function deletarCategoria(Request $request)
    {
        $idCategoriaCripto = $request->input('itensCategoria', []);
        if (empty($idCategoriaCripto)) {
            return redirect()->route('home');
        }

        $idCategoria = array_map(function ($idCripto) {
            return $this->descriptografar($idCripto);
        }, $idCategoriaCripto);

        foreach ($idCategoria as $id) {
            DB::table('tb_produtos')
                ->join('tb_categoria_produto', 'tb_produtos.idProdutos', '=', 'tb_categoria_produto.produto_id')
                ->where('tb_categoria_produto.categoria_id', $id)
                ->update(['ativoCategoria' => 0]);
            DB::table('tb_categoria_produto')->where('categoria_id', $id)->delete();
            $categoria = tb_categoria::find($id);
            if ($categoria) {
                $categoria->delete();
            }
        }

        return redirect()->route('home');
    }

    public function deletarMarca(Request $request)
    {
        $idMarcaCripto = $request->input('itensMarcas', []);
        if (empty($idMarcaCripto)) {
            return redirect()->route('home');
        }

        $idMarca = array_map(function ($id) {
            return $this->descriptografar($id);
        }, $idMarcaCripto);

        foreach ($idMarca as $id) {
            DB::table('tb_produtos')
                ->join('tb_marca_produto', 'tb_produtos.idProdutos', '=', 'tb_marca_produto.produto_id')
                ->where('tb_marca_produto.marca_id', $id)
                ->update(['ativoMarca' => 0]);

            DB::table('tb_marca_produto')->where('marca_id', $id)->delete();

            $marca = tb_marca::find($id);
            if ($marca) {
                $marca->delete();
            }
        }

        return redirect()->route('home');
    }


    public function deletarProduto(Request $request)
    {
        $idProdutosCripto = $request->input('itensProdutos', []);
        if (empty($idProdutosCripto)) {
            return redirect()->route('home');
        }

        $idProduto = array_map(function ($idProduto) {
            return $this->descriptografar($idProduto);
        }, $idProdutosCripto);

        foreach ($idProduto as $id) {
            $produto = tb_produto::find($id);
            $produto->delete();
        }
        return redirect()->route('home');
    }
    public function deletarFornecedor(Request $request)
    {
        $idFornecedorCripto = $request->input('itensFornecedor', []);
        if (empty($idFornecedorCripto)) {
            return redirect()->route('home');
        }

        $idFornecedor = array_map(function ($idFornecedor) {
            return $this->descriptografar($idFornecedor);
        }, $idFornecedorCripto);

        foreach ($idFornecedor as $id) {
            $produto = tb_produto::find($id);
            $produto->delete();
        }
        return redirect()->route('home');
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
}
