<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function empleado(): HasOne
    {
        return $this->hasOne(Empleado::class, 'id_usuario');
    }

    public function comprador(): HasOne
    {
        return $this->hasOne(Comprador::class, 'id_usuario');
    }

    /**
     * Obtener las respuestas de seguridad del usuario.
     */
    public function respuestas_seguridad(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RespuestaSeguridad::class, 'id_usuario');
    }
}
