<?php

namespace App\Services;

use App\Models\Factura;
use App\Models\Venta;
use App\Models\Obra;
use Illuminate\Support\Facades\DB;
use Exception;

class FacturacionService
{
    const TASA_IVA = 0.16; 

    /**
     * Procesa la facturación, calcula impuestos y cambia estatus de la obra.
     */
    public function procesarVenta($obraId, $compradorId, $empleadoAdminId, $direccionEnvioId, $porcentajeGanancia = 10)
    {
        return DB::transaction(function () use ($obraId, $compradorId, $empleadoAdminId, $direccionEnvioId, $porcentajeGanancia) {
            
            $obra = Obra::findOrFail($obraId);

            if ($obra->estado === 'Vendido') {
                throw new Exception("La obra ya ha sido vendida y no puede facturarse nuevamente.");
            }

            // 1. Cálculos
            $precioBase = $obra->precio_venta;
            $montoIva = $precioBase * self::TASA_IVA;
            $precioTotalFactura = $precioBase + $montoIva;
            
            // 2. Buscar si existe una reserva previa
            $venta = Venta::where('id_obra', $obraId)
                          ->where('estado', 'Reservada')
                          ->first();

            if ($venta) {
                // Actualizar reserva existente
                $venta->update([
                    'id_empleado' => $empleadoAdminId,
                    'id_comprador' => $compradorId,
                    'id_direccion_envio' => $direccionEnvioId,
                    'estado' => 'Completada',
                    'fecha_concretacion' => now(),
                ]);
            } else {
                // Crear nueva venta si no había reserva
                $venta = Venta::create([
                    'id_obra' => $obraId,
                    'id_empleado' => $empleadoAdminId,
                    'id_comprador' => $compradorId,
                    'id_direccion_envio' => $direccionEnvioId,
                    'estado' => 'Completada',
                    'fecha_venta' => now(),
                    'fecha_concretacion' => now(),
                ]);
            }

            // 3. Generar la Factura vinculada a la Venta
            $factura = Factura::create([
                'id_venta' => $venta->id,
                'id_usuario_administrador' => $empleadoAdminId,
                'nombre_obra' => $obra->titulo,
                'genero_obra' => $obra->genero->nombre ?? 'N/A', 
                'precio_obra' => $precioBase,
                'iva' => $montoIva,
                'precio_venta' => $precioTotalFactura,
                'porcentaje_ganancia' => $porcentajeGanancia, 
                'fecha_facturacion' => now(),
            ]);

            // 4. Cambiar el estatus de la obra a 'Vendido'
            $obra->update(['estado' => 'Vendido']);

            return $factura;
        });
    }
}