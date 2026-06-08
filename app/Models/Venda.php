<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    protected $guarded = []; // Permite salvar em todas as colunas

    public function itens()
    {
        return $this->hasMany(ItemVenda::class, 'venda_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

