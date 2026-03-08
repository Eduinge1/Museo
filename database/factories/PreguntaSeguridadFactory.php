<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PreguntaSeguridadFactory extends Factory
{
    public function definition(): array
    {
        $preguntas = [
            '¿Cuál es el nombre de tu primera mascota?',
            '¿En qué ciudad nació tu madre?',
            '¿Cuál era el nombre de tu escuela primaria?',
            '¿Cuál es tu color favorito?',
            '¿Cuál fue tu primer coche?',
        ];

        return [
            'pregunta' => fake()->unique()->randomElement($preguntas),
        ];
    }
}