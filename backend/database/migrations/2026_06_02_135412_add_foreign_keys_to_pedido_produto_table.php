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
        Schema::table('pedido_produto', function (Blueprint $table) {
            $table->foreign(['pedido_id'])->references(['id'])->on('pedidos')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['produto_id'])->references(['id'])->on('produtos')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido_produto', function (Blueprint $table) {
            $table->dropForeign('pedido_produto_pedido_id_foreign');
            $table->dropForeign('pedido_produto_produto_id_foreign');
        });
    }
};
