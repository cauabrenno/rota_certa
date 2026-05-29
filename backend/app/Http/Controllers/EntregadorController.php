<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntregadorController extends Controller
{
    // === 1. DADOS DO PERFIL ===
    public function meuPerfil()
    {
        $usuarioAutenticado = auth()->user();
        
        // Busca os dados do entregador na tabela específica
        $dadosDoEntregador = DB::table('entregadores')
            ->where('entregadores.user_id', $usuarioAutenticado->id)
            ->orderBy('entregadores.id', 'desc')
            ->first();

        // Busca o pedido ativo ignorando status concluído ou cancelado, e omitindo o código de entrega
        $pedidoAtivoDoEntregador = DB::table('pedidos')
            ->select(
                'pedidos.id',
                'pedidos.user_id',
                'pedidos.lojista_id',
                'pedidos.entregador_id',
                'pedidos.valor_total',
                'pedidos.taxa_entrega',
                'pedidos.endereco_entrega',
                'pedidos.descricao',
                'pedidos.status',
                'pedidos.lat_entrega',
                'pedidos.lng_entrega',
                'pedidos.forma_pagamento',
                'pedidos.avaliacao_do_entregador_concluida',
                'pedidos.created_at',
                'pedidos.updated_at'
            )
            ->where(function($query) use ($dadosDoEntregador, $usuarioAutenticado) {
                if ($dadosDoEntregador) {
                    $query->where('pedidos.entregador_id', $dadosDoEntregador->id);
                }
                $query->orWhere('pedidos.entregador_id', $usuarioAutenticado->id);
            })
            ->whereNotIn('pedidos.status', ['finalizado', 'cancelado', 'entregue', 'Entregue'])
            ->first();

        // Formata os dados da loja para a navegação
        if ($pedidoAtivoDoEntregador) {
            $dadosDaLoja = DB::table('lojista')->where('lojista.id', $pedidoAtivoDoEntregador->lojista_id)->first();
            if ($dadosDaLoja) {
                $donoDaLoja = DB::table('users')->where('users.id', $dadosDaLoja->user_id)->first();
                $pedidoAtivoDoEntregador->loja = $donoDaLoja->name ?? 'Loja Parceira';
                $pedidoAtivoDoEntregador->loja_endereco = $dadosDaLoja->endereco ?? null;
            }
        }

        return response()->json([
            'nome' => $usuarioAutenticado->name,
            'avaliacao' => $dadosDoEntregador->avaliacao ?? 5.0, 
            'total_entregas' => $dadosDoEntregador->total_entregas ?? 0, 
            'saldo_semana' => $dadosDoEntregador->saldo ?? 0.00, 
            'ultimo_repasse' => 'Sem repasses recentes',
            'veiculo' => [
                'modelo' => $usuarioAutenticado->modelo_veiculo,
                'placa' => $usuarioAutenticado->placa_veiculo
            ],
            'pedido_ativo' => $pedidoAtivoDoEntregador,
            'debug_id_entregador' => $dadosDoEntregador->id ?? null,
            'debug_id_user' => $usuarioAutenticado->id
        ], 200);
    }

    public function atualizarVeiculo(Request $requisicao)
    {
        $requisicao->validate([
            'modelo_veiculo' => 'required|string|max:255',
            'placa_veiculo' => 'required|string|max:15',
        ]);

        $usuarioAutenticado = auth()->user();
        
        DB::table('users')
            ->where('users.id', $usuarioAutenticado->id)
            ->update([
                'modelo_veiculo' => $requisicao->modelo_veiculo,
                'placa_veiculo' => $requisicao->placa_veiculo
            ]);

        return response()->json([
            'message' => 'Veículo updated com sucesso!',
            'veiculo' => [
                'modelo' => $usuarioAutenticado->modelo_veiculo,
                'placa' => $usuarioAutenticado->placa_veiculo
            ]
        ], 200);
    }

    // === 2. LÓGICA DE BUSCA DE NOVAS CORRIDAS ===
    public function buscarPedidoDisponivel()
    {
        $pedidoDisponivel = DB::table('pedidos')
            ->join('lojista', 'pedidos.lojista_id', '=', 'lojista.id')
            ->join('users', 'lojista.user_id', '=', 'users.id')
            ->select(
                'pedidos.id',
                'pedidos.taxa_entrega',
                'pedidos.endereco_entrega',
                'pedidos.lat_entrega',
                'pedidos.lng_entrega',
                'users.name as nome_loja',
                'lojista.endereco as loja_endereco'
            )
            ->whereIn('pedidos.status', ['despachado', 'Despachado'])
            ->whereNull('pedidos.entregador_id')
            ->first();

        if (!$pedidoDisponivel) {
            return response()->json(['message' => 'Nenhuma corrida disponível no momento'], 404);
        }

        $objetoEndereco = json_decode($pedidoDisponivel->endereco_entrega);
        $textoDoEndereco = is_object($objetoEndereco) 
            ? "{$objetoEndereco->rua}, {$objetoEndereco->numero}" 
            : $pedidoDisponivel->endereco_entrega;

        return response()->json([
            'id' => $pedidoDisponivel->id,
            'loja' => $pedidoDisponivel->nome_loja,
            'loja_endereco' => $pedidoDisponivel->loja_endereco,
            'taxa_entrega' => $pedidoDisponivel->taxa_entrega,
            'endereco' => $textoDoEndereco,
            'endereco_entrega' => $pedidoDisponivel->endereco_entrega,
            'lat_entrega' => $pedidoDisponivel->lat_entrega, 
            'lng_entrega' => $pedidoDisponivel->lng_entrega  
        ], 200);
    }

    public function aceitarPedido($identificadorDoPedido)
    {
        try {
            $usuarioAutenticado = auth()->user();
            $dadosDoEntregador = DB::table('entregadores')->where('entregadores.user_id', $usuarioAutenticado->id)->first();

            if (!$dadosDoEntregador) {
                return response()->json(['message' => 'Perfil de entregador não encontrado.'], 403);
            }

            $pedidoAceito = DB::table('pedidos')->where('pedidos.id', $identificadorDoPedido)->first();

            if (!$pedidoAceito || $pedidoAceito->entregador_id !== null) {
                return response()->json(['message' => 'Pedido indisponível.'], 400);
            }

            DB::table('pedidos')
                ->where('pedidos.id', $identificadorDoPedido)
                ->update([
                    'entregador_id' => $dadosDoEntregador->id,
                    'status' => 'saiu'
                ]);

            return response()->json(['message' => 'Corrida aceita com sucesso!'], 200);

        } catch (\Exception $excecaoLancada) {
            return response()->json(['error' => $excecaoLancada->getMessage()], 500);
        }
    }
}