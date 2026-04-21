<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    // Liberando os campos para serem salvos
    protected $fillable = [
        'comercio_id', 
        'nome', 
        'marca', 
        'preco', 
        'preco_antigo', 
        'imagem_url'
    ];
}