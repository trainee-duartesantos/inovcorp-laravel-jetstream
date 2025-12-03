<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Livro;
use App\Models\Autor;
use App\Models\Editora;

class RealLibrarySeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('autor_livro')->truncate();
        Livro::truncate();
        Autor::truncate();
        Editora::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1️⃣ EDITORAS
        $editoras = [
            'Leya' => 'images/editoras/leya.jpg',
            'Porto Editora' => 'images/editoras/porto-editora.jpg',
            'Penguin Random House' => 'images/editoras/penguin.jpg',
            'Bertrand Editora' => 'images/editoras/bertrand.jpg',
        ];

        $editorasIds = [];
        foreach ($editoras as $nome => $logo) {
            $editorasIds[$nome] = Editora::forceCreate([
                'nome' => $nome,
                'logo_url' => $logo
            ])->id;
        }

        // 2️⃣ AUTORES
        $autores = [
            'J.R.R. Tolkien' => 'images/autores/tolkien.jpg',
            'George Orwell' => 'images/autores/orwell.jpg',
            'Miguel de Cervantes' => 'images/autores/cervantes.jpg',
            'José Saramago' => 'images/autores/saramago.jpg',
            'Umberto Eco' => 'images/autores/eco.jpg',
        ];

        $autoresIds = [];
        foreach ($autores as $nome => $foto) {
            $autoresIds[$nome] = Autor::forceCreate([
                'nome' => $nome,
                'foto_url' => $foto
            ])->id;
        }

        // 3️⃣ LIVROS
        $livros = [
            [
                'nome' => 'O Senhor dos Anéis',
                'isbn' => '978-972-004-621-2',
                'editora' => 'Leya',
                'capa' => 'images/livros/senhor-dos-aneis.jpg',
                'descricao' => 'Uma jornada épica pela Terra Média.',
                'preco' => 24.99,
                'autores' => ['J.R.R. Tolkien'],
            ],

            [
                'nome' => '1984',
                'isbn' => '978-972-0-07061-0',
                'editora' => 'Porto Editora',
                'capa' => 'images/livros/1984.jpg',
                'descricao' => 'Distopia clássica sobre vigilância governamental.',
                'preco' => 16.50,
                'autores' => ['George Orwell'],
            ],

            [
                'nome' => 'Dom Quixote de La Mancha',
                'isbn' => '978-972-004-732-5',
                'editora' => 'Porto Editora',
                'capa' => 'images/livros/dom-quixote.jpg',
                'descricao' => 'A aventura épica do cavaleiro andante.',
                'preco' => 19.99,
                'autores' => ['Miguel de Cervantes'],
            ],

            [
                'nome' => 'O Nome da Rosa',
                'isbn' => '978-972-004-823-0',
                'editora' => 'Leya',
                'capa' => 'images/livros/nome-da-rosa.jpg',
                'descricao' => 'Mistério medieval num mosteiro beneditino.',
                'preco' => 22.75,
                'autores' => ['Umberto Eco'],
            ],
        ];

        foreach ($livros as $data) {

            $livro = Livro::forceCreate([
                'nome' => $data['nome'],
                'isbn' => $data['isbn'],
                'editora_id' => $editorasIds[$data['editora']],
                'capa_url' => $data['capa'],
                'bibliografia' => $data['descricao'],
                'preco' => $data['preco'],
                'disponivel' => true,
            ]);

            foreach ($data['autores'] as $autor) {
                $livro->autores()->attach($autoresIds[$autor]);
            }
        }

        $this->command->info("📚 Biblioteca realista criada com sucesso!");
    }
}
