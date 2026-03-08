<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pintura extends Model
{
    use HasFactory;

    protected $table = 'pinturas';

    protected $fillable = [
        'id_obra',
        'nombre_tecnica',
        'nombre_soporte',
    ];

    /**
     * Obtener la obra base asociada a esta pintura.
     */
    public function obra(): BelongsTo
    {
        return $this->belongsTo(Obra::class, 'id_obra');
    }
}