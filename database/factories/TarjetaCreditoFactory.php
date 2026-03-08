<?php

namespace Database\Factories;

use App\Models\Comprador;
use Illuminate\Database\Eloquent\Factories\Factory;

class TarjetaCreditoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_comprador' => Comprador::factory(),
            'tipo_tarjeta' => fake()->creditCardType(),
            'nombre_asociado' => fake('es_ES')->name(),
            'mes_vencimiento' => fake()->numberBetween(1, 12),
            'anio_vencimiento' => fake()->numberBetween(2026, 2032),
            'cvv' => fake()->numberBetween(100, 999),
        ];
    }
}