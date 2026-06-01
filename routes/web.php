<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProdutoController;
use App\Models\Perfil;


Route::get('/', function () {
    return view('test');
});
Route::get('/login', function () {
    return view('auth.login'); // Aponta para resources/views/auth/login.blade.php
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



    Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index');

    // Rota de logout
    Route::post('/sair', [AuthController::class, 'sair'])->name('logout');

});