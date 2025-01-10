<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaSolicitudCompra extends Model
{
    use HasFactory;

    protected $fillable = [
        'solicitud_compra_id',
        'nota',
        'motivo'
    ];

    public function solicitudCompra()
    {
        return $this->belongsTo(SolicitudCompra::class);
    }
}
