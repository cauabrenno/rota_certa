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
        Schema::create('avaliacoes_dos_entregadores', function (Blueprint $tabela) {
            $tabela->id();
            $tabela->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $tabela->foreignId('entregador_id')->constrained('entregadores')->onDelete('cascade');
            $tabela->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $tabela->integer('nota_da_avaliacao');
            $tabela->string('motivos_da_avaliacao')->nullable();
            $tabela->text('comentarios_adicionais')->nullable();
            $tabela->timestamps();
        });

        Schema::table('pedidos', function (Blueprint $tabela) {
            $tabela->boolean('avaliacao_do_entregador_concluida')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $tabela) {
            $tabela->dropColumn('avaliacao_do_entregador_concluida');
        });

        Schema::dropIfExists('avaliacoes_dos_entregadores');
    }
};
