<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'perfil_id',
        'primeiro_acesso',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function perfil()
    {
        return $this->belongsTo(Perfil::class);
    }
    public function hasPermission($modulo)
    {
        // 1. Regra Master: ID 1 tem acesso total
        if ($this->id === 1) {
            return true;
        }

        // 2. Garante que o usuário tem perfil
        if (!$this->perfil) {
            return false;
        }

        // 3. Verifica se a permissão existe no array
        $permissoes = $this->perfil->permissoes ?? [];
        
        // Verifica se é um array, caso tenha sido salvo como string ou nulo no banco
        return is_array($permissoes) && in_array($modulo, $permissoes);
    }
}
