<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoExistencia extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'estado'];

    public function existencias(): HasMany
    {
        return $this->hasMany(Existencia::class);
    }
}
