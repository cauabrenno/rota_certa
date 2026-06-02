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
        // 1. Tabela LOJISTA
        if (Schema::hasTable('lojista')) {
            Schema::table('lojista', function (Blueprint $table) {
                if (!Schema::hasColumn('lojista', 'aberto')) {
                    $table->boolean('aberto')->default(false);
                }
            });
        }

        // 2. Tabela ENDERECOS
        if (Schema::hasTable('enderecos')) {
            Schema::table('enderecos', function (Blueprint $table) {
                if (!Schema::hasColumn('enderecos', 'bairro')) {
                    $table->text('bairro')->nullable();
                }
                if (!Schema::hasColumn('enderecos', 'cidade')) {
                    $table->text('cidade')->nullable();
                }
            });
        }

        // 3. Tabela ENTREGADORES
        if (Schema::hasTable('entregadores')) {
            Schema::table('entregadores', function (Blueprint $table) {
                if (!Schema::hasColumn('entregadores', 'telefone')) {
                    $table->string('telefone', 20)->nullable();
                }
                if (!Schema::hasColumn('entregadores', 'avaliacao')) {
                    $table->decimal('avaliacao', 2, 1)->default(5.0);
                }
                if (!Schema::hasColumn('entregadores', 'total_entregas')) {
                    $table->integer('total_entregas')->default(0);
                }
                if (!Schema::hasColumn('entregadores', 'saldo')) {
                    $table->decimal('saldo', 10, 2)->default(0.00);
                }
            });
        }

        // 4. Tabela PEDIDOS
        if (Schema::hasTable('pedidos')) {
            Schema::table('pedidos', function (Blueprint $table) {
                if (!Schema::hasColumn('pedidos', 'codigo_entrega')) {
                    $table->string('codigo_entrega', 4)->nullable();
                }
                if (!Schema::hasColumn('pedidos', 'forma_pagamento')) {
                    $table->string('forma_pagamento')->nullable();
                }
                if (!Schema::hasColumn('pedidos', 'lat_entrega')) {
                    $table->string('lat_entrega', 50)->nullable();
                }
                if (!Schema::hasColumn('pedidos', 'lng_entrega')) {
                    $table->string('lng_entrega', 50)->nullable();
                }
                if (!Schema::hasColumn('pedidos', 'avaliacao_do_entregador_concluida')) {
                    $table->boolean('avaliacao_do_entregador_concluida')->default(false);
                }
            });
        }

        // 5. Tabela USERS
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'telefone')) {
                    $table->string('telefone')->nullable();
                }
                if (!Schema::hasColumn('users', 'pontos')) {
                    $table->integer('pontos')->default(0);
                }
                if (!Schema::hasColumn('users', 'modelo_veiculo')) {
                    $table->string('modelo_veiculo')->nullable();
                }
                if (!Schema::hasColumn('users', 'placa_veiculo')) {
                    $table->string('placa_veiculo')->nullable();
                }
                if (!Schema::hasColumn('users', 'nome_loja')) {
                    $table->string('nome_loja')->nullable();
                }
                if (!Schema::hasColumn('users', 'logo_loja')) {
                    $table->text('logo_loja')->nullable();
                }
            });
        }

        // 6. Tabela CLIENTES
        if (Schema::hasTable('clientes')) {
            Schema::table('clientes', function (Blueprint $table) {
                if (!Schema::hasColumn('clientes', 'pontos')) {
                    $table->integer('pontos')->default(0);
                }
            });
        }
        
        // 7. Tabela PRODUTOS
        if (Schema::hasTable('produtos')) {
            Schema::table('produtos', function (Blueprint $table) {
                if (!Schema::hasColumn('produtos', 'preco_antigo')) {
                    $table->decimal('preco_antigo', 8, 2)->nullable();
                }
                if (!Schema::hasColumn('produtos', 'imagem_url')) {
                    $table->text('imagem_url')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Vazio de propósito. Como é uma migration de segurança em massa, 
        // não queremos correr o risco de dropar colunas acidentalmente num rollback.
    }
};