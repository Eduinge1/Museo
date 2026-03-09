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
        $partes = [
            'MUS',
            strtoupper(substr(bin2hex(random_bytes(2)), 0, 4)),
            strtoupper(substr(bin2hex(random_bytes(2)), 0, 4)),
            strtoupper(substr(bin2hex(random_bytes(2)), 0, 4))
        ];
        return implode('-', $partes);
    }
}