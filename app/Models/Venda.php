<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    protected $fillable = [
        'user_id',
        'cliente_nome',
        'cliente_telefone',
        'cliente_email',       
        'cliente_cpf_cnpj',
        'cliente_rg_ie',       
        'cliente_endereco',    
        'cliente_bairro',      
        'cliente_cidade',      
        'cliente_estado',      
        'cliente_cep',         
        'subtotal',
        'valor_desconto',
        'tipo_desconto',
        'total',
        'status',
        'observacoes'
    ];

    public function itens()
    {
        return $this->hasMany(ItemVenda::class, 'venda_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

