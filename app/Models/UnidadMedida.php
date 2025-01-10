<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnidadMedida extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'simbolo', 'estado'];

    public function existencias(): HasMany
    {
        return $this->hasMany(Existencia::class);
    }
}
