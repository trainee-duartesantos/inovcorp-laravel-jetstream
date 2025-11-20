<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Livro;
use App\Models\Autor;
use App\Models\Editora;
use Faker\Factory as Faker;

class FakeDataSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('pt_PT');

        // Criar editoras fake (se existirem, reutiliza)
        for ($i = 0; $i < 5; $i++) {
            Editora::firstOrCreate([
                'nome' => $faker->company()
            ]);
        }

        // Criar 20 autores aleatórios
        for ($i = 0; $i < 20; $i++) {
            Autor::firstOrCreate([
                'nome' => $faker->name(),
                'foto_url' => 'https://picsum.photos/seed/autor'.$i.'/200/200'
            ]);
        }

        // Criar 30 livros aleatórios
        for ($i = 0; $i < 30; $i++) {
            $livro = Livro::create([
                'isbn' => $faker->isbn13(),
                'nome' => $faker->sentence(3),
                'editora_id' => Editora::inRandomOrder()->first()->id,
                'bibliografia' => $faker->paragraph(),
                'preco' => $faker->randomFloat(2, 5, 40),
                'capa_url' => 'https://picsum.photos/seed/livro'.$i.'/200/300',
                'disponivel' => true,
            ]);

            // Associar 1 a 2 autores
            $autores = Autor::inRandomOrder()->take(rand(1, 2))->pluck('id');
            $livro->autores()->attach($autores);
        }
    }
}
