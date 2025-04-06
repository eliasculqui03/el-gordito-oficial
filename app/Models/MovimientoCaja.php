<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoCaja extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'caja_id',
        'tipo_transaccion',
        'medio_pago_id',
        'monto',
        'descripcion',
    ];

    protected $casts = [
        'monto' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function caja(): BelongsTo
    {
        return $this->belongsTo(Caja::class);
    }

    public function medioPago(): BelongsTo
    {
        return $this->belongsTo(MedioPago::class);
    }
}
