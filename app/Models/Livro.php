<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Requisicao;


class Livro extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'isbn_hash',
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
        if (!$value) return;

        // Hash SEMPRE do valor em claro
        $this->attributes['isbn_hash'] = hash('sha256', $value);

        // Cifra o valor
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

    public function getDisponivelAttribute($value)
    {
        // Se a BD diz que não está disponível
        if (!$value) {
            return false;
        }

        // Se existe uma requisição ativa, também não está disponível
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

    public static function relacionados(Livro $livro, $limit = 3)
    {
        $baseTokens = \App\Helpers\TextSimilarity::tokenize($livro->bibliografia);
        $baseVector = \App\Helpers\TextSimilarity::vectorize($baseTokens);

        return Livro::where('id', '!=', $livro->id)
            ->get()
            ->map(function ($outro) use ($baseVector) {
                $tokens = \App\Helpers\TextSimilarity::tokenize($outro->bibliografia);
                $vector = \App\Helpers\TextSimilarity::vectorize($tokens);

                $score = \App\Helpers\TextSimilarity::cosineSimilarity($baseVector, $vector);

                return [
                    'livro' => $outro,
                    'score' => $score
                ];
            })
            ->sortByDesc('score')
            ->take($limit)
            ->pluck('livro');
    }

    public function getCapaFinalAttribute(): string
{
    if ($this->capa_url && file_exists(storage_path('app/public/' . $this->capa_url))) {
        return asset('storage/' . $this->capa_url);
    }

    return asset('storage/images/placeholders/book-placeholder.png');
}

}