<?php

namespace App\Http\Controllers;

use App\Models\Artista;
use App\Models\Genero;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArtistaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $artistas = Artista::with('generos')->paginate(10);
        return view('admin.artistas.index', compact('artistas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $generos = Genero::all();
        return view('admin.artistas.create', compact('generos'));
    }

    /**
     * Genera un nombre aleatorio único para la imagen
     */
    private function generarNombreImagen($extension)
    {
        return Str::uuid() . '.' . $extension;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255|unique:artistas,nombre',
            'nacionalidad' => 'required|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'fecha_defuncion' => 'nullable|date',
            'imagen' => 'required|image|max:5120',
            'id_genero' => 'required|array',
            'id_genero.*' => 'exists:generos,id',
        ]);

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $nombreArchivo = $this->generarNombreImagen($archivo->getClientOriginalExtension());
            
            // Guardar físicamente en storage/app/public/artistas usando el disco 'public'
            $ruta = $archivo->storeAs('artistas', $nombreArchivo, 'public');
            
            // Guardar el nombre/ruta generado en la base de datos en la variable image_url
            $validatedData['image_url'] = '/storage/' . $ruta;
        }

        $artista = Artista::create([
            'nombre' => $validatedData['nombre'],
            'nacionalidad' => $validatedData['nacionalidad'],
            'fecha_nacimiento' => $validatedData['fecha_nacimiento'],
            'fecha_defuncion' => $validatedData['fecha_defuncion'],
            'image_url' => $validatedData['image_url'] ?? null,
        ]);

        $artista->generos()->attach($validatedData['id_genero']);

        return redirect()->route('admin.artistas.index')->with('success', 'Artista registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Artista $artista)
    {
        return view('admin.artistas.show', compact('artista'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Artista $artista)
    {
        $generos = Genero::all();
        $artista->load('generos');
        return view('admin.artistas.edit', compact('artista', 'generos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Artista $artista)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255|unique:artistas,nombre,' . $artista->id,
            'nacionalidad' => 'required|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'fecha_defuncion' => 'nullable|date',
            'image_url' => 'nullable|url',
            'id_genero' => 'required|array',
            'id_genero.*' => 'exists:generos,id',
        ]);

        $artista->update([
            'nombre' => $validatedData['nombre'],
            'nacionalidad' => $validatedData['nacionalidad'],
            'fecha_nacimiento' => $validatedData['fecha_nacimiento'],
            'fecha_defuncion' => $validatedData['fecha_defuncion'],
            'image_url' => $validatedData['image_url'],
        ]);

        $artista->generos()->sync($validatedData['id_genero']);

        return redirect()->route('admin.artistas.index')->with('success', 'Artista actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Artista $artista)
    {
        if ($artista->obras()->count() > 0) {
            return redirect()->route('admin.artistas.index')->with('error', 'No se puede eliminar un artista que tiene obras registradas.');
        }

        $artista->delete();

        return redirect()->route('admin.artistas.index')->with('success', 'Artista eliminado correctamente.');
    }
}