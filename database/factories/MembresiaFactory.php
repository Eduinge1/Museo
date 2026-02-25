<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MembresiaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'is_active' => fake()->boolean(80),
            'monto' => fake()->randomFloat(2, 50, 500),
            'fecha_expiracion' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
        ];
    }
}
