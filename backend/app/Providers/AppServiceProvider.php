<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// ✨ Importação necessária para a lógica de senha
use Illuminate\Auth\Notifications\ResetPassword;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ✨ A lógica do link de recuperação deve ficar EXATAMENTE aqui dentro
        ResetPassword::createUrlUsing(function (object $user, string $token) {
            return 'http://localhost:5173/reset-password?token='.$token.'&email='.$user->email;
        });
    }
}