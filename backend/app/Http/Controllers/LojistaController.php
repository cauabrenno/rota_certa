<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LojistaController extends Controller
{
    public function atualizarPerfil(Request $request)
    {
        // Validação: garante que o nome é texto e a logo é uma imagem de até 2MB
        $request->validate([
            'nome_loja' => 'nullable|string|max:255',
            'logo_loja' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $user = auth()->user();
        $dadosParaAtualizar = [];

        if ($request->has('nome_loja')) {
            $dadosParaAtualizar['nome_loja'] = $request->nome_loja;
        }

        // Se o front-end enviou um arquivo de imagem
        if ($request->hasFile('logo_loja')) {
            // Se já tinha um logo antes, deleta o antigo para não lotar o servidor
            if ($user->logo_loja) {
                Storage::disk('public')->delete($user->logo_loja);
            }

            // Salva a nova imagem na pasta 'logos' e pega o caminho
            $caminhoImagem = $request->file('logo_loja')->store('logos', 'public');
            $dadosParaAtualizar['logo_loja'] = $caminhoImagem;
        }

        // Atualiza o banco de dados
        $user->update($dadosParaAtualizar);

        return response()->json([
            'message' => 'Perfil da loja atualizado com sucesso!',
            'loja' => [
                'nome_loja' => $user->nome_loja,
                // Monta a URL completa para o front-end poder exibir a imagem
                'logo_url' => $user->logo_loja ? asset('storage/' . $user->logo_loja) : null
            ]
        ], 200);
    }
}