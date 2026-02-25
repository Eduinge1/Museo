<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DireccionEnvio extends Model
{
    use HasFactory;

    protected $table = 'direcciones_envio';

    protected $fillable = [
        'pais',
        'estado_provincia',
        'ciudad',
        'parroquia',
        'calle',
    ];
}