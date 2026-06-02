<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
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
use App\Http\Controllers\EntregadorController;
use App\Http\Controllers\LojistaController;
use App\Http\Controllers\AvaliacaoDoEntregadorController;
use App\Http\Controllers\CategoriaDeProdutoController;

// --- ROTAS PÚBLICAS ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::get('/busca', [BuscaController::class, 'index']);
//  PÚBLICA: Traz todos os produtos do banco para o app do cliente
Route::get('/produtos', [ProdutoController::class, 'index']); 
Route::post('/frete/calcular', [FreteController::class, 'calcular']);
Route::get('/lojistas', function () { return response()->json(Lojista::all()); });
Route::get('/lojas-parceiras', [LojistaController::class, 'listarLojas']);
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
        
        //  Endereços (Listar, Salvar e Excluir)
        Route::get('/enderecos', [EnderecoController::class, 'index']);
        Route::post('/enderecos', [EnderecoController::class, 'store']);
        Route::delete('/enderecos/{id}', [EnderecoController::class, 'destroy']);
        Route::put('/enderecos/{id}', [EnderecoController::class, 'update']);
        
        //  Cartões (Listar, Salvar e Excluir)
        Route::get('/cartoes', [CartaoController::class, 'index']);
        Route::post('/cartoes', [CartaoController::class, 'store']);
        Route::delete('/cartoes/{id}', [CartaoController::class, 'destroy']);
        
        // Suporte
        Route::post('/suporte', [TicketSuporteController::class, 'store']);
        
        // Pedidos
        Route::post('/pedidos', [PedidoController::class, 'store']);
        Route::get('/meus-pedidos', [PedidoController::class, 'meusPedidos']);
        Route::put('/pedidos/{id}/cancelar', [PedidoController::class, 'cancelar']);
        Route::post('/pedidos/{id}/avaliar-entregador', [AvaliacaoDoEntregadorController::class, 'salvarAvaliacaoDoEntregador']);
    });

    // --- ÁREA DO LOJISTA ---
    Route::middleware(VerificaTipoUsuario::class . ':lojista')->group(function () {
        // PRIVADA: Traz SÓ os produtos do lojista logado
        Route::get('/meus-produtos', [ProdutoController::class, 'meusProdutos']); 
        Route::post('/produtos', [ProdutoController::class, 'store']);
        Route::put('/produtos/{id}', [ProdutoController::class, 'update']);
        Route::delete('/produtos/{id}', [ProdutoController::class, 'destroy']);
        
        // ROTAS DO PERFIL CORRIGIDAS 
        Route::get('/lojista/perfil', [LojistaController::class, 'meuPerfil']); 
        Route::post('/lojista/perfil', [LojistaController::class, 'atualizarPerfil']); // ✨ POST para as fotos funcionarem!

        //  ROTA DA LOJA ABERTA/FECHADA (Restaurada)
        Route::put('/lojista/status', [LojistaController::class, 'toggleAberto']);

        // ROTAS DE PEDIDOS DO LOJISTA 
        Route::get('/lojista/pedidos', [PedidoController::class, 'pedidosDoLojista']);
        Route::put('/lojista/pedidos/{id}/status', [PedidoController::class, 'atualizarStatusLojista']);

        //  ROTA PARA CONTAGEM DE ENTREGADORES ONLINE
        Route::get('/lojista/entregadores-online', [LojistaController::class, 'obterQuantidadeDeEntregadoresOnline']);

        // Rotas para gerenciamento de categorias de produtos
        Route::get('/lojista/categorias', [CategoriaDeProdutoController::class, 'listarCategorias']);
        Route::post('/lojista/categorias', [CategoriaDeProdutoController::class, 'criarCategoria']);
    });
 
    // --- ÁREA DO ENTREGADOR ---
    Route::middleware(VerificaTipoUsuario::class . ':entregador')->group(function () {
        Route::put('/pedidos/{id}/status', [PedidoController::class, 'atualizarStatus']);
        Route::get('/entregador/perfil', [EntregadorController::class, 'meuPerfil']);
        Route::put('/entregador/veiculo', [EntregadorController::class, 'atualizarVeiculo']);
        
        //  ROTA PARA ATUALIZAR STATUS DE DISPONIBILIDADE DO ENTREGADOR
        Route::put('/entregador/status', [EntregadorController::class, 'atualizarStatusDeDisponibilidade']);

        //  NOVAS ROTAS DE CORRIDA DO ENTREGADOR
        Route::get('/entregador/buscar-corrida', [EntregadorController::class, 'buscarPedidoDisponivel']);
        Route::put('/entregador/aceitar-corrida/{id}', [EntregadorController::class, 'aceitarPedido']);
    });

});

Route::get('/instalar-banco-render', function () {
    try {
        // O '--force' é obrigatório em produção
        Artisan::call('migrate', ['--force' => true]);
        return 'Tabelas do RotaCerta criadas com sucesso no Render! 🚀';
    } catch (\Exception $e) {
        return 'Deu erro: ' . $e->getMessage();
    }
});
