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
        $temas = [
            'fiction',
            'science',
            'fantasy',
            'history',
            'business'
        ];

        $totalCriados = 0;

        foreach ($temas as $tema) {
            $this->command->info("📚 A importar livros do tema: $tema");

            // Google Books devolve máx. 40, vamos buscar 20
            $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
                'q'         => $tema,
                'maxResults'=> 20,
            ]);

            $dados = $response->json();

            if (!isset($dados['items'])) {
                $this->command->error("⚠ Nenhum resultado para $tema");
                continue;
            }

            foreach ($dados['items'] as $item) {
                $info = $item['volumeInfo'] ?? [];

                // 1️⃣ ISBN obrigatório para evitar duplicação
                $isbn = $info['industryIdentifiers'][0]['identifier'] ?? null;
                if (!$isbn) continue;

                if (Livro::where('isbn', $isbn)->exists()) continue;

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
