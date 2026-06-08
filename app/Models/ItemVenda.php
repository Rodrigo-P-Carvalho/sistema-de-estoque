<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemVenda extends Model
{
    protected $table = 'itens_venda';
    protected $guarded = [];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
