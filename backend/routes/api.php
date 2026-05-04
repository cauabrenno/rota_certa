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
use App\Http\Middleware\VerificaTipoUsuario;
use App\Http\Controllers\ResetSenhaController;

// --- ROTAS PÚBLICAS ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/busca', [BuscaController::class, 'index']);
Route::get('/produtos', [ProdutoController::class, 'index']);
Route::post('/frete/calcular', [FreteController::class, 'calcular']);
Route::get('/lojistas', function () { return response()->json(Lojista::all()); });
Route::post('/esqueceu-senha', [ResetSenhaController::class, 'enviarLink']);
Route::post('/resetar-senha', [ResetSenhaController::class, 'resetar'])->name('password.reset');


// --- ROTAS PROTEGIDAS (Precisa de Token) ---
Route::middleware('auth:api')->group(function () {
    
    // Perfil Comum
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/perfil', [AuthController::class, 'updatePerfil']);
    Route::put('/perfil/senha', [AuthController::class, 'alterarSenha']);

    // Pedido Show (Liberado para todos os tipos logados)
    Route::get('/pedidos/{id}', [PedidoController::class, 'show']);

    // --- ÁREA DO CLIENTE ---
    Route::middleware(VerificaTipoUsuario::class . ':cliente')->group(function () {
        
        // Clube RotaCerta
        Route::get('/meus-pontos', function (\Illuminate\Http\Request $request) {
            return response()->json([
                'pontos' => $request->user()->pontos ?? 0
            ], 200);
        });        
        // 📍 Endereços (Listar, Salvar e Excluir)
        Route::get('/enderecos', [EnderecoController::class, 'index']);
        Route::post('/enderecos', [EnderecoController::class, 'store']);
        Route::delete('/enderecos/{id}', [EnderecoController::class, 'destroy']);
        
        // 💳 Cartões (Listar, Salvar e Excluir)
        Route::get('/cartoes', [CartaoController::class, 'index']);
        Route::post('/cartoes', [CartaoController::class, 'store']);
        Route::delete('/cartoes/{id}', [CartaoController::class, 'destroy']);
        
        // Suporte
        Route::post('/suporte', [TicketSuporteController::class, 'store']);
        
        // Pedidos
        Route::post('/pedidos', [PedidoController::class, 'store']);
        Route::get('/meus-pedidos', [PedidoController::class, 'meusPedidos']);
        Route::put('/pedidos/{id}/cancelar', [PedidoController::class, 'cancelar']);
    });

    // --- ÁREA DO LOJISTA ---
    Route::middleware(VerificaTipoUsuario::class . ':lojista')->group(function () {
        Route::post('/produtos', [ProdutoController::class, 'store']);
    });

    // --- ÁREA DO ENTREGADOR ---
    Route::middleware(VerificaTipoUsuario::class . ':entregador')->group(function () {
        Route::put('/pedidos/{id}/status', [PedidoController::class, 'atualizarStatus']);
    });

});