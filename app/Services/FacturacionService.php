<?php

namespace App\Services;

use App\Models\Factura;
use App\Models\Venta;
use App\Models\Obra;
use Illuminate\Support\Facades\DB;
use Exception;

class FacturacionService
{
    // Definimos el IVA como constante para no tener "números mágicos" regados en el código
    const TASA_IVA = 0.16; 

    /**
     * Procesa la facturación, calcula impuestos y cambia estatus de la obra.
     */
    public function procesarVenta($obraId, $compradorId, $empleadoAdminId, $direccionEnvioId, $porcentajeGanancia = 10)
    {
        // DB::transaction asegura que si hay un error en la Venta, la Factura no se guarde a medias.
        return DB::transaction(function () use ($obraId, $compradorId, $empleadoAdminId, $direccionEnvioId, $porcentajeGanancia) {
            
            $obra = Obra::findOrFail($obraId);

            // 1. Validar máquina de estados: Solo se vende si estaba reservada o disponible
            if ($obra->estado === 'Vendida') {
                throw new Exception("La obra ya ha sido vendida y no puede facturarse nuevamente.");
            }

            // 2. Cálculos Matemáticos (Tu responsabilidad principal)
            $precioBase = $obra->precio_venta;
            $montoIva = $precioBase * self::TASA_IVA;
            $precioTotalFactura = $precioBase + $montoIva;
            
            // 3. Generar la Factura
            $factura = Factura::create([
                'id_usuario_administrador' => $empleadoAdminId,
                'nombre_obra' => $obra->titulo,
                'genero_obra' => $obra->id_genero, // Aquí idealmente pasas el nombre real si tienes el 'with('genero')'
                'precio_obra' => $precioBase,
                'iva' => $montoIva,
                'precio_venta' => $precioTotalFactura,
                'porcentaje_ganancia' => $porcentajeGanancia, 
                'fecha_facturacion' => now(),
            ]);

            // 4. Registrar la Venta (vinculando todo)
            Venta::create([
                'id_empleado' => $empleadoAdminId,
                'id_obra' => $obra->id,
                'id_factura' => $factura->id,
                'id_comprador' => $compradorId,
                'id_direccion_envio' => $direccionEnvioId,
                'estado' => 'Concretada',
                'fecha_venta' => now(),
                'fecha_concretacion' => now(),
            ]);

            // 5. Cambiar el estatus de la obra
            $obra->update(['estado' => 'Vendida']);

            return $factura;
        });
    }
}