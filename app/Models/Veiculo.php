<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    protected $fillable = ['marca', 'modelo', 'ano'];

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'produto_veiculo');
    }
}