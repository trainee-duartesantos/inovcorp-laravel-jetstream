<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Editora extends Model
{
    protected $fillable = ['nome', 'logotipo'];

    // 🔐 CIFRAR nome
    public function setNomeAttribute($value)
    {
        $this->attributes['nome'] = Crypt::encryptString($value);
    }

    // 🔓 DESCIFRAR nome
    public function getNomeAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    // Relação com livros - AGORA CORRETO
    public function livros()
    {
        return $this->hasMany(Livro::class);
    }
}