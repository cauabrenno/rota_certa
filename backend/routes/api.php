<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProdutoController;
use App\Models\Lojista;
use App\Http\Controllers\BuscaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EnderecoController;

// ROTAS PÚBLICAS (Qualquer um acessa)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/busca', [BuscaController::class, 'index']);
Route::get('/produtos', [ProdutoController::class, 'index']);
Route::post('/produtos', [ProdutoController::class, 'store']);

Route::get('/lojistas', function () {
    return response()->json(Lojista::all());
});

// ROTAS PROTEGIDAS (Precisa de Token)
Route::middleware('auth:api')->group(function () {
    
    // Perfil e Pontos
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/perfil', [AuthController::class, 'updatePerfil']);
    Route::get('/meus-pontos', [ClienteController::class, 'meusPontos']);
    
    // Pedidos
    Route::post('/pedidos', [PedidoController::class, 'store']);
    Route::get('/meus-pedidos', [PedidoController::class, 'meusPedidos']);
    Route::get('/pedidos/{id}', [PedidoController::class, 'show']); 

    Route::post('/enderecos', [EnderecoController::class, 'store']);
    
});