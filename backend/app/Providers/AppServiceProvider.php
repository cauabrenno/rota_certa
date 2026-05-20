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
            
            $enderecoDoFrontEnd = env('FRONTEND_URL', 'http://localhost:5173');
            $enderecoDeEmailParaRedefinicao = $notifiable->getEmailForPasswordReset();
            
            $linkDeRedefinicaoDeSenha = rtrim($enderecoDoFrontEnd, '/') . '/reset-password?token=' . urlencode($token) . '&email=' . urlencode($enderecoDeEmailParaRedefinicao);

            // Pegamos o link da imagem usando o asset() igual fizemos antes
            $linkDaImagem = asset('images/rotaLogo.png');

            return (new MailMessage)
                ->subject('Recuperação de Senha - RotaCerta')
                ->greeting('Olá, Usuário!')
                // Adicionamos uma "linha" que na verdade é a tag HTML da imagem
                ->line(new \Illuminate\Support\HtmlString('<div style="text-align: center; margin-bottom: 20px;"><img src="'.$linkDaImagem.'" alt="Logo RotaCerta" style="max-width: 100%; height: auto; width: 250px;"></div>'))
                ->line('Você está recebendo este e-mail porque solicitou a redefinição da sua senha no RotaCerta.')
                ->action('Criar Nova Senha', $linkDeRedefinicaoDeSenha)
                ->line('Se você não pediu para mudar a senha, pode ignorar este e-mail tranquilamente. Sua conta está segura.')
                ->salutation('Acelere com segurança, Equipe RotaCerta 🛵');
        });
    }
}