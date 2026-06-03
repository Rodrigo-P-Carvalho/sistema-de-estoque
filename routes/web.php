<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\VendaController;
use App\Models\Perfil;


Route::get('/', function () {
    return view('auth.login');
});
Route::get('/login', function () {
    return view('auth.login'); 
})->name('login');

Route::post('/login', [AuthController::class, 'logar']);

Route::middleware('auth')->group(function () {
    
    // Rota do painel principal
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    // Usuarios
    Route::get('/usuarios', function () {
        return view('usuarios.index');
    })->name('usuarios.index');

    Route::get('/usuarios/novo', function () {
        $perfis = Perfil::all();

        return view('usuarios.create', compact('perfis'));
    })->name('usuarios.create');

    Route::patch('/usuarios/primeira-senha', [UserController::class, 'salvarNovaSenha'])->name('usuarios.salvar-senha');

    Route::post('/usuarios/novo', [UserController::class, 'store'])->name('usuarios.store');

    Route::get('/usuarios/lista', [UserController::class, 'lista'])->name('usuarios.lista');


    // Produtos
    Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index');

    //Vendas

    Route::get('/vendas', [VendaController::class, 'index'])->name('vendas.index');

    // Rota de logout
    Route::post('/sair', [AuthController::class, 'sair'])->name('logout');

});