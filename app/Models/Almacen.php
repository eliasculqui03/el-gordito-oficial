<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Almacen extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];



    public function ingresoAlmacen(): HasMany
    {
        return $this->hasMany(IngresoAlmacen::class);
    }

    public function inventario(): HasMany
    {
        return $this->hasMany(Inventario::class);
    }

    public function salidaAlmacens(): HasMany
    {
        return $this->hasMany(SalidaAlmacen::class);
    }
}
