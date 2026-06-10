<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    // Exibe a tela dividida com a lista de fornecedores
    public function index(Request $request)
    {
        $search = $request->input('search');
        $searchNumeros = preg_replace('/\D/', '', $search);

        $searchLower = mb_strtolower($search);

        $fornecedores = Fornecedor::query()
            ->when($search, function ($query) use ($searchLower, $searchNumeros) {
                
                $query->where(function ($q) use ($searchLower, $searchNumeros) {

                    $q->whereRaw('LOWER(razao_social) LIKE ?', ["%{$searchLower}%"])
                    ->orWhereRaw('LOWER(nome_fantasia) LIKE ?', ["%{$searchLower}%"])
                    ->orWhereRaw('LOWER(email) LIKE ?', ["%{$searchLower}%"]);

                    if (!empty($searchNumeros)) {
                        $q->orWhere('cnpj', 'like', "%{$searchNumeros}%")
                        ->orWhere('telefone', 'like', "%{$searchNumeros}%");
                    }
                });
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('administracao.fornecedores.index', compact('fornecedores', 'search'));
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
            'edit_cnpj' => preg_replace('/\D/', '', $request->edit_cnpj),
            'edit_telefone' => preg_replace('/\D/', '', $request->edit_telefone),
        ]);

        $request->validate([
            'edit_cnpj'     => 'nullable|max:14|unique:fornecedores,cnpj,' . $id,
            'edit_telefone' => 'required_without:edit_email|max:11',
            'edit_email'    => 'nullable|email|max:150',
        ], [
            'edit_cnpj.unique' => 'Já existe outro fornecedor cadastrado com este CNPJ.',
            'edit_telefone.required_without' => 'Por favor, informe pelo menos um Telefone ou E-mail para contato.',
            'edit_email.email' => 'O formato do e-mail digitado é inválido.'
        ]);

        $fornecedor = Fornecedor::findOrFail($id);
        $fornecedor->update([
            'razao_social'  => $request->edit_razao_social,
            'nome_fantasia' => $request->edit_nome_fantasia,
            'cnpj'          => $request->edit_cnpj,
            'telefone'      => $request->edit_telefone,
            'email'         => $request->edit_email,
        ]);

        return redirect()->route('fornecedores.index')->with('sucesso', 'Fornecedor atualizado com sucesso!');
    }
}