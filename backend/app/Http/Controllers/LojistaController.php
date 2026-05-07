<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LojistaController extends Controller
{
public function meuPerfil()
    {
        $user = auth()->user();
        $lojista = DB::table('lojista')->where('user_id', $user->id)->first();

        if (!$lojista) {
            return response()->json(['message' => 'Lojista não encontrado.'], 404);
        }

        // Tenta ler o JSON. Se o banco tiver apenas um texto normal (ou vazio), ele cria o objeto zerado.
        $enderecoObj = json_decode($lojista->endereco);
        
        if (!is_object($enderecoObj)) {
            $enderecoObj = (object) [
                'cep' => '', 'cidade' => 'Juazeiro do Norte - CE', 'rua' => '', 'numero' => '', 'bairro' => ''
            ];
        }

        return response()->json([
            'nome'      => $user->name,
            'logo_loja' => $user->logo_loja, 
            'cnpj'      => $lojista->cnpj,
            'telefone'  => $lojista->telefone,
            'aberto'    => (bool) $lojista->aberto,
            'endereco'  => $enderecoObj
        ], 200);
    }

public function atualizarPerfil(Request $request)
{
    $user = auth()->user();
    
    // Logica para achar o lojista pelo user_id
    $lojista = DB::table('lojista')->where('user_id', $user->id)->first();

    if (!$lojista) {
        return response()->json(['message' => 'Lojista não localizado no banco.'], 404);
    }

    try {
        DB::beginTransaction();

        // 1. Atualiza a tabela USERS (Nome e Logo)
        // Verifique se o nome da coluna no seu pgAdmin é exatamente 'logo_loja'
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'name' => $request->nome,
                'logo_loja' => $request->logo_loja
            ]);

        // 2. Atualiza a tabela LOJISTA
        DB::table('lojista')
            ->where('user_id', $user->id)
            ->update([
                'cnpj' => $request->cnpj,
                'telefone' => $request->telefone,
                'aberto' => $request->aberto,
                'endereco' => json_encode($request->endereco), // Empacota o objeto
                'updated_at' => now()
            ]);

        DB::commit();
        return response()->json(['message' => 'Sucesso total!'], 200);

    } catch (\Exception $e) {
        DB::rollBack();
        // Esse retorno vai te mostrar o erro real no console do navegador
        return response()->json([
            'message' => 'Erro interno no servidor',
            'debug' => $e->getMessage()
        ], 500);
    }
}
}