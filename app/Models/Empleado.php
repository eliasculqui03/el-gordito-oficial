<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'tipo_documento_id',
        'numero_documento',
        'telefono',
        'email',
        'direccion',
        'entidad_bancaria',
        'numero_cuenta',
        'sueldo',
        'hora_entrada',
        'hora_salida',
        'estado',
    ];


    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
