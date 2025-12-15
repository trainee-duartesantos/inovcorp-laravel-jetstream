<?php

namespace Database\Factories;

use App\Models\Livro;
use App\Models\Editora;
use Illuminate\Database\Eloquent\Factories\Factory;

class LivroFactory extends Factory
{
    protected $model = Livro::class;

    public function definition(): array
    {
        return [
            'isbn'         => fake()->isbn13(),
            'nome'         => fake()->sentence(3),
            'editora_id'   => Editora::factory(),
            'bibliografia' => fake()->paragraph(),
            'preco'        => fake()->randomFloat(2, 5, 50),
            'disponivel'   => true,
            'capa_url'     => null,
            'capa'         => null,
        ];
    }
}
