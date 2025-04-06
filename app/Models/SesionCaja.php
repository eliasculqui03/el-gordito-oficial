<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SesionCaja extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'caja_id',
        'fecha_apertura',
        'fecha_cierra',
        'saldo_inicial',
        'saldo_cierre',
        'estado',
    ];

    protected $casts = [
        'saldo_inicial' => 'float',
        'saldo_cierre' => 'float',
        'estado' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function caja(): BelongsTo
    {
        return $this->belongsTo(Caja::class);
    }
}
