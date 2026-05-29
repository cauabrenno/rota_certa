<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoriaDeProduto extends Model
{
    // Define explicitamente o nome da tabela no banco de dados
    protected $table = 'categorias_de_produtos';

    // Campos permitidos para preenchimento em lote
    protected $fillable = [
        'lojista_id',
        'nome',
    ];

    /**
     * Relação de pertencimento: uma categoria pertence a um lojista.
     */
    public function lojista(): BelongsTo
    {
        return $this->belongsTo(Lojista::class, 'lojista_id');
    }
}
