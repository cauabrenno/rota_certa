<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvaliacaoDoEntregador extends Model
{
    protected $table = 'avaliacoes_dos_entregadores';

    protected $fillable = [
        'pedido_id',
        'entregador_id',
        'usuario_id',
        'nota_da_avaliacao',
        'motivos_da_avaliacao',
        'comentarios_adicionais',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function entregador()
    {
        return $this->belongsTo(User::class, 'entregador_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
