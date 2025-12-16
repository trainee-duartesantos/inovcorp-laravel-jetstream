<?php

namespace Tests\Feature\Requisicoes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Livro;
use App\Models\Requisicao;
use Illuminate\Support\Facades\Mail;

class CreateRequisicaoTest extends TestCase
{
    use RefreshDatabase;

    // 1. Teste de Criação de Requisição de Livro
    public function test_utilizador_pode_criar_requisicao_de_livro()
    {
        Mail::fake();

        $user = User::factory()->create();
        $livro = Livro::factory()->create([
            'disponivel' => true,
        ]);

        $this->actingAs($user);

        $response = $this->withoutMiddleware()
            ->post(route('requisicoes.store'), [
                'livro_id' => $livro->id,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('requisicoes', [
            'user_id'  => $user->id,
            'livro_id' => $livro->id,
            'estado'   => 'ativa',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'module'  => 'requisicoes',
            'action'  => 'created',
        ]);
    }

    // 2. Teste de Validação de Requisição
    public function test_nao_permite_criar_requisicao_com_livro_inexistente()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->withoutMiddleware()->post(
            route('requisicoes.store'),
            ['livro_id' => 999999]
        );

        $response->assertSessionHasErrors('livro_id');

        $this->assertDatabaseCount('requisicoes', 0);
    }

    // 3. Teste de Devolução de Livro
    public function test_utilizador_pode_devolver_livro()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $livro = Livro::factory()->create(['disponivel' => false]);

        $requisicao = Requisicao::create([
            'numero'          => 'R-0001',
            'user_id'         => $user->id,
            'livro_id'        => $livro->id,
            'data_requisicao' => now(),
            'data_prevista'   => now()->addDays(5),
            'estado'          => 'ativa',
        ]);

        $response = $this
            ->actingAs($user)
            ->postJson(route('requisicoes.devolver', $requisicao));

        $response->assertStatus(200);

        $this->assertDatabaseHas('requisicoes', [
            'id'     => $requisicao->id,
            'estado' => 'entregue',
        ]);

        $this->assertDatabaseHas('livros', [
            'id'         => $livro->id,
            'disponivel' => true,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'module'  => 'requisicoes',
            'action'  => 'returned',
        ]);
    }
}
