<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\BookSimilarityService;
use App\Models\AlertaLivro;
use App\Mail\LivroDisponivelMail;
use Illuminate\Support\Facades\Mail;

class LivroController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->input('q'));

        $livros = Livro::with(['editora', 'autores'])
            ->when($query, function ($qBuilder) use ($query) {

                $qBuilder->where(function ($sub) use ($query) {

                    $sub->where('nome', 'like', "%{$query}%")   // título
                        ->orWhere('isbn', 'like', "%{$query}%") // isbn
                        ->orWhereHas('editora', function ($q) use ($query) {
                            $q->where('nome', 'like', "%{$query}%");
                        })
                        ->orWhereHas('autores', function ($q) use ($query) {
                            $q->where('nome', 'like', "%{$query}%");
                        });
                });
            })
            ->orderBy('nome')
            ->paginate(12);

        return view('livros.index', compact('livros', 'query'));
    }


    public function show(Livro $livro)
    {
        $livro->load([
            'editora',
            'autores',
            'reviews.user',
            'requisicoes.user'
        ]);

        // 📌 Apenas reviews aprovadas (status = 1)
        $reviews = $livro->reviews()
            ->where('status', 1)
            ->latest()
            ->get();

        // 📌 Dados de rating
        $mediaRating = $reviews->avg('rating')
            ? round($reviews->avg('rating'), 1)
            : null;
        $totalReviews = $reviews->count();

        // 📌 Histórico requisições (sem alteração)
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

        $podeAvaliar = false;

        if (auth()->check()) {
            $podeAvaliar = $livro->requisicoes()
                ->where('user_id', auth()->id())
                ->where('estado', 'entregue')
                ->exists();
        }

        $relacionados = Livro::relacionados($livro, 3);

        // LIVROS RELACIONADOS INTELIGENTES 
        $service = new BookSimilarityService();
        $relacionados = $service->getRelatedBooks($livro, 3);

        return view('livros.show', compact(
            'livro',
            'historico',
            'sugestoes',
            'mediaRating',
            'totalReviews',
            'reviews',
            'podeAvaliar',
            'relacionados'
        ));

    }

    // SUBMETER / EDITAR REVIEW
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

    public function alerta(Livro $livro)
    {
        if ($livro->disponivel) {
            return back()->with('error', 'O livro já está disponível!');
        }

        AlertaLivro::firstOrCreate([
            'user_id' => auth()->id(),
            'livro_id' => $livro->id,
        ]);

        return back()->with('success', 'Será avisado quando o livro estiver disponível 👍');
    }
}
