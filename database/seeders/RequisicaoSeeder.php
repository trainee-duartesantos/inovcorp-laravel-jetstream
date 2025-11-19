<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Requisicao;

class RequisicaoSeeder extends Seeder
{
    public function run(): void
    {
        Requisicao::create([
            'numero' => 1,
            'user_id' => 1,
            'livro_id' => 1,
            'foto_cidadao' => 'images/fotos/default.jpg', // Apenas para teste
            'data_requisicao' => now()->toDateString(),
            'data_prevista' => now()->addDays(5)->toDateString(),
            'estado' => 'ativa'
        ]);

        echo "✔ Requisição criada com sucesso!\n";
    }
}
