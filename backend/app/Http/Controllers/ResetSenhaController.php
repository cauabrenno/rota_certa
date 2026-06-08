<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetSenhaController extends Controller
{
    // 1. Envia o e-mail com o link/token
    public function enviarLink(Request $request)
    {
        // 1. Valida se é um e-mail válido
        $request->validate(['email' => 'required|email']);

        // 2. Busca manual no banco de dados para tirar a prova
        $user = User::where('email', $request->email)->first();

        // 3. Se não achar, devolve exatamente o que ele tentou procurar
        if (!$user) {
            return response()->json([
                'message' => 'RAIO-X: O banco de dados da nuvem confirmou que [' . $request->email . '] NÃO existe na tabela users.'
            ], 400);
        }

        // 4. Se achou, força o envio
        $status = Password::sendResetLink(['email' => $request->email]);

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'E-mail de recuperação enviado com sucesso!'], 200)
            : response()->json(['message' => 'RAIO-X: O Laravel bloqueou pelo motivo: ' . $status], 400);
    }

    // 2. Recebe o token e a nova senha
    public function resetar(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Senha alterada com sucesso!'], 200)
            : response()->json(['message' => 'Erro ao resetar senha. Token inválido ou expirado.'], 400);
    }
}