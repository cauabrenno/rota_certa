<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EnderecoController extends Controller
{
    public function store(Request $request)
    {
        try {
            $endereco = \App\Models\Endereco::create([
                'user_id' => auth()->id(),
                'nome_local' => $request->nome_local,
                'cep' => $request->cep,
                'rua' => $request->rua,
                'numero' => $request->numero
            ]);

            return response()->json([
                'message' => 'Endereço salvo com sucesso!',
                'endereco' => $endereco
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao salvar endereço.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
