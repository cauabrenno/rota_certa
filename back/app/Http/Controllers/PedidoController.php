<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function store(Request $request)
{
    $pedido = Pedido::create([
        'user_id' => 1, 
        'cliente_nome' => $request->cliente_nome,
        'endereco' => $request->endereco,
        'descricao' => $request->descricao,
        'status' => 'pendente'
    ]);

    return response()->json([
        'message' => 'Pedido criado com sucesso',
        'pedido' => $pedido
    ]);
}
}
