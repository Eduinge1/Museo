<?php

namespace Database\Factories;

use App\Models\Empleado;
use App\Models\Obra;
use App\Models\Factura;
use App\Models\Comprador;
use App\Models\DireccionEnvio;
use Illuminate\Database\Eloquent\Factories\Factory;

class VentaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_empleado' => Empleado::factory(),
            'id_obra' => Obra::factory(),
            'id_factura' => Factura::factory(),
            'id_comprador' => Comprador::factory(),
            'id_direccion_envio' => DireccionEnvio::factory(),
            'estado' => fake()->randomElement(['Pendiente', 'Enviado', 'Entregado']),
            'fecha_venta' => fake()->date('Y-m-d', 'now'),
            'fecha_concretacion' => fake()->optional()->date('Y-m-d', 'now'),
        ];
    }
}