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
        Schema::table('categorias_de_produtos', function (Blueprint $table) {
            $table->foreign(['lojista_id'])->references(['id'])->on('lojista')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categorias_de_produtos', function (Blueprint $table) {
            $table->dropForeign('categorias_de_produtos_lojista_id_foreign');
        });
    }
};
