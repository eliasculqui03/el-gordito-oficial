<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComandaExistencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'comanda_id',
        'existencia_id',
        'cantidad',
        'subtotal',
        'helado',
        'estado',
    ];

    public function comanda(): BelongsTo
    {
        return $this->belongsTo(Comanda::class);
    }

    public function existencia(): BelongsTo
    {
        return $this->belongsTo(Existencia::class);
    }

    public function salidaAlmacens(): HasMany
    {
        return $this->hasMany(SalidaAlmacen::class);
    }

    public function asignacionExistencias(): HasMany
    {
        return $this->hasMany(AsignacionExistencia::class);
    }
}
