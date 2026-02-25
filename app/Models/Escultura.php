<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Escultura extends Model
{
    use HasFactory;

    protected $table = 'esculturas';

    protected $fillable = [
        'id_obra',
        'nombre_material',
        'peso',
        'dimensiones_alto',
        'dimensiones_largo',
        'dimensiones_ancho',
    ];

    protected $casts = [
        'peso' => 'double',
        'dimensiones_alto' => 'integer',
        'dimensiones_largo' => 'integer',
        'dimensiones_ancho' => 'integer',
    ];

    /**
     * Obtener la obra base asociada a esta escultura.
     */
    public function obra(): BelongsTo
    {
        return $this->belongsTo(Obra::class, 'id_obra');
    }
}