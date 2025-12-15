<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Livro;
use App\Models\Autor;
use App\Models\Editora;

class GoogleBooksMegaSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 GoogleBooksMegaSeeder STARTED');
        
        $temas = [
            'fiction',
            'science',
            'fantasy',
            'history',
            'business',
            'technology',
            'philosophy',
            'psychology',
            'art',
            'music'
        ];

        $totalCriados = 0;

        foreach ($temas as $tema) {
            $this->command->info("📚 A importar livros do tema: $tema");

            try {
                // Google Books devolve máx. 40, vamos buscar 20
                $response = Http::withoutVerifying()->get(
                    'https://www.googleapis.com/books/v1/volumes',
                    [
                        'q' => $tema,
                        'maxResults' => 40,
                    ]
                );
            } catch (\Exception $e) {
                $this->command->error("❌ Falha SSL / HTTP para o tema: $tema");
                continue;
            }

            $dados = $response->json();

            if (!isset($dados['items'])) {
                $this->command->error("⚠ Nenhum resultado para $tema");
                continue;
            }

            foreach ($dados['items'] as $item) {
                $info = $item['volumeInfo'] ?? [];

                // 1️⃣ ISBN obrigatório para evitar duplicação
                $isbn = null;

                if (isset($info['industryIdentifiers'])) {
                    foreach ($info['industryIdentifiers'] as $identifier) {
                        if (in_array($identifier['type'], ['ISBN_13', 'ISBN_10'])) {
                            $isbn = $identifier['identifier'];
                            break;
                        }
                    }
                }

                if (!$isbn) {
                    continue;
                }

                $isbnHash = hash('sha256', $isbn);

                if (Livro::where('isbn_hash', $isbnHash)->exists()) continue;

                // 2️⃣ Editora
                $editoraNome = $info['publisher'] ?? 'Editora desconhecida';
                $editora = Editora::firstOrCreate([
                    'nome' => $editoraNome
                ]);

                // 3️⃣ Capa — download local
                $capaPath = null;
                if (isset($info['imageLinks']['thumbnail'])) {
                    $url = $info['imageLinks']['thumbnail'];
                    try {
                        $image = file_get_contents($url);
                        $filename = 'capas/' . uniqid() . '.jpg';
                        Storage::disk('public')->put($filename, $image);
                        $capaPath = $filename;
                    } catch (\Exception $e) {
                        $capaPath = null;
                    }
                }

                // 4️⃣ Criar livro
                $livro = Livro::create([
                    'isbn'         => $isbn,
                    'nome'         => $info['title'] ?? 'Sem título',
                    'editora_id'   => $editora->id,
                    'bibliografia' => $info['description'] ?? 'Sem descrição disponível.',
                    'preco'        => "0.00",
                    'capa_url'     => $capaPath,
                    'disponivel'   => true,
                ]);

                // 5️⃣ Criar autores e pivot
                if (isset($info['authors'])) {
                    foreach ($info['authors'] as $autorNome) {

                        $autor = Autor::firstOrCreate([
                            'nome' => $autorNome
                        ]);

                        $livro->autores()->attach($autor->id);
                    }
                }

                $totalCriados++;
            }
        }

        $this->command->info("🎉 Finalizado! {$totalCriados} livros reais importados.");
    }
}
