<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Obra;
use App\Models\Pintura;
use App\Models\Ceramica;

class ObraSeeder extends Seeder
{
    public function run(): void
    {
        // 1. La noche estrellada (Pintura)
        $obra1 = Obra::create([
            'id_genero' => 1, 
            'id_artista' => 1,
            'titulo' => 'La noche estrellada',
            'precio_venta' => 1000,
            'fecha_creacion' => '1889-06-01',
            'image_url' => 'la_noche_estrellada.jpg', // Nombre que pusiste manualmente
            'estado' => 'Disponible'
        ]);

        Pintura::create([
            'id_obra' => $obra1->id,
            'nombre_tecnica' => 'Óleo',
            'nombre_soporte' => 'Lienzo'
        ]);

        // 2. Taza Campesina (Cerámica)
        $obra2 = Obra::create([
            'id_genero' => 2,
            'id_artista' => 1,
            'titulo' => 'Taza Campesina',
            'precio_venta' => 600,
            'fecha_creacion' => '2026-03-10',
            'image_url' => 'taza_campesina.jpg',
            'estado' => 'Disponible'
        ]);

        Ceramica::create([
            'id_obra' => $obra2->id,
            'tipo_barro' => 'Arcilla',
            'temperatura_coccion' => '1000°C'
        ]);
        
        // Agrega aquí el resto de las obras siguiendo este patrón
    }
}
