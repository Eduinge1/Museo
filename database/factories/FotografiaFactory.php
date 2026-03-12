<?php

namespace Database\Factories;

use App\Models\Obra;
use Illuminate\Database\Eloquent\Factories\Factory;

class FotografiaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_obra' => Obra::factory(),
            'resolucion' => fake()->numberBetween(12, 100) . ' MP',
            'tipo_impresion' => fake()->randomElement(['Digital', 'Analógica', 'Giclée']),
        ];
    }
}