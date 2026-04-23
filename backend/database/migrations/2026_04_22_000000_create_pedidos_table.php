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
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Quem comprou
            $table->foreignId('lojista_id')->constrained('lojista')->onDelete('cascade'); // Quem vendeu
            $table->foreignId('entregador_id')->nullable()->constrained('entregadores')->onDelete('set null'); // Quem entrega
            $table->decimal('valor_total', 8, 2);
            $table->decimal('taxa_entrega', 8, 2)->default(0);
            $table->string('endereco_entrega');
            $table->string('descricao')->nullable(); // Aqui vcs podem mandar os itens como texto por enquanto
            $table->string('status')->default('pendente'); // pendente, aceito, em_rota, entregue
            
            $table->timestamps();
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
