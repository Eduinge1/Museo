<?php

namespace Database\Factories;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpleadoAdministradorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_empleado' => Empleado::factory(),
            'is_active' => true,
        ];
    }
}