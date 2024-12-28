<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sucursal extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'empresa_id',
        'tipo_establecimiento',
        'fecha_inicio_operaciones',
        'fecha_final_operaciones',
        'direccion',
        'telefono',
        'correo',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function caja(): HasMany
    {
        return $this->hasMany(Caja::class);
    }
}
