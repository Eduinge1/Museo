<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ceramica extends Model
{
    use HasFactory;

    protected $table = 'ceramicas';

    protected $fillable = [
        'id_obra',
        'tipo_arcilla',
        'tecnica_coccion',
    ];


    public function obra(): BelongsTo
    {
        return $this->belongsTo(Obra::class, 'id_obra');
    }
}