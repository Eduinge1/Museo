<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genero extends Model
{
    use HasFactory;

    protected $table = 'generos';

    protected $fillable = [
        'nombre',
    ];

    /**
     * Obtener las obras asociadas a este género.
     */
    public function obras(): HasMany
    {
        return $this->hasMany(Obra::class, 'id_genero');
    }

    /**
     * Los artistas que pertenecen a este género.
     */
    public function artistas(): BelongsToMany
    {
        return $this->belongsToMany(Artista::class, 'genero_artista', 'id_genero', 'id_artista');
    }
}