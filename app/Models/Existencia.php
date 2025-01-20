<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Existencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'tipo_existencia_id',
        'categoria_existencia_id',
        'unidad_medida_id',
        'costo_compra',
        'precio_venta',
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

    public function inventario(): HasMany
    {
        return $this->hasMany(Inventario::class);
    }

    public function comandas(): HasMany
    {
        return $this->hasMany(ComandaExistencia::class);
    }
}
