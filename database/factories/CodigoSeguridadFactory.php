<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CodigoSeguridadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'hash_code' => $this->generarHashCode(),
            'fecha_expiracion' => fake()->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
        ];
    }

    private function generarHashCode(): string
    {
        return str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}