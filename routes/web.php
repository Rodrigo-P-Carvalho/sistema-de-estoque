<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

    // Rota de logout
    Route::post('/sair', [AuthController::class, 'sair'])->name('logout');

});