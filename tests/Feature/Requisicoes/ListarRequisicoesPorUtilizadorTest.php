<?php

namespace Tests\Feature\Requisicoes;

use Tests\TestCase;
use App\Models\User;
use App\Models\Livro;
use App\Models\Requisicao;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListarRequisicoesPorUtilizadorTest extends TestCase
{
    use RefreshDatabase;

    // 4. Teste de Listagem de Requisições por Utilizador
    public function test_utilizador_ve_apenas_as_suas_requisicoes()
    {
        // 🔹 Criar utilizadores
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        // 🔹 Criar livros
        $livro1 = Livro::factory()->create();
        $livro2 = Livro::factory()->create();

        // 🔹 Requisições do utilizador A
        $reqA1 = Requisicao::factory()->create([
            'user_id' => $userA->id,
            'livro_id' => $livro1->id,
        ]);

        $reqA2 = Requisicao::factory()->create([
            'user_id' => $userA->id,
            'livro_id' => $livro2->id,
        ]);

        // 🔹 Requisição do utilizador B
        $reqB = Requisicao::factory()->create([
            'user_id' => $userB->id,
        ]);

        // 🔹 Simular login do utilizador A
        $response = $this
            ->actingAs($userA)
            ->get(route('requisicoes.index'));

        // 🔹 Verificações
        $response->assertStatus(200);

        // Deve ver as suas
        $response->assertSee((string) $reqA1->numero);
        $response->assertSee((string) $reqA2->numero);

        // NÃO deve ver as dos outros
        $response->assertDontSee((string) $reqB->numero);
    }
}
