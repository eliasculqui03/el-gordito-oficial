<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_comprobante_id',
        'cliente_id',
        'comanda_id',
        'metodo_pago',
        'subtotal',
        'igv',
        'total',
        'user_id',
        'observaciones',
    ];

    public function tipoComprobante(): BelongsTo
    {
        return $this->belongsTo(TipoComprobante::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function comanda(): BelongsTo
    {
        return $this->belongsTo(Comanda::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ventaPlatos(): HasMany
    {
        return $this->hasMany(VentaPlato::class);
    }

    public function ventaExistencias(): HasMany
    {
        return $this->hasMany(VentaExistencia::class);
    }
}
