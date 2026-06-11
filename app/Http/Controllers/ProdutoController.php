<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto; 
use App\Models\Veiculo;
use Illuminate\Support\Facades\DB;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $query = Produto::with('veiculos');

        // 1. BUSCA PRINCIPAL (Ignora Maiúsculas, Minúsculas e Acentos no Postgres)
        if ($request->filled('busca')) {
            $busca = $request->input('busca');
            $termo = "%{$busca}%";
            
            $query->where(function($q) use ($termo) {
                $q->whereRaw("unaccent(nome) ILIKE unaccent(?)", [$termo])
                ->orWhereRaw("unaccent(codigo_interno) ILIKE unaccent(?)", [$termo])
                ->orWhereRaw("unaccent(codigo_barras) ILIKE unaccent(?)", [$termo])
                ->orWhereRaw("unaccent(marca) ILIKE unaccent(?)", [$termo]);
            });
        }

        // 2. FILTRO POR APLICAÇÃO VEICULAR (Ignora Maiúsculas, Minúsculas e Acentos no Postgres)
        if ($request->filled('aplicacao')) {
            $aplicacao = $request->input('aplicacao');
            $palavras = explode(' ', $aplicacao);

            $query->whereHas('veiculos', function($q) use ($palavras) {
                foreach ($palavras as $palavra) {
                    $palavraLimpa = trim($palavra, '()');
                    
                    if (empty($palavraLimpa)) {
                        continue;
                    }

                    $termoPalavra = "%{$palavraLimpa}%";

                    $q->where(function($sub) use ($termoPalavra) {
                        $sub->whereRaw("unaccent(marca) ILIKE unaccent(?)", [$termoPalavra])
                            ->orWhereRaw("unaccent(modelo) ILIKE unaccent(?)", [$termoPalavra])
                            ->orWhereRaw("unaccent(CAST(ano AS TEXT)) ILIKE unaccent(?)", [$termoPalavra]);
                    });
                }
            });
        }

        // 3. Filtro de Estoque Crítico
        if ($request->input('estoque_baixo') == '1') {
            $query->whereColumn('estoque', '<=', 'quantidade_minima');
        }

        $produtos = $query->paginate(10);
        $todosVeiculos = Veiculo::all();

        return view('produtos.index', compact('produtos', 'todosVeiculos'));
    }

    public function update(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);
        
        // Atualiza os dados liberados no $fillable (ou $guarded)
        $produto->update($request->all());
        
        // Atualiza as tags de veículos
        $produto->veiculos()->sync($request->veiculos ?? []);

        return redirect()->back()->with('sucesso', 'Produto atualizado com sucesso!');
    }

    // Salva o novo produto no banco e recarrega a página
    public function store(Request $request)
    {
        // Cria a peça
        $produto = Produto::create($request->all());

        // <-- CORREÇÃO 3: Sincroniza as tags de veículos na hora de criar a peça
        if ($request->has('veiculos')) {
            $produto->veiculos()->sync($request->veiculos);
        }

        // Retorna para a mesma tela, recarregando os dados, e manda uma mensagem de sucesso
        return redirect()->back()->with('sucesso', 'Nova peça cadastrada com sucesso!');
    }
}   