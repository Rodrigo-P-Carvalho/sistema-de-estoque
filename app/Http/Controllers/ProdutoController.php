<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto; 

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $query = Produto::query();

        // 1. Filtro de Busca (Nome, Cód Interno ou Cód Barras)
        if ($request->filled('busca')) {
            $busca = $request->busca;
            $query->where(function($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                ->orWhere('codigo_interno', 'like', "%{$busca}%")
                ->orWhere('codigo_barras', 'like', "%{$busca}%");
            });
        }

        // 2. Filtro de Estoque Baixo
        if ($request->has('estoque_baixo')) {
            $query->whereColumn('estoque', '<=', 'quantidade_minima');
        }

        // Traz os resultados ordenados pelo nome e pagina de 10 em 10
        $produtos = $query->orderBy('nome')->paginate(10);

        return view('produtos.index', compact('produtos'));

    }
    public function update(Request $request, $id)
        {
        $produto = Produto::findOrFail($id);
        
        // Atualiza os dados liberados no $fillable (ou $guarded)
        $produto->update($request->all());

        return redirect()->back()->with('sucesso', 'Produto atualizado com sucesso!');
    }
    // Salva o novo produto no banco e recarrega a página
    public function store(Request $request)
    {
        // Opcional: Se quiser adicionar validação de dados antes de salvar, coloque aqui.
        
        Produto::create($request->all());

        // Retorna para a mesma tela, recarregando os dados, e manda uma mensagem de sucesso invisível
        return redirect()->back()->with('sucesso', 'Nova peça cadastrada com sucesso!');
    }
}