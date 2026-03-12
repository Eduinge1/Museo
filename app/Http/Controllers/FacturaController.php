<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FacturacionService;
use App\Models\Obra;
use App\Models\Comprador;

class FacturaController extends Controller
{
    protected $facturacionService;

    // Inyección de dependencias del servicio
    public function __construct(FacturacionService $facturacionService)
    {
        $this->facturacionService = $facturacionService;
    }

    public function index()
    {
        $facturas = \App\Models\Factura::with(['administrador.empleado.user', 'venta.comprador.user'])
                                      ->latest()
                                      ->paginate(15);
        
        return view('admin.facturas.index', compact('facturas'));
    }

    public function create(Request $request)
    {
        $venta_id = $request->get('venta_id');
        
        // Traemos las ventas en estado 'Reservada' con sus relaciones
        $reservas = \App\Models\Venta::with(['obra.artista', 'obra.genero', 'comprador.user'])
                                    ->where('estado', 'Reservada')
                                    ->get();

        // Traemos las últimas 5 facturas emitidas para el historial
        $ultimasFacturas = \App\Models\Factura::with(['venta.obra', 'venta.comprador.user'])
                                              ->latest()
                                              ->take(5)
                                              ->get();

        // Calculamos algunos KPIs para la vista
        $totalReservadas = $reservas->count();
        $totalFacturadas = \App\Models\Factura::count();
        $ingresosMes = \App\Models\Factura::whereMonth('fecha_facturacion', now()->month)->sum('precio_venta');

        return view('admin.facturas.create', compact('reservas', 'totalReservadas', 'totalFacturadas', 'ingresosMes', 'ultimasFacturas', 'venta_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_obra' => 'required|exists:obras,id',
            'id_comprador' => 'required|exists:compradores,id',
            'id_direccion_envio' => 'required|exists:direcciones_envio,id',
            'porcentaje_ganancia' => 'required|numeric|min:5|max:10'
        ]);

        try {
            $admin = auth()->user()->empleado->administrador;
            
            if (!$admin) {
                return back()->with('error', 'El usuario actual no tiene perfil de administrador registrado.');
            }

            $factura = $this->facturacionService->procesarVenta(
                $request->id_obra,
                $request->id_comprador,
                $admin->id,
                $request->id_direccion_envio,
                $request->porcentaje_ganancia
            );

            return redirect()->route('admin.facturas.show', $factura->id)
                             ->with('success', "Factura #{$factura->id} emitida exitosamente.");

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $factura = \App\Models\Factura::with([
            'administrador.empleado.user', 
            'venta.obra.artista', 
            'venta.comprador.user',
            'venta.direccion_envio'
        ])->findOrFail($id);

        // Traer facturas recientes para la barra de navegación lateral/superior si es necesario
        $recientes = \App\Models\Factura::latest()->take(5)->get();

        return view('admin.facturas.show', compact('factura', 'recientes'));
    }
}