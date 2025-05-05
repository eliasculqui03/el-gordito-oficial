<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'tipo_actividad',
        'ruc',
        'nombre_comercial',
        'numero_decreto',
        'logo',
        'email',
        'telefono',
        'ubigeo',
        'direccion',
        'departamento',
        'provincia',
        'distrito',
        'moneda',
        'mision',
        'vision',
        'descripcion',
        'facebook',
        'youtube',
        'whatsapp',
        'nombre_gerente',
        'dni_gerente',
        'telefono_gerente',
        'correo_gerente',
        'direccion_gerente',
        'fecha_ingreso_gerente',
    ];

    public function Sucursals(): HasMany
    {
        return $this->hasMany(Sucursal::class);
    }
}
