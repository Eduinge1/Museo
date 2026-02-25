<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoSeguridad extends Model
{
    use HasFactory;

    protected $table = 'codigos_seguridad';

    protected $fillable = [
        'hash_code',
        'fecha_expiracion',
    ];

    protected $casts = [
        'fecha_expiracion' => 'date',
    ];
}