<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Autor;
use App\Models\Editora;

class DashboardController extends Controller
{
    public function index()
    {
        $livros = Livro::with(['editora', 'autores'])->get();
        $autores = Autor::all();
        $editoras = Editora::all();

        return view('dashboard', compact('livros', 'autores', 'editoras'));
    }
}
