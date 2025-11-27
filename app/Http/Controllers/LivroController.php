<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LivroController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        $livros = Livro::with(['editora', 'autores'])
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
        $livro->load([
            'editora',
            'autores',
            'reviews.user', // ⭐ carregar utilizadores das reviews
            'requisicoes.user'
        ]);

        // 📌 Dados resumidos de reviews
        $mediaRating = round($livro->reviews()->avg('rating'), 1);
        $totalReviews = $livro->reviews()->count();

        // 📌 Histórico requisições (como tinhas)
        $historico = $livro->requisicoes()
            ->with('user')
            ->orderByDesc('data_requisicao')
            ->get();

        // === 📡 Sugestões Google Books ===
        $query = $livro->nome;

        try {
            $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
                'q' => $query,
                'maxResults' => 6,
            ]);

            $data = $response->json();
            $sugestoes = collect($data['items'] ?? [])
                ->filter(fn($item) =>
                    ($item['volumeInfo']['title'] ?? '') !== $livro->nome
                )
                ->take(3);
        } catch (\Exception $e) {
            $sugestoes = collect();
        }

        return view('livros.show', compact(
            'livro',
            'historico',
            'sugestoes',
            'mediaRating',
            'totalReviews'
        ));
    }

    // 📌 SUBMETER / EDITAR REVIEW
    public function review(Request $request, Livro $livro)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = $livro->reviews()->updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'rating' => $request->rating,
                'comment' => $request->comment
            ]
        );

        return back()->with('success', $review->wasRecentlyCreated
            ? 'Avaliação registada! ⭐'
            : 'Avaliação atualizada! ⭐'
        );
    }
}
