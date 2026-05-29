<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                'valor_total' => $valorTotal, 
                'taxa_entrega' => $request->taxa_entrega,
                'endereco_entrega' => $request->endereco_entrega,
                'lat_entrega' => $request->lat_entrega, // ✨ AQUI: Salvando Latitude
                'lng_entrega' => $request->lng_entrega, // ✨ AQUI: Salvando Longitude
                'descricao' => implode(', ', $descricaoArray), 
                'forma_pagamento' => $request->forma_pagamento,
                'codigo_entrega' => $codigoAleatorio
            ]);

            // 4. Salva os produtos na tabela pivot
            $pedido->produtos()->attach($itensComprados);

            // ✨ 5. Atualiza os pontos no Clube RotaCerta! ✨
            if ($request->has('pontos_ganhos') && $request->pontos_ganhos > 0) {
                $user = auth()->user();
                $user->pontos = $user->pontos + $request->pontos_ganhos;
                $user->save();
            }

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
            $usuarioAutenticado = auth()->user();

            $pedidos = \App\Models\Pedido::where('user_id', $usuarioAutenticado->id)
                                         ->with('produtos')
                                         ->orderBy('created_at', 'desc')
                                         ->get();

            // Buscar a logo, nome do lojista e nome do entregador para cada pedido
            $pedidosComLoja = $pedidos->map(function ($pedido) {
                $lojista = DB::table('lojista')
                    ->join('users', 'lojista.user_id', '=', 'users.id')
                    ->where('lojista.id', $pedido->lojista_id)
                    ->select('users.name as nome_loja', 'users.logo_loja')
                    ->first();

                $entregador = DB::table('entregadores')
                    ->join('users', 'entregadores.user_id', '=', 'users.id')
                    ->where('entregadores.id', $pedido->entregador_id)
                    ->select('users.name as nome_entregador')
                    ->first();

                $pedidoArray = $pedido->toArray();
                $pedidoArray['loja'] = [
                    'nome' => $lojista ? $lojista->nome_loja : 'Loja Parceira',
                    'logo' => $lojista ? \App\Helpers\FormatadorDeImagem::obterCaminhoCompletoDaImagem($lojista->logo_loja) : null,
                ];
                $pedidoArray['entregador'] = [
                    'nome' => $entregador ? $entregador->nome_entregador : 'Entregador Rota Certa',
                ];
                return $pedidoArray;
            });

            return response()->json([
                'pedidos' => $pedidosComLoja
            ], 200);

        } catch (\Exception $excecaoLancada) {
            return response()->json([
                'message' => 'Erro ao buscar o histórico de pedidos',
                'error' => $excecaoLancada->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $usuarioAutenticado = auth()->user();

            $pedido = \App\Models\Pedido::where('id', $id)
                                        ->where('user_id', $usuarioAutenticado->id)
                                        ->with('produtos')
                                        ->first();

            if (!$pedido) {
                return response()->json([
                    'message' => 'Pedido não encontrado ou não autorizado.'
                ], 404);
            }

            // Buscar a logo, nome do lojista e nome do entregador para o pedido
            $lojista = DB::table('lojista')
                ->join('users', 'lojista.user_id', '=', 'users.id')
                ->where('lojista.id', $pedido->lojista_id)
                ->select('users.name as nome_loja', 'users.logo_loja')
                ->first();

            $entregador = DB::table('entregadores')
                ->join('users', 'entregadores.user_id', '=', 'users.id')
                ->where('entregadores.id', $pedido->entregador_id)
                ->select('users.name as nome_entregador')
                ->first();

            $pedidoArray = $pedido->toArray();
            $pedidoArray['loja'] = [
                'nome' => $lojista ? $lojista->nome_loja : 'Loja Parceira',
                'logo' => $lojista ? \App\Helpers\FormatadorDeImagem::obterCaminhoCompletoDaImagem($lojista->logo_loja) : null,
            ];
            $pedidoArray['entregador'] = [
                'nome' => $entregador ? $entregador->nome_entregador : 'Entregador Rota Certa',
            ];

            return response()->json($pedidoArray, 200);

        } catch (\Exception $excecaoLancada) {
            return response()->json([
                'message' => 'Erro ao buscar o pedido',
                'error' => $excecaoLancada->getMessage()
            ], 500);
        }
    }

    public function atualizarStatus(Request $requisicao, $identificadorDoPedido)
    {
        $requisicao->validate([
            'status' => 'required|string', 
            'codigo_entrega' => 'nullable|string'
        ]);

        try {
            $pedido = \App\Models\Pedido::findOrFail($identificadorDoPedido);
            $statusAntigo = $pedido->status;
            
            // Se o novo status for entregue, valida o código de entrega
            if (strtolower($requisicao->status) === 'entregue') {
                if (!$requisicao->has('codigo_entrega') || $requisicao->codigo_entrega !== $pedido->codigo_entrega) {
                    return response()->json([
                        'message' => 'Código incorreto'
                    ], 400);
                }
            }

            $pedido->status = $requisicao->status;
            $pedido->save();

            // LÓGICA DA CARTEIRA: Se a corrida foi finalizada agora, paga o entregador!
            if (strtolower($requisicao->status) === 'entregue' && strtolower($statusAntigo) !== 'entregue') {
                if ($pedido->entregador_id) {
                    DB::table('entregadores')
                        ->where('id', $pedido->entregador_id)
                        ->increment('saldo', $pedido->taxa_entrega);
                    
                    // Incrementa o contador de entregas realizadas
                    DB::table('entregadores')
                        ->where('id', $pedido->entregador_id)
                        ->increment('total_entregas', 1);
                }
            }

            return response()->json([
                'message' => 'Status do pedido atualizado com sucesso!',
                'pedido' => $pedido
            ], 200);

        } catch (\Exception $excecaoLancada) {
            return response()->json([
                'message' => 'Erro ao atualizar o pedido.',
                'error' => $excecaoLancada->getMessage()
            ], 500);
        }
    }

    public function cancelar($identificadorDoPedido)
    {
        try {
            $usuarioAutenticado = auth()->user();

            $pedido = \App\Models\Pedido::where('id', $identificadorDoPedido)
                                        ->where('user_id', $usuarioAutenticado->id)
                                        ->first();

            if (!$pedido) {
                return response()->json(['message' => 'Pedido não encontrado ou não pertence a você.'], 404);
            }

            $statusAtual = strtolower($pedido->status);
            $statusBloqueados = ['preparo', 'preparando', 'despachado', 'saiu', 'caminho', 'perto', 'entregue', 'concluido'];
            
            foreach($statusBloqueados as $bloqueado) {
                if (str_contains($statusAtual, $bloqueado)) {
                    return response()->json(['message' => 'Não é possível cancelar. O restaurante já começou o preparo!'], 403);
                }
            }

            $pedido->status = 'Cancelado';
            $pedido->save();

            return response()->json([
                'message' => 'Pedido cancelado com sucesso!'
            ], 200);

        } catch (\Exception $excecaoLancada) {
            return response()->json([
                'message' => 'Erro ao cancelar o pedido.',
                'error' => $excecaoLancada->getMessage()
            ], 500);
        }
    }

    // ==========================================
    // --- ÁREA EXCLUSIVA DO PAINEL DO LOJISTA ---
    // ==========================================

    public function pedidosDoLojista()
    {
        try {
            $usuarioAutenticado = auth()->user();
            
            $lojista = DB::table('lojista')->where('user_id', $usuarioAutenticado->id)->first();

            if (!$lojista) {
                return response()->json(['message' => 'Lojista não encontrado.'], 404);
            }

            $pedidos = \App\Models\Pedido::where('lojista_id', $lojista->id)
                                         ->with('produtos')
                                         ->orderBy('created_at', 'desc')
                                         ->get();

            // Puxa o nome do cliente para cada pedido
            $pedidosComCliente = $pedidos->map(function ($pedido) {
                $cliente = DB::table('users')->where('id', $pedido->user_id)->first();
                
                $pedidoArray = $pedido->toArray();
                $pedidoArray['nome_cliente'] = $cliente ? $cliente->name : 'Cliente #' . $pedido->user_id;
                return $pedidoArray;
            });

            return response()->json($pedidosComCliente, 200);

        } catch (\Exception $excecaoLancada) {
            return response()->json([
                'message' => 'Erro ao buscar os pedidos da loja.',
                'error' => $excecaoLancada->getMessage()
            ], 500);
        }
    }

    public function atualizarStatusLojista(Request $requisicao, $identificadorDoPedido)
    {
        $requisicao->validate([
            'status' => 'required|string', 
        ]);

        try {
            $usuarioAutenticado = auth()->user();
            $lojista = DB::table('lojista')->where('user_id', $usuarioAutenticado->id)->first();

            if (!$lojista) {
                return response()->json(['message' => 'Acesso negado. Lojista não encontrado.'], 404);
            }

            $pedido = \App\Models\Pedido::where('id', $identificadorDoPedido)
                                        ->where('lojista_id', $lojista->id)
                                        ->first();

            if (!$pedido) {
                return response()->json(['message' => 'Pedido não encontrado ou não pertence à sua loja.'], 404);
            }

            $pedido->status = $requisicao->status;
            $pedido->save();

            return response()->json([
                'message' => 'Status do pedido atualizado com sucesso!',
                'pedido' => $pedido
            ], 200);

        } catch (\Exception $excecaoLancada) {
            return response()->json([
                'message' => 'Erro interno ao atualizar o pedido.',
                'error' => $excecaoLancada->getMessage()
            ], 500);
        }
    }
}