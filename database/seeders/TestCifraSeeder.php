<?php

namespace Database\Seeders;

use App\Models\Editora;
use App\Models\Autor;
use App\Models\Livro; // ← MUDADO para Livro
use Illuminate\Database\Seeder;

class TestCifraSeeder extends Seeder
{
    public function run()
    {
        // Criar Editora
        $editora = Editora::create([
            'nome' => 'Editora Teste Cifra',
            'logotipo' => 'teste.png'
        ]);

        // Criar Autor
        $autor = Autor::create([
            'nome' => 'Autor Teste Cifrado',
            'foto' => 'autor.jpg'
        ]);

        // Criar Livro
        $livro = Livro::create([ // ← MUDADO para Livro
            'isbn' => '978-123-456-789-0',
            'nome' => 'Livro Teste Cifrado',
            'editora_id' => $editora->id,
            'bibliografia' => 'Esta bibliografia está cifrada na base de dados.',
            'preco' => '29.99',
            'capa' => 'capa.jpg'
        ]);

        // Associar autor ao livro
        $livro->autores()->attach($autor->id);

        $this->command->info('✅ Dados de teste com cifra criados!');
    }
}