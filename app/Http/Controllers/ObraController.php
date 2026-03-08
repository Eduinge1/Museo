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

    /**
     * Muestra los detalles de una obra específica (Panel Admin)
     */
    public function show(Obra $obra)
    {
        // Cargar las relaciones para evitar consultas adicionales en la vista
        $obra->load(['artista', 'genero']);
        
        return view('admin.obras.show', compact('obra'));
    }

    /**
     * Muestra el formulario para editar una obra existente
     */
    public function edit(Obra $obra)
    {
        // Necesitamos los catálogos para llenar los <select> del formulario
        $artistas = Artista::all();
        $generos = Genero::all();
        
        return view('admin.obras.edit', compact('obra', 'artistas', 'generos'));
    }

    /**
     * Actualiza los datos de la obra en la base de datos
     */
    public function update(Request $request, Obra $obra)
    {
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:255',
            'id_artista' => 'required|exists:artistas,id',
            'id_genero' => 'required|exists:generos,id',
            'precio_venta' => 'required|numeric|min:0',
            'fecha_creacion' => 'required|date',
            'image_url' => 'required|url',
            // Opcional: si el admin puede cambiar el estado manualmente en la edición
            // 'estado' => 'required|in:Disponible,Reservada,Vendida', 
        ]);

        $obra->update($validatedData);

        return redirect()->route('admin.obras.index')
                         ->with('success', 'Los datos de la obra se actualizaron correctamente.');
    }

    /**
     * Elimina una obra de la base de datos
     */
    public function destroy(Obra $obra)
    {
        // Regla de negocio de seguridad: Evitar que se eliminen obras que ya generaron dinero/facturas
        if ($obra->estado === 'Vendida' || $obra->estado === 'Reservada') {
            return redirect()->route('admin.obras.index')
                             ->with('error', 'No puedes eliminar una obra que está reservada o vendida por integridad de los reportes.');
        }

        $obra->delete();

        return redirect()->route('admin.obras.index')
                         ->with('success', 'La obra fue eliminada del catálogo exitosamente.');
    }
}