<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntregadorController extends Controller
{
    // === 1. DADOS DO PERFIL (100% REAIS) ===
    public function meuPerfil()
    {
        $user = auth()->user();
        
        // Busca os dados específicos na tabela de entregadores
        $entregador = DB::table('entregadores')
            ->where('entregadores.user_id', $user->id)
            ->first();

        return response()->json([
            'nome' => $user->name,
            'avaliacao' => $entregador->avaliacao ?? 5.0, 
            'total_entregas' => $entregador->total_entregas ?? 0, 
            'saldo_semana' => $entregador->saldo ?? 0.00, 
            'ultimo_repasse' => 'Sem repasses recentes',
            'veiculo' => [
                'modelo' => $user->modelo_veiculo,
                'placa' => $user->placa_veiculo
            ]
        ], 200);
    }

    public function atualizarVeiculo(Request $request)
    {
        $request->validate([
            'modelo_veiculo' => 'required|string|max:255',
            'placa_veiculo' => 'required|string|max:15',
        ]);

        $user = auth()->user();
        
        DB::table('users')
            ->where('users.id', $user->id)
            ->update([
                'modelo_veiculo' => $request->modelo_veiculo,
                'placa_veiculo' => $request->placa_veiculo
            ]);

        return response()->json([
            'message' => 'Veículo atualizado com sucesso!',
            'veiculo' => [
                'modelo' => $user->modelo_veiculo,
                'placa' => $user->placa_veiculo
            ]
        ], 200);
    }

    // === 2. LÓGICA DE CORRIDAS (ANTENA AMPLIADA) ===
    
    public function buscarPedidoDisponivel()
    {
        // Agora busca pedidos em qualquer status de processamento da loja
        $pedido = DB::table('pedidos')
            ->join('lojista', 'pedidos.lojista_id', '=', 'lojista.id')
            ->join('users', 'lojista.user_id', '=', 'users.id')
            ->select(
                'pedidos.id',
                'pedidos.taxa_entrega',
                'pedidos.codigo_entrega',
                'pedidos.endereco_entrega',
                'users.name as nome_loja'
            )
            // ✨ Aceita múltiplos status para garantir que a chamada apareça
            ->whereIn('pedidos.status', ['aceito', 'preparo', 'preparando'])
            ->whereNull('pedidos.entregador_id')
            ->first();

        if (!$pedido) {
            return response()->json(['message' => 'Nenhuma corrida disponível no momento'], 404);
        }

        $enderecoObj = json_decode($pedido->endereco_entrega);
        $enderecoTexto = is_object($enderecoObj) 
            ? "{$enderecoObj->rua}, {$enderecoObj->numero}" 
            : $pedido->endereco_entrega;

        return response()->json([
            'id' => $pedido->id,
            'loja' => $pedido->nome_loja,
            'taxa_entrega' => $pedido->taxa_entrega,
            'codigo' => $pedido->codigo_entrega,
            'endereco' => $enderecoTexto
        ], 200);
    }

public function aceitarPedido($id)
{
    try {
        $user = auth()->user();

        // 1. Verificamos se o pedido existe e está disponível
        $pedido = DB::table('pedidos')->where('id', $id)->first();

        if (!$pedido) {
            return response()->json(['message' => 'Pedido não encontrado.'], 404);
        }

        if ($pedido->entregador_id !== null) {
            return response()->json(['message' => 'Este pedido já foi aceito por outro entregador.'], 400);
        }

        // 2. Fazemos o update ignorando timestamps se der erro
        DB::table('pedidos')
            ->where('id', $id)
            ->update([
                'entregador_id' => $user->id,
                'status' => 'saiu'
                // Removi o 'updated_at' para evitar erro caso a coluna não exista
            ]);

        return response()->json(['message' => 'Corrida aceita com sucesso!'], 200);

    } catch (\Exception $e) {
        // Isso aqui vai te mostrar o erro REAL no console do navegador agora
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}