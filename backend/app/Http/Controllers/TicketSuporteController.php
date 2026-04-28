<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketSuporteController extends Controller
{
    public function store(\Illuminate\Http\Request $request)
    {
        try {
            $ticket = \App\Models\TicketSuporte::create([
                'user_id' => auth()->id(),
                'assunto' => $request->assunto,
                'mensagem' => $request->mensagem,
                'status' => 'aberto'
            ]);

            return response()->json([
                'message' => 'Mensagem enviada com sucesso! A nossa equipe entrará em contato.',
                'ticket' => $ticket
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao enviar a mensagem.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
