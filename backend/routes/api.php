<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProdutoController;
use App\Models\Lojista;
use App\Http\Controllers\BuscaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\CartaoController;
use App\Http\Controllers\TicketSuporteController;
use App\Http\Controllers\FreteController;

// ROTAS PÚBLICAS (Qualquer um acessa)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/busca', [BuscaController::class, 'index']);
Route::get('/produtos', [ProdutoController::class, 'index']);
Route::post('/produtos', [ProdutoController::class, 'store']);

Route::get('/lojistas', function () {
    return response()->json(Lojista::all());
});
Route::post('/frete/calcular', [FreteController::class, 'calcular']);

// ROTAS PROTEGIDAS (Precisa de Token)
Route::middleware('auth:api')->group(function () {
    
    // Perfil e Pontos
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/perfil', [AuthController::class, 'updatePerfil']);
    Route::get('/meus-pontos', [ClienteController::class, 'meusPontos']);
    Route::post('/enderecos', [EnderecoController::class, 'store']);
    Route::put('/perfil/senha', [\App\Http\Controllers\AuthController::class, 'alterarSenha']);
    Route::post('/cartoes', [CartaoController::class, 'store']);
    Route::post('/suporte', [TicketSuporteController::class, 'store']);
    
    // Pedidos
    Route::post('/pedidos', [PedidoController::class, 'store']);
    Route::get('/meus-pedidos', [PedidoController::class, 'meusPedidos']);
    Route::get('/pedidos/{id}', [PedidoController::class, 'show']); 

    
});