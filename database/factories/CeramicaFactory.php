<?php

namespace Database\Factories;

use App\Models\Obra;
use Illuminate\Database\Eloquent\Factories\Factory;

class CeramicaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_obra' => Obra::factory(),
            'tipo_arcilla' => fake()->randomElement(['Roja', 'Blanca', 'Gres', 'Porcelana']),
            'tecnica_coccion' => fake()->randomElement(['Horno eléctrico', 'Leña', 'Raku']),
        ];
    }
}