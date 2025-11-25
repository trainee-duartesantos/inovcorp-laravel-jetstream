<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\Request;

class LivroController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        $livros = \App\Models\Livro::with(['editora', 'autores'])
            ->when($query, function ($q) use ($query) {
                $q->where('nome', 'like', "%$query%")
                ->orWhere('isbn', 'like', "%$query%")
                ->orWhereHas('autores', function ($q) use ($query) {
                    $q->where('nome', 'like', "%$query%");
                })
                ->orWhereHas('editora', function ($q) use ($query) {
                    $q->where('nome', 'like', "%$query%");
                });
            })
            ->paginate(15);

        return view('livros.index', compact('livros', 'query'));
    }

    

    public function show(Livro $livro)
    {
        $historico = $livro->requisicoes()
            ->with('user')
            ->orderByDesc('data_requisicao')
            ->get();

        return view('livros.index', [
            'livros' => Livro::with(['editora'])->get()
        ]);
    }

}
