<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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
        return view('dashboard'); // Agora aponta para um arquivo blade!
    })->name('dashboard');

    Route::get('/usuarios', function () {
        return view('usuarios.index');
    })->name('usuarios.index');

    // 2. Tela de criação de usuário (A que criamos no passo anterior)
    Route::get('/usuarios/novo', function () {
        $perfis = Perfil::all();

        return view('usuarios.create', compact('perfis'));
    })->name('usuarios.create');

    Route::patch('/usuarios/primeira-senha', [UserController::class, 'salvarNovaSenha'])->name('usuarios.salvar-senha');

    Route::post('/usuarios/novo', [UserController::class, 'store'])->name('usuarios.store');

    Route::get('/usuarios/lista', [UserController::class, 'lista'])->name('usuarios.lista');

    // Rota de logout
    Route::post('/sair', [AuthController::class, 'sair'])->name('logout');

});