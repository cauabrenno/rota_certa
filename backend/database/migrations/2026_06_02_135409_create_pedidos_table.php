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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('lojista_id');
            $table->bigInteger('entregador_id')->nullable();
            $table->decimal('valor_total');
            $table->decimal('taxa_entrega')->default(0);
            $table->string('endereco_entrega');
            $table->string('descricao')->nullable();
            $table->string('status')->default('pendente');
            $table->timestamps();
            $table->string('codigo_entrega', 4)->nullable();
            $table->string('forma_pagamento')->nullable();
            $table->string('lat_entrega', 50)->nullable();
            $table->string('lng_entrega', 50)->nullable();
            $table->boolean('avaliacao_do_entregador_concluida')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
