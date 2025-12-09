<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'livro_id', 'quantity'];

    public function livro()
    {
        return $this->belongsTo(Livro::class);
    }
}
