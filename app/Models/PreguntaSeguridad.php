<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PreguntaSeguridad extends Model
{
    use HasFactory;

    protected $table = 'preguntas_seguridad';

    protected $fillable = [
        'pregunta',
    ];

    /**
     * Obtener las respuestas asociadas a esta pregunta de seguridad.
     */
    public function respuestas_seguridad(): HasMany
    {
        return $this->hasMany(RespuestaSeguridad::class, 'id_pregunta');
    }
}