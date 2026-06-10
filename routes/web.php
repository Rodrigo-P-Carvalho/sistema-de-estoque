<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\VendaController;
use App\Http\Controllers\FornecedorController;
use App\Models\Fornecedor;
use App\Models\Produto;
use App\Models\Venda;
use Carbon\Carbon;
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
        $totalProdutos = Produto::count();

        $estoqueBaixo = Produto::whereColumn('estoque', '<=', 'quantidade_minima')->count();

        $vendasMes = Venda::where('status', 'concluido')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');

        $vendasHoje = Venda::where('status', 'concluido')
            ->whereDate('created_at', Carbon::today())
            ->sum('total');

        return view('dashboard', compact('totalProdutos', 'estoqueBaixo', 'vendasMes', 'vendasHoje'));
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
        Route::get('/fornecedores', [FornecedorController::class, 'index'])->name('fornecedores.index');
        Route::post('/fornecedores/salvar', [FornecedorController::class, 'store'])->name('fornecedores.store');
        Route::get('/fornecedores/{id}/editar', [FornecedorController::class, 'edit']);
    Route::put('/fornecedores/{id}', [FornecedorController::class, 'update'])->name('fornecedores.update');
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