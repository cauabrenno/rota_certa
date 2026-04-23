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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            
            // Ligação à tabela de users (Se apagar o user, apaga o cliente)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Campos específicos do cliente (nullable para o momento do registo)
            $table->string('telefone')->nullable();
            $table->string('endereco')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};