<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        return redirect()->route('usuarios.index');

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
}