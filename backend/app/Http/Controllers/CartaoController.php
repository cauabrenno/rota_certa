<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartaoController extends Controller
{
    public function store(\Illuminate\Http\Request $request)
    {
        try {
            // Cria o cartão e atrela ao usuário logado
            $cartao = \App\Models\Cartao::create([
                'user_id' => auth()->id(),
                'numero_cartao' => $request->numero_cartao,
                'nome_impresso' => $request->nome_impresso,
                'validade' => $request->validade,
                'cvv' => $request->cvv
            ]);

            return response()->json([
                'message' => 'Cartão salvo com sucesso!',
                'cartao' => $cartao
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao salvar cartão.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
