<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller {

    public function store(Request $request) {
        
        try {

            $codigoAleatorio = (string) rand(1000, 9999);
            
            $pedido = Pedido::create([
                'user_id' => auth()->id(), 
                'lojista_id' => $request->lojista_id, 
                'valor_total' => $request->valor_total,
                'taxa_entrega' => $request->taxa_entrega ?? 0, // Se não mandar taxa, fica 0
                'endereco_entrega' => $request->endereco_entrega, 
                'descricao' => $request->descricao,
                'status' => 'pendente',
                'codigo_entrega' => $codigoAleatorio,
                'forma_pagamento' => $request->forma_pagamento
            ]);

            return response()->json([
                'message' => 'Pedido criado com sucesso!',
                'pedido' => $pedido
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar pedido',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function meusPedidos()
    {
        try {
            // 1. Descobre quem é o cliente logado através do Token
            $user = auth()->user();

            // 2. Vai no banco, pega só os pedidos dele e ordena do mais novo pro mais velho
            $pedidos = \App\Models\Pedido::where('user_id', $user->id)
                                         ->orderBy('created_at', 'desc')
                                         ->get();

            // 3. Devolve a lista para o frontend desenhar a tela
            return response()->json([
                'pedidos' => $pedidos
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar o histórico de pedidos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            // 1. Pega o usuário logado
            $user = auth()->user();

            // 2. Busca o pedido específico, mas garante que pertence a este usuário
            $pedido = \App\Models\Pedido::where('id', $id)
                                        ->where('user_id', $user->id)
                                        ->first();

            // Se o pedido não existir ou for de outra pessoa, bloqueia
            if (!$pedido) {
                return response()->json([
                    'message' => 'Pedido não encontrado ou não autorizado.'
                ], 404);
            }

            // 3. Devolve os dados do pedido (incluindo o status e o código de entrega)
            return response()->json($pedido, 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar o pedido',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
