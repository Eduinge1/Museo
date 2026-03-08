<?php

namespace Database\Factories;

use App\Models\EmpleadoAdministrador;
use Illuminate\Database\Eloquent\Factories\Factory;

class FacturaFactory extends Factory
{
    public function definition(): array
    {
        $precioObra = fake()->randomFloat(2, 500, 10000);
        $porcentajeGanancia = 0.15; // 15%
        $iva = $precioObra * 0.16;
        $precioVenta = $precioObra + ($precioObra * $porcentajeGanancia) + $iva;

        return [
            'id_usuario_administrador' => EmpleadoAdministrador::factory(),
            'nombre_obra' => fake('es_ES')->sentence(2),
            'genero_obra' => fake()->randomElement(['Pintura', 'Escultura', 'Cerámica']),
            'precio_obra' => $precioObra,
            'iva' => $iva,
            'precio_venta' => $precioVenta,
            'porcentaje_ganancia' => $porcentajeGanancia * 100,
            'fecha_facturacion' => fake()->date('Y-m-d', 'now'),
        ];
    }
}