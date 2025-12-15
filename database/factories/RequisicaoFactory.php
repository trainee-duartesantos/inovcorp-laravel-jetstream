<?php

namespace Database\Factories;

use App\Models\Livro;
use App\Models\Requisicao;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Requisicao>
 */
class RequisicaoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'numero' => 'R-' . str_pad((string) fake()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'user_id' => User::factory(),
            'livro_id' => Livro::factory(),
            'data_requisicao' => now(),
            'data_prevista' => now()->addDays(5),
            'estado' => 'ativa',
        ];
    }
}
