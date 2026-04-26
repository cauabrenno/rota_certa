<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\User;

class BuscaController extends Controller
{
    public function index(Request $request)
    {
        // 1. Pega a palavra que o front-end mandou na URL (ex: ?q=arroz)
        $termo = $request->query('q');

        // Se o usuário não digitou nada, a gente avisa
        if (!$termo) {
            return response()->json([
                'message' => 'Por favor, digite algo para buscar.'
            ], 400);
        }

        // 2. Busca nos Produtos (ILIKE ignora letras maiúsculas/minúsculas no PostgreSQL)
        $produtos = Produto::where('nome', 'ILIKE', "%{$termo}%")->get();

        // 3. Busca nos Lojistas (Lembrando que o nome do Mercado fica na tabela Users!)
        $mercados = User::where('tipo', 'lojista')
                        ->where('name', 'ILIKE', "%{$termo}%")
                        ->get();

        // 4. Devolve tudo mastigadinho pro Frontend
        return response()->json([
            'resultados' => [
                'produtos' => $produtos,
                'mercados' => $mercados
            ]
        ], 200);
    }
}