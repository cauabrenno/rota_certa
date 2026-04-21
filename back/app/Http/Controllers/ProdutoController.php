<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;

class ProdutoController extends Controller
{
    // Lista todos os produtos (O que a tela do front vai chamar)
    public function index()
    {
        $produtos = Produto::all();
        return response()->json($produtos);
    }

    // Cria um novo produto (Para a gente testar no Postman)
    public function store(Request $request)
    {
        $produto = Produto::create($request->all());
        
        return response()->json([
            'message' => 'Produto cadastrado com sucesso!',
            'produto' => $produto
        ], 201);
    }
}