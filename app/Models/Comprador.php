<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comprador extends Model
{
    use HasFactory;

    protected $table = 'compradores';

    protected $fillable = [
        'id_usuario',
        'id_codigo_seguridad',
        'id_membresia',
        'telefono',
    ];

    /**
     * Obtener el usuario asociado al comprador.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    /**
     * Obtener el código de seguridad asociado.
     */
    public function codigos_seguridad(): BelongsTo
    {
        // Ajusté la referencia a PascalCase
        return $this->belongsTo(CodigoSeguridad::class, 'id_codigo_seguridad');
    }

    /**
     * Obtener la membresía asociada.
     */
    public function membresias(): BelongsTo
    {
        // Ajusté la referencia a PascalCase
        return $this->belongsTo(Membresia::class, 'id_membresia');
    }
}