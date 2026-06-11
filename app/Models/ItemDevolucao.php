<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemDevolucao extends Model
{
    protected $table = 'itens_devolucao';

    protected $fillable = [
        'devolucao_id',
        'produto_id',
        'quantidade_devolvida',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}