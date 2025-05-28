<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Existencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'tipo_existencia_id',
        'categoria_existencia_id',
        'unidad_medida_id',
        'precio_compra',
        'precio_venta',
        'area_existencia_id',
        'descripcion',
        'estado'
    ];

    public function tipoExistencia(): BelongsTo
    {
        return $this->belongsTo(TipoExistencia::class);
    }

    public function categoriaExistencia(): BelongsTo
    {
        return $this->belongsTo(CategoriaExistencia::class);
    }

    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class);
    }

    public function detalleOrdenCompra(): HasMany
    {
        return $this->hasMany(DetalleOrdenCompra::class);
    }

    public function solicitudCompra(): HasMany
    {
        return $this->hasMany(SolicitudCompra::class);
    }

    public function ingresoAlmacen(): HasMany
    {
        return $this->hasMany(IngresoAlmacen::class);
    }

    public function inventario(): HasOne
    {
        return $this->hasOne(Inventario::class);
    }

    public function comandas(): HasMany
    {
        return $this->hasMany(ComandaExistencia::class);
    }

    public function areaExistencia(): BelongsTo
    {
        return $this->belongsTo(AreaExistencia::class);
    }

    public function salidaAlmacens(): HasMany
    {
        return $this->hasMany(SalidaAlmacen::class);
    }

    public function cajas(): BelongsToMany
    {
        return $this->belongsToMany(Caja::class, 'existencia_caja')
            ->withPivot(['precio_venta'])
            ->withTimestamps();
    }
}
