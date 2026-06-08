<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    use HasFactory;
    protected $table = 'veiculos';

    protected $fillable = [
        'marca',
        'modelo',
        'ano',
    ];

    protected function casts(): array
    {
        return [
            'ano' => 'integer',
        ];
    }

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'produto_veiculo', 'veiculo_id', 'produto_id');
    }
}