<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GoogleBooksController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->query('q');

        if (!$query) {
            return view('admin.google-books.search');
        }

        $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
            'q' => $query,
            'maxResults' => 10,
        ]);

        $data = $response->json();

        return view('admin.google-books.search', [
            'results' => $data['items'] ?? []
        ]);
    }

    public function import(Request $request)
    {
        return back()->with('success', 'Recebido! Importação será feita no próximo passo 🚀');
    }

    /**
     * Apenas um teste simples para ver se a API responde.
     */
    public function test()
    {
        // 🔎 Termo de pesquisa de teste (mais tarde isto virá de um formulário)
        $query = 'harry potter';

        // 📡 Chamada à Google Books API
        $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
            'q'         => $query,
            'maxResults'=> 5,
            // 'key'    => config('services.google_books.key'), // opcional, se tiveres API key
        ]);

        if (! $response->successful()) {
            // Se algo correr mal, mostramos o erro
            abort(500, 'Erro ao contactar a Google Books API');
        }

        $data = $response->json();

        // Para já, vamos só ver o que vem da API
        // Mais tarde transformamos isto em tabela / importação
        return response()->json($data);
    }
}
