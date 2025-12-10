<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'livro_id',
        'quantity',
        'preco_unitario',
        'subtotal',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function livro()
    {
        return $this->belongsTo(\App\Models\Livro::class);
    }

}
