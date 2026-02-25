<?php

namespace Database\Factories;

use App\Models\Genero;
use App\Models\Artista;
use Illuminate\Database\Eloquent\Factories\Factory;

class GeneroArtistaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_genero' => Genero::factory(),
            'id_artista' => Artista::factory(),
        ];
    }
}