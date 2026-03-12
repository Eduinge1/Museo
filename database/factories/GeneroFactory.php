<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GeneroFactory extends Factory
{
    public function definition(): array
    {
        $generos = ['Pintura', 'Escultura', 'Fotografía', 'Orfebreria', 'Ceramica'];

        return [
            'nombre' => fake()->unique()->randomElement($generos),
        ];
    }
}