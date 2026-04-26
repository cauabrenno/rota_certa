<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProdutoController;
use App\Models\Lojista;
use App\Http\Controllers\BuscaController;
use App\Http\Controllers\ClienteController;

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->post('/pedidos', [PedidoController::class, 'store']);

Route::get('/produtos', [ProdutoController::class, 'index']);

Route::post('/produtos', [ProdutoController::class, 'store']);

Route::get('/lojistas', function () {
    return response()->json(Lojista::all());
});

Route::get('/busca', [BuscaController::class, 'index']);

Route::middleware('auth:api')->group(function () {
    Route::get('/meus-pontos', [ClienteController::class, 'meusPontos']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('/meus-pontos', [ClienteController::class, 'meusPontos']);
    Route::get('/me', [AuthController::class, 'me']); // <-- ADICIONE ESTA LINHA!
});