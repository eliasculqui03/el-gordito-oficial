<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Plato extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'categoria_plato_id',
        'precio',
        'precio_llevar',
        'area_id',
        'descripcion',
        'estado',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function comandas(): HasMany
    {
        return $this->hasMany(ComandaPlato::class);
    }

    public function categoriaPlato(): BelongsTo
    {
        return $this->belongsTo(CategoriaPlato::class);
    }

    public function ventaPlatos(): HasMany
    {
        return $this->hasMany(VentaPlato::class);
    }

    public function disponibilidadPlato(): HasOne
    {
        return $this->hasOne(DisponibilidadPlato::class);
    }
}
