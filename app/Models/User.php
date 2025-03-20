<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasAvatar
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'empleado_id',
        'foto',
        'descripcion'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }
    public function cajas(): BelongsToMany
    {
        return $this->belongsToMany(Caja::class);
    }

    public function areas(): BelongsToMany
    {
        return $this->belongsToMany(Area::class);
    }

    public function areaExistencias(): BelongsToMany
    {
        return $this->belongsToMany(AreaExistencia::class);
    }

    public function ordencompra(): HasMany
    {
        return $this->hasMany(OrdenCompra::class);
    }

    public function solicitudCompra(): HasMany
    {
        return $this->hasMany(SolicitudCompra::class);
    }
    public function ingresoAlmacen(): HasMany
    {
        return $this->hasMany(IngresoAlmacen::class);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->foto ? Storage::url($this->foto) : null;
    }

    public function zonas(): BelongsToMany
    {
        return $this->belongsToMany(Zona::class);
    }

    public function asignacionPlatos(): HasMany
    {
        return $this->hasMany(AsignacionPlato::class);
    }

    public function salidaAlmacens(): HasMany
    {
        return $this->hasMany(SalidaAlmacen::class);
    }

    public function asignacionExistencias(): HasMany
    {
        return $this->hasMany(AsignacionExistencia::class);
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }
}
