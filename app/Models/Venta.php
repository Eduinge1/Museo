<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'id_empleado',
        'id_obra',
        'id_factura',
        'id_comprador',
        'id_direccion_envio',
        'estado',
        'fecha_venta',
        'fecha_concretacion',
    ];

    protected $casts = [
        'fecha_venta' => 'date',
        'fecha_concretacion' => 'date',
    ];

    /**
     * Obtener el empleado que realizó la venta.
     */
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'id_empleado');
    }

    /**
     * Obtener la obra vendida.
     */
    public function obra(): BelongsTo
    {
        return $this->belongsTo(Obra::class, 'id_obra');
    }

    /**
     * Obtener la factura de la venta.
     */
    public function factura(): BelongsTo
    {
        return $this->belongsTo(Factura::class, 'id_factura');
    }

    /**
     * Obtener el comprador.
     */
    public function comprador(): BelongsTo
    {
        return $this->belongsTo(Comprador::class, 'id_comprador');
    }

    /**
     * Obtener la dirección de envío de la venta.
     */
    public function direccion_envio(): BelongsTo
    {
        return $this->belongsTo(DireccionEnvio::class, 'id_direccion_envio');
    }
}