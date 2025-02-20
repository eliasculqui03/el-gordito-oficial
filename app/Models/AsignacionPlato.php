<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsignacionPlato extends Model
{
    use HasFactory;

    protected $fillable = [
        'comanda_plato_id',
        'user_id',
        'estado'
    ];

    public function comandaPlato(): BelongsTo
    {
        return $this->belongsto(ComandaPlato::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsto(User::class);
    }
}
