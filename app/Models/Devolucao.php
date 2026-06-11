<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devolucao extends Model
{
    protected $table = 'devolucoes';

    protected $fillable = [
        'venda_id',
        'user_id',
        'data_devolucao',
        'motivo_devolucao',
        'valor_estornado',
    ];

    public function itens()
    {
        return $this->hasMany(ItemDevolucao::class, 'devolucao_id');
    }

    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }
}