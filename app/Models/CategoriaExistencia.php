<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriaExistencia extends Model
{
    use HasFactory;

    protected $fillable = ['tipo_existencia_id', 'nombre', 'descripcion', 'estado'];

    public function existencias(): HasMany
    {
        return $this->hasMany(Existencia::class);
    }

    public function tipoExistencia(): BelongsTo
    {
        return $this->belongsTo(TipoExistencia::class);
    }
}
