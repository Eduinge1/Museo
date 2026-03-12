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

public function getImageUrlAttribute($value)
{
    if (!$value) return null;
    
    // Si el valor ya tiene "obras/", solo le ponemos "storage/" delante
    if (str_contains($value, 'obras/')) {
        return asset('storage/' . $value);
    }
    
    // Si NO tiene "obras/", se lo ponemos nosotros para que la ruta sea correcta
    return asset('storage/obras/' . $value);
}

    // Cambiado de artistas() a artista()
    public function artista(): BelongsTo
    {
        return $this->belongsTo(Artista::class, 'id_artista');
    }

    // Cambiado de generos() a genero()
    public function genero(): BelongsTo
    {
        return $this->belongsTo(Genero::class, 'id_genero');
    }
}