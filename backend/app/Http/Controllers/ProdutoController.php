<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdutoController extends Controller
{
    // 1. LISTAR TODOS OS PRODUTOS (Público - Para o App do Cliente)
    public function index()
    {
        // Retorna todos os produtos de todos os lojistas para a vitrine principal
        $produtos = DB::table('produtos')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($produtos, 200);
    }

    // 2. LISTAR APENAS MEUS PRODUTOS (Privado - Para o Painel do Lojista)
    public function meusProdutos()
    {
        $user = auth()->user();

        // Pega o ID real do lojista logado
        $lojista = DB::table('lojista')->where('user_id', $user->id)->first();

        if (!$lojista) {
            return response()->json(['message' => 'Lojista não encontrado.'], 404);
        }

        // Traz apenas os produtos que pertencem a este lojista específico
        $produtos = DB::table('produtos')
            ->where('lojista_id', $lojista->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($produtos, 200);
    }

    // 3. CRIAR UM NOVO PRODUTO (POST)
    public function store(Request $request)
    {
        $user = auth()->user();
        $lojista = DB::table('lojista')->where('user_id', $user->id)->first();

        if (!$lojista) {
            return response()->json(['message' => 'Lojista não encontrado.'], 404);
        }

        try {
            DB::table('produtos')->insert([
                'lojista_id'   => $lojista->id,
                'nome'         => $request->nome,
                'marca'        => $request->marca,
                'categoria'    => $request->categoria,
                'preco'        => $request->preco,
                'preco_antigo' => $request->preco_antigo,
                'imagem_url'   => $request->imagem_url,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            return response()->json(['message' => 'Produto criado com sucesso!'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao salvar produto.', 'error' => $e->getMessage()], 500);
        }
    }

    // 4. EDITAR UM PRODUTO EXISTENTE (PUT)
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $lojista = DB::table('lojista')->where('user_id', $user->id)->first();

        try {
            DB::table('produtos')
                ->where('id', $id)
                ->where('lojista_id', $lojista->id)
                ->update([
                    'nome'         => $request->nome,
                    'marca'        => $request->marca,
                    'categoria'    => $request->categoria,
                    'preco'        => $request->preco,
                    'preco_antigo' => $request->preco_antigo,
                    'imagem_url'   => $request->imagem_url,
                    'updated_at'   => now(),
                ]);

            return response()->json(['message' => 'Produto atualizado com sucesso!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar produto.', 'error' => $e->getMessage()], 500);
        }
    }

    // 5. DELETAR UM PRODUTO (DELETE)
    public function destroy($id)
    {
        $user = auth()->user();
        $lojista = DB::table('lojista')->where('user_id', $user->id)->first();

        try {
            DB::table('produtos')
                ->where('id', $id)
                ->where('lojista_id', $lojista->id)
                ->delete();

            return response()->json(['message' => 'Produto excluído com sucesso!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao excluir produto.', 'error' => $e->getMessage()], 500);
        }
    }
}