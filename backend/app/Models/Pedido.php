<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'user_id',
        'lojista_id',
        'entregador_id',
        'valor_total',
        'taxa_entrega',
        'endereco_entrega',
        'descricao',
        'status',
        'codigo_entrega',
        'forma_pagamento'
    ];

    // Relacionamento: Um pedido tem vários produtos
    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'pedido_produto')
                    ->withPivot('quantidade', 'preco_unitario')
                    ->withTimestamps();
    }
}