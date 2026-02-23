<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneroArtista extends Model
{
    use HasFactory;

    protected $table = 'genero_artista';

    protected $fillable = [
        'id_genero',
        'id_artista',
    ];
}
