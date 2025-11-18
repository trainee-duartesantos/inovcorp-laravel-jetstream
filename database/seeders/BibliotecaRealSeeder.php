<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Livro;
use App\Models\Autor;
use App\Models\Editora;
use Illuminate\Support\Facades\DB;

class BibliotecaRealSeeder extends Seeder
{
    public function run()
    {
        // 🔐 Desativar FKs antes de truncar
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Limpar dados existentes
        DB::table('autor_livro')->truncate();
        Livro::truncate();
        Autor::truncate();
        Editora::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ----- 1️⃣ Editoras -----
        $editoras = [
            ['nome' => 'Porto Editora', 'logo_url' => 'images/editoras/porto-editora.jpg'],
            ['nome' => 'Penguin Random House', 'logo_url' => 'images/editoras/penguin.jpg'],
            ['nome' => 'Editora Leya', 'logo_url' => 'images/editoras/leya.jpg'],
            ['nome' => 'Bertrand Editora', 'logo_url' => 'images/editoras/bertrand.jpg'],
        ];

        $editoraIds = [];
        foreach ($editoras as $editoraData) {
            $editora = Editora::create($editoraData);
            $editoraIds[$editoraData['nome']] = $editora->id;
        }

        // ----- 2️⃣ Autores -----
        $autores = [
            ['nome' => 'J.R.R. Tolkien', 'foto_url' => 'images/autores/tolkien.jpg'],
            ['nome' => 'George Orwell', 'foto_url' => 'images/autores/orwell.jpg'],
            ['nome' => 'Miguel de Cervantes', 'foto_url' => 'images/autores/cervantes.jpg'],
            ['nome' => 'José Saramago', 'foto_url' => 'images/autores/saramago.jpg'],
            ['nome' => 'Umberto Eco', 'foto_url' => 'images/autores/eco.jpg'],
        ];

        $autorIds = [];
        foreach ($autores as $autorData) {
            $autor = Autor::create($autorData);
            $autorIds[$autorData['nome']] = $autor->id;
        }

        // ----- 3️⃣ Livros -----
        $livrosData = [
            [
                'isbn' => '978-972-004-621-2',
                'nome' => 'O Senhor dos Anéis',
                'editora_id' => $editoraIds['Editora Leya'],
                'bibliografia' => 'Uma jornada épica pela Terra Média.',
                'preco' => 24.99,
                'capa_url' => 'images/livros/senhor-dos-aneis.jpg'
            ],
            [
                'isbn' => '978-972-0-07061-0',
                'nome' => '1984',
                'editora_id' => $editoraIds['Porto Editora'],
                'bibliografia' => 'Distopia clássica sobre vigilância governamental.',
                'preco' => 16.50,
                'capa_url' => 'images/livros/1984.jpg'
            ],
            [
                'isbn' => '978-972-004-732-5',
                'nome' => 'Dom Quixote de La Mancha',
                'editora_id' => $editoraIds['Porto Editora'],
                'bibliografia' => 'Aventura icónica do cavaleiro andante.',
                'preco' => 19.99,
                'capa_url' => 'images/livros/dom-quixote.jpg'
            ],
            [
                'isbn' => '978-972-004-823-0',
                'nome' => 'O Nome da Rosa',
                'editora_id' => $editoraIds['Editora Leya'],
                'bibliografia' => 'Mistério medieval num mosteiro beneditino.',
                'preco' => 22.75,
                'capa_url' => 'images/livros/nome-da-rosa.jpg'
            ],
        ];

        $livroIds = [];
        foreach ($livrosData as $livroData) {
            $livro = Livro::create($livroData);
            $livroIds[$livroData['nome']] = $livro->id;
        }

        // ----- 4️⃣ Pivot autor_livro -----
        $associations = [
            'O Senhor dos Anéis' => ['J.R.R. Tolkien'],
            '1984' => ['George Orwell'],
            'Dom Quixote de La Mancha' => ['Miguel de Cervantes'],
            'O Nome da Rosa' => ['Umberto Eco']
        ];

        foreach ($associations as $livroNome => $autoresNomes) {
            $livroId = $livroIds[$livroNome];

            foreach ($autoresNomes as $autorNome) {
                $autorId = $autorIds[$autorNome];
                DB::table('autor_livro')->insert([
                    'autor_id' => $autorId,
                    'livro_id' => $livroId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        $this->command->info("📚 Biblioteca realista populada com sucesso!");
    }
}
