<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 🚨 A MÁGICA ENTRA AQUI: Validação antes de tudo!
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users', // Garante que não tenha 2 emails iguais
            'password' => 'required|string|min:6|confirmed', // Exige o password_confirmation!
            'tipo' => 'required|string',
        ]);

        // 1. Inicia uma transação na base de dados para segurança
        DB::beginTransaction();

        try {
            // 2. Cria o Utilizador Base (comum a todos)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tipo' => $request->tipo,
            ]);

            // 3. Verifica o tipo e guarda na tabela específica correta
            if ($request->tipo === 'cliente') {
                
                DB::table('clientes')->insert([
                    'user_id' => $user->id,
                    'telefone' => $request->telefone ?? null,
                    'endereco' => $request->endereco ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            } elseif ($request->tipo === 'lojista') {
                
                DB::table('lojista')->insert([
                    'user_id' => $user->id,
                    'cnpj' => $request->cnpj,  
                    'endereco' => $request->endereco ?? null, 
                    'telefone' => $request->telefone ?? null,
                    'nota' => 0, // Valor default
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            } elseif ($request->tipo === 'entregador') {
                
                DB::table('entregadores')->insert([
                    'user_id' => $user->id,
                    'cpf' => $request->cpf,
                    'cnh' => $request->cnh,
                    'status' => 'disponivel', // Valor default
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Se tudo correu bem, confirma as gravações na base de dados
            DB::commit();

            return response()->json([
                'message' => 'Utilizador criado com sucesso!',
                'user' => $user
            ], 201);

        } catch (\Exception $e) {
            // Se deu algum erro, desfaz tudo para não guardar dados pela metade
            DB::rollBack();
            
            return response()->json([
                'message' => 'Erro ao criar conta.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // A sua função de login continua igual!
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'message' => 'Email ou password inválidos'
            ], 401);
        }

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'token' => $token
        ]);
    }
}