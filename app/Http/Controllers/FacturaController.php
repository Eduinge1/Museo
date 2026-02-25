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

    public function create()
    {
        // Esta vista sería cargada por el Frontend 2
        // Aquí mandarías las obras con estatus 'Reservada' para que el admin las seleccione
        $obrasReservadas = Obra::where('estado', 'Reservada')->get();
        return view('admin.facturas.create', compact('obrasReservadas'));
    }

    public function store(Request $request)
    {
        // Validar que los datos vengan del formulario correctamente
        $request->validate([
            'id_obra' => 'required|exists:obras,id',
            'id_comprador' => 'required|exists:compradores,id',
            'id_direccion_envio' => 'required|exists:direcciones_envio,id',
            'porcentaje_ganancia' => 'required|numeric|min:5|max:10' // Restricción de la profesora
        ]);

        try {
            // Suponiendo que el usuario autenticado es un admin
            $adminId = auth()->id(); // O auth()->user()->empleado_administrador->id dependiendo de tu auth

            $factura = $this->facturacionService->procesarVenta(
                $request->id_obra,
                $request->id_comprador,
                $adminId,
                $request->id_direccion_envio,
                $request->porcentaje_ganancia
            );

            return redirect()->route('admin.facturas.show', $factura->id)
                             ->with('success', 'Factura emitida y venta concretada exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}