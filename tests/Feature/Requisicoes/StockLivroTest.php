<?php

namespace Tests\Feature\Requisicoes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Livro;
use App\Models\Requisicao;

class StockLivroTest extends TestCase
{
    use RefreshDatabase;

    public function test_nao_permite_requisitar_livro_sem_disponibilidade()
    {
        $user = User::factory()->create();
        $outroUser = User::factory()->create();

        $livro = Livro::factory()->create();

        // 👇 simula livro já requisitado (indisponível)
        Requisicao::create([
            'numero' => 'R-0001',
            'user_id' => $outroUser->id,
            'livro_id' => $livro->id,
            'data_requisicao' => now(),
            'data_prevista' => now()->addDays(5),
            'estado' => 'ativa',
        ]);

        $response = $this->actingAs($user)
            ->post(route('requisicoes.store'), [
                'livro_id' => $livro->id,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        // ✔ continua apenas 1 requisição (a original)
        $this->assertEquals(
            1,
            Requisicao::where('livro_id', $livro->id)->count()
        );
    }
}
