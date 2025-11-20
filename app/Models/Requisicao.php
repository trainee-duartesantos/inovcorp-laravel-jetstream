<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Requisicao extends Model
{
    use HasFactory;

    protected $table = 'requisicoes';

    protected $fillable = [
        'numero',
        'user_id',
        'livro_id',
        'data_requisicao',
        'data_prevista',
        'data_entrega',
        'estado',
    ];

    protected $dates = [
        'data_requisicao',
        'data_prevista',
        'data_entrega',
    ];

    // Requisição pertence a um utilizador (cidadão)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Requisição pertence a um livro
    public function livro()
    {
        return $this->belongsTo(Livro::class);
    }

    // Accessor opcional para mostrar número formatado
    public function getCodigoAttribute()
    {
        // Usa o id como base. Ajusta se tiveres outra coluna.
        return 'REQ-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    // Escopo para requisicoes ativas (ainda não devolvidas)
    public function scopeAtivas($query)
    {
        return $query->where('estado', 'pending');
    }
}
