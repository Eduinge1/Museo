<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membresia extends Model
{
    use HasFactory;

    protected $table = 'membresias';

    protected $fillable = [
        'is_active',
        'monto',
        'fecha_expiracion',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'monto' => 'double',
        'fecha_expiracion' => 'date',
    ];
}