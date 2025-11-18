<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Livro;
use App\Models\Autor;

class AutorLivroSeeder extends Seeder
{
    public function run(): void
    {
        $pivot = [
            'O Senhor dos Anéis' => ['J.R.R. Tolkien'],
            '1984' => ['George Orwell'],
            'Dom Quixote de La Mancha' => ['Miguel de Cervantes'],
            'O Nome da Rosa' => ['Umberto Eco'],
        ];

        foreach ($pivot as $livroNome => $autores) {
            $livro = Livro::where('nome', $livroNome)->first();

            foreach ($autores as $autorNome) {
                $autor = Autor::where('nome', $autorNome)->first();
                $livro->autores()->attach($autor->id);
            }
        }
    }
}
