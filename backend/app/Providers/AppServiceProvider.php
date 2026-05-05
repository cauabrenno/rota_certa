<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

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
        // Personaliza o e-mail de recuperação de senha
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            
            // Aqui você pode montar a URL que vai pro Front-end (caso o front tenha uma tela específica)
            // Se o front já está capturando a URL atual, pode manter a route('password.reset')
            $url = route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]);

            return (new MailMessage)
                ->subject('Recuperação de Senha - RotaCerta') // Título do e-mail
                ->greeting('Olá, Usuáeio!') // Saudação inicial
                ->line('Você está recebendo este e-mail porque solicitou a redefinição da sua senha no RotaCerta.')
                ->action('Criar Nova Senha', $url) // Botão
                ->line('Se você não pediu para mudar a senha, pode ignorar este e-mail tranquilamente. Sua conta está segura.')
                ->salutation('Acelere com segurança, Equipe RotaCerta 🛵'); // Despedida
        });
    }
}