<?php

namespace Database\Factories;

use App\Models\Obra;
use Illuminate\Database\Eloquent\Factories\Factory;

class PinturaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_obra' => Obra::factory(),
            'nombre_tecnica' => fake()->randomElement(['Óleo', 'Acuarela', 'Acrílico', 'Fresco']),
            'nombre_soporte' => fake()->randomElement(['Lienzo', 'Madera', 'Papel', 'Muro']),
        ];
    }
}