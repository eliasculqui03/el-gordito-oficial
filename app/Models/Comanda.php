<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comanda extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'zona_id',
        'mesa_id',
        'estado',
    ];
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function zona(): BelongsTo
    {
        return $this->belongsTo(Zona::class);
    }

    public function mesa(): BelongsTo
    {
        return $this->belongsTo(Mesa::class);
    }

    public function existencias(): HasMany
    {
        return $this->hasMany(ComandaExistencia::class);
    }

    public function platos(): HasMany
    {
        return $this->hasMany(ComandaPlato::class);
    }
}
