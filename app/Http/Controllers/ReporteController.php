<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Factura;
use Carbon\Carbon; // Librería de fechas nativa en Laravel

class ReporteController extends Controller
{
    /**
     * Reporte 1: Listado de obras vendidas en un periodo.
     */
    public function obrasVendidas(Request $request)
    {
        // 1. Recibir las fechas del formulario (por defecto, el mes actual)
        $fechaInicio = $request->input('fecha_inicio', Carbon::now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', Carbon::now()->endOfMonth()->toDateString());

        // 2. Consulta Eloquent (equivalente a un SELECT con INNER JOIN)
        // Traemos las relaciones para que el Frontend pueda mostrar quién la vendió y a quién
        $ventas = Venta::with(['obra.artista', 'comprador', 'empleado'])
            ->where('estado', 'Concretada')
            ->whereBetween('fecha_venta', [$fechaInicio, $fechaFin])
            ->orderBy('fecha_venta', 'desc')
            ->get();

        return view('admin.reportes.ventas', compact('ventas', 'fechaInicio', 'fechaFin'));
    }

    /**
     * Reporte 2: Resumen de facturación (El dinero y las ganancias del museo).
     */
    public function resumenFacturacion(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', Carbon::now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', Carbon::now()->endOfMonth()->toDateString());

        // 1. Obtenemos todas las facturas del periodo
        $facturas = Factura::whereBetween('fecha_facturacion', [$fechaInicio, $fechaFin])
            ->orderBy('fecha_facturacion', 'desc')
            ->get();

        // 2. Cálculos globales para la cabecera del reporte (SQL SUM)
        // Total Recaudado (Precio Base + IVA)
        $totalRecaudado = $facturas->sum('precio_venta'); 
        
        // Total Ganancias del Museo
        // Si no agregaron la columna "monto_ganancia" a la BD, lo calculamos iterando la colección:
        $totalGananciaMuseo = $facturas->sum(function ($factura) {
            // Formula: Precio Base * (Porcentaje / 100)
            return $factura->precio_obra * ($factura->porcentaje_ganancia / 100);
        });

        return view('admin.reportes.facturacion', compact(
            'facturas', 
            'totalRecaudado', 
            'totalGananciaMuseo', 
            'fechaInicio', 
            'fechaFin'
        ));
    }
}