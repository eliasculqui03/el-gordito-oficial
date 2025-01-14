<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DetalleOrdenCompra extends Model
{
    use HasFactory;

    protected $fillable = [
        'orden_compra_id',
        'pedido_compra',
        'existencia_id',
        'cantidad',
        'subtotal',
        'estado',
    ];

    public function ordenCompra(): BelongsTo
    {
        return $this->belongsTo(OrdenCompra::class);
    }

    public function solicitudCompra(): BelongsTo
    {
        return $this->belongsTo(SolicitudCompra::class);
    }

    public function existencia(): BelongsTo
    {
        return $this->belongsTo(Existencia::class);
    }

    public function ingresoAlmacen(): HasMany
    {
        return $this->hasMany(IngresoAlmacen::class);
    }
}
