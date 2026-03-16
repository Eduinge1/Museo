<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MuseoDatosSeeder extends Seeder
{
    public function run(): void
    {
        // 1. INSERTAR GÉNEROS (Aseguramos que existan los 5 IDs)
        $generos = [
            ['id' => 1, 'nombre' => 'Pintura'],
            ['id' => 2, 'nombre' => 'Escultura'],
            ['id' => 3, 'nombre' => 'Cerámica'],
            ['id' => 4, 'nombre' => 'Fotografía'],
            ['id' => 5, 'nombre' => 'Orfebrería'],
        ];

        foreach ($generos as $genero) {
            DB::table('generos')->updateOrInsert(['id' => $genero['id']], [
                'nombre' => $genero['nombre'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 2. INSERTAR 10 ARTISTAS
        $artistas = [
            ['id' => 1, 'nombre' => 'Vincent van Gogh', 'nacionalidad' => 'Holandesa'],
            ['id' => 2, 'nombre' => 'Pablo Picasso', 'nacionalidad' => 'Española'],
            ['id' => 3, 'nombre' => 'Leonardo da Vinci', 'nacionalidad' => 'Italiana'],
            ['id' => 4, 'nombre' => 'Frida Kahlo', 'nacionalidad' => 'Mexicana'],
            ['id' => 5, 'nombre' => 'Claude Monet', 'nacionalidad' => 'Francesa'],
            ['id' => 6, 'nombre' => 'Salvador Dalí', 'nacionalidad' => 'Española'],
            ['id' => 7, 'nombre' => 'Rembrandt', 'nacionalidad' => 'Holandesa'],
            ['id' => 8, 'nombre' => 'Edvard Munch', 'nacionalidad' => 'Noruega'],
            ['id' => 9, 'nombre' => 'Gustav Klimt', 'nacionalidad' => 'Austriaca'],
            ['id' => 10, 'nombre' => 'Johannes Vermeer', 'nacionalidad' => 'Holandesa'],
        ];

        foreach ($artistas as $artista) {
            DB::table('artistas')->updateOrInsert(['id' => $artista['id']], [
                'nombre' => $artista['nombre'],
                'nacionalidad' => $artista['nacionalidad'],
                'image_url' => strtolower(str_replace(' ', '_', $artista['nombre'])) . '.jpg'
            ]);
        }

        // 3. INSERTAR 20 OBRAS (Repartidas por géneros)
        $obras = [
            // Pinturas (ID 1)
            ['id_artista' => 1, 'id_genero' => 1, 'titulo' => 'La noche estrellada', 'precio_venta' => 1000],
            ['id_artista' => 2, 'id_genero' => 1, 'titulo' => 'Guernica', 'precio_venta' => 2500],
            ['id_artista' => 3, 'id_genero' => 1, 'titulo' => 'Mona Lisa', 'precio_venta' => 5000],
            ['id_artista' => 4, 'id_genero' => 1, 'titulo' => 'Las dos Fridas', 'precio_venta' => 1200],
            // Esculturas (ID 2)
            ['id_artista' => 3, 'id_genero' => 2, 'titulo' => 'El Caballo de Sforza', 'precio_venta' => 4500],
            ['id_artista' => 6, 'id_genero' => 2, 'titulo' => 'Elefante Espacial', 'precio_venta' => 3200],
            ['id_artista' => 2, 'id_genero' => 2, 'titulo' => 'Cabeza de mujer', 'precio_venta' => 1800],
            ['id_artista' => 9, 'id_genero' => 2, 'titulo' => 'Busto de Adele', 'precio_venta' => 2900],
            // Cerámicas (ID 3)
            ['id_artista' => 2, 'id_genero' => 3, 'titulo' => 'Jarra de Búho', 'precio_venta' => 800],
            ['id_artista' => 4, 'id_genero' => 3, 'titulo' => 'Vasija Ancestral', 'precio_venta' => 1100],
            ['id_artista' => 10, 'id_genero' => 3, 'titulo' => 'Plato de Delft', 'precio_venta' => 950],
            ['id_artista' => 1, 'id_genero' => 3, 'titulo' => 'Taza Campesina', 'precio_venta' => 600],
            // Fotografía (ID 4)
            ['id_artista' => 5, 'id_genero' => 4, 'titulo' => 'Estudio de Luz', 'precio_venta' => 1400],
            ['id_artista' => 7, 'id_genero' => 4, 'titulo' => 'Retrato Sombrío', 'precio_venta' => 2100],
            ['id_artista' => 8, 'id_genero' => 4, 'titulo' => 'Captura del Grito', 'precio_venta' => 1650],
            ['id_artista' => 6, 'id_genero' => 4, 'titulo' => 'Ojo Surrealista', 'precio_venta' => 1900],
            // Orfebrería (ID 5)
            ['id_artista' => 3, 'id_genero' => 5, 'titulo' => 'Colgante Renacentista', 'precio_venta' => 3800],
            ['id_artista' => 9, 'id_genero' => 5, 'titulo' => 'Anillo de Oro Klimt', 'precio_venta' => 2700],
            ['id_artista' => 10, 'id_genero' => 5, 'titulo' => 'Pendiente de Perla Real', 'precio_venta' => 4200],
            ['id_artista' => 7, 'id_genero' => 5, 'titulo' => 'Cáliz de Plata', 'precio_venta' => 3100],
        ];

        foreach ($obras as $obra) {
            DB::table('obras')->insert(array_merge($obra, [
                'estado' => 'Disponible',
                'fecha_creacion' => now()->subYears(rand(10, 100))->format('Y-m-d'),
                'image_url' => strtolower(str_replace(' ', '_', $obra['titulo'])) . '.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
}