<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\AvaliacaoDoEntregador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AvaliacaoDoEntregadorController extends Controller
{
    public function salvarAvaliacaoDoEntregador(Request $requisicao, $identificadorDoPedido)
    {
        $requisicao->validate([
            'nota_da_avaliacao' => 'required|integer|min:1|max:5',
            'motivos_da_avaliacao' => 'nullable|string',
            'comentarios_adicionais' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $pedido = Pedido::findOrFail($identificadorDoPedido);

            // Verifica se o pedido pertence ao usuário autenticado
            if ($pedido->user_id !== auth()->id()) {
                return response()->json([
                    'mensagem' => 'Este pedido não pertence a você.'
                ], 403);
            }

            // Verifica se o pedido está entregue
            if (strtolower($pedido->status) !== 'entregue') {
                return response()->json([
                    'mensagem' => 'Este pedido ainda não foi entregue.'
                ], 400);
            }

            // Verifica se já foi avaliado
            if ($pedido->avaliacao_do_entregador_concluida) {
                return response()->json([
                    'mensagem' => 'Este pedido já foi avaliado.'
                ], 400);
            }

            // Verifica se o entregador está associado
            if (!$pedido->entregador_id) {
                return response()->json([
                    'mensagem' => 'Este pedido não possui um entregador associado.'
                ], 400);
            }

            // Salva a avaliação
            AvaliacaoDoEntregador::create([
                'pedido_id' => $pedido->id,
                'entregador_id' => $pedido->entregador_id,
                'usuario_id' => auth()->id(),
                'nota_da_avaliacao' => $requisicao->nota_da_avaliacao,
                'motivos_da_avaliacao' => $requisicao->motivos_da_avaliacao,
                'comentarios_adicionais' => $requisicao->comentarios_adicionais,
            ]);

            // Atualiza o pedido como avaliado
            $pedido->avaliacao_do_entregador_concluida = true;
            $pedido->save();

            // Atualiza a média de avaliação do entregador na tabela entregadores
            $mediaDeAvaliacoes = AvaliacaoDoEntregador::where('entregador_id', $pedido->entregador_id)
                ->avg('nota_da_avaliacao');

            DB::table('entregadores')
                ->where('id', $pedido->entregador_id)
                ->update([
                    'avaliacao' => round($mediaDeAvaliacoes, 1)
                ]);

            DB::commit();

            return response()->json([
                'mensagem' => 'Avaliação do entregador salva com sucesso!'
            ], 200);

        } catch (\Exception $excecaoLancada) {
            DB::rollBack();
            return response()->json([
                'mensagem' => 'Erro ao salvar avaliação do entregador.',
                'erro' => $excecaoLancada->getMessage()
            ], 500);
        }
    }
}
