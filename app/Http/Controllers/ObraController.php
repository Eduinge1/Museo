<?php

namespace App\Http\Controllers;

use App\Models\Obra;
use App\Models\Artista;
use App\Models\Genero;
use Illuminate\Http\Request;

class ObraController extends Controller
{
    public function index()
    {
        // Ideal para el panel administrativo
        $obras = Obra::with(['artista', 'genero'])->paginate(10);
        return view('admin.obras.index', compact('obras'));
    }

    public function create()
    {
        $artistas = Artista::all();
        $generos = Genero::all();
        return view('admin.obras.create', compact('artistas', 'generos'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:255',
            'id_artista' => 'required|exists:artistas,id',
            'id_genero' => 'required|exists:generos,id',
            'precio_venta' => 'required|numeric|min:0',
            'fecha_creacion' => 'required|date',
            'image_url' => 'required|url', // O manejo de subida de archivos si lo requieren
        ]);

        // Al crearla nueva, siempre entra como Disponible
        $validatedData['estado'] = 'Disponible'; 

        Obra::create($validatedData);

        return redirect()->route('admin.obras.index')->with('success', 'Obra registrada correctamente.');
    }

    // Faltarían edit(), update() y destroy() siguiendo este mismo patrón estándar
}