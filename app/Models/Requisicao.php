<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Requisicao extends Model
{
    use HasFactory;

    protected $table = 'requisicoes';

    protected $fillable = [
        'numero',
        'user_id',
        'livro_id',
        'foto_cidadao',
        'data_requisicao',
        'data_prevista',
        'data_entrega',
        'estado',
    ];

    protected $casts = [
        'data_requisicao' => 'datetime',
        'data_prevista'   => 'datetime',
        'data_entrega'    => 'datetime',
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
    
    public function getDiasDecorridosAttribute()
    {
        if (!$this->data_requisicao) return null;

        return (int) now()->diffInRealDays($this->data_requisicao);
    }


    public function getEstadoFormatadoAttribute()
    {
        return match ($this->estado) {
            'ativa'     => 'Ativa',
            'entregue'  => 'Entregue',
            'atrasada'  => 'Atrasada',
            'cancelada' => 'Cancelada',
            default     => ucfirst($this->estado ?? 'Desconhecido'),
        };
    }

    public function getEstadoBadgeAttribute()
    {
        return match ($this->estado) {
            'ativa'     => 'badge-info',
            'entregue'  => 'badge-success',
            'atrasada'  => 'badge-error',
            'cancelada' => 'badge-ghost',
            default     => 'badge-neutral',
        };
    }

}
