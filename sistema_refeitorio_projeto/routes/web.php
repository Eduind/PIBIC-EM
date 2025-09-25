<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CreateController;
use App\Http\Controllers\DeleteController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\RelatoriosController;
use App\Http\Controllers\UpdateController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'Login'])->name('login');
    Route::post('/login', [AuthController::class, 'LoginSubmit'])->name('loginSubmit');
});

Route::middleware('auth')->group(function () {

    #logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    #intermediario e relatorios
    Route::prefix('/relatorios')->group(function () {
        Route::prefix('/Excel')->group(function(){
            Route::get('/inventario/{produtos}',[RelatoriosController::class,'InventarioExport'])->name('InventarioExcel');
            Route::get('/movimentacao/{movimentacao}',[RelatoriosController::class,'MovimentacoesExport'])->name('MovimentacaoExcel');
            Route::get('/analise-vencimento/{produtos}',[RelatoriosController::class,'AnaliseVencimentoExport'])->name('VencidoExcel');
            Route::get('/nivel-preposicao/{produtos}',[RelatoriosController::class,'NivelReposicaoExport'])->name('NivelExcel');
            Route::get('/compra/{produtos}',[RelatoriosController::class,'ComprasExport'])->name('CompraExcel');
            Route::get('/financeiro/{produtos}',[RelatoriosController::class,'FinanceiroExport'])->name('FinanceiroExcel');
        });
        Route::prefix('/Pdf')->group(function(){
            Route::get('/inventario/{produtos}',[RelatoriosController::class,'InventarioPDF'])->name('InventarioPDF');
            Route::get('/movimentacao/{movimentacao}',[RelatoriosController::class,'MovimentacoesPDF'])->name('MovimentacaoPDF');
            Route::get('/analise-vencimento/{produtos}',[RelatoriosController::class,'AnaliseVencimentoPDF'])->name('VencidoPDF');
            Route::get('/nivel-preposicao/{produtos}',[RelatoriosController::class,'NivelReposicaoPDF'])->name('NivelPDF');
            Route::get('/compra/{produtos}',[RelatoriosController::class,'ComprasPDF'])->name('CompraPDF');
            Route::get('/financeiro/{produtos}',[RelatoriosController::class,'FinanceiroPDF'])->name('FinanceiroPDF');
        });
        Route::post('/intermediario',[RelatoriosController::class,'redirecionamento'])->name('intermediario');
        Route::get('/inventario',[RelatoriosController::class,'inventario'])->name('relatorioInventario');
        Route::get('/movimentacao/{data}',[RelatoriosController::class,'movimentacao'])->name('relatorioMovimentacao');
        Route::get('/analise-vencimento',[RelatoriosController::class,'analiseVencimento'])->name('relatorioVencido');
        Route::get('/nivel-preposicao',[RelatoriosController::class,'nivelReposicao'])->name('relatorioNivel');
        Route::match(['get', 'post'],'/compra/{data}',[RelatoriosController::class,'compras'])->name('relatorioCompra');
        Route::match(['get', 'post'],'/financeiro/{data}',[RelatoriosController::class,'financeiro'])->name('relatorioFinanceiro');
    });

    Route::get('/registroitens', [MainController::class, 'registroitens'])->name('home');
    Route::get('/estoque', [MainController::class, 'estoque'])->name('estoque');
    Route::get('/movimentacao', [MainController::class, 'movimentacao'])->name('movimentacao');
    Route::get('/relatorios', [MainController::class, 'relatorios'])->name('relatorios');

    Route::prefix('/registroitens')->group(function(){

        Route::prefix('/exibir')->group(function(){
            Route::get('/categoria/{id}', [MainController::class, 'exibirCategoria'])->name('ExibirCategoria');
            Route::get('/categoria', [MainController::class, 'exibirCategoriaSubmit'])->name('ExibirCategoriaSubmit');
            Route::get('/marca/{id}', [MainController::class, 'exibirMarca'])->name('ExibirMarca');
            Route::get('/marca', [MainController::class, 'exibirMarcaSubmit'])->name('ExibirMarcaSubmit');
        });

        Route::prefix('/associar')->group(function(){
            Route::get('/categoria', [MainController::class, 'associarCategoria'])->name('AssociarCategoria');
            Route::post('/categoria', [MainController::class, 'associarCategoriaSubmit'])->name('AssociarCategoriaSubmit');
            Route::get('/marca', [MainController::class, 'associarMarca'])->name('AssociarMarca');
            Route::post('/marca', [MainController::class, 'associarMarcaSubmit'])->name('AssociarMarcaSubmit');
        });

        #deleção itens
        Route::prefix('deletar')->group(function(){
            Route::post('/categoria', [DeleteController::class, 'deletarCategoria'])->name('deletarCategoria');
            Route::post('/marca', [DeleteController::class, 'deletarMarca'])->name('deletarMarca');
            Route::post('/produto', [DeleteController::class, 'deletarProduto'])->name('deletarProduto');
            Route::post('/fornecedor', [DeleteController::class, 'deletarFornecedor'])->name('deletarFornecedor');
            });


        Route::prefix('deletarConfirmacao')->group(function(){
            Route::post('/categoria', [DeleteController::class, 'deletarCategoriaConfirmar'])->name('deletarCategoriaConfirmar');
            Route::post('/marca', [DeleteController::class, 'deletarMarcaConfirmar'])->name('deletarMarcaConfirmar');
            Route::post('/produto', [DeleteController::class, 'deletarProdutoConfirmar'])->name('deletarProdutoConfirmar');
            Route::post('/fornecedor', [DeleteController::class, 'deletarFornecedorConfirmar'])->name('deletarFornecedorConfirmar');
            });

        #cofirmação de deleção
        #criação itens
        Route::prefix('novo')->group(function(){
            Route::get('/categoria', [CreateController::class, 'novaCategoria'])->name('novaCategoria');
            Route::post('/categoria', [CreateController::class, 'novaCategoriaSubmit'])->name('novaCategoriaSubmit');
            Route::get('/marca', [CreateController::class, 'novaMarca'])->name('novaMarca');
            Route::post('/marca', [CreateController::class, 'novaMarcaSubmit'])->name('novaMarcaSubmit');
            Route::get('/produto', [CreateController::class, 'novoProduto'])->name('novoProduto');
            Route::post('/produto', [CreateController::class, 'novoProdutoSubmit'])->name('novoProdutoSubmit');
            Route::get('/fornecedor', [CreateController::class, 'novoFornecedor'])->name('novoFornecedor');
            Route::post('/fornecedor', [CreateController::class, 'novoFornecedorSubmit'])->name('novoFornecedorSubmit');
        });

        #atualização de itens
        Route::prefix('atualizar')->group(function(){
            Route::get('/categoria/{id}', [UpdateController::class, 'atualizarCategoria'])->name('atualizarCategoria');
            Route::post('/categoria', [UpdateController::class, 'atualizarCategoriaSubmit'])->name('atualizarCategoriaSubmit');
            Route::get('/marca/{id}', [UpdateController::class, 'atualizarMarca'])->name('atualizarMarca');
            Route::post('/marca', [UpdateController::class, 'atualizarMarcaSubmit'])->name('atualizarMarcaSubmit');
            Route::get('/produto/{id}', [UpdateController::class, 'atualizarProduto'])->name('atualizarProduto');
            Route::post('/produto', [UpdateController::class, 'atualizarProdutoSubmit'])->name('atualizarProdutoSubmit');
            Route::get('/fornecedor/{id}', [UpdateController::class, 'atualizarFornecedor'])->name('atualizarFornecedor');
            Route::post('/fornecedor', [UpdateController::class, 'atualizarFornecedorSubmit'])->name('atualizarFornecedorSubmit');
        });
    });

    #movimentação de estoque
    Route::get('/movimentacao/entrada/nova', [CreateController::class, 'novaEntrada'])->name('novaEntrada');
    Route::post('/movimentacao/entrada/nova', [CreateController::class, 'novaEntradaSubmit'])->name('novaEntradaSubmit');
    Route::get('/movimentacao/saida/nova', [CreateController::class, 'novaSaida'])->name('novaSaida');
    Route::post('/movimentacao/saida/nova', [CreateController::class, 'novaSaidaSubmit'])->name('novaSaidaSubmit');

    Route::get('/restaurarCategoria',[MainController::class, 'restaurarCategoria'])->name('restaurarCategoria');
    Route::post('/restaurarCategoria',[MainController::class, 'restaurarCategoriaSubmit'])->name('restaurarCategoriaSubmit');
});
