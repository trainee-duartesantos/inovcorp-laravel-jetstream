<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


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
        $data = json_decode($request->book, true);
        $volume = $data['volumeInfo'] ?? [];

        // 📌 ISBN
        $isbn = null;
        if (isset($volume['industryIdentifiers'][0]['identifier'])) {
            $isbn = $volume['industryIdentifiers'][0]['identifier'];
        }

        if (!$isbn) {
            return back()->with('error', 'Este livro não contém ISBN válido.');
        }

        // ❌ Se já existir um livro com este ISBN, não duplicar
        if (\App\Models\Livro::where('isbn', $isbn)->exists()) {
            return back()->with('error', 'Este livro já existe na base de dados.');
        }

        // 📌 Editora
        $editoraNome = $volume['publisher'] ?? 'Editora desconhecida';

        $editora = \App\Models\Editora::firstOrCreate([
            'nome' => $editoraNome
        ]);

        // 📌 Capa — Download
        $capaPath = null;
        if (isset($volume['imageLinks']['thumbnail'])) {
            $url = $volume['imageLinks']['thumbnail'];
            $image = file_get_contents($url);
            $filename = 'capas/' . uniqid() . '.jpg';

            Storage::disk('public')->put($filename, $image);
            $capaPath = $filename;
        }

        // 📌 Criar livro
        $livro = \App\Models\Livro::create([
            'isbn'         => $isbn,
            'nome'         => $volume['title'] ?? 'Sem título',
            'editora_id'   => $editora->id,
            'bibliografia' => $volume['description'] ?? 'Sem descrição disponível.',
            'preco'        => "0.00", // API Não tem preço → Pode ser ajustado mais tarde
            'capa_url'     => $capaPath,
            'disponivel'   => true,
        ]);

        // 📌 Autores
        if (isset($volume['authors'])) {
            foreach ($volume['authors'] as $autorNome) {
                $autor = \App\Models\Autor::firstOrCreate([
                    'nome' => $autorNome
                ]);

                // Associar autor ao livro
                $livro->autores()->attach($autor->id);
            }
        }

        return redirect()
        ->route('admin.livros')
        ->with('success', 'Livro importado com sucesso! 📚✨');
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
