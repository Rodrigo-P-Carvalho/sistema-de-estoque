<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Veiculo;

class VeiculoController extends Controller
{
    public function store(Request $request)
    {
        // 1. Valida se preencheu a marca e o modelo (ano é opcional)
        $request->validate([
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'ano' => 'nullable|integer'
        ]);

        // 2. Salva no banco de dados
        Veiculo::create($request->all());

        // 3. Volta para a página de produtos com uma mensagem de sucesso
        return redirect()->back()->with('sucesso', 'Nova Aplicação/Veículo cadastrada com sucesso!');
    }
}