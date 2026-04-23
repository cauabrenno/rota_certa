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
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            
            // Ligação com a tabela de comércios (quem está vendendo esse produto?)
            $table->foreignId('lojista_id')->constrained('lojista')->onDelete('cascade');
            
            // Dados do produto que aparecem nos cards
            $table->string('nome'); // Ex: Arroz Branco
            $table->string('marca')->nullable(); // Ex: Camil
            $table->string('categoria');
            $table->decimal('preco', 8, 2); // Ex: 5.89
            $table->decimal('preco_antigo', 8, 2)->nullable(); // Ex: 7.50 (nullable porque nem todo produto tá em promoção)
            $table->string('imagem_url')->nullable(); // Link da foto do produto
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
