<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisicao extends Model
{
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function livro() {
        return $this->belongsTo(Livro::class);
    }

}
