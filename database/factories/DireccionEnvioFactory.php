<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DireccionEnvioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pais' => 'Venezuela',
            'estado_provincia' => fake('es_ES')->state(),
            'ciudad' => fake('es_ES')->city(),
            'parroquia' => 'Parroquia ' . fake('es_ES')->word(),
            'calle' => fake('es_ES')->streetAddress(),
        ];
    }
}