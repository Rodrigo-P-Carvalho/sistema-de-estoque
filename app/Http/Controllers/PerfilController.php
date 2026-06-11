<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perfil;

class PerfilController extends Controller
{
    // Exibe a página principal com o formulário e a listagem
    public function index()
    {
        $perfis = Perfil::all();
        return view('administracao.perfis.index', compact('perfis'));
    }

    // Salva um novo perfil
    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string|max:50|unique:perfis,descricao', // limite de 50 do seu banco
            'permissoes' => 'nullable|array'
        ]);

        Perfil::create([
            'descricao' => $request->descricao,
            'permissoes' => $request->permissoes ?? []
        ]);
        return redirect()->back()->with('sucesso', 'Perfil criado com sucesso!');
    }

    // Exibe a página própria de edição
    public function edit($id)
    {
        // Proteção: Impede entrar na página de edição do ADM (ID 1) ou de si mesmo
        if ($id == 1 || $id == auth()->user()->perfil_id) {
            return redirect()->route('perfis.index')->with('erro', 'Este perfil está protegido e não pode ser editado.');
        }

        $perfil = Perfil::findOrFail($id);
        return view('administracao.perfis.edit', compact('perfil'));
    }

    // Atualiza os dados do perfil
    public function update(Request $request, $id)
    {
        // Proteção: Segunda barreira caso tentem burlar via requisição direta
        if ($id == 1 || $id == auth()->user()->perfil_id) {
            return redirect()->route('perfis.index')->with('erro', 'Ação não permitida para este perfil.');
        }

        $request->validate([
            'descricao' => 'required|string|max:50|unique:perfis,descricao,' . $id,
            'permissoes' => 'nullable|array'
        ]);

        $perfil->update([
            'descricao' => $request->descricao,
            'permissoes' => $request->permissoes ?? []
        ]);

        return redirect()->route('perfis.index')->with('sucesso', 'Perfil atualizado com sucesso!');
    }
}