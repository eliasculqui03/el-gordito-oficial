<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalidaAlmacen extends Model
{
    use HasFactory;

    protected $fillable = [
        'comanda_existencia_id',
        'user_id',
        'existencia_id',
        'almacen_id',
        'cantidad',
        'nota',
    ];

    protected static function booted()
    {
        static::created(function ($salidaAlmacen) {
            try {
                DB::beginTransaction();

                // 1. Actualizar inventario
                $inventario = Inventario::where('existencia_id', $salidaAlmacen->existencia_id)
                    ->where('almacen_id', $salidaAlmacen->almacen_id)
                    ->lockForUpdate()  // Bloquear el registro para evitar condiciones de carrera
                    ->first();

                if ($inventario) {
                    if ($inventario->stock >= $salidaAlmacen->cantidad) {
                        // Restar la cantidad del inventario
                        $inventario->stock -= $salidaAlmacen->cantidad;
                        $inventario->save();

                        // 2. Actualizar estado de la comanda existencia si existe
                        if ($salidaAlmacen->comanda_existencia_id) {
                            $comandaExistencia = ComandaExistencia::find($salidaAlmacen->comanda_existencia_id);
                            if ($comandaExistencia) {
                                $comandaExistencia->update(['estado' => 'Listo']);
                            }
                        }

                        // Notificar éxito
                        Notification::make()
                            ->success()
                            ->title('Salida procesada')
                            ->body("Stock actualizado. Nuevo stock: {$inventario->stock}")
                            ->send();
                    } else {
                        throw new \Exception("Stock insuficiente. Stock actual: {$inventario->stock}, Cantidad solicitada: {$salidaAlmacen->cantidad}");
                    }
                } else {
                    throw new \Exception('No se encontró el inventario para la existencia y almacén especificados');
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();

                Notification::make()
                    ->danger()
                    ->title('Error al procesar la salida')
                    ->body($e->getMessage())
                    ->send();

                // Registrar el error
                Log::error('Error en salida de almacén: ' . $e->getMessage());

                // Opcional: Si quieres que el error detenga la creación del registro
                throw $e;
            }
        });
    }

    // Método para validar stock antes de crear
    public static function validarStock($existenciaId, $almacenId, $cantidad)
    {
        $stockDisponible = Inventario::where('existencia_id', $existenciaId)
            ->where('almacen_id', $almacenId)
            ->value('stock') ?? 0;

        return $stockDisponible >= $cantidad;
    }

    public function comandaExistencia(): BelongsTo
    {
        return $this->belongsTo(ComandaExistencia::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function existencia(): BelongsTo
    {
        return $this->belongsTo(Existencia::class);
    }

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class);
    }
}
