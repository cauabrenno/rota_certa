<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartaoController extends Controller
{
    public function index()
    {
        // Pega todos os cartões onde o user_id é o do cliente logado
        $cartoes = \App\Models\Cartao::where('user_id', auth()->id())->get();
        return response()->json($cartoes, 200);
    }

    public function store(Request $request)
    {
        try {
            $cartao = \App\Models\Cartao::create([
                'user_id' => auth()->id(),
                'numero_cartao' => $request->numero_cartao, // <-- Nomes certinhos batendo com o banco!
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

    public function destroy($id)
    {
        // Procura o cartão e garante que ele é do usuário logado antes de apagar
        \App\Models\Cartao::where('id', $id)->where('user_id', auth()->id())->delete();
        
        return response()->json(['message' => 'Cartão excluído com sucesso!'], 200);
    }
}