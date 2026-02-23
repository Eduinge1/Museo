<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\PreguntaSeguridad;
use Illuminate\Database\Eloquent\Factories\Factory;

class RespuestaSeguridadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_usuario' => User::factory(),
            'id_pregunta' => PreguntaSeguridad::factory(),
            'respuesta' => fake('es_ES')->word(),
        ];
    }
}