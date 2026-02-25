<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Obra extends Model
{
    use HasFactory;

    protected $table = 'obras';

    protected $fillable = [
        'id_genero',
        'id_artista',
        'estado',
        'titulo',
        'precio_venta',
        'fecha_creacion',
        'image_url',
    ];

    protected $casts = [
        'precio_venta' => 'double',
        'fecha_creacion' => 'date',
    ];


    public function artistas(): BelongsTo
    {
        
        return $this->belongsTo(Artista::class, 'id_artista');
    }


    public function generos(): BelongsTo
    {
        
        return $this->belongsTo(Genero::class, 'id_genero');
    }
}