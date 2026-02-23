<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ArtistaFactory extends Factory
{
    public function definition(): array
    {
        $fechaNacimiento = fake()->date('Y-m-d', '-40 years');
        $fallecido = fake()->boolean(30); // 30% de probabilidad de haber fallecido

        return [
            'nombre' => fake('es_ES')->name(),
            'nacionalidad' => fake('es_ES')->country(),
            'fecha_nacimiento' => $fechaNacimiento,
            'fecha_defuncion' => $fallecido ? fake()->date('Y-m-d', 'now') : null,
            'image_url' => fake()->imageUrl(640, 480, 'people'),
        ];
    }
}