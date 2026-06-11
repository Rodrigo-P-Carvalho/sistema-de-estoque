<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ChecarPermissao
{
    /**
     * Manipula uma requisição de entrada.
     */
    public function handle(Request $request, Closure $next, string $permissao): Response
    {
        // 1. REGRA MASTER: Se for o ID 1 (Administrador Geral), tem acesso total automático
        if (Auth::id() === 1) {
            return $next($request);
        }

        // 2. Busca o perfil e as permissões do usuário logado
        $perfil = Auth::user()->perfil;

        // 3. Se ele tiver um perfil e a permissão solicitada estiver dentro do array de permissões, deixa passar
        if ($perfil && is_array($perfil->permissoes) && in_array($permissao, $perfil->permissoes)) {
            return $next($request);
        }

        // 4. Se não tiver permissão, barra o acesso
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'error' => 'Você não tem permissão para realizar esta ação.'], 403);
        }

        abort(403, 'Você não tem permissão para acessar esta página.');
    }
}