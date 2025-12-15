<?php

namespace Tests\Feature\Requisicoes;

use Tests\TestCase;
use App\Models\User;
use App\Models\Livro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequisicaoCreatedMail;

class CreateRequisicaoTest extends TestCase
{
    use RefreshDatabase;

    public function test_utilizador_pode_criar_requisicao_de_livro()
    {
        Mail::fake();

        $this->withoutMiddleware();

        $user = User::factory()->create();
        $livro = Livro::factory()->create([
            'disponivel' => true,
        ]);

        $response = $this->actingAs($user)->post(route('requisicoes.store'), [
            'livro_id' => $livro->id,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('requisicoes', [
            'user_id'  => $user->id,
            'livro_id' => $livro->id,
            'estado'   => 'ativa',
        ]);

        $this->assertDatabaseHas('livros', [
            'id' => $livro->id,
            'disponivel' => false,
        ]);

        Mail::assertSent(RequisicaoCreatedMail::class);
    }
}
