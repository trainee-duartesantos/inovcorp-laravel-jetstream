<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Requisicao;
use App\Models\User;
use App\Models\Livro;

class RequisicaoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); // Usuário admin
        $livros = Livro::take(4)->get();

        foreach ($livros as $index => $livro) {
            $numero = 'REQ-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);

            Requisicao::create([
                'numero' => $numero,
                'user_id' => $user->id,
                'livro_id' => $livro->id,
                'foto_cidadao' => 'images/fotos/default.jpg',
                'data_requisicao' => now()->subDays(10),
                'data_prevista' => now()->subDays(3),
                'estado' => 'ativa',
            ]);
        }

        echo "✔ Requisições criadas para testes!\n";
    }
}
