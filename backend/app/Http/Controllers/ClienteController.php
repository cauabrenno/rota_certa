<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function meusPontos()
    {
        // 1. Descobre quem é o usuário fazendo a requisição através do Token JWT
        $user = auth()->user();

        // 2. Vai lá na tabela de clientes e pega a carteira dele
        $cliente = DB::table('clientes')->where('user_id', $user->id)->first();

        // Se por acaso for um lojista ou admin tentando ver pontos, a gente barra
        if (!$cliente) {
            return response()->json([
                'message' => 'Apenas clientes participam do Clube RotaCerta.'
            ], 403);
        }

        // 3. Devolve os pontos lindamente pro frontend
        return response()->json([
            'nome' => $user->name,
            'pontos' => $cliente->pontos
        ], 200);
    }
}