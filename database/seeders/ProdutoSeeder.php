<?php

namespace Database\Seeders;

use App\Models\Produto;
use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        Produto::create([
            'nome' => 'Alternador 12V 90A',
            'marca' => 'Bosch',
            'descricao' => 'alternador da bosch, teste',
            'preco' => 489.90,
            'estoque' => 15,
            'quantidade_minima' => 5,
            'codigo_interno' => 'ALT-2041',
            'codigo_barras' => '7891234567890',
            'localizacao' => 'Corredor A - Prateleira 3'
        ]);

        Produto::create([
            'nome' => 'Motor de Partida ZM',
            'marca' => 'ZM SA',
            'preco' => 620.00,
            'estoque' => 2,
            'quantidade_minima' => 4, // Vai disparar o alerta de estoque crítico
            'codigo_interno' => 'MOT-0912',
            'codigo_barras' => '7890001112223',
            'localizacao' => 'Corredor C - Gaveta 12'
        ]);
    }
}