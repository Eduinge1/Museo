<?php

namespace Database\Factories;

use App\Models\Obra;
use Illuminate\Database\Eloquent\Factories\Factory;

class EsculturaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_obra' => Obra::factory(),
            'nombre_material' => fake()->randomElement(['Mármol', 'Bronce', 'Arcilla', 'Madera']),
            'peso' => fake()->randomFloat(2, 5, 500),
            'dimensiones_alto' => fake()->numberBetween(20, 300),
            'dimensiones_largo' => fake()->numberBetween(10, 200),
            'dimensiones_ancho' => fake()->numberBetween(10, 200),
        ];
    }
}