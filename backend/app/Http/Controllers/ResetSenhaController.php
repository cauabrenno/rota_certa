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
        $request->validate(['email' => 'required|email']);

        // O Laravel gera um token e envia o e-mail automaticamente
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'E-mail de recuperação enviado!'], 200)
            : response()->json(['message' => 'Não conseguimos encontrar um usuário com esse e-mail.'], 400);
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