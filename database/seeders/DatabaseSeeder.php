<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artista;
use App\Models\Genero;
use App\Models\Membresia;
use App\Models\PreguntaSeguridad;
use App\Models\EmpleadoAdministrador;
use App\Models\Empleado;
use App\Models\Comprador;
use App\Models\Obra;
use App\Models\Pintura;
use App\Models\Escultura;
use App\Models\Ceramica;
use App\Models\Fotografia;
use App\Models\Orfebreria;
use App\Models\Factura;
use App\Models\Venta;
use App\Models\DireccionEnvio;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear los 5 Géneros Base (Únicos y Obligatorios)
        $nombresGeneros = ['Pintura', 'Escultura', 'Ceramica', 'Fotografia', 'Orfebreria'];
        $generosMap = [];
        foreach ($nombresGeneros as $nombre) {
            $generosMap[$nombre] = Genero::factory()->create(['nombre' => $nombre]);
        }

        // 2. Catálogos simples
        PreguntaSeguridad::factory(5)->create();
        $artistas = Artista::factory(20)->create();

        // 3. Personal y Clientes (Usando recursividad de Factories)
        // Esto crea automáticamente los Usuarios vinculados correctamente
        $admins = EmpleadoAdministrador::factory(5)->create();
        $empleadosComunes = Empleado::factory(10)->create();
        $compradores = Comprador::factory(20)->create();

        // 4. Relación N:M Artista-Género (Aleatoria)
        $artistas->each(function ($artista) use ($generosMap) {
            $artista->generos()->attach(
                collect($generosMap)->random(rand(1, 2))->pluck('id')
            );
        });

        // 5. Obras y sus Hijos Especializados
        // Creamos 50 obras repartidas equitativamente entre los 5 tipos
        foreach ($generosMap as $nombreTipo => $genero) {
            Obra::factory(10)->create([
                'id_genero' => $genero->id,
                'id_artista' => fn() => $artistas->random()->id,
            ])->each(function ($obra) use ($nombreTipo) {
                // Creamos el hijo correspondiente según el nombre del género
                match ($nombreTipo) {
                    'Pintura'    => Pintura::factory()->create(['id_obra' => $obra->id]),
                    'Escultura'  => Escultura::factory()->create(['id_obra' => $obra->id]),
                    'Ceramica'   => Ceramica::factory()->create(['id_obra' => $obra->id]),
                    'Fotografia' => Fotografia::factory()->create(['id_obra' => $obra->id]),
                    'Orfebreria' => Orfebreria::factory()->create(['id_obra' => $obra->id]),
                };
            });
        }

        // 6. Ventas y Facturas
        $obrasDisponibles = Obra::all()->random(30);

        $obrasDisponibles->each(function ($obra) use ($admins, $compradores, $empleadosComunes) {
            // La factura la emite un administrador
            $factura = Factura::factory()->create([
                'id_usuario_administrador' => $admins->random()->id,
                'nombre_obra' => $obra->titulo,
                'precio_obra' => $obra->precio_venta,
            ]);

            // La venta la registra cualquier empleado (común o admin)
            $vendedor = collect([$admins->random()->empleado, $empleadosComunes->random()])->random();

            Venta::factory()->create([
                'id_obra' => $obra->id,
                'id_factura' => $factura->id,
                'id_comprador' => $compradores->random()->id,
                'id_empleado' => $vendedor->id,
                'id_direccion_envio' => DireccionEnvio::factory()->create()->id,
                'estado' => 'Completada',
                'fecha_venta' => $factura->fecha_facturacion,
            ]);

            $obra->update(['estado' => 'Vendido']);
        });
    }
}