<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
