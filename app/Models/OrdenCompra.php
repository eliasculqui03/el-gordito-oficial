<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrdenCompra extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'metodo_pago',
        'factura',
        'foto',
        'igv',
        'total',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detalleOrdenCompra(): HasMany
    {
        return $this->hasMany(DetalleOrdenCompra::class);
    }
}
