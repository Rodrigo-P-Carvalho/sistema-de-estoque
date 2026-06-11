<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compras';

    protected $fillable = [
        'fornecedor_id', 
        'data_compra', 
        'subtotal', 
        'valor_desconto', 
        'tipo_desconto', 
        'total', 
        'observacoes'
    ];

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function itens()
    {
        return $this->hasMany(ItemCompra::class);
    }
}