<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IngresoAlmacen extends Model
{
    use HasFactory;

    protected $fillable = [
        'detalle_orden_compra_id',
        'user_id',
        'existencia_id',
        'cantidad',
        'almacen_id',
        
    ];

    public function detalleOrdenCompra(): BelongsTo
    {
        return $this->belongsTo(DetalleOrdenCompra::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class);
    }

    public function existencia(): BelongsTo
    {
        return $this->belongsTo(Existencia::class);
    }
}
