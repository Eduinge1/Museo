<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';

    protected $fillable = [
        'id_usuario',
        'fecha_ingreso',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
    ];

    /**
     * Obtener el usuario asociado al empleado.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}