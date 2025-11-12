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
        // Limpar dados existentes na ordem correta
        DB::table('autor_livro')->truncate();
        Livro::truncate();
        Autor::truncate(); 
        Editora::truncate();

        // 1. Criar editoras primeiro
        $editoras = [
            ['nome' => 'Porto Editora', 'logo_url' => null],
            ['nome' => 'Penguin Random House', 'logo_url' => null],
            ['nome' => 'Editora Leya', 'logo_url' => null],
            ['nome' => 'Bertrand Editora', 'logo_url' => null]
        ];

        $editoraIds = [];
        foreach ($editoras as $editoraData) {
            $editora = Editora::create($editoraData);
            $editoraIds[$editoraData['nome']] = $editora->id;
        }

        // 2. Criar autores
        $autores = [
            ['nome' => 'J.R.R. Tolkien', 'foto_url' => null],
            ['nome' => 'George Orwell', 'foto_url' => null],
            ['nome' => 'Miguel de Cervantes', 'foto_url' => null],
            ['nome' => 'José Saramago', 'foto_url' => null],
            ['nome' => 'Umberto Eco', 'foto_url' => null]
        ];

        $autorIds = [];
        foreach ($autores as $autorData) {
            $autor = Autor::create($autorData);
            $autorIds[$autorData['nome']] = $autor->id;
        }

        // 3. Criar livros - USANDO editora_id E capa_url
        $livrosData = [
            [
                'isbn' => '978-972-004-621-2',
                'nome' => 'O Senhor dos Anéis',
                'editora_id' => $editoraIds['Editora Leya'],
                'bibliografia' => 'Uma jornada épica pela Terra Média onde a Sociedade do Anel tenta destruir o Um Anel para salvar a Terra Média das trevas.',
                'preco' => '24.99',
                'capa' => '📖', // Manter o emoji original
                'capa_url' => 'images/livros/senhor-dos-aneis.jpg' // Nova imagem real
            ],
            [
                'isbn' => '978-972-0-07061-0', 
                'nome' => '1984',
                'editora_id' => $editoraIds['Porto Editora'],
                'bibliografia' => 'Um clássico da distopia sobre vigilância total e controle governamental numa sociedade futurista.',
                'preco' => '16.50',
                'capa' => '📖',
                'capa_url' => 'images/livros/1984.jpg'
            ],
            [
                'isbn' => '978-972-004-732-5',
                'nome' => 'Dom Quixote de La Mancha',
                'editora_id' => $editoraIds['Porto Editora'],
                'bibliografia' => 'As aventuras do famoso cavaleiro andante e seu fiel escudeiro Sancho Pança pela Espanha.',
                'preco' => '19.99',
                'capa' => '📖',
                'capa_url' => 'images/livros/don-quixote.jpg'
            ],
            [
                'isbn' => '978-972-004-823-0',
                'nome' => 'O Nome da Rosa',
                'editora_id' => $editoraIds['Editora Leya'],
                'bibliografia' => 'Mistério medieval num mosteiro beneditino onde uma série de crimes acontece na biblioteca.',
                'preco' => '22.75',
                'capa' => '📖',
                'capa_url' => 'images/livros/nome-da-rosa.jpg'
            ]
        ];

        $livroIds = [];
        foreach ($livrosData as $livroData) {
            $livro = Livro::create($livroData);
            $livroIds[$livroData['nome']] = $livro->id;
        }

        // 4. Associar autores aos livros (tabela pivot autor_livro)
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

        $this->command->info('✅ Seeder executado com sucesso!');
        $this->command->info('📚 Livros criados: ' . Livro::count());
        $this->command->info('✍️ Autores criados: ' . Autor::count());
        $this->command->info('🏢 Editoras criadas: ' . Editora::count());
    }
}