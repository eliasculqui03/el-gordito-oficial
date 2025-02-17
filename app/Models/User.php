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
    public function caja(): HasMany
    {
        return $this->hasMany(Caja::class);
    }

    public function area(): HasMany
    {
        return $this->hasMany(Area::class);
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
}
