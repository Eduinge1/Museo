<?php

namespace App\Http\Controllers;

use App\Models\Obra;
use App\Models\Artista;
use App\Models\Genero;
use Illuminate\Http\Request;
use App\Models\CodigoSeguridad;


class CatalogoController extends Controller
{
    /**
     *  Muestra el catálogo con filtros
     */
    public function index(Request $request)
    {
        // Consulta base con relaciones
        $query = Obra::with(['artista', 'genero'])->where('estado', 'Disponible');

        // Filtro por Género
        $query->when($request->filled('genero'), function ($q) use ($request) {
            $q->where('id_genero', $request->genero);
        });

        // Filtro por Artista
        $query->when($request->filled('artista'), function ($q) use ($request) {
            $q->where('id_artista', $request->artista);
        });

        // Ordenamiento por precio (Requisito de la profesora)
        $query->orderBy('precio_venta', 'asc');

        // Paginación
        $obras = $query->paginate(12)->withQueryString();

        // Colecciones para los selectores del blade
        $generosLista  = Genero::all();
        $artistasLista = Artista::all();

        // Conteos para las estadísticas del hero
        $artistas = Artista::count();
        $generos  = Genero::count();

        return view('catalogo.index', compact(
            'obras', 'generos', 'artistas', 'generosLista', 'artistasLista'
        ));
    }

    /**
     *  Muestra el detalle de una obra
     */
    public function show($id)
    {
        $obra = Obra::with(['artista', 'genero'])->findOrFail($id);

        $obrasRelacionadas = Obra::with('artista')
                                 ->where('id_artista', $obra->id_artista)
                                 ->where('id', '!=', $obra->id)
                                 ->take(4)
                                 ->get();

        return view('catalogo.show', compact('obra', 'obrasRelacionadas'));
    }

    /**
     *  Biografía del artista
     */
    public function biografia($id)
    {
        // Busca el artista, si no existe muestra error 404
        $artista = Artista::findOrFail($id);

        // Trae todas las obras de ese artista
        $obras = Obra::with('genero')
                     ->where('id_artista', $id)
                     ->get();

        return view('catalogo.biografia', compact('artista', 'obras'));
    }

    /**
     * PROCESO DE RESERVA
     */
    public function reservarObra(Request $request, Obra $id)
    {
        $request->validate([
            'codigo_seguridad' => 'required|string'
        ]);

        $user = auth()->user();

        if (!$user || !$user->comprador) {
            return back()->with('error', 'Debes estar registrado como comprador.');
        }

        $comprador = $user->comprador;

        $codigoValido = CodigoSeguridad::where('id_comprador', $comprador->id)
                                       ->where('codigo', $request->codigo_seguridad)
                                       ->first();

        if (!$codigoValido) {
            return back()->with('error', 'El código de seguridad es inválido.');
        }

        if ($id->estado !== 'Disponible') {
            return back()->with('error', 'La obra ya no está disponible.');
        }

        $id->update(['estado' => 'Reservada']);

        return redirect()->route('home')
                         ->with('success', '¡Obra reservada con éxito!');
    }
}