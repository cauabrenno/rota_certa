<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. CAMINHO FELIZ: Credenciais Perfeitas
     */
    public function test_login_sucesso_com_credenciais_validas()
    {
        $user = User::factory()->create([
            'email' => 'dev@teste.com',
            'password' => bcrypt('senha123'),
            'tipo' => 'cliente',
            'status' => 'ativo',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'dev@teste.com',
            'password' => 'senha123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'token']);
        $response->assertJson(['message' => 'Login realizado com sucesso']);
    }

    /**
     * 2. CAMINHO TRISTE: Senha Incorreta
     */
    public function test_login_falha_com_senha_errada()
    {
        $user = User::factory()->create([
            'email' => 'dev@teste.com',
            'password' => bcrypt('senha123'),
            'tipo' => 'cliente',
            'status' => 'ativo',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'dev@teste.com',
            'password' => 'senha-errada',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Email ou password inválidos']);
    }

    /**
     * 3. CAMINHO TRISTE: Email Inexistente
     */
    public function test_login_falha_com_email_nao_registado()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'fantasma@teste.com',
            'password' => 'qualquer-senha',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Email ou password inválidos']);
    }

    /**
     * 4. VALIDAÇÃO: Campos Totalmente Vazios
     */
    public function test_login_falha_com_campos_vazios()
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);
    }

    /**
     * 5. VALIDAÇÃO: Formato de Email Inválido
     */
    public function test_login_falha_se_email_for_invalido()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'email_invalido_sem_arroba',
            'password' => 'senha123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    /**
     * 6. TRATAMENTO DE TEXTO: Sensibilidade a Maiúsculas (Case Insensitivity)
     */
    public function test_login_deve_funcionar_mesmo_com_email_em_maiusculas()
    {
        $user = User::factory()->create([
            'email' => 'dev@teste.com', // Gravado em minúsculas
            'password' => bcrypt('senha123'),
            'tipo' => 'cliente',
            'status' => 'ativo',
        ]);

        // Front-end envia em maiúsculas
        $response = $this->postJson('/api/login', [
            'email' => 'DEV@TESTE.COM', 
            'password' => 'senha123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    /**
     * 7. REGRA DE NEGÓCIO: Utilizador Inativo ou Bloqueado
     */
    public function test_login_bloqueado_para_usuario_inativo()
    {
        $user = User::factory()->create([
            'email' => 'bloqueado@teste.com',
            'password' => bcrypt('senha123'),
            'tipo' => 'cliente',
            'status' => 'inativo', // <-- Conta Inativa
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'bloqueado@teste.com',
            'password' => 'senha123',
        ]);

        // 403 Forbidden (Proibido)
        $response->assertStatus(403);
        $response->assertJson(['message' => 'Esta conta encontra-se inativa ou bloqueada.']);
    }

    /**
     * 8. SEGURANÇA: Proteção de Rate Limiting (Muitas tentativas)
     */
    public function test_login_bloqueia_por_rate_limiting_apos_multiplas_tentativas()
    {
        $email = 'alvo@teste.com';

        // Simula 5 tentativas falhadas seguidas
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/login', [
                'email' => $email,
                'password' => 'senha-errada',
            ]);
        }

        // A 6ª tentativa deve ser bloqueada imediatamente antes de validar qualquer coisa
        $response = $this->postJson('/api/login', [
            'email' => $email,
            'password' => 'senha-errada',
        ]);

        // 429 Too Many Requests
        $response->assertStatus(429);
        $response->assertJson(['message' => 'Muitas tentativas de login. Por favor, tente mais tarde.']);
    }

    /**
     * 9. SEGURANÇA: Higienização contra Injeção de SQL (SQL Injection)
     */
    public function test_login_esta_protegido_contra_sql_injection()
    {
        $response = $this->postJson('/api/login', [
            'email' => "' OR '1'='1", // Tentativa maliciosa clássica
            'password' => 'senha123',
        ]);

        // O Eloquent deve tratar como string comum, não achar o email e retornar 401
        $response->assertStatus(401);
    }
}