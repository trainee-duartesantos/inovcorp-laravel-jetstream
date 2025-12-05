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

        // 🔹 Normalizar paths das fotos dos autores
        $autores = Autor::all()->map(function ($autor) {
            if ($autor->foto_url) {
                $autor->foto_url = 'images/autores/' . basename($autor->foto_url);
            }
            return $autor;
        });

        // 🔹 Normalizar paths dos logótipos das editoras
        $editoras = Editora::all()->map(function ($editora) {
            if ($editora->logo_url) {
                $editora->logo_url = 'images/editoras/' . basename($editora->logo_url);
            }
            return $editora;
        });

        return view('dashboard', [
            'autores'  => $autores,
            'editoras' => $editoras,
            'livros'   => $livros,
        ]);
    }
}
