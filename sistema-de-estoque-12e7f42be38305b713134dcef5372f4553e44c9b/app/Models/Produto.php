<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'marca',
        'descricao', 
        'preco',
        'estoque',
        'quantidade_minima',
        'codigo_barras',
        'codigo_interno',
        'localizacao',
    ];

    protected function casts(): array
    {
        return [
            'preco' => 'decimal:2',
            'estoque' => 'integer',
            'quantidade_minima' => 'integer',
        ];
    }


    public function temEstoqueCritico(): bool
    {
        return $this->estoque <= $this->quantidade_minima;
    }
    public function veiculos()
    {
        return $this->belongsToMany(Veiculo::class, 'produto_veiculo', 'produto_id', 'veiculo_id');
    }
}