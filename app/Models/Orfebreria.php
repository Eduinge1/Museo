<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Orfebreria extends Model
{
    use HasFactory;

    protected $table = 'orfebrerias';

    protected $fillable = [
        'id_obra',
        'metal_principal',
        'peso_gramos',
    ];

    protected $casts = [
        'peso_gramos' => 'double',
    ];

    /**
     * Obtener la obra base asociada a esta pieza de orfebrería.
     */
    public function medical(): BelongsTo
    {
        return $this->belongsTo(Obra::class, 'id_obra');
    }
}