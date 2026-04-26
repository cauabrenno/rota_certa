<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;

class ProdutoController extends Controller
{
    // Lista todos os produtos (O que a tela do front vai chamar)
    public function index(Request $request)
    {
        // 1. Iniciamos a "pergunta" ao banco de dados, mas sem executar ainda
        $query = \App\Models\Produto::query();

        // 2. O truque: verificamos se o frontend enviou a palavra "categoria" na URL
        if ($request->has('categoria')) {
            // Se enviou, adicionamos um filtro na nossa busca
            $query->where('categoria', $request->query('categoria'));
        }

        // 3. Agora sim, executamos a busca (com ou sem filtro) e pegamos tudo
        $produtos = $query->get();

        // 4. Devolvemos a lista para o frontend
        return response()->json($produtos, 200);
    }

    public function store(Request $request)
    {
        try {
            // Cria o produto no banco de dados com os dados que vieram do Postman
            $produto = \App\Models\Produto::create([
                'lojista_id' => $request->lojista_id,
                'nome' => $request->nome,
                'marca' => $request->marca,
                'categoria' => $request->categoria,
                'preco' => $request->preco,
                'preco_antigo' => $request->preco_antigo,
                'imagem_url' => $request->imagem_url
            ]);

            // Devolve o 201 Created se der tudo certo
            return response()->json([
                'message' => 'Produto criado com sucesso!',
                'produto' => $produto
            ], 201);

        } catch (\Exception $e) {
            // Se der erro, avisa o que foi em vez de quebrar tudo
            return response()->json([
                'message' => 'Erro ao criar produto',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}