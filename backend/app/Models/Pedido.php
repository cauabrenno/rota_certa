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
        'codigo_entrega'
    ];
}