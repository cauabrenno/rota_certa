<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('entregadores', function (Blueprint $table) {
            // Adiciona a coluna telefone logo após a CNH
            $table->string('telefone')->nullable()->after('cnh');
        });
    }

    public function down()
    {
        Schema::table('entregadores', function (Blueprint $table) {
            // Remove a coluna caso você precise desfazer a migration
            $table->dropColumn('telefone');
        });
    }
};
