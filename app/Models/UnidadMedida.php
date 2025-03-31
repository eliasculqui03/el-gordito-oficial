<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnidadMedida extends Model
{
    use HasFactory;

    protected $fillable = ['codigo', 'descripcion', 'simbolo', 'estado'];

    public function existencias(): HasMany
    {
        return $this->hasMany(Existencia::class);
    }

    public function platos(): HasMany
    {
        return $this->hasMany(Plato::class);
    }
}
