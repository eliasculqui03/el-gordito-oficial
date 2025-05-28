<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Caja extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasFactory;
    protected $fillable = [
        'nombre',
        'sucursal_id',
        'user_id',
        'saldo_actual',
        'estado',
    ];

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'caja_user');
    }

    public function zonas(): BelongsToMany
    {
        return $this->belongsToMany(Zona::class);
    }

    public function sesionCajas(): HasMany
    {
        return $this->hasMany(SesionCaja::class);
    }

    public function movimientoCajas(): HasMany
    {
        return $this->hasMany(MovimientoCaja::class);
    }

    public function comprobantePago(): HasMany
    {
        return $this->hasMany(ComprobantePago::class);
    }
    public function comandas(): HasMany
    {
        return $this->hasMany(Comanda::class);
    }

    public function platos(): BelongsToMany
    {
        return $this->belongsToMany(Plato::class, 'plato_caja')
            ->withPivot('precio', 'precio_llevar')
            ->withTimestamps();
    }

    public function existencias(): BelongsToMany
    {
        return $this->belongsToMany(Existencia::class, 'existencia_caja')
            ->withPivot(['precio_venta'])
            ->withTimestamps();
    }
}
