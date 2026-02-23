<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fotografia extends Model
{
    use HasFactory;

    protected $table = 'fotografias';

    protected $fillable = [
        'id_obra',
        'resolucion',
        'tipo_impresion',
    ];

    /**
     * Obtener la obra base asociada a esta fotografía.
     */
    public function obra(): BelongsTo
    {
        return $this->belongsTo(Obra::class, 'id_obra');
    }
}