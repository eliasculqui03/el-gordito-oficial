<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComprobantePago extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_comprobante_id',
        'serie',
        'numero',
        'cliente_id',
        'comanda_id',
        'moneda',
        'medio_pago',
        'hash_cpe',
        'hash_cdr',
        'xml_cpe',
        'xml_cdr',
        'user_id',
        'caja_id',
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


    public function caja(): BelongsTo
    {
        return $this->belongsTo(Caja::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
