<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class Livro extends Model
{
    protected $fillable = [
        'isbn',
        'nome',
        'editora_id',
        'bibliografia',
        'preco',
        'capa_url',
        'disponivel',
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
        if (!$value) return null;

        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value; // já está limpo
        }
    }

    public function getIsbnAttribute($value)
    {
        if (!$value) return null;

        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function getBibliografiaAttribute($value)
    {
        if (!$value) return null;

        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
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
    public function requisicoes()
    {
        return $this->hasMany(\App\Models\Requisicao::class);
    }

    public function getDisponivelAttribute()
    {
        return !Requisicao::where('livro_id', $this->id)
        ->where('estado', 'ativa')
        ->exists();
    }
    public function scopeOrWhereEncrypted($query, $column, $operator, $value)
    {
        return $query->orWhere(DB::raw("CAST(AES_DECRYPT($column, '" . config('app.key') . "') AS CHAR)"), $operator, $value);
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }


}