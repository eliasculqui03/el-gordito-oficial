<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AreaExistencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'user_id',
        'descripcion',
        'estado',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function existencia(): HasMany
    {
        return $this->hasMany(Existencia::class);
    }
}
