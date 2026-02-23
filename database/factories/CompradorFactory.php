<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\CodigoSeguridad;
use App\Models\Membresia;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompradorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_usuario' => User::factory(),
            'id_codigo_seguridad' => CodigoSeguridad::factory(),
            'id_membresia' => Membresia::factory(),
            'telefono' => fake('es_ES')->phoneNumber(),
        ];
    }
}