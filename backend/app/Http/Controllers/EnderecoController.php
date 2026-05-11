<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EnderecoController extends Controller
{
    public function index()
    {
        // Pega todos os endereços onde o user_id é o do cliente logado
        // O Eloquent já vai trazer a coluna 'bairro' automaticamente no get()
        $enderecos = \App\Models\Endereco::where('user_id', auth()->id())->get();
        return response()->json($enderecos, 200);
    }

    public function store(Request $request)
    {
        try {
            $endereco = \App\Models\Endereco::create([
                'user_id' => auth()->id(),
                'nome_local' => $request->nome_local, 
                'cep' => $request->cep,
                'rua' => $request->rua,
                'numero' => $request->numero,
                'bairro' => $request->bairro // ✨ Coluna nova adicionada aqui!
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

    public function destroy($id)
    {
        // Procura o endereço e garante que ele é do usuário logado antes de apagar
        \App\Models\Endereco::where('id', $id)->where('user_id', auth()->id())->delete();
        
        return response()->json(['message' => 'Endereço excluído com sucesso!'], 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $endereco = \App\Models\Endereco::where('id', $id)->where('user_id', auth()->id())->first();
            
            if (!$endereco) {
                return response()->json(['message' => 'Endereço não encontrado.'], 404);
            }

            $endereco->update([
                'nome_local' => $request->nome_local,
                'cep' => $request->cep,
                'rua' => $request->rua,
                'numero' => $request->numero,
                'bairro' => $request->bairro,
                'cidade' => $request->cidade
            ]);

            return response()->json(['message' => 'Endereço atualizado!', 'endereco' => $endereco], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar.', 'error' => $e->getMessage()], 500);
        }
    }
}