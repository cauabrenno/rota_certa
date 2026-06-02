<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('tipo');
            $table->string('status')->default('ativo');
            $table->rememberToken();
            $table->timestamps();
            $table->string('telefone')->nullable();
            $table->integer('pontos')->nullable()->default(0);
            $table->string('modelo_veiculo')->nullable();
            $table->string('placa_veiculo')->nullable();
            $table->string('nome_loja')->nullable();
            $table->text('logo_loja')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
