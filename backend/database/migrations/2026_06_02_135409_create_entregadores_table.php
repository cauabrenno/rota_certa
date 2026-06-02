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
        Schema::create('entregadores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('cpf');
            $table->string('cnh');
            $table->string('status')->default('disponivel');
            $table->timestamps();
            $table->string('telefone', 20)->nullable();
            $table->decimal('avaliacao', 2, 1)->nullable()->default(5);
            $table->integer('total_entregas')->nullable()->default(0);
            $table->decimal('saldo', 10)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entregadores');
    }
};
