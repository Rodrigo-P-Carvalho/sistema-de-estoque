<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $table = 'perfis';

    // Mudamos de 'nome' para 'descricao' para casar com sua migration
    protected $fillable = ['descricao', 'permissoes'];

    // Pode manter! O cast 'array' transforma o 'text' do banco em array no PHP automaticamente
    protected $casts = [
        'permissoes' => 'array',
    ];
}