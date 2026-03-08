<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Artista extends Model
{
    use HasFactory;

    protected $table = 'artistas';

    protected $fillable = [
        'nombre',
        'nacionalidad',
        'fecha_nacimiento',
        'fecha_defuncion',
        'image_url',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_defuncion' => 'date',
    ];

    /**
     * Get the obras for the artista.
     */
    public function obras(): HasMany
    {
        return $this->hasMany(Obra::class, 'id_artista');
    }

    /**
     * The generos that belong to the artista.
     */
    public function generos(): BelongsToMany
    {
        return $this->belongsToMany(Genero::class, 'genero_artista', 'id_artista', 'id_genero');
    }
}