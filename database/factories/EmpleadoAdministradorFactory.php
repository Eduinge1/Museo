<?php

namespace Database\Factories;

use App\Models\Empleado;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpleadoAdministradorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_empleado' => Empleado::factory()->state(function (array $attributes) {
                return [
                    'id_usuario' => User::factory()->state(['role' => 'admin']),
                ];
            }),
            'is_active' => true,
        ];
    }
}