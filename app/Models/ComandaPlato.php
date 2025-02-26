<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComandaPlato extends Model
{
    use HasFactory;

    protected $fillable = [
        'comanda_id',
        'plato_id',
        'cantidad',
        'subtotal',
        'estado',
    ];

    public function comanda(): BelongsTo
    {
        return $this->belongsTo(Comanda::class);
    }

    public function plato(): BelongsTo
    {
        return $this->belongsTo(Plato::class);
    }

    public function asignacionPlatos(): HasMany
    {
        return $this->hasMany(AsignacionPlato::class);
    }
}
