<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido; // Caso precise buscar os pedidos dele depois

class EntregadorController extends Controller
{
    // 1. Carrega todos os dados da tela
    public function meuPerfil()
    {
        $user = auth()->user();

        // Para um MVP de TCC, podemos simular os dados que ainda não têm tabela,
        // ou você pode depois calcular o saldo real somando as taxas de entrega dele.
        return response()->json([
            'nome' => $user->name,
            'avaliacao' => 4.9, // Dado simulado para apresentação
            'total_entregas' => 124, // Dado simulado
            'saldo_semana' => 342.50, // Dado simulado
            'ultimo_repasse' => 'Há 2 dias',
            'veiculo' => [
                'modelo' => $user->modelo_veiculo,
                'placa' => $user->placa_veiculo
            ]
        ], 200);
    }

    // 2. Para quando o front colocar o botão "Salvar"
    public function atualizarVeiculo(Request $request)
    {
        $request->validate([
            'modelo_veiculo' => 'required|string|max:255',
            'placa_veiculo' => 'required|string|max:15',
        ]);

        $user = auth()->user();
        
        $user->update([
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
}