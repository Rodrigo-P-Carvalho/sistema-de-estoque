<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto; 

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $query = Produto::query();

        if ($request->filled('busca')) {
            $busca = $request->busca;
            $query->where(function($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                  ->orWhere('codigo_interno', 'like', "%{$busca}%")
                  ->orWhere('codigo_barras', 'like', "%{$busca}%");
            });
        }

        if ($request->filled('aplicacao')) {
            $query->where('aplicacao_veicular', 'like', "%{$request->aplicacao}%");
        }

        if ($request->has('estoque_baixo')) {
            $query->whereColumn('estoque', '<=', 'quantidade_minima');
        }

        $produtos = Produto::whereHas('veiculos', function($query) {
             $query->where('veiculos.id', 5); 
         })->get();

        return view('produtos.index', compact('produtos'));
    }
}