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
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear los 5 Géneros Base (Únicos y Obligatorios)
        $nombresGeneros = ['Pintura', 'Escultura', 'Ceramica', 'Fotografia', 'Orfebreria'];
        $generosMap = [];
        foreach ($nombresGeneros as $nombre) {
            $generosMap[$nombre] = Genero::firstOrCreate(['nombre' => $nombre]);
        }

        // 2. Catálogos simples
        PreguntaSeguridad::factory(5)->create();

        // 3 artistas únicos y sus obras fijas
        //Si se agregan mas obras y artistas, seguir el formato establecido aca
        // El titulo debe ser igual al nombre del archivo de la imagen slugificado, para que se asocie correctamente
        $artistasData = [
            [
                'nombre' => 'Leonardo da Vinci',
                'obras' => [
                    ['titulo' => 'La Mona Lisa', 'genero' => 'Pintura'],
                    ['titulo' => 'La Última Cena', 'genero' => 'Pintura'],
                    ['titulo' => 'Hombre de Vitruvio', 'genero' => 'Ceramica'],
                ],
            ],
            [
                'nombre' => 'Vincent van Gogh',
                'obras' => [
                    ['titulo' => 'La Noche Estrellada', 'genero' => 'Fotografia'],
                    ['titulo' => 'Los Girasoles', 'genero' => 'Orfebreria'],
                    ['titulo' => 'Terraza de café por la noche', 'genero' => 'Escultura'],
                ],
            ],
            [
                'nombre' => 'Claude Monet',
                'obras' => [
                    ['titulo' => 'Impresión, sol naciente', 'genero' => 'Pintura'],
                    ['titulo' => 'Nenúfares', 'genero' => 'Pintura'],
                    ['titulo' => 'Mujer con sombrilla', 'genero' => 'Pintura'],
                ],
            ],
        ];

        //Devuelve una coleccion de artistas pasado el array de data como parametro
        //EL map implica que cada elemento se iterara
        //El map recibe un callback, el cual puede ser otra funcion, o una funcion anonima, donde por parametro va la data
        //El use, implica que pasaremos otra data referenciada
        $artistas = collect($artistasData)->map(function (array $artistaData) use (&$generosMap) {
            $artista = Artista::firstOrCreate(
                ['nombre' => $artistaData['nombre']],
                [
                    'nacionalidad' => fake('es_ES')->country(),
                    'fecha_nacimiento' => fake()->date('Y-m-d', '-40 years'),
                    'fecha_defuncion' => null,
                    'image_url' => 'artistas/' . Str::slug($artistaData['nombre']) . '.jpg',
                ]
            );

            $idsGeneros = [];

            foreach ($artistaData['obras'] as $obraData) {
                $nombreGeneroBase = trim(explode('/', $obraData['genero'])[0]);

                if (!isset($generosMap[$nombreGeneroBase])) {
                    $generosMap[$nombreGeneroBase] = Genero::firstOrCreate(['nombre' => $nombreGeneroBase]);
                }

                $genero = $generosMap[$nombreGeneroBase];
                $idsGeneros[] = $genero->id;

                $obra = Obra::firstOrCreate(
                    [
                        'id_artista' => $artista->id,
                        'titulo' => $obraData['titulo'],
                    ],
                    [
                        'id_genero' => $genero->id,
                        'estado' => 'Disponible',
                        'precio_venta' => fake()->randomFloat(2, 1000, 50000),
                        'fecha_creacion' => fake()->date('Y-m-d', 'now'),
                        'image_url' => 'obras/' . Str::slug($obraData['titulo']) . '.jpg',
                    ]
                );

                //Este match implica que es un switch case, pero mas moderno
                match ($nombreGeneroBase) {
                    'Pintura' => Pintura::firstOrCreate(
                        ['id_obra' => $obra->id],
                        Pintura::factory()->make(['id_obra' => $obra->id])->toArray()
                    ),
                    'Escultura' => Escultura::firstOrCreate(
                        ['id_obra' => $obra->id],
                        Escultura::factory()->make(['id_obra' => $obra->id])->toArray()
                    ),
                    'Ceramica' => Ceramica::firstOrCreate(
                        ['id_obra' => $obra->id],
                        Ceramica::factory()->make(['id_obra' => $obra->id])->toArray()
                    ),
                    'Fotografia' => Fotografia::firstOrCreate(
                        ['id_obra' => $obra->id],
                        Fotografia::factory()->make(['id_obra' => $obra->id])->toArray()
                    ),
                    'Orfebreria' => Orfebreria::firstOrCreate(
                        ['id_obra' => $obra->id],
                        Orfebreria::factory()->make(['id_obra' => $obra->id])->toArray()
                    ),
                    default => null,
                };
            }

            //Aqui se hace la relacion muchos a muchos entre artistas y generos
            //Con syncWithoutDetaching para no eliminar relaciones previas
            $artista->generos()->syncWithoutDetaching(array_unique($idsGeneros));

            return $artista;
        });

        // 3. Personal y Clientes (Usando recursividad de Factories)
        // Esto crea automáticamente los Usuarios vinculados correctamente
        $admins = EmpleadoAdministrador::factory(5)->create();
        $empleadosComunes = Empleado::factory(10)->create();
        $compradores = Comprador::factory(20)->create();

        // 6. Ventas y Facturas
        $cantidadVentas = min(2, Obra::count()); //Se crearon 9 obras estaticas, que se vendan 2
        $obrasDisponibles = Obra::query()->inRandomOrder()->limit($cantidadVentas)->get();

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