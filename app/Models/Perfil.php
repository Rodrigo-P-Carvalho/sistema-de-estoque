<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $table = 'perfis';

    protected $fillable = ['descricao', 'permissoes'];

    protected $casts = [
        'permissoes' => 'array',
    ];

    /**
     * HasMany serve para fazer o perfil ter varios usuarios vinculados
     * exemplo: 4 adms, 10 gerentes, etc
     * então no users a gente pode colocar o ID de gerente e o usuario assim vai ter permissão
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
