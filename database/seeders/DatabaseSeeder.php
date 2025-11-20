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
            EditorasSeeder::class, 
            AutoresSeeder::class,
            LivrosSeeder::class,
            RequisicaoSeeder::class, 
            BibliotecaRealSeeder::class,
            
            FakeDataSeeder::class,
        ]);

        echo "✔ Base de dados totalmente populada com sucesso!\n";
    }
}
