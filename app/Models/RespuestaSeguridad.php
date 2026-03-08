<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RespuestaSeguridad extends Model
{
    use HasFactory;

    protected $table = 'respuestas_seguridad';

    protected $fillable = [
        'id_usuario',
        'id_pregunta',
        'respuesta',
    ];

    /**
     * Obtener el usuario que respondió.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    /**
     * Obtener la pregunta de seguridad.
     */
    public function preguntas_seguridad(): BelongsTo
    {
        // Ajusté la referencia a PascalCase
        return $this->belongsTo(PreguntaSeguridad::class, 'id_pregunta');
    }
}