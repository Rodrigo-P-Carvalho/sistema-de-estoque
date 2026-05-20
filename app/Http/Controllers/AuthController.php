<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function logar(Request $request)
    {
        // 1. Valida se o usuário preencheu os campos corretamente
        $credenciais = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Tenta fazer o login com as credenciais
        if (Auth::attempt($credenciais)) {
            // Previne falhas de segurança de sessão (Session Fixation)
            $request->session()->regenerate();

            // Redireciona para o painel principal (que vamos criar)
            return redirect()->intended('/dashboard');
        }

        // 3. Se a senha estiver errada, volta para a tela de login com um erro
        return back()->withErrors([
            'email' => 'E-mail ou senha incorretos.',
        ])->onlyInput('email'); // Mantém o e-mail preenchido na tela
    }

    /**
     * Faz o logout (sair do sistema)
     */
    public function sair(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}
