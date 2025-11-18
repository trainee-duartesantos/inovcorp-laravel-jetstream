<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Livro;
use App\Models\Editora;

class LivrosSeeder extends Seeder
{
    public function run(): void
    {
        $livros = [
            [
                'isbn' => '978-972-004-621-2',
                'nome' => 'O Senhor dos Anéis',
                'editora_id' => Editora::where('nome', 'Editora Leya')->first()->id,
                'bibliografia' => 'Uma jornada épica na Terra Média.',
                'preco' => 24.99,
                'capa_url' => 'images/livros/senhor-dos-aneis.jpg'
            ],
            [
                'isbn' => '978-972-0-07061-0',
                'nome' => '1984',
                'editora_id' => Editora::where('nome', 'Porto Editora')->first()->id,
                'bibliografia' => 'Distopia sobre vigilância e controle governamental.',
                'preco' => 16.50,
                'capa_url' => 'images/livros/1984.jpg'
            ],
            [
                'isbn' => '978-972-004-732-5',
                'nome' => 'Dom Quixote de La Mancha',
                'editora_id' => Editora::where('nome', 'Porto Editora')->first()->id,
                'bibliografia' => 'Aventuras do cavaleiro Dom Quixote.',
                'preco' => 19.99,
                'capa_url' => 'images/livros/dom-quixote.jpg'
            ],
            [
                'isbn' => '978-972-004-823-0',
                'nome' => 'O Nome da Rosa',
                'editora_id' => Editora::where('nome', 'Editora Leya')->first()->id,
                'bibliografia' => 'Mistério num mosteiro medieval.',
                'preco' => 22.75,
                'capa_url' => 'images/livros/nome-da-rosa.jpg'
            ]
        ];

        foreach ($livros as $livro) {
            Livro::create($livro);
        }
    }
}
