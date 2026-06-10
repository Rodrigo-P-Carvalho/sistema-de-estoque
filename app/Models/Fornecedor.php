<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    use HasFactory;

    protected $table = 'fornecedores';
    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'telefone', 
        'email',    
    ];
    public function getCnpjFormatadoAttribute()
    {
        if (!$this->cnpj) return '-';
        
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "$1.$2.$3/$4-$5", $this->cnpj);
    }
    public function getTelefoneFormatadoAttribute()
    {
        if (!$this->telefone) return null;

        $tel = $this->telefone;
        
        if (strlen($tel) === 11) {
            return preg_replace("/(\d{2})(\d{5})(\d{4})/", "($1) $2-$3", $tel);
        }
        return preg_replace("/(\d{2})(\d{4})(\d{4})/", "($1) $2-$3", $tel);
    }
}