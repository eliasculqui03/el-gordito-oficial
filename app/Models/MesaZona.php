<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MesaZona extends Model
{
    use HasFactory;

    protected $table = 'mesa_zona';

    protected $fillable = [
        'zona_id',
        'mesa_id',
    ];
}
