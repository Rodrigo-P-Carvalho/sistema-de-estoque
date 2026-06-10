<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Perfil;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Mail; 
use App\Mail\NovoUsuarioMail; 


class UserController extends Controller
{
    public function store(Request $request)
    {
        $dados = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'perfil_id' => ['required', 'exists:perfis,id'],
        ]);

        $senhaTemporaria = Str::random(8);

        $usuario = User::create([
            'name' => $dados['name'],
            'email' => $dados['email'],
            'perfil_id' => $dados['perfil_id'],
            'password' => Hash::make($senhaTemporaria),
            'primeiro_acesso' => true,
        ]);

        Mail::to($usuario->email)->send(new NovoUsuarioMail($usuario->name, $senhaTemporaria));

        return redirect()->route('administracao.index');

    }
    public function salvarNovaSenha(Request $request)
    {
        // 1. Valida se a senha tem 8 caracteres e se a confirmação bate
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.confirmed' => 'As senhas não são iguais.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.'
        ]);

        // 2. Pega o usuário que está logado
        $user = auth()->user();

        // 3. Atualiza a senha e desliga o aviso de primeiro acesso
        $user->update([
            'password' => Hash::make($request->password),
            'primeiro_acesso' => false
        ]);

        // 4. Redireciona para o próprio dashboard, mas agora o pop-up não vai mais aparecer!
        return redirect()->route('dashboard')->with('sucesso', 'Senha atualizada com sucesso!');
    }

    public function lista(Request $request)
    {
        // Começamos a consulta e já pedimos para trazer a tabela de perfis junto (Eager Loading)
        $query = User::with('perfil');

        // 1. SISTEMA DE PESQUISA INTELIGENTE (Ignora estritamente o Caps Lock)
        if ($request->filled('busca')) {
            // Converte o termo digitado para minúsculo, tratando caracteres especiais/acentos
            $buscaLower = mb_strtolower($request->busca);
            
            $query->where(function($q) use ($buscaLower) {
                // Força a coluna do banco de dados a ficar em minúsculo na hora de comparar
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$buscaLower}%"])
                ->orWhereRaw('LOWER(email) LIKE ?', ["%{$buscaLower}%"]);
            });
        }

        // 2. SISTEMA DE FILTRO (Se o usuário selecionou um perfil específico)
        if ($request->filled('perfil_id')) {
            $query->where('perfil_id', $request->perfil_id);
        }

        // Executamos a consulta trazendo os mais recentes primeiro
        // IMPORTANTE: .withQueryString() anexado para não perder a busca/filtro ao mudar de página
        $usuarios = $query->latest()->paginate(10)->withQueryString(); 
        
        // Pegamos todos os perfis para montar o campo do filtro
        $perfis = Perfil::all(); 

        return view('administracao.usuarios.lista', compact('usuarios', 'perfis'));
    }
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return response()->json($usuario);
    }

    // Salva as alterações do usuário
    public function update(Request $request, $id)
    {
        // Validação dos dados vindos do Modal
        $request->validate([
            'edit_name'      => 'required|string|max:255',
            'edit_email'     => 'required|email|max:255|unique:users,email,' . $id,
            'edit_perfil_id' => 'required|exists:perfis,id',
        ], [
            'edit_name.required'      => 'O nome do usuário é obrigatório.',
            'edit_email.required'     => 'O e-mail é obrigatório.',
            'edit_email.email'        => 'Digite um formato de e-mail válido.',
            'edit_email.unique'       => 'Este e-mail já está cadastrado para outro usuário.',
            'edit_perfil_id.required' => 'Selecione um perfil válido para o usuário.',
        ]);

        $usuario = User::findOrFail($id);
        $usuario->update([
            'name'      => $request->edit_name,
            'email'     => $request->edit_email,
            'perfil_id' => $request->edit_perfil_id,
        ]);

        return redirect()->back()->with('sucesso', 'Usuário atualizado com sucesso!');
    }
}