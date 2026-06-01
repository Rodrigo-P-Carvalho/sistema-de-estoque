<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    // Usando a variável padrão do Laravel para proteção de dados
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

    // Garante que os números venham nos formatos corretos (ex: preços com 2 casas decimais)
    protected function casts(): array
    {
        return [
            'preco' => 'decimal:2',
            'estoque' => 'integer',
            'quantidade_minima' => 'integer',
        ];
    }

    /**
     * Helper prático para o RF04 (Alerta de Estoque)
     * No seu Blade, você pode usar apenas: @if($produto->temEstoqueCritico())
     */
    public function temEstoqueCritico(): bool
    {
        return $this->estoque <= $this->quantidade_minima;
    }
    public function veiculos()
    {
        return $this->belongsToMany(Veiculo::class, 'produto_veiculo', 'produto_id', 'veiculo_id');
    }
}