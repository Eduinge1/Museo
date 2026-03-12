<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genero;
use App\Models\Obra;
// Importa los demás modelos solo si los vas a usar
// use App\Models\PreguntaSeguridad; 

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. EJECUTAMOS TUS DATOS MANUALES PRIMERO
        // Esto crea al Admin, los 5 Géneros, los 10 Artistas y las 20 Obras
        $this->call([
            UsuarioAdminSeeder::class,
            MuseoDatosSeeder::class,
        ]);

        /* 2. COMENTAMOS LAS FACTORIES POR AHORA 
           Si aún no has configurado los archivos en /database/factories, 
           estas líneas harán que el comando falle y no se carguen las obras.
        */

         PreguntaSeguridad::factory(5)->create();
         $admins = EmpleadoAdministrador::factory(5)->create();
         $empleadosComunes = Empleado::factory(10)->create();
         $compradores = Comprador::factory(20)->create();

        /*
           3. LÓGICA DE VENTAS (Mantenla comentada hasta que las obras aparezcan)
           Esta parte intenta vender obras, pero si falla un solo campo, 
           se detiene todo el proceso de carga.
        */
        
        
        $obrasDisponibles = Obra::where('estado', 'Disponible')->get();
        if ($obrasDisponibles->count() > 0) {
             // Lógica de ventas...
        }
        
    }
}