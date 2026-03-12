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
use Illuminate\Support\Str;

class ObraController extends Controller
{
    /**
     * Muestra el listado de obras.
     */
    public function index()
    {
        $obras = Obra::with(['artista', 'genero'])->paginate(20);
        return view('admin.obras.index', compact('obras'));
    }

    /**
     * Muestra el formulario para crear una obra.
     */
    public function create()
    {
        $artistas = Artista::all();
        $generos = Genero::all();
        return view('admin.obras.create', compact('artistas', 'generos'));
    }

    /**
     * Genera un nombre único para los archivos de imagen.
     */
    private function generarNombreImagen($extension)
    {
        return Str::uuid() . '.' . $extension;
    }

    /**
     * Guarda una nueva obra en la base de datos.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:255',
            'id_artista' => 'required|exists:artistas,id',
            'id_genero' => 'required|exists:generos,id',
            'precio_venta' => 'required|numeric|min:0',
            'fecha_creacion' => 'required|date',
            'image_url' => 'nullable|string',
            'imagen' => 'nullable|image|max:5120',
            // Validaciones dinámicas (se pueden hacer más estrictas según el género)
            'nombre_tecnica' => 'nullable|string',
            'nombre_soporte' => 'nullable|string',
            // ... (otros campos técnicos)
        ]);

        return DB::transaction(function () use ($request, $validatedData) {
            // Lógica de imagen
            if ($request->hasFile('imagen')) {
                $archivo = $request->file('imagen');
                $nombreArchivo = $this->generarNombreImagen($archivo->getClientOriginalExtension());
                $ruta = $archivo->storeAs('obras', $nombreArchivo, 'public');
                $validatedData['image_url'] = '/storage/' . $ruta;
            }

            $validatedData['estado'] = 'Disponible'; 
            $obra = Obra::create($validatedData);

            $this->actualizarDetallesGenero($request, $obra);

            return redirect()->route('admin.obras.index')->with('success', 'Obra registrada correctamente.');
        });
    }

    /**
     * Muestra una obra específica.
     */
    public function show(Obra $obra)
    {
        $obra->load(['artista', 'genero']);
        return view('admin.obras.show', compact('obra'));
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(Obra $obra)
    {
        $artistas = Artista::all();
        $generos = Genero::all();
        
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

    /**
     * Actualiza la obra.
     */
    public function update(Request $request, Obra $obra)
    {
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:255',
            'id_artista' => 'required|exists:artistas,id',
            'id_genero' => 'required|exists:generos,id',
            'precio_venta' => 'required|numeric|min:0',
            'fecha_creacion' => 'required|date',
            'estado' => 'required|in:Disponible,Reservada,Vendida',
            'image_url' => 'nullable|string',
            'imagen' => 'nullable|image|max:5120',
        ]);

        return DB::transaction(function () use ($request, $obra, $validatedData) {
            
            if ($request->hasFile('imagen')) {
                $archivo = $request->file('imagen');
                $nombreArchivo = $this->generarNombreImagen($archivo->getClientOriginalExtension());
                $ruta = $archivo->storeAs('obras', $nombreArchivo, 'public');
                $validatedData['image_url'] = '/storage/' . $ruta;
            } elseif ($request->filled('image_url')) {
                $validatedData['image_url'] = $request->image_url;
            }

            $obra->update($validatedData);
            $this->actualizarDetallesGenero($request, $obra);

            return redirect()->route('admin.obras.index')->with('success', '¡Obra actualizada con éxito!');
        });
    }

    /**
     * Elimina la obra.
     */
    public function destroy(Obra $obra)
    {
        if (in_array($obra->estado, ['Vendida', 'Reservada'])) {
            return redirect()->route('admin.obras.index')
                             ->with('error', 'No puedes eliminar una obra con transacciones activas.');
        }

        $obra->delete();
        return redirect()->route('admin.obras.index')->with('success', 'Obra eliminada.');
    }

    /**
     * Función privada para no repetir código de actualización de géneros.
     */
    private function actualizarDetallesGenero($request, $obra)
    {
        $genero = Genero::find($request->id_genero);
        if (!$genero) return;

        switch ($genero->nombre) {
            case 'Pintura':
                Pintura::updateOrCreate(['id_obra' => $obra->id], [
                    'nombre_tecnica' => $request->nombre_tecnica ?? 'No especificada',
                    'nombre_soporte' => $request->nombre_soporte ?? 'No especificado',
                ]);
                break;
            case 'Fotografía':
                Fotografia::updateOrCreate(['id_obra' => $obra->id], [
                    'resolucion' => $request->resolucion ?? 'N/A',
                    'tipo_impresion' => $request->tipo_impresion ?? 'N/A',
                ]);
                break;
            case 'Escultura':
                Escultura::updateOrCreate(['id_obra' => $obra->id], [
                    'nombre_material' => $request->nombre_material ?? 'Desconocido',
                    'peso' => $request->peso ?? 0,
                    'dimensiones_alto' => $request->dimensiones_alto ?? 0,
                    'dimensiones_largo' => $request->dimensiones_largo ?? 0,
                    'dimensiones_ancho' => $request->dimensiones_ancho ?? 0,
                ]);
                break;
            case 'Cerámica':
                Ceramica::updateOrCreate(['id_obra' => $obra->id], [
                    'tipo_arcilla' => $request->tipo_arcilla ?? 'N/A',
                    'tecnica_coccion' => $request->tecnica_coccion ?? 'N/A',
                ]);
                break;
            case 'Orfebrería':
                Orfebreria::updateOrCreate(['id_obra' => $obra->id], [
                    'metal_principal' => $request->metal_principal ?? 'N/A',
                    'peso_gramos' => $request->peso_gramos ?? 0,
                ]);
                break;
        }
 
        }
        public function cambiarEstado(Request $request, Obra $obra)
{
    $request->validate([
        'estado' => 'required|in:Disponible,Vendida',
    ]);

    $obra->update(['estado' => $request->estado]);

    $mensaje = $request->estado === 'Vendida' 
        ? 'La obra ha sido marcada como Vendida.' 
        : 'La obra ha vuelto a estar Disponible.';

 return redirect()->back()->with('success', $mensaje);
}
public function reservadas()
{
    $obras = Obra::with(['artista', 'genero'])
                 ->where('estado', 'Reservada')
                 ->orderBy('updated_at', 'desc')
                 ->get();

    return view('admin.obras.reservadas', compact('obras'));
}
}