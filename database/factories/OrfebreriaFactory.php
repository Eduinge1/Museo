<?php

namespace Database\Factories;

use App\Models\Obra;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrfebreriaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_obra' => Obra::factory(),
            'metal_principal' => fake()->randomElement(['Oro', 'Plata', 'Cobre', 'Platino']),
            'peso_gramos' => fake()->randomFloat(2, 10, 2000),
        ];
    }
}