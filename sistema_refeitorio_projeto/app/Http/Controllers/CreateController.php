<?php

namespace App\Http\Controllers;

use App\Models\tb_categoria;
use App\Models\tb_entrada;
use App\Models\tb_fornecedor;
use App\Models\tb_lote;
use App\Models\tb_marca;
use App\Models\tb_produto;
use App\Models\tb_saida;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class CreateController extends Controller
{
    public function novaCategoria(): View
    {
        return view('create.categoria-criar');
    }

    public function novaCategoriaSubmit(Request $request): View
    {

        $data = $request->validate(
            //validação
            [
                'text_name' => 'required|min:3|max:255',
            ],
            //mensagens de erro
            [
                'text_name.required' => 'O nome da categoria é obrigatório',
                'text_name.min' => 'O nome da categoria deve ter pelo menos :min caracteres',
                'text_name.max' => 'O nome da categoria deve ter no máximo :max caracteres'
            ]
        );

        $categoria = new tb_categoria();
        $categoria->nomeCategoria = $data['text_name'];
        $categoria->save();

        session()->flash('criacao_valida', 'Categoria criada com sucesso!');

        return view('create.categoria-criar');
    }

    public function novaMarca(): View
    {
        return view('create.marca-criar');
    }

    public function novaMarcaSubmit(Request $request): View
    {

        $data = $request->validate(
            //validação
            [
                'text_name' => 'required|min:3|max:255',
                'text_descricao' => 'required|min:3|max:300'
            ],
            //mensagens de erro
            [
                'text_name.required' => 'O nome da marca é obrigatório',
                'text_name.min' => 'O nome da marca deve ter pelo menos :min caracteres',
                'text_name.max' => 'O nome da marca deve ter no máximo :max caracteres',
                'text_descricao.required' => 'A descrição é obrigatório',
                'text_descricao.min' => 'A descrição deve ter pelo menos :min caracteres',
                'text_descricao.max' => 'A descrição deve ter no máximo :max caracteres'
            ]
        );

        $marca = new tb_marca();
        $marca->nomeMarca = $data['text_name'];
        $marca->descricao = $data['text_descricao'];
        $marca->save();

        session()->flash('criacao_valida', 'Marca criada com sucesso!');

        return view('create.marca-criar');
    }

    public function novoProduto(): View
    {
        return view('create/produto-criar');
    }

    public function novoProdutoSubmit(Request $request): View
    {
        $unidadesComPeso = ['bandeja', 'placa', 'pacote', 'sache', 'lata', 'pote', 'balde'];

        $data = $request->validate(
            [
                'text_name' => 'required|min:3|max:255',
                'text_tamanho' => 'required|integer|min:1',
                'text_qtdminima' => 'required|integer|min:1',
                'text_preco' => 'required|numeric|gt:0',
                'unidade' => 'required',
                // Valida o peso líquido APENAS se a unidade for uma das que exigem
                'peso_liquido' => [
                    function ($attribute, $value, $fail) use ($request, $unidadesComPeso) {
                        if (in_array(strtolower($request->unidade), $unidadesComPeso) && empty($value)) {
                            $fail('O peso líquido é obrigatório para essa unidade.');
                        }
                        if (!empty($value) && !is_numeric($value)) {
                            $fail('O peso líquido deve ser um número.');
                        }
                        if (!empty($value) && $value <= 0) {
                            $fail('O peso líquido deve ser maior que zero.');
                        }
                    }
                ]
            ],
            [
                'text_name.required' => 'O nome do produto é obrigatório',
                'text_name.min' => 'O nome do produto deve ter pelo menos :min caracteres',
                'text_name.max' => 'O nome do produto deve ter no máximo :max caracteres',
                'text_tamanho.required' => 'O tamanho do produto é obrigatório',
                'text_tamanho.integer' => 'O tamanho do produto deve ser um número inteiro',
                'text_tamanho.min' => 'O tamanho do produto deve ser no mínimo :min',
                'text_qtdminima.required' => 'A quantidade mínima deve ser preenchida',
                'text_qtdminima.integer' => 'A quantidade mínima deve ser um número inteiro',
                'text_qtdminima.min' => 'A quantidade mínima deve ser no mínimo :min',
                'text_preco.required' => 'O preço é obrigatório',
                'text_preco.numeric' => 'O preço deve ser um número',
                'text_preco.gt' => 'O preço deve ser maior que :gt',
                'unidade.required' => 'Deve se escolher uma opção',
            ]
        );

        $produto = new tb_produto();
        $produto->nomeProduto = $data['text_name'];
        $produto->tamanho = (int)$data['text_tamanho'];
        $produto->qtdMinima = (int)$data['text_qtdminima'];
        $produto->precoProduto = (float)$data['text_preco'];
        $produto->UnidadeMedida = $data['unidade'];
        $produto->ativoCategoria = 0;
        $produto->ativoMarca = 0;
        if (!empty($request->peso_liquido)) {
            $produto->pesoLiquido = (float)$request->peso_liquido;
        }

        $produto->save();

        session()->flash('criacao_valida', 'Produto criado com sucesso!');

        return view('create.produto-criar');
    }


    public function novoFornecedor(): View
    {

        return view('create.fornecedor-criar');
    }

    public function novoFornecedorSubmit(Request $request): View
    {

        $data = $request->validate(
            //validação
            [
                'text_name' => 'required|min:3|max:255',
                'text_cnpj' => 'required|min:14|max:14',
                'text_email' => 'required|email',
                'text_endereco' => 'required|min:3|max:500',
                'text_phone' => 'required|min:8|max:45',
                'text_num_empenho' => 'required'

            ],
            //mensagens de erro
            [
                'text_name.required' => 'O nome da categoria é obrigatório',
                'text_name.min' => 'O nome da categoria deve ter pelo menos :min caracteres',
                'text_name.max' => 'O nome da categoria deve ter no máximo :max caracteres',
                'text_cnpj.required' => 'O CNPJ é obrigatório',
                'text_cnpj.min' => 'O CNPJ deve ter :min números',
                'text_cnpj.max' => 'O CNPJ deve ter :max números ',
                'text_email.required' => 'O email é obrigatório',
                'text_email.email' => 'O email deve ser um email válido',
                'text_endereco.required' => 'O endereço é obrigatório',
                'text_endereco.min' => 'O endereço deve ter pelo menos :min caracteres',
                'text_endereco.max' => 'O endereço deve ter no máximo :max caracteres',
                'text_phone.required' => 'O telefone é obrigatório',
                'text_phone.min' => 'O telefone deve ter pelo menos :min números',
                'text_phone.max' => 'O telefone deve ter no máximo :max números',
                'text_num_empenho.required' => 'O numero de empenho é obrigatório',
            ]
        );

        $fornecedor = new tb_fornecedor();
        $fornecedor->nomeFornecedor = $data['text_name'];
        $fornecedor->cnpj = $data['text_cnpj'];
        $fornecedor->email = $data['text_email'];
        $fornecedor->endereco = $data['text_endereco'];
        $fornecedor->telefone = $data['text_phone'];
        $fornecedor->Num_Empenho = $data['text_num_empenho'];
        $fornecedor->save();

        session()->flash('criacao_valida', 'Fornecedor criado com sucesso!');

        return view('create.fornecedor-criar');
    }

    public function novaEntrada(): View
    {
        $produtos = DB::table('tb_produtos')
            ->leftJoin('tb_marca_produto', 'tb_produtos.idProdutos', '=', 'tb_marca_produto.produto_id')
            ->leftJoin('tb_marca', 'tb_marca_produto.marca_id', '=', 'tb_marca.idMarca')
            ->select(
                'tb_produtos.idProdutos',
                'tb_produtos.nomeProduto',
                'tb_produtos.tamanho',
                'tb_produtos.UnidadeMedida',
                'tb_marca.nomeMarca',
            )
            ->whereNull('tb_produtos.deleted_at')
            ->get()
            ->map(fn($item) => (array) $item)
            ->toArray();

        $fornecedor = tb_fornecedor::all()->whereNull('deleted_at')->toArray();

        return view('create.entrada-criar', ['dados' => [
            'produtos' => $produtos,
            'fornecedor' => $fornecedor
        ]]);
    }

    public function novaEntradaSubmit(Request $request)
    {
        $data = $request->validate(
            //validação
            [
                'text_datavalidade' => 'required|date',
                'text_dataentrada' => 'required|date',
                'text_notafiscal' => 'required|integer|min:1',
                'text_qtd' => 'required|integer|min:1',
                'id_produto' => 'required',
                'fornecedor' => 'required'
            ],
            //mensagens de erro
            [
                'text_datavalidade.required' => 'A data de validade é obrigatoria',
                'text_datavalidade.date' => 'A data de validade tem que ser uma data valida',
                'text_dataentrada.required' => 'A data de entrada é obrigatoria',
                'text_dataentrada.date' => 'A data de entrada tem que ser uma data valida',
                'text_notafiscal.required' => 'A nota fiscal é obrigatória',
                'text_notafiscal.integer' => 'A nota fiscal deve ser um número',
                'text_notafiscal.min' => 'A nota fiscal deve ser no minimo :min',
                'text_qtd.required' => 'A quantidade é obrigatória',
                'text_qtd.integer' => 'A quantidade deve ser um número',
                'text_qtd.min' => 'A quantidade deve ser no minimo :min',
                'id_produto.required' => 'Selecionar um produto é obrigatório',
                'fornecedor.required' => 'Selecionar um fornecedor é obrigatório',
            ]
        );

        $idproduto = $this->descriptografar($request->id_produto);
        $idfornecedor = $this->descriptografar($request->fornecedor);

        $lote = new tb_lote();
        $lote->idProdutos = $idproduto;
        $lote->qtdLote = $data['text_qtd'];
        $lote->dataValidade = $data['text_datavalidade'];
        $lote->save();

        $idlote = DB::table('tb_lotes')
            ->where('idProdutos', $idproduto)
            ->max('idLotes');

        $entrada = new tb_entrada();
        $entrada->idFornecedor = $idfornecedor;
        $entrada->idLotes = $idlote;
        $entrada->dataEntrada = $data['text_dataentrada'];
        $entrada->numeroNotaFiscal = $data['text_notafiscal'];
        $entrada->qtdEntrada = $data['text_qtd'];
        $entrada->save();

        $produto = tb_produto::find($idproduto);
        $produto->qtdEstoque = (int)$produto->qtdEstoque + (int)$data['text_qtd'];
        $produto->save();

        session()->flash('criacao_valida', 'Entrada registrada com sucesso!');

        return redirect()->route('novaEntrada');
    }

    public function novaSaida(): View
    {
        $produtos = DB::table('tb_produtos as p')
            ->leftJoin('tb_marca_produto as mp', 'p.idProdutos', '=', 'mp.produto_id')
            ->leftJoin('tb_marca as m', 'mp.marca_id', '=', 'm.idMarca')
            ->leftJoin('tb_lotes as l', function ($join) {
                $join->on('p.idProdutos', '=', 'l.idProdutos')
                    ->where('l.qtdLote', '>', 0);
            })
            ->select(
                'p.idProdutos',
                'p.nomeProduto',
                'p.tamanho',
                'p.UnidadeMedida',
                'm.nomeMarca',
                DB::raw('COALESCE(SUM(l.qtdLote), 0) as qtdTotalLotes'),
                DB::raw('MIN(l.dataValidade) as dataValidade')
            )
            ->whereNull('p.deleted_at')
            ->groupBy(
                'p.idProdutos',
                'p.nomeProduto',
                'p.tamanho',
                'p.UnidadeMedida',
                'm.nomeMarca'
            )
            ->havingRaw('qtdTotalLotes > 0')
            ->orderBy('p.nomeProduto', 'asc')
            ->get()
            ->map(fn($item) => (array) $item)
            ->toArray();

        return view('create.saida-criar', ['dados' => ['produtos' => $produtos]]);
    }


    public function novaSaidaSubmit(Request $request)
    {
        $data = $request->validate(
            //validação
            [
                'text_datasaida' => 'required|date',
                'destino' => 'required',
                'text_qtd' => 'required|integer|min:1',
                'id_produto' => 'required',
            ],
            //mensagens de erro
            [
                'text_datasaida.required' => 'A data de saida é obrigatoria',
                'text_datasaida.date' => 'A data de saida tem que ser uma data valida',
                'destino.required' => 'O destino é obrigatória',
                'text_qtd.required' => 'A quantidade é obrigatória',
                'text_qtd.integer' => 'A quantidade deve ser um número',
                'text_qtd.min' => 'A quantidade deve ser no minimo :min',
                'id_produto.required' => 'Selecionar um produto é obrigatório',
            ]
        );

        $idProduto = $this->descriptografar($request->id_produto);

        $produto = tb_produto::find($idProduto);

        if ($data['text_qtd'] > $produto->qtdEstoque) {
            session()->flash('criacao_falha', 'A quantidade retirada excede o estoque!');
            return redirect()->route('novaSaida');
        }

        $lotes = tb_Lote::where('idProdutos', $produto->idProdutos)
            ->where('qtdLote', '>', 0)
            ->orderBy('dataValidade', 'asc')
            ->get();

        $qtdNecessaria = (int) $data['text_qtd'];
        $qtdRetirada = 0;

        foreach ($lotes as $lote) {
            if ($qtdNecessaria <= 0) break;

            $retirar = min($qtdNecessaria, $lote->qtdLote);

            $saida = new tb_saida();
            $saida->idLotes = $lote->idLotes;
            $saida->dataSaida = $data['text_datasaida'];
            $saida->qtdSaida = $retirar;
            $saida->destino = $data['destino'];
            $saida->save();

            $lote->qtdLote -= $retirar;
            $lote->save();

            $qtdRetirada += $retirar;
            $qtdNecessaria -= $retirar;
        }

        if ($qtdNecessaria > 0) {
            session()->flash('criacao_falha', "Não há estoque suficiente para atender a quantidade solicitada.");
            return redirect()->route('novaSaida');
        }
        $produto->qtdEstoque -= $qtdRetirada;
        $produto->save();

        session()->flash('criacao_valida', 'Saída registrada com sucesso!');
        return redirect()->route('novaSaida');
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
