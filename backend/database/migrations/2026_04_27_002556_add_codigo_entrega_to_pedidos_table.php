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
        Schema::table('pedidos', function (Blueprint $table) {
            // Adiciona uma coluna de texto (string) com limite de 4 caracteres
            // O "nullable" é porque os pedidos antigos não têm esse código
            $table->string('codigo_entrega', 4)->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            // Se precisarmos desfazer, ele apaga só essa coluna
            $table->dropColumn('codigo_entrega');
        });
    }
};
