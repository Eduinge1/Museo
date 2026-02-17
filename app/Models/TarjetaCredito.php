<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TarjetaCredito extends Model
{
    use HasFactory;

    protected $table = 'tarjetas_credito';

    protected $fillable = [
        'id_comprador',
        'tipo_tarjeta',
        'nombre_asociado',
        'mes_vencimiento',
        'anio_vencimiento',
        'cvv',
    ];

    protected $casts = [
        'mes_vencimiento' => 'integer',
        'anio_vencimiento' => 'integer',
        'cvv' => 'integer',
    ];

    /**
     * Obtener el comprador dueño de la tarjeta.
     */
    public function comprador(): BelongsTo
    {
        return $this->belongsTo(Comprador::class, 'id_comprador');
    }
}

