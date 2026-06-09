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


    // Administração

    Route::prefix('administracao')->group(function () {
        Route::get('/', function () {
            return view('administracao.index');
        })->name('administracao.index');

        // usuarios
        Route::get('/usuarios/novo', function () {
            $perfis = Perfil::all();
            return view('administracao.usuarios.create', compact('perfis'));
        })->name('usuarios.create');

        Route::patch('/usuarios/primeira-senha', [UserController::class, 'salvarNovaSenha'])->name('usuarios.salvar-senha');
        Route::post('/usuarios/novo', [UserController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/lista', [UserController::class, 'lista'])->name('usuarios.lista');

        // fornecedores
        Route::get('/cadastrar-fornecedores', function () {
            return view('administracao.fornecedores.create');
        })->name('fornecedores.index');
    });




    // Produtos
    Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index');
    Route::put('/produtos/{id}', [App\Http\Controllers\ProdutoController::class, 'update'])->name('produtos.update');
    Route::post('/produtos', [App\Http\Controllers\ProdutoController::class, 'store'])->name('produtos.store');

    //Vendas

    Route::get('/vendas', [VendaController::class, 'exibirPagina'])->name('vendas.index');
    Route::get('/api/vendas', [VendaController::class, 'index']);
    Route::post('/api/vendas', [VendaController::class, 'store']);
    Route::post('/api/vendas/{id}/devolver', [VendaController::class, 'devolver']);

    // Rota de logout
    Route::post('/sair', [AuthController::class, 'sair'])->name('logout');

});