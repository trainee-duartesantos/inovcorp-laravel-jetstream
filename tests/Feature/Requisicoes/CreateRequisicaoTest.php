<?php

namespace Tests\Feature\Requisicoes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Livro;

class CreateRequisicaoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function utilizador_pode_criar_requisicao_de_livro()
    {
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
}
