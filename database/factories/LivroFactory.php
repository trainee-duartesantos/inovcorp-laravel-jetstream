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
            'isbn' => $this->faker->isbn13(),
            'isbn_hash' => hash('sha256', $this->faker->isbn13()),
            'nome' => $this->faker->sentence(3),
            'editora_id' => Editora::factory(),
            'bibliografia' => $this->faker->paragraph(),
            'preco' => '10.00',
            'disponivel' => true,
        ];
    }
}
