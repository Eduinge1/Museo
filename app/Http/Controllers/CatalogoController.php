<?php

namespace App\Http\Controllers;

use App\Models\Obra;
use App\Models\Artista;
use App\Models\Genero;
use Illuminate\Http\Request;
use App\Models\CodigoSeguridad; 

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        // 1. Iniciamos la consulta base
        // Traemos las relaciones (artista, genero) para evitar el problema N+1
        // Y filtramos ESTRICTAMENTE para que solo salgan obras disponibles
        $query = Obra::with(['artista', 'genero'])->where('estado', 'Disponible');

        // 2. Aplicamos Filtro por Género (si el usuario lo seleccionó)
        $query->when($request->filled('genero'), function ($q) use ($request) {
            $q->where('id_genero', $request->genero);
        });

        // 3. Aplicamos Filtro por Artista (si el usuario lo seleccionó)
        $query->when($request->filled('artista'), function ($q) use ($request) {
            $q->where('id_artista', $request->artista);
        });

        // 4. Ordenamiento Obligatorio: Precio de menor a mayor
        // Tal como lo exige la profesora en las instrucciones
        $query->orderBy('precio_venta', 'asc');

        // 5. Ejecutamos la consulta con Paginación
        // ->withQueryString() es clave para que al pasar a la página 2, no se borren los filtros
        $obras = $query->paginate(12)->withQueryString();

        // 6. Consultamos los catálogos para llenar los <select> del Frontend
        $generos = Genero::all(); 
        $artistas = Artista::all(); 

        // 7. Retornamos la vista (que diseñará el Frontend 1)
        return view('catalogo.index', compact('obras', 'generos', 'artistas'));
    }

    /**
     * Procesa el intento de compra de un visitante desde el Catálogo
     */
    public function reservarObra(Request $request, Obra $obra)
    {
        // 1. Validar que vengan los datos
        $request->validate([
            'codigo_seguridad' => 'required|string'
        ]);

        // 2. Verificar que el usuario sea un comprador autenticado
        $user = auth()->user();
        $comprador = $user->comprador; // Asumiendo que User tiene una relación hasOne con Comprador

        if (!$comprador) {
            return back()->with('error', 'Debes estar registrado como comprador para adquirir obras.');
        }

        // 3. Verificar que el código ingresado sea correcto y pertenezca al comprador
        $codigoValido = CodigoSeguridad::where('id_comprador', $comprador->id)
                                       ->where('codigo', $request->codigo_seguridad)
                                       ->first();

        if (!$codigoValido) {
            return back()->with('error', 'El código de seguridad es inválido o no te pertenece.');
        }

        // 4. Regla de Negocio: Solo se puede reservar si está disponible
        if ($obra->estado !== 'Disponible') {
            return back()->with('error', 'Lo sentimos, esta obra ya no está disponible.');
        }

        // 5. ¡Éxito! Cambiamos el estatus a Reservada
        $obra->update(['estado' => 'Reservada']);

        // Según el PDF: Un trabajador se comunicará en las próximas 24 horas
        return redirect()->route('catalogo.index')
                         ->with('success', '¡Obra reservada con éxito! Un trabajador del museo se comunicará contigo en las próximas 24 horas para concretar la venta.');
    }
}