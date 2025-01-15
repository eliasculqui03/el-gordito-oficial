<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'zona_id',
        'numero',
        'estado',
    ];


    public function zona(): BelongsTo
    {
        return $this->belongsTo(Zona::class);
    }
}
