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
        Schema::create('categorias_de_produtos', function (Blueprint $tabelaDeCategorias) {
            $tabelaDeCategorias->id();
            $tabelaDeCategorias->foreignId('lojista_id')->constrained('lojista')->onDelete('cascade');
            $tabelaDeCategorias->string('nome');
            $tabelaDeCategorias->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias_de_produtos');
    }
};
