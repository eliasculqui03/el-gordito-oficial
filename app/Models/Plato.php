<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'categoria',
        'precio',
        'area_id',
        'descripcion',
        'estado',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
