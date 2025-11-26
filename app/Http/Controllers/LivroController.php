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

        // === 📡 Sugestões Google Books ===
        $query = $livro->nome;

        try {
            $response = \Illuminate\Support\Facades\Http::get(
                'https://www.googleapis.com/books/v1/volumes',
                [
                    'q' => $query,
                    'maxResults' => 6,
                    // 'key' => config('services.google_books.key') // opcional se tiver key
                ]
            );

            $data = $response->json();
            $sugestoes = collect($data['items'] ?? [])
                ->filter(function ($item) use ($livro) {
                    return ($item['volumeInfo']['title'] ?? '') !== $livro->nome;
                })
                ->take(3);
        } catch (\Exception $e) {
            $sugestoes = collect();
        }

        return view('livros.show', [
            'livro' => $livro,
            'historico' => $historico,
            'sugestoes' => $sugestoes,
        ]);
    }



}
