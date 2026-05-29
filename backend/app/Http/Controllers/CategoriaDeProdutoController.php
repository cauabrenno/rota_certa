<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\CategoriaDeProduto;

class CategoriaDeProdutoController extends Controller
{
    /**
     * Lista todas as categorias de produtos associadas ao lojista autenticado.
     */
    public function listarCategorias(): JsonResponse
    {
        $usuarioAutenticado = auth()->user();
        
        $lojista = DB::table('lojista')
            ->where('user_id', $usuarioAutenticado->id)
            ->first();

        if (!$lojista) {
            return response()->json([
                'mensagem' => 'Estabelecimento comercial do lojista não foi encontrado.'
            ], 404);
        }

        $categoriasObtidas = DB::table('categorias_de_produtos')
            ->where('lojista_id', $lojista->id)
            ->orderBy('nome', 'asc')
            ->get();

        return response()->json($categoriasObtidas, 200);
    }

    /**
     * Cria uma nova categoria de produto associada ao lojista autenticado.
     */
    public function criarCategoria(Request $requisicao): JsonResponse
    {
        $usuarioAutenticado = auth()->user();
        
        $lojista = DB::table('lojista')
            ->where('user_id', $usuarioAutenticado->id)
            ->first();

        if (!$lojista) {
            return response()->json([
                'mensagem' => 'Estabelecimento comercial do lojista não foi encontrado.'
            ], 404);
        }

        $requisicao->validate([
            'nome' => 'required|string|max:255',
        ]);

        $nomeFormatado = trim($requisicao->input('nome'));

        // Valida se a categoria já existe para este lojista específico (ignora maiúsculas/minúsculas)
        $categoriaExistente = DB::table('categorias_de_produtos')
            ->where('lojista_id', $lojista->id)
            ->whereRaw('LOWER(nome) = ?', [strtolower($nomeFormatado)])
            ->first();

        if ($categoriaExistente) {
            return response()->json([
                'mensagem' => 'Já existe uma categoria cadastrada com este nome.'
            ], 422);
        }

        try {
            $novaCategoriaId = DB::table('categorias_de_produtos')->insertGetId([
                'lojista_id' => $lojista->id,
                'nome'       => $nomeFormatado,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $categoriaCriada = DB::table('categorias_de_produtos')
                ->where('id', $novaCategoriaId)
                ->first();

            return response()->json([
                'mensagem' => 'Categoria criada com sucesso!',
                'categoria' => $categoriaCriada
            ], 201);

        } catch (\Exception $excecaoLancada) {
            return response()->json([
                'mensagem' => 'Ocorreu um erro ao salvar a categoria no banco de dados.',
                'erro' => $excecaoLancada->getMessage()
            ], 500);
        }
    }
}
