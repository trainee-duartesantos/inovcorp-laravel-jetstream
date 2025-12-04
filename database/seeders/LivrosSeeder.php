<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Livro;
use App\Models\Editora;

class LivrosSeeder extends Seeder
{
    public function run(): void
    {
        $editoraLeya = Editora::where('nome', 'like', '%Leya%')->first();
        $editoraPorto = Editora::where('nome', 'like', '%Porto%')->first();

        if (!$editoraLeya || !$editoraPorto) {
            dd('❌ Editoras não encontradas! Verifica EditorasSeeder.');
        }

        $livros = [
            [
                'isbn' => '978-972-004-621-2',
                'nome' => 'O Senhor dos Anéis',
                'editora_id' => $editoraLeya->id,
                'bibliografia' => 'Uma jornada épica na Terra Média.',
                'preco' => "24.99",
                'capa_url' => 'images/livros/senhor-dos-aneis.jpg'
            ],
            [
                'isbn' => '978-972-0-07061-0',
                'nome' => '1984',
                'editora_id' => $editoraPorto->id,
                'bibliografia' => 'Distopia sobre vigilância e controle governamental.',
                'preco' => "16.50",
                'capa_url' => 'images/livros/1984.jpg'
            ],
        ];

        foreach ($livros as $livro) {
            Livro::create($livro);
        }

        echo "✔ Livros criados com sucesso!\n";
    }
}
