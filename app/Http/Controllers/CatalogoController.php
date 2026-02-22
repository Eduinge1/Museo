<?php

namespace App\Http\Controllers;

use App\Models\Obra;
use App\Models\Artista;
use App\Models\Genero;
use Illuminate\Http\Request;

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
}