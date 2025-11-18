<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Editora;

class EditorasSeeder extends Seeder
{
    public function run(): void
    {
        $editoras = [
            ['nome' => 'Porto Editora', 'logo_url' => 'images/editoras/porto-editora.jpg'],
            ['nome' => 'Penguin Random House', 'logo_url' => 'images/editoras/penguin.jpg'],
            ['nome' => 'Editora Leya', 'logo_url' => 'images/editoras/leya.jpg'],
            ['nome' => 'Bertrand Editora', 'logo_url' => 'images/editoras/bertrand.jpg'],
        ];

        foreach ($editoras as $editora) {
            Editora::create($editora);
        }
    }
}
