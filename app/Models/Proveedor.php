<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'tipo_documento_id',
        'numero_documento',
        'telefono',
        'email',
        'estado',
    ];

    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class);
    }

    public function solicitudCompra(): HasMany
    {
        return $this->hasMany(SolicitudCompra::class);
    }
}
