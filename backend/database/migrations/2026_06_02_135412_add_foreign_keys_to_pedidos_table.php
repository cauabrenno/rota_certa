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
            $table->foreign(['entregador_id'])->references(['id'])->on('entregadores')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['lojista_id'])->references(['id'])->on('lojista')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign('pedidos_entregador_id_foreign');
            $table->dropForeign('pedidos_lojista_id_foreign');
            $table->dropForeign('pedidos_user_id_foreign');
        });
    }
};
