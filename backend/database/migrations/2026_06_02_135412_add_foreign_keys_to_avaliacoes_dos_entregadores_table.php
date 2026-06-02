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
        Schema::table('avaliacoes_dos_entregadores', function (Blueprint $table) {
            $table->foreign(['entregador_id'])->references(['id'])->on('entregadores')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['pedido_id'])->references(['id'])->on('pedidos')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['usuario_id'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('avaliacoes_dos_entregadores', function (Blueprint $table) {
            $table->dropForeign('avaliacoes_dos_entregadores_entregador_id_foreign');
            $table->dropForeign('avaliacoes_dos_entregadores_pedido_id_foreign');
            $table->dropForeign('avaliacoes_dos_entregadores_usuario_id_foreign');
        });
    }
};
