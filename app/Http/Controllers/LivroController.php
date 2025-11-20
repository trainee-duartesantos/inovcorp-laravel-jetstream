<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\Request;

class LivroController extends Controller
{
    public function index()
    {
        $livros = Livro::with('editora', 'autores')->get();

        return view('livros.index', compact('livros'));
    }

    public function show(Livro $livro)
    {
        $livro->load('editora', 'autores', 'requisicoes');

        return view('livros.show', compact('livro'));
    }
}
