<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CodigoSeguridadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'hash_code' => Str::random(10),
            'fecha_expiracion' => fake()->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
        ];
    }
}
