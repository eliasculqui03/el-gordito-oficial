<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDocumento extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'descripcion_larga',
        'descripcion_corta',
        'estado',
    ];


    public function proveedores(): HasMany
    {
        return $this->hasMany(Proveedor::class);
    }

    public function empleados(): HasMany
    {
        return $this->hasMany(Empleado::class);
    }

    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class);
    }
}
