<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificaTipoUsuario
{
    public function handle(Request $request, Closure $next, $tipoExigido): Response
    {
        // 1. Pega o usuário logado
        $user = auth()->user();

        // 2. Se não tiver logado, ou se o 'tipo' for diferente do exigido, barrado!
        if (!$user || $user->tipo !== $tipoExigido) {
            return response()->json([
                'message' => 'Acesso Negado! Você não tem permissão para fazer isso.'
            ], 403); // 403 é o código oficial de "Proibido" na web
        }

        // 3. Se estiver tudo certo, deixa a requisição passar para o Controller
        return $next($request);
    }
}