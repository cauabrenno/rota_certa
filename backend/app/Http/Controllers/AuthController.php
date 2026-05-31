<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 1. Atualizamos a validação para esperar 'tipo_usuario' do Vue
        $request->validate([
            'name'         => 'required|string',
            'email'        => 'required|email|unique:users', 
            'password'     => 'required|string|min:6|confirmed', 
            'tipo_usuario' => 'required|string', 
        ]);

        DB::beginTransaction();

        try {
            // 2. Na tabela users, a coluna ainda se chama 'tipo'
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'tipo'     => $request->tipo_usuario, 
            ]);

            // 3. Cadastramos na tabela específica baseada no tipo
            if ($request->tipo_usuario === 'cliente') {
                DB::table('clientes')->insert([
                    'user_id'    => $user->id,
                    'telefone'   => $request->telefone ?? null,
                    'endereco'   => $request->endereco ?? null,
                    'pontos'     => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            } elseif ($request->tipo_usuario === 'lojista') {
                DB::table('lojista')->insert([
                    'user_id'    => $user->id,
                    'cnpj'       => $request->cnpj,  
                    'endereco'   => $request->endereco ?? null, 
                    'telefone'   => $request->telefone ?? null,
                    'nota'       => 0, 
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            } elseif ($request->tipo_usuario === 'entregador') {
                DB::table('entregadores')->insert([
                    'user_id'    => $user->id,
                    'cpf'        => $request->cpf,
                    'cnh'        => $request->cnh,
                    'telefone'   => $request->telefone ?? null, // Salvando telefone também
                    'status'     => 'disponivel', 
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Utilizador criado com sucesso!',
                'user'    => $user
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao criar conta.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request) 
    {
        // 1. Validação forte (Barra campos vazios e e-mails inválidos)
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Formato de e-mail inválido.',
            'password.required' => 'A senha é obrigatória.'
        ]);

        // 2. Normalizar o e-mail (transforma tudo em minúsculo para evitar bugs)
        $email_limpo = Str::lower($request->email);

        // 3. Buscar o utilizador para verificar o status ANTES de autenticar
        $user = User::where('email', $email_limpo)->first();

        // Verifica se o usuário existe e se está inativo/banido
        // (Usa isset() para não quebrar caso a coluna status não exista em alguns casos)
        if ($user && isset($user->status) && in_array($user->status, ['inativo', 'banido'])) {
            return response()->json([
                'message' => 'Acesso negado. Esta conta foi suspensa ou está inativa.'
            ], 403);
        }

        // 4. Autenticação (AGORA SIM usando o e-mail limpo!)
        $credentials = [
            'email' => $email_limpo,
            'password' => $request->password
        ];

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'message' => 'Email ou password inválidos'
            ], 401);
        }

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'token'   => $token
        ]);
    }

    public function me()
    {
        $user = auth()->user();
        $endereco = null;
        $telefone = null;

        // Adicionado suporte para Entregador não quebrar o me()
        if ($user->tipo === 'cliente') {
            $cliente = DB::table('clientes')->where('user_id', $user->id)->first();
            $endereco = $cliente ? $cliente->endereco : null;
            $telefone = $cliente ? $cliente->telefone : null;
            
        } elseif ($user->tipo === 'lojista') {
            $lojista = DB::table('lojista')->where('user_id', $user->id)->first();
            $endereco = $lojista ? $lojista->endereco : null;
            $telefone = $lojista ? $lojista->telefone : null;
            
        } elseif ($user->tipo === 'entregador') {
            $entregador = DB::table('entregadores')->where('user_id', $user->id)->first();
            $telefone = $entregador ? $entregador->telefone : null;
        }

        return response()->json([
            'id'             => $user->id,
            'nome'           => $user->name,
            'email'          => $user->email,
            'tipo'           => $user->tipo,
            'telefone'       => $telefone, 
            'endereco_atual' => $endereco,
            'created_at'     => $user->created_at 
        ], 200);
    }

    public function updatePerfil(Request $request)
    {
        try {
            $user = auth()->user();

            // Atualiza na tabela USERS
            if ($request->has('nome')) {
                $user->name = $request->nome; 
            }
            if ($request->has('email')) {
                $user->email = $request->email;
            }
            $user->save();

            // Atualiza o Telefone na tabela certa garantindo integridade
            if ($request->has('telefone')) {
                if ($user->tipo === 'cliente') {
                    DB::table('clientes')
                        ->where('user_id', $user->id)
                        ->update(['telefone' => $request->telefone]);
                } elseif ($user->tipo === 'lojista') {
                    DB::table('lojista')
                        ->where('user_id', $user->id)
                        ->update(['telefone' => $request->telefone]);
                } elseif ($user->tipo === 'entregador') {
                    DB::table('entregadores')
                        ->where('user_id', $user->id)
                        ->update(['telefone' => $request->telefone]);
                }
            }

            return response()->json([
                'message' => 'Perfil atualizado com sucesso!',
                'user'    => $user
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar o perfil.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function alterarSenha(\Illuminate\Http\Request $request)
    {
        try {
            $user = auth()->user();

            if (!Hash::check($request->senha_atual, $user->password)) {
                return response()->json([
                    'message' => 'A senha atual está incorreta. Tente novamente.'
                ], 400);
            }

            if ($request->nova_senha !== $request->nova_senha_confirmation) {
                return response()->json([
                    'message' => 'A nova senha e a confirmação não combinam.'
                ], 400);
            }

            $user->password = Hash::make($request->nova_senha);
            $user->save();

            return response()->json([
                'message' => 'Senha atualizada com sucesso!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar a senha.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}