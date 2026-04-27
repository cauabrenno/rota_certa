<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller {

    public function store(Request $request) {
        
        try {
            // Cria o pedido usando os nomes corretos das colunas novas
            $pedido = Pedido::create([
                'user_id' => $request->user_id, 
                'lojista_id' => $request->lojista_id, 
                'valor_total' => $request->valor_total,
                'taxa_entrega' => $request->taxa_entrega ?? 0, // Se não mandar taxa, fica 0
                'endereco_entrega' => $request->endereco_entrega, 
                'descricao' => $request->descricao,
                'status' => 'pendente'
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
}
