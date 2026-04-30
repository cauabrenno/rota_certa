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
                
                // ✨ Aqui fazemos a ponte: a coluna do banco (nome_local) recebe o dado do Vue (titulo)
                'nome_local' => $request->titulo, 
                
                'cep' => $request->cep,
                'rua' => $request->rua,
                'numero' => $request->numero,
                
                // Se você tiver essas colunas no banco de dados, pode descomentar:
                // 'bairro' => $request->bairro,
                // 'cidade' => $request->cidade
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

    public function index()
    {
        // Busca os endereços apenas do cliente que está logado no momento
        $enderecos = auth()->user()->enderecos; 
        
        return response()->json($enderecos);
    }
}
