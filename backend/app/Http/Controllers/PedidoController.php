<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller {

    public function store(Request $request)
    {
        try {
            // 1. Começamos a conta do valor total com a taxa de entrega
            $valorTotal = $request->taxa_entrega;
            $itensComprados = [];
            $descricaoArray = [];

            // 2. Lemos a lista de itens que o frontend mandou e calculamos os preços
            foreach ($request->itens as $item) {
                // Busca o produto real no banco de dados
                $produto = \App\Models\Produto::find($item['id']);
                
                if (!$produto) {
                    return response()->json(['message' => "Produto ID {$item['id']} não encontrado"], 404);
                }

                // Soma no valor total da compra
                $valorTotal += ($produto->preco * $item['quantidade']);

                // Prepara os dados para salvar na nossa tabela pivot nova
                $itensComprados[$produto->id] = [
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $produto->preco
                ];

                // Monta aquela descrição em texto automaticamente (ex: "2x Arroz")
                $descricaoArray[] = $item['quantidade'] . 'x ' . $produto->nome;
            }

            // 3. Gera o código de segurança e cria o pedido
            $codigoAleatorio = (string) rand(1000, 9999);
            
            $pedido = \App\Models\Pedido::create([
                'user_id' => auth()->id(),
                'lojista_id' => $request->lojista_id,
                'valor_total' => $valorTotal, // <-- Agora o valor total é calculado por nós!
                'taxa_entrega' => $request->taxa_entrega,
                'endereco_entrega' => $request->endereco_entrega,
                'descricao' => implode(', ', $descricaoArray), // Junta os nomes com vírgula
                'forma_pagamento' => $request->forma_pagamento,
                'codigo_entrega' => $codigoAleatorio
            ]);

            // 4. Salva os produtos na tabela pivot (A MÁGICA!)
            $pedido->produtos()->attach($itensComprados);

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
                                         ->with('produtos')
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
                                        ->with('produtos')
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

    // A NOSSA NOVA FUNÇÃO ENTRA AQUI 
    public function atualizarStatus(Request $request, $id)
    {
        // 1. Valida se o front-end mandou o status
        $request->validate([
            'status' => 'required|string', 
        ]);

        try {
            // 2. Procura o pedido no banco de dados pelo ID
            $pedido = \App\Models\Pedido::findOrFail($id);

            // 3. Atualiza apenas o status e salva
            $pedido->status = $request->status;
            $pedido->save();

            return response()->json([
                'message' => 'Status do pedido atualizado com sucesso!',
                'pedido' => $pedido
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar o pedido.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}