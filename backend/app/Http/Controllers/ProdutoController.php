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

        $produtosFormatados = $produtos->map(function ($produto) {
            $produto->imagem_url = \App\Helpers\FormatadorDeImagem::obterCaminhoCompletoDaImagem($produto->imagem_url);
            return $produto;
        });

        return response()->json($produtosFormatados, 200);
    }

    // 2. LISTAR APENAS MEUS PRODUTOS (Privado - Para o Painel do Lojista)
    public function meusProdutos()
    {
        $usuarioAutenticado = auth()->user();

        // Pega o ID real do lojista logado
        $lojista = DB::table('lojista')->where('user_id', $usuarioAutenticado->id)->first();

        if (!$lojista) {
            return response()->json(['message' => 'Lojista não encontrado.'], 404);
        }

        // Traz apenas os produtos que pertencem a este lojista específico
        $produtos = DB::table('produtos')
            ->where('lojista_id', $lojista->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $produtosFormatados = $produtos->map(function ($produto) {
            $produto->imagem_url = \App\Helpers\FormatadorDeImagem::obterCaminhoCompletoDaImagem($produto->imagem_url);
            return $produto;
        });

        return response()->json($produtosFormatados, 200);
    }

    // 3. CRIAR UM NOVO PRODUTO (POST)
    public function store(Request $requisicao)
    {
        $usuarioAutenticado = auth()->user();
        $lojista = DB::table('lojista')->where('user_id', $usuarioAutenticado->id)->first();

        if (!$lojista) {
            return response()->json(['message' => 'Lojista não encontrado.'], 404);
        }

        try {
            $imagemProcessada = \App\Helpers\FormatadorDeImagem::salvarImagemBase64($requisicao->imagem_url, 'produtos');

            DB::table('produtos')->insert([
                'lojista_id'   => $lojista->id,
                'nome'         => $requisicao->nome,
                'marca'        => $requisicao->marca,
                'categoria'    => $requisicao->categoria,
                'preco'        => $requisicao->preco,
                'preco_antigo' => $requisicao->preco_antigo,
                'imagem_url'   => $imagemProcessada,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            return response()->json(['message' => 'Produto criado com sucesso!'], 201);
        } catch (\Exception $excecaoLancada) {
            return response()->json(['message' => 'Erro ao salvar produto.', 'error' => $excecaoLancada->getMessage()], 500);
        }
    }

    // 4. EDITAR UM PRODUTO EXISTENTE (PUT)
    public function update(Request $requisicao, $identificadorDoProduto)
    {
        $usuarioAutenticado = auth()->user();
        $lojista = DB::table('lojista')->where('user_id', $usuarioAutenticado->id)->first();

        try {
            $imagemProcessada = \App\Helpers\FormatadorDeImagem::salvarImagemBase64($requisicao->imagem_url, 'produtos');

            DB::table('produtos')
                ->where('id', $identificadorDoProduto)
                ->where('lojista_id', $lojista->id)
                ->update([
                    'nome'         => $requisicao->nome,
                    'marca'        => $requisicao->marca,
                    'categoria'    => $requisicao->categoria,
                    'preco'        => $requisicao->preco,
                    'preco_antigo' => $requisicao->preco_antigo,
                    'imagem_url'   => $imagemProcessada,
                    'updated_at'   => now(),
                ]);

            return response()->json(['message' => 'Produto atualizado com sucesso!'], 200);
        } catch (\Exception $excecaoLancada) {
            return response()->json(['message' => 'Erro ao atualizar produto.', 'error' => $excecaoLancada->getMessage()], 500);
        }
    }

    // 5. DELETAR UM PRODUTO (DELETE)
    public function destroy($identificadorDoProduto)
    {
        $usuarioAutenticado = auth()->user();
        $lojista = DB::table('lojista')->where('user_id', $usuarioAutenticado->id)->first();

        try {
            DB::table('produtos')
                ->where('id', $identificadorDoProduto)
                ->where('lojista_id', $lojista->id)
                ->delete();

            return response()->json(['message' => 'Produto excluído com sucesso!'], 200);
        } catch (\Exception $excecaoLancada) {
            return response()->json(['message' => 'Erro ao excluir produto.', 'error' => $excecaoLancada->getMessage()], 500);
        }
    }
}