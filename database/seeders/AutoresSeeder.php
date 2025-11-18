<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Autor;

class AutoresSeeder extends Seeder
{
    public function run(): void
    {
        $autores = [
            ['nome' => 'J.R.R. Tolkien', 'foto_url' => 'images/autores/tolkien.jpg'],
            ['nome' => 'George Orwell', 'foto_url' => 'images/autores/orwell.jpg'],
            ['nome' => 'Miguel de Cervantes', 'foto_url' => 'images/autores/cervantes.jpg'],
            ['nome' => 'José Saramago', 'foto_url' => 'images/autores/saramago.jpg'],
            ['nome' => 'Umberto Eco', 'foto_url' => 'images/autores/eco.jpg'],
        ];

        foreach ($autores as $autor) {
            Autor::create($autor);
        }
    }
}
