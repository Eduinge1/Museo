<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Factura extends Model
{
    use HasFactory;

    protected $table = 'facturas';

    protected $fillable = [
        'id_usuario_administrador',
        'nombre_obra',
        'genero_obra',
        'precio_obra',
        'iva',
        'precio_venta',
        'porcentaje_ganancia',
        'fecha_facturacion',
    ];

    protected $casts = [
        'precio_obra' => 'double',
        'iva' => 'double',
        'precio_venta' => 'double',
        'porcentaje_ganancia' => 'double',
        'fecha_facturacion' => 'date',
    ];

    /**
     * Obtener el administrador que emitió la factura.
     */
    public function administrador(): BelongsTo
    {
        return $this->belongsTo(EmpleadoAdministrador::class, 'id_usuario_administrador');
    }

    /**
     * Obtener la venta asociada a esta factura.
     */
    public function venta(): HasOne
    {
        return $this->hasOne(Venta::class, 'id_factura');
    }
}