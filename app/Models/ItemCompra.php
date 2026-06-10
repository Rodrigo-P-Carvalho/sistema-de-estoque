<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCompra extends Model
{
    use HasFactory;

    protected $table = 'itens_compra';

    // Como a sua tabela tem chave primária composta nas migrations, precisamos indicar isso
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'compra_id',
        'produto_id',
        'quantidade',
        'custo_unitario',
        'subtotal'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }
}