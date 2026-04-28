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
                    'pontos' => 0,
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

    public function me()
    {
        // 1. Pega o usuário dono do Token
        $user = auth()->user();

        $endereco = null;

        // 2. Vai buscar o endereço dele dependendo de qual tabela ele pertence
        if ($user->tipo === 'cliente') {
            $cliente = \Illuminate\Support\Facades\DB::table('clientes')->where('user_id', $user->id)->first();
            $endereco = $cliente ? $cliente->endereco : null;
            
        } elseif ($user->tipo === 'lojista') {
            $lojista = \Illuminate\Support\Facades\DB::table('lojistas')->where('user_id', $user->id)->first();
            $endereco = $lojista ? $lojista->endereco : null;
        }

        // 3. Devolve um pacote completo pro front-end
        return response()->json([
            'id' => $user->id,
            'nome' => $user->name,
            'email' => $user->email,
            'tipo' => $user->tipo,
            'endereco_atual' => $endereco
        ], 200);
    }

    public function updatePerfil(Request $request)
    {
        try {
            // Pega o usuário que está logado no momento
            $user = auth()->user();

            // Atualiza o nome se o frontend tiver enviado
            if ($request->has('name')) {
                $user->name = $request->name;
            }

            // Atualiza o telefone se o frontend tiver enviado
            if ($request->has('telefone')) {
                $user->telefone = $request->telefone;
            }

            // Salva as alterações no banco de dados
            $user->save();

            return response()->json([
                'message' => 'Perfil atualizado com sucesso!',
                'user' => $user
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar o perfil.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function alterarSenha(\Illuminate\Http\Request $request)
    {
        try {
            // Pega o usuário logado
            $user = auth()->user();

            // 1. Verifica se a "Senha Atual" que ele digitou bate com a do banco
            if (!\Illuminate\Support\Facades\Hash::check($request->senha_atual, $user->password)) {
                return response()->json([
                    'message' => 'A senha atual está incorreta. Tente novamente.'
                ], 400);
            }

            // 2. Verifica se a "Nova Senha" e a "Confirmação" são iguais
            if ($request->nova_senha !== $request->confirmar_nova_senha) {
                return response()->json([
                    'message' => 'A nova senha e a confirmação não combinam.'
                ], 400);
            }

            // 3. Se tudo deu certo, criptografa a nova senha e salva!
            $user->password = \Illuminate\Support\Facades\Hash::make($request->nova_senha);
            $user->save();

            return response()->json([
                'message' => 'Senha atualizada com sucesso!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar a senha.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}