<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisponibilidadPlato extends Model
{
    use HasFactory;

    protected $fillable = [
        'plato_id',
        'cantidad',
        'disponibilidad',
    ];


    public function plato(): BelongsTo
    {
        return $this->belongsTo(Plato::class);
    }
}
