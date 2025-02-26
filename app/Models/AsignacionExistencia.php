<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsignacionExistencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'comanda_existencia_id',
        'user_id',
        'estado'
    ];

    public function comandaExistencia(): BelongsTo
    {
        return $this->belongsto(ComandaExistencia::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsto(User::class);
    }
}
