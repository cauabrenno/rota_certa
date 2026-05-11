<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntregadorController extends Controller
{
    // === 1. DADOS DO PERFIL (COM FILTRO DE LOOP CORRIGIDO) ===
    public function meuPerfil()
    {
        $user = auth()->user();
        
        // Busca os dados do entregador na tabela específica
        $entregador = DB::table('entregadores')
            ->where('entregadores.user_id', $user->id)
            ->orderBy('entregadores.id', 'desc')
            ->first();

        // ✨ BUSCA BLINDADA: Agora ignora status de conclusão para evitar o loop no F5
        $pedidoAtivo = DB::table('pedidos')
            ->select('pedidos.*') // Adicionado Select All para não omitir colunas (lat/lng)
            ->where(function($query) use ($entregador, $user) {
                if ($entregador) {
                    $query->where('pedidos.entregador_id', $entregador->id);
                }
                $query->orWhere('pedidos.entregador_id', $user->id);
            })
            // ✨ Adicionado 'entregue' e 'Entregue' para o card não voltar após finalizar
            ->whereNotIn('pedidos.status', ['finalizado', 'cancelado', 'entregue', 'Entregue'])
            ->first();

        // Formata os dados da loja para a navegação do Vue
        if ($pedidoAtivo) {
            $loja = DB::table('lojista')->where('lojista.id', $pedidoAtivo->lojista_id)->first();
            if ($loja) {
                $donoLoja = DB::table('users')->where('users.id', $loja->user_id)->first();
                $pedidoAtivo->loja = $donoLoja->name ?? 'Loja Parceira';
                $pedidoAtivo->loja_endereco = $loja->endereco ?? null;
            }
        }

        return response()->json([
            'nome' => $user->name,
            'avaliacao' => $entregador->avaliacao ?? 5.0, 
            'total_entregas' => $entregador->total_entregas ?? 0, 
            'saldo_semana' => $entregador->saldo ?? 0.00, 
            'ultimo_repasse' => 'Sem repasses recentes',
            'veiculo' => [
                'modelo' => $user->modelo_veiculo,
                'placa' => $user->placa_veiculo
            ],
            'pedido_ativo' => $pedidoAtivo,
            'debug_id_entregador' => $entregador->id ?? null,
            'debug_id_user' => $user->id
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

    // === 2. LÓGICA DE BUSCA DE NOVAS CORRIDAS ===
    public function buscarPedidoDisponivel()
    {
        $pedido = DB::table('pedidos')
            ->join('lojista', 'pedidos.lojista_id', '=', 'lojista.id')
            ->join('users', 'lojista.user_id', '=', 'users.id')
            ->select(
                'pedidos.id',
                'pedidos.taxa_entrega',
                'pedidos.codigo_entrega',
                'pedidos.endereco_entrega',
                'pedidos.lat_entrega', // ✨ FALTAVA ISSO: Pega o GPS Real
                'pedidos.lng_entrega', // ✨ FALTAVA ISSO: Pega o GPS Real
                'users.name as nome_loja',
                'lojista.endereco as loja_endereco'
            )
            ->whereIn('pedidos.status', ['aceito', 'preparo', 'preparando', 'Aceito', 'Preparo', 'Preparando'])
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
            'loja_endereco' => $pedido->loja_endereco,
            'taxa_entrega' => $pedido->taxa_entrega,
            'codigo' => $pedido->codigo_entrega,
            'endereco' => $enderecoTexto,
            'endereco_entrega' => $pedido->endereco_entrega,
            'lat_entrega' => $pedido->lat_entrega, // ✨ Envia o GPS pro front
            'lng_entrega' => $pedido->lng_entrega  // ✨ Envia o GPS pro front
        ], 200);
    }

    public function aceitarPedido($id)
    {
        try {
            $user = auth()->user();
            $entregador = DB::table('entregadores')->where('entregadores.user_id', $user->id)->first();

            if (!$entregador) {
                return response()->json(['message' => 'Perfil de entregador não encontrado.'], 403);
            }

            $pedido = DB::table('pedidos')->where('pedidos.id', $id)->first();

            if (!$pedido || $pedido->entregador_id !== null) {
                return response()->json(['message' => 'Pedido indisponível.'], 400);
            }

            DB::table('pedidos')
                ->where('pedidos.id', $id)
                ->update([
                    'entregador_id' => $entregador->id,
                    'status' => 'saiu'
                ]);

            return response()->json(['message' => 'Corrida aceita com sucesso!'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}