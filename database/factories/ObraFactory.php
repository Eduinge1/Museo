<?php

namespace Database\Factories;

use App\Models\Artista;
use App\Models\Genero;
use Illuminate\Database\Eloquent\Factories\Factory;

class ObraFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_genero' => Genero::factory(),
            'id_artista' => Artista::factory(),
            'estado' => fake()->randomElement(['Disponible', 'Vendido', 'En Exhibición', 'Reservado']),
            'titulo' => fake('es_ES')->sentence(3),
            'precio_venta' => fake()->randomFloat(2, 1000, 50000),
            'fecha_creacion' => fake()->date('Y-m-d', 'now'),
            'image_url' => fake()->imageUrl(800, 600, 'art'),
        ];
    }
}