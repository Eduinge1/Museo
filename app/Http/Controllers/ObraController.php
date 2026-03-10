<?php

namespace App\Http\Controllers;

use App\Models\Obra;
use App\Models\Artista;
use App\Models\Genero;
use App\Models\Fotografia;
use App\Models\Pintura;
use App\Models\Escultura;
use App\Models\Ceramica;
use App\Models\Orfebreria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ObraController extends Controller
{
    public function index()
    {
        $obras = Obra::with(['artista', 'genero'])->paginate(10);
        return view('admin.obras.index', compact('obras'));
    }

    public function create()
    {
        $artistas = Artista::all();
        $generos = Genero::all();
        return view('admin.obras.create', compact('artistas', 'generos'));
    }

    private function generarNombreImagen($extension)
    {
        return \Illuminate\Support\Str::uuid() . '.' . $extension;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:255',
            'id_artista' => 'required|exists:artistas,id',
            'id_genero' => 'required|exists:generos,id',
            'precio_venta' => 'required|numeric|min:0',
            'fecha_creacion' => 'required|date',
            'imagen' => 'required|image|max:5120',
            // Validaciones dinámicas por género
            'resolucion' => 'nullable|required_if:genero_nombre,Fotografía|string',
            'tipo_impresion' => 'nullable|required_if:genero_nombre,Fotografía|string',
            'nombre_tecnica' => 'nullable|required_if:genero_nombre,Pintura|string',
            'nombre_soporte' => 'nullable|required_if:genero_nombre,Pintura|string',
            'nombre_material' => 'nullable|required_if:genero_nombre,Escultura|string',
            'peso' => 'nullable|required_if:genero_nombre,Escultura|numeric',
            'dimensiones_alto' => 'nullable|required_if:genero_nombre,Escultura|numeric',
            'dimensiones_largo' => 'nullable|required_if:genero_nombre,Escultura|numeric',
            'dimensiones_ancho' => 'nullable|required_if:genero_nombre,Escultura|numeric',
            'tipo_arcilla' => 'nullable|required_if:genero_nombre,Cerámica|string',
            'tecnica_coccion' => 'nullable|required_if:genero_nombre,Cerámica|string',
            'metal_principal' => 'nullable|required_if:genero_nombre,Orfebrería|string',
            'peso_gramos' => 'nullable|required_if:genero_nombre,Orfebrería|numeric',
        ]);

        return DB::transaction(function () use ($request, $validatedData) {
            if ($request->hasFile('imagen')) {
                $archivo = $request->file('imagen');
                $nombreArchivo = $this->generarNombreImagen($archivo->getClientOriginalExtension());
                $ruta = $archivo->storeAs('obras', $nombreArchivo, 'public');
                $validatedData['image_url'] = '/storage/' . $ruta;
            }

            $validatedData['estado'] = 'Disponible'; 
            $obra = Obra::create($validatedData);

            $genero = Genero::find($request->id_genero);
            
            switch ($genero->nombre) {
                case 'Fotografía':
                    Fotografia::create([
                        'id_obra' => $obra->id,
                        'resolucion' => $request->resolucion,
                        'tipo_impresion' => $request->tipo_impresion,
                    ]);
                    break;
                case 'Pintura':
                    Pintura::create([
                        'id_obra' => $obra->id,
                        'nombre_tecnica' => $request->nombre_tecnica,
                        'nombre_soporte' => $request->nombre_soporte,
                    ]);
                    break;
                case 'Escultura':
                    Escultura::create([
                        'id_obra' => $obra->id,
                        'nombre_material' => $request->nombre_material,
                        'peso' => $request->peso,
                        'dimensiones_alto' => $request->dimensiones_alto,
                        'dimensiones_largo' => $request->dimensiones_largo,
                        'dimensiones_ancho' => $request->dimensiones_ancho,
                    ]);
                    break;
                case 'Cerámica':
                    Ceramica::create([
                        'id_obra' => $obra->id,
                        'tipo_arcilla' => $request->tipo_arcilla,
                        'tecnica_coccion' => $request->tecnica_coccion,
                    ]);
                    break;
                case 'Orfebrería':
                    Orfebreria::create([
                        'id_obra' => $obra->id,
                        'metal_principal' => $request->metal_principal,
                        'peso_gramos' => $request->peso_gramos,
                    ]);
                    break;
            }

            return redirect()->route('admin.obras.index')->with('success', 'Obra registrada correctamente.');
        });
    }

    public function show(Obra $obra)
    {
        $obra->load(['artista', 'genero']);
        return view('admin.obras.show', compact('obra'));
    }

    public function edit(Obra $obra)
    {
        $artistas = Artista::all();
        $generos = Genero::all();
        
        // Cargar los datos específicos del género
        $detalle = null;
        switch ($obra->genero->nombre) {
            case 'Fotografía': $detalle = Fotografia::where('id_obra', $obra->id)->first(); break;
            case 'Pintura': $detalle = Pintura::where('id_obra', $obra->id)->first(); break;
            case 'Escultura': $detalle = Escultura::where('id_obra', $obra->id)->first(); break;
            case 'Cerámica': $detalle = Ceramica::where('id_obra', $obra->id)->first(); break;
            case 'Orfebrería': $detalle = Orfebreria::where('id_obra', $obra->id)->first(); break;
        }

        return view('admin.obras.edit', compact('obra', 'artistas', 'generos', 'detalle'));
    }

    public function update(Request $request, Obra $obra)
    {
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:255',
            'id_artista' => 'required|exists:artistas,id',
            'id_genero' => 'required|exists:generos,id',
            'precio_venta' => 'required|numeric|min:0',
            'fecha_creacion' => 'required|date',
            'estado' => 'required|in:Disponible,Reservada,Vendida',
            'imagen' => 'nullable|image|max:5120',
        ]);

        return DB::transaction(function () use ($request, $obra, $validatedData) {
            if ($request->hasFile('imagen')) {
                $archivo = $request->file('imagen');
                $nombreArchivo = $this->generarNombreImagen($archivo->getClientOriginalExtension());
                $ruta = $archivo->storeAs('obras', $nombreArchivo, 'public');
                $validatedData['image_url'] = '/storage/' . $ruta;
            }

            $obra->update($validatedData);

            $genero = Genero::find($request->id_genero);
            
            // Actualizar o crear el detalle según el género
            switch ($genero->nombre) {
                case 'Fotografía':
                    Fotografia::updateOrCreate(['id_obra' => $obra->id], [
                        'resolucion' => $request->resolucion,
                        'tipo_impresion' => $request->tipo_impresion,
                    ]);
                    break;
                case 'Pintura':
                    Pintura::updateOrCreate(['id_obra' => $obra->id], [
                        'nombre_tecnica' => $request->nombre_tecnica,
                        'nombre_soporte' => $request->nombre_soporte,
                    ]);
                    break;
                case 'Escultura':
                    Escultura::updateOrCreate(['id_obra' => $obra->id], [
                        'nombre_material' => $request->nombre_material,
                        'peso' => $request->peso,
                        'dimensiones_alto' => $request->dimensiones_alto,
                        'dimensiones_largo' => $request->dimensiones_largo,
                        'dimensiones_ancho' => $request->dimensiones_ancho,
                    ]);
                    break;
                case 'Cerámica':
                    Ceramica::updateOrCreate(['id_obra' => $obra->id], [
                        'tipo_arcilla' => $request->tipo_arcilla,
                        'tecnica_coccion' => $request->tecnica_coccion,
                    ]);
                    break;
                case 'Orfebrería':
                    Orfebreria::updateOrCreate(['id_obra' => $obra->id], [
                        'metal_principal' => $request->metal_principal,
                        'peso_gramos' => $request->peso_gramos,
                    ]);
                    break;
            }

            return redirect()->route('admin.obras.index')
                             ->with('success', 'Los datos de la obra se actualizaron correctamente.');
        });
    }

    public function destroy(Obra $obra)
    {
        if ($obra->estado === 'Vendida' || $obra->estado === 'Reservada') {
            return redirect()->route('admin.obras.index')
                             ->with('error', 'No puedes eliminar una obra que está reservada o vendida.');
        }

        $obra->delete();
        return redirect()->route('admin.obras.index')
                         ->with('success', 'La obra fue eliminada del catálogo exitosamente.');
    }
}