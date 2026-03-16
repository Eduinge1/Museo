<?php

namespace App\Http\Controllers;

use App\Models\Obra;
use App\Models\Artista;
use App\Models\Genero;
use App\Models\Venta;
use App\Models\Factura;
use App\Models\DireccionEnvio;
use App\Models\CodigoSeguridad;
use App\Models\Fotografia;
use App\Models\Pintura;
use App\Models\Escultura;
use App\Models\Ceramica;
use App\Models\Orfebreria;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        $query = Obra::with(['artista', 'genero'])->where('estado', 'Disponible');

        $query->when($request->filled('genero'), function ($q) use ($request) {
            $q->where('id_genero', $request->genero);
        });

        $query->when($request->filled('artista'), function ($q) use ($request) {
            $q->where('id_artista', $request->artista);
        });

        $query->orderBy('precio_venta', 'asc');
        $obras = $query->paginate(20)->withQueryString();

        $generosLista  = Genero::all();
        $artistasLista = Artista::all();
        $artistas = Artista::count();
        $generos  = Genero::count();

        return view('catalogo.index', compact(
            'obras', 'generos', 'artistas', 'generosLista', 'artistasLista'
        ));
    }

    public function artistas()
    {
        $artistas = Artista::withCount('obras')->orderBy('nombre', 'asc')->get();
        return view('catalogo.artistas', compact('artistas'));
    }

    public function generos()
    {
        $generos = Genero::withCount('obras')->orderBy('nombre', 'asc')->get();
        return view('catalogo.generos', compact('generos'));
    }

    public function show($id)
    {
        $obra = Obra::with(['artista', 'genero'])->findOrFail($id);
        $obrasRelacionadas = Obra::with('artista')
                                 ->where('id_artista', $obra->id_artista)
                                 ->where('id', '!=', $obra->id)
                                 ->take(4)
                                 ->get();

        $detalle = null;
        switch ($obra->genero->nombre) {
            case 'Fotografía': $detalle = Fotografia::where('id_obra', $obra->id)->first(); break;
            case 'Pintura':    $detalle = Pintura::where('id_obra', $obra->id)->first(); break;
            case 'Escultura':  $detalle = Escultura::where('id_obra', $obra->id)->first(); break;
            case 'Cerámica':   $detalle = Ceramica::where('id_obra', $obra->id)->first(); break;
            case 'Orfebrería': $detalle = Orfebreria::where('id_obra', $obra->id)->first(); break;
        }

        return view('catalogo.show', compact('obra', 'obrasRelacionadas', 'detalle'));
    }

    public function biografia($id)
    {
        $artista = Artista::findOrFail($id);
        $obras = Obra::with('genero')->where('id_artista', $id)->get();
        return view('catalogo.biografia', compact('artista', 'obras'));
    }

    public function reservarObra(Request $request, Obra $obra)
    {
        $request->validate([
            'codigo_seguridad' => 'required|string',
            'pais' => 'required|string|max:100',
            'estado_provincia' => 'required|string|max:100',
            'ciudad' => 'required|string|max:100',
            'parroquia' => 'required|string|max:100',
            'calle' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $comprador = $user->comprador;

        if (!$comprador) {
            return back()->with('error', 'Debes estar registrado como comprador para adquirir obras.');
        }

        $codigoValido = CodigoSeguridad::where('id', $comprador->id_codigo_seguridad)
                                       ->where('hash_code', $request->codigo_seguridad)
                                       ->first();

        if (!$codigoValido) {
            return back()->with('error', 'El código de seguridad es inválido.');
        }

        if ($obra->estado !== 'Disponible') {
            return back()->with('error', 'La obra ya no está disponible.');
        }

        return DB::transaction(function () use ($request, $obra, $comprador) {
            // 4. Crear la dirección de envío real
            $direccion = DireccionEnvio::create([
                'pais' => $request->pais,
                'estado_provincia' => $request->estado_provincia,
                'ciudad' => $request->ciudad,
                'parroquia' => $request->parroquia,
                'calle' => $request->calle
            ]);

            $venta = Venta::create([
                'id_obra' => $obra->id,
                'id_comprador' => $comprador->id,
                'id_empleado' => null,
                'id_direccion_envio' => $direccion->id,
                'estado' => 'Reservada',
                'fecha_venta' => now(),
            ]);

            // 6. Crear la Factura asociada a la venta
            $iva = $obra->precio_venta * 0.16;
            $precioFinal = $obra->precio_venta + $iva;

            Factura::create([
                'id_venta' => $venta->id,
                'id_usuario_administrador' => null,
                'nombre_obra' => $obra->titulo,
                'genero_obra' => $obra->genero->nombre ?? 'N/A',
                'precio_obra' => $obra->precio_venta,
                'iva' => $iva,
                'precio_venta' => $precioFinal,
                'porcentaje_ganancia' => 0,
                'fecha_facturacion' => now(),
            ]);

            $obra->update(['estado' => 'Reservada']);

            return redirect()->route('home')->with('success', '¡Obra reservada con éxito!');
        });
    }
}