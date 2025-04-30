<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoComprobante extends Model
{
    use HasFactory;
    protected $table = 'tipo_comprobantes'; // Nombre de la tabla

    // Campos asignables en masa
    protected $fillable = [
        'codigo',
        'descripcion',
        'estado'

    ];

    public function ordenCompras(): HasMany
    {
        return $this->hasMany(OrdenCompra::class);
    }
}
