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
        Schema::create('bairros', function (Blueprint $table) {
            // Chave primária
            $table->id();
            
            // Nome do bairro (o unique impede de cadastrarem o mesmo bairro duas vezes)
            $table->string('nome')->unique(); 
            
            // Valor do frete para esse bairro (Ex: 5.50). Padrão é 0.00 (Grátis)
            $table->decimal('taxa_de_frete', 8, 2)->default(0.00); 
            
            // Status do bairro (true = faz entrega / false = não faz entrega)
            $table->boolean('ativo')->default(true); 
            
            // Cria as colunas created_at e updated_at automaticamente
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bairros');
    }
};