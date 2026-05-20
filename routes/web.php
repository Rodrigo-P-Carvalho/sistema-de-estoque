<?php

use Illuminate\Support\Facades\Route;

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
        return '<h1>Bem-vindo ao estoque, ' . auth()->user()->name . '!</h1>
                <form action="/sair" method="POST">
                    '.csrf_field().'
                    <button type="submit">Sair do Sistema</button>
                </form>';
    })->name('dashboard');

    // Rota de logout
    Route::post('/sair', [AuthController::class, 'sair'])->name('logout');

});