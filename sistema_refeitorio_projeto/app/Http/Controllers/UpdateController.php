<?php

namespace App\Http\Controllers;

use App\Models\tb_categoria;
use App\Models\tb_fornecedor;
use App\Models\tb_marca;
use App\Models\tb_produto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class UpdateController extends Controller
{
    public function atualizarCategoria($id) : View {
        $identificador = $this->descriptografar($id);

        $categoria = tb_categoria::find($identificador);
        $categoria = $categoria->toArray();
        return view('update.categoria-update',
        ['dados'=>[
            'categoria' => $categoria,
            'id' => $id
        ]]);
    }

    public function atualizarCategoriaSubmit(Request $request) {

        $data=$request->validate(
            //validação
            [
                'text_name' => 'required|min:3|max:255',
            ],
            //mensagens de erro
            [
                'text_name.required' => 'O nome da categoria é obrigatório',
                'text_name.min' => 'O nome da categoria deve ter pelo menos :min caracteres',
                'text_name.max' => 'O nome da categoria deve ter no máximo :max caracteres'
            ]);

        $identificador = $this->descriptografar($request->id);

        $categoria = tb_categoria::find($identificador);
        $categoria->nomeCategoria = $data['text_name'];
        $categoria->save();

        session()->flash('atualizacao_valida', 'Categoria foi atualizada com sucesso!');

        return redirect()->route('atualizarCategoria', ['id' => $request->id]);
    }

    public function atualizarMarca($id) : View {

        $identificador = $this->descriptografar($id);

        $marca = tb_marca::find($identificador);
        $marca = $marca->toArray();
        return view('update/marca-update',
        ['dados'=>[
            'marca' => $marca,
            'id' => $id
        ]]);

    }

    public function atualizarMarcaSubmit(Request $request){

        $data=$request->validate(
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
            ]);

        $identificador = $this->descriptografar($request->id);

        $marca = tb_marca::find($identificador);
        $marca->nomeMarca = $data['text_name'];
        $marca->descricao = $data['text_descricao'];
        $marca->save();

        session()->flash('atualizacao_valida', 'Marca atualizada com sucesso!');

        return redirect()->route('atualizarMarca', ['id' => $request->id]);
    }

    public function atualizarProduto($id) : View {
        $identificador = $this->descriptografar($id);

        $produto = tb_produto::find($identificador);
        $produto = $produto->toArray();
        return view('update.produto-update',
        ['dados'=>[
            'produto' => $produto,
            'id' => $id
        ]]);
    }

    public function atualizarProdutoSubmit(Request $request)
{

    $data = $request->validate(
        [
            'text_name' => 'required|min:3|max:255',
            'text_tamanho' => 'required|integer|min:1',
            'text_qtdminima' => 'required|integer|min:1',
            'text_preco' => 'required|numeric|gt:0',
            'unidade' => 'required',
            'peso_liquido' => 'nullable|numeric|min:1',
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
            'text_preco.gt' => 'O preço deve ser maior que zero',
            'unidade.required' => 'Deve escolher uma unidade de medida',
            'peso_liquido.numeric' => 'O peso líquido deve ser um número',
            'peso_liquido.min' => 'O peso líquido deve ser maior que zero',
        ]
    );

    $identificador = $this->descriptografar($request->id);

    $produto = tb_produto::findOrFail($identificador);
    $produto->nomeProduto = $data['text_name'];
    $produto->tamanho = (int) $data['text_tamanho'];
    $produto->qtdMinima = (int) $data['text_qtdminima'];
    $produto->precoProduto = (float) $data['text_preco'];
    $produto->UnidadeMedida = $data['unidade'];

    $unidadesQuePrecisamDePeso = ["bandeja", "placa", "pacote", "sache", "lata", "pote", "balde"];

    if (in_array($data['unidade'], $unidadesQuePrecisamDePeso)) {
        $produto->pesoLiquido = $data['peso_liquido'] ?? $produto->pesoLiquido;
    } else {
        $produto->pesoLiquido = null;
    }

    $produto->save();

    session()->flash('atualizacao_valida', 'Produto atualizado com sucesso!');
    return redirect()->route('atualizarProduto', ['id' => $request->id]);
    }

    public function atualizarFornecedor($id) : View {
        $identificador = $this->descriptografar($id);

        $fornecedor = tb_fornecedor::find($identificador);
        $fornecedor = $fornecedor->toArray();
        return view('update.fornecedor-update',
        ['dados'=>[
            'fornecedor' => $fornecedor,
            'id' => $id
        ]]);
    }

    public function atualizarFornecedorSubmit(Request $request) {
        $data=$request->validate(
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
            ]);

        $identificador = $this->descriptografar($request->id);

        $fornecedor = tb_fornecedor::find($identificador);
        $fornecedor->nomeFornecedor = $data['text_name'];
        $fornecedor->cnpj = $data['text_cnpj'];
        $fornecedor->email = $data['text_email'];
        $fornecedor->endereco = $data['text_endereco'];
        $fornecedor->telefone = $data['text_phone'];
        $fornecedor->Num_Empenho = $data['text_num_empenho'];
        $fornecedor->save();

        session()->flash('atualizacao_valida', 'Fornecedor atualizada com sucesso!');

        return redirect()->route('atualizarFornecedor', ['id' => $request->id]);
    }

    private function descriptografar($id){
        try{
            $id = Crypt::decrypt($id);
        }catch(DecryptException $e){
            return redirect()->route('home');
        }
        return $id;
    }
}
