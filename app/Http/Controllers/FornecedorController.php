<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    // Exibe a tela dividida com a lista de fornecedores
    public function index()
    {
        $fornecedores = Fornecedor::orderBy('created_at', 'desc')->get();

        return view('administracao.fornecedores.index', compact('fornecedores'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'cnpj' => preg_replace('/\D/', '', $request->cnpj),
            'telefone' => preg_replace('/\D/', '', $request->telefone),
        ]);

        $request->validate([
            'cnpj'     => 'nullable|unique:fornecedores,cnpj|max:14', 
            'telefone' => 'required_without:email|max:11', 
            'email'    => 'nullable|email|max:150',
        ], [
            'cnpj.unique' => 'Já existe um fornecedor cadastrado com este CNPJ.',
            'telefone.required_without' => 'Por favor, informe pelo menos um Telefone ou E-mail para contato.',
            'email.email' => 'O formato do e-mail digitado é inválido.'
        ]);

        // 3. Salva no banco com os dados que já foram limpos no passo 1
        Fornecedor::create([
            'razao_social'  => $request->razao_social,
            'nome_fantasia' => $request->nome_fantasia,
            'cnpj'          => $request->cnpj,
            'telefone'      => $request->telefone,
            'email'         => $request->email,
        ]);

        return redirect()->back()->with('sucesso', 'Fornecedor cadastrado com sucesso!');
    }
    public function edit($id)
    {
        $fornecedor = Fornecedor::findOrFail($id);
        return response()->json($fornecedor);
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'cnpj' => preg_replace('/\D/', '', $request->cnpj),
            'telefone' => preg_replace('/\D/', '', $request->telefone),
        ]);

        $request->validate([
            'cnpj'     => 'nullable|max:14|unique:fornecedores,cnpj,' . $id,
            'telefone' => 'required_without:email|max:11',
            'email'    => 'nullable|email|max:150',
        ], [
            'cnpj.unique' => 'Já existe outro fornecedor cadastrado com este CNPJ.',
            'telefone.required_without' => 'Por favor, informe pelo menos um Telefone ou E-mail para contato.',
            'email.email' => 'O formato do e-mail digitado é inválido.'
        ]);

        $fornecedor = Fornecedor::findOrFail($id);
        $fornecedor->update([
            'razao_social'  => $request->razao_social,
            'nome_fantasia' => $request->nome_fantasia,
            'cnpj'          => $request->cnpj,
            'telefone'      => $request->telefone,
            'email'         => $request->email,
        ]);

        return redirect()->back()->with('sucesso', 'Fornecedor atualizado com sucesso!');
    }
}