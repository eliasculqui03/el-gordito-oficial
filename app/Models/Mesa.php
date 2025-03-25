<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'zona_id',
        'numero',
        'estado',
        'capacidad'
    ];


    public function zona(): BelongsTo
    {
        return $this->belongsTo(Zona::class);
    }

    public function comandas(): HasMany
    {
        return $this->hasMany(Comanda::class);
    }
}
