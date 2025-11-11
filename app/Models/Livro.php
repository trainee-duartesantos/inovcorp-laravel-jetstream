<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Livro extends Model
{
    protected $fillable = [
        'isbn', 'nome', 'editora_id', 'bibliografia', 'preco', 'capa'
    ];

    // 🔐 CIFRAR ao guardar na base de dados
    public function setPrecoAttribute($value)
    {
        $this->attributes['preco'] = Crypt::encryptString($value);
    }

    public function setIsbnAttribute($value)
    {
        $this->attributes['isbn'] = Crypt::encryptString($value);
    }

    public function setBibliografiaAttribute($value)
    {
        $this->attributes['bibliografia'] = Crypt::encryptString($value);
    }

    // 🔓 DESCIFRAR ao ler da base de dados
    public function getPrecoAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function getIsbnAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function getBibliografiaAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    // Relações
    public function autores()
    {
        return $this->belongsToMany(Autor::class);
    }

    public function editora()
    {
        return $this->belongsTo(Editora::class);
    }
}