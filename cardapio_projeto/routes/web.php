<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExtraController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


Route::get('/', [MainController::class, 'ExibirCardapioGeral'])->name('ExibirCardapioGeral');
Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'Login'])->name('login');
        Route::post('/login', [AuthController::class, 'LoginSubmit'])->name('loginSubmit');
    });
    Route::middleware('auth')->group(function () {
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::match(['get', 'post'], '/alimentos', [MainController::class, 'ExibirAlimentos'])->name('home');
        Route::get('/criar_alimentos', [MainController::class, 'CriarAlimentos'])->name('CriarAlimentos');
        Route::get('/editar_alimentos/{id}', [MainController::class, 'EditarAlimento'])->name('EditarAlimento');
        Route::post('/editar_alimentos', [MainController::class, 'EditarAlimentoSubmit'])->name('EditarAlimentoSubmit');
        Route::get('/deletar_alimentos/{id}', [MainController::class, 'DeletarAlimentos'])->name('DeletarAlimento');
        Route::post('/deletar_alimentos', [MainController::class, 'DeletarAlimentosConfirm'])->name('DeletarAlimentosConfirm');
        Route::post('/criar_alimentos', [MainController::class, 'CriarAlimentosSubmit'])->name('CriarAlimentosSubmit');
        Route::get('/cardapios', [MainController::class, 'ExibirCardapios'])->name('ExibirCardapios');
        Route::get('/criar_cardapios', [MainController::class, 'CriarCardapios'])->name('CriarCardapios');
        Route::post('/criar_cardapios', [MainController::class, 'CriarCardapiosSubmit'])->name('CriarCardapiosSubmit');
        Route::get('/editar_cardapios/{id}', [MainController::class, 'EditarCardapio'])->name('EditarCardapios');
        Route::post('/editar_cardapios', [MainController::class, 'EditarCardapioSubmit'])->name('EditarCardapiosSubmit');
        Route::get('/ativarCardapio/{id}', [MainController::class, 'AtivarCardapio'])->name('AtivarCardapio');
    });
});
