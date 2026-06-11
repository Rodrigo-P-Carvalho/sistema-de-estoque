<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\VendaController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\VeiculoController;
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

// Grupo Geral de Autenticados
Route::middleware('auth')->group(function () {
    
    // Todos os usuários logados podem ver o Dashboard
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

    Route::patch('/administracao/usuarios/primeira-senha', [UserController::class, 'salvarNovaSenha'])->name('usuarios.salvar-senha');


    // ==========================================
    // MÓDULO DE ADMINISTRAÇÃO (Usuários e Fornecedores)
    // ==========================================
    Route::prefix('administracao')->middleware('permissao:administracao')->group(function () {

        Route::get('/', function () {
            return view('administracao.index');
        })->name('administracao.index');

        // Usuários
        Route::get('/novo-usuario', function () {
            $perfis = Perfil::all();
            return view('administracao.usuarios.create', compact('perfis'));
        })->name('usuarios.create');

        Route::post('/usuarios/novo', [UserController::class, 'store'])->name('usuarios.store');
        Route::get('/lista-usuarios', [UserController::class, 'lista'])->name('usuarios.lista');
        Route::get('/usuarios/{id}/editar', [UserController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{id}', [UserController::class, 'update'])->name('usuarios.update');

        // Fornecedores
        Route::get('/fornecedores', [FornecedorController::class, 'index'])->name('fornecedores.index');
        Route::post('/fornecedores/salvar', [FornecedorController::class, 'store'])->name('fornecedores.store');
        Route::get('/fornecedores/{id}/editar', [FornecedorController::class, 'edit']);
        Route::put('/fornecedores/{id}', [FornecedorController::class, 'update'])->name('fornecedores.update');

        Route::get('/perfis', [PerfilController::class, 'index'])->name('perfis.index');
        Route::post('/perfis', [PerfilController::class, 'store'])->name('perfis.store');
        Route::get('/perfis/{id}/editar', [PerfilController::class, 'edit'])->name('perfis.edit');
        Route::put('/perfis/{id}', [PerfilController::class, 'update'])->name('perfis.update');
    });


    // ==========================================
    // MÓDULO DE PRODUTOS
    // ==========================================
    Route::middleware('permissao:produtos')->group(function () {
        Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index');
        Route::put('/produtos/{id}', [ProdutoController::class, 'update'])->name('produtos.update');
        Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.store');
        Route::post('/veiculos/salvar', [VeiculoController::class, 'store'])->name('veiculos.store');
    });


    // ==========================================
    // MÓDULO DE VENDAS
    // ==========================================
    Route::middleware('permissao:vendas')->group(function () {
        Route::get('/vendas', [VendaController::class, 'exibirPagina'])->name('vendas.index');
        Route::get('/api/vendas', [VendaController::class, 'index']);
        Route::post('/api/vendas', [VendaController::class, 'store']);
        Route::post('/api/vendas/{id}/devolver', [VendaController::class, 'registrarDevolucao']);
    });


    // ==========================================
    // MÓDULO DE COMPRAS
    // ==========================================
    Route::middleware('permissao:compras')->group(function () {
        Route::get('/compras', [CompraController::class, 'index'])->name('compras.index');
        Route::post('/compras', [CompraController::class, 'store'])->name('compras.store');
        Route::get('/api/compras/listar', [CompraController::class, 'listarAPI']);
    });

    // Rota de logout
    Route::post('/sair', [AuthController::class, 'sair'])->name('logout');
});