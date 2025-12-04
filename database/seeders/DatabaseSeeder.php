<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            //AutoresSeeder::class,
            //EditorasSeeder::class,
            //LivrosSeeder::class,
            //AutorLivroSeeder::class,
            BibliotecaRealSeeder::class,
            GoogleBooksMegaSeeder::class,
        ]);

        echo "✔ Base de dados totalmente populada com sucesso!\n";
    }
}
