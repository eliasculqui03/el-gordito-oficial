<?php

namespace App\Livewire;

use App\Models\AsignacionPlato;
use App\Models\Comanda;
use App\Models\ComandaPlato;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class MozosPlatos extends Component
{
    public $refreshInterval = 2000;

    public $mostrarConfirmacion = false;
    public $platoCancelarId = null;

    public function confirmarCancelacion($comandaPlatoId)
    {
        $this->platoCancelarId = $comandaPlatoId;
        $this->mostrarConfirmacion = true;
    }

    public function cerrarConfirmacion()
    {
        $this->mostrarConfirmacion = false;
        $this->platoCancelarId = null;
    }

    public function procederCancelacion()
    {
        if ($this->platoCancelarId) {
            $this->cancelarPlato($this->platoCancelarId);
            $this->mostrarConfirmacion = false;
            $this->platoCancelarId = null;
        }
    }

    protected $listeners = [
        'echo:comandas,ComandaPlatoActualizado' => '$refresh'
    ];

    private function getZonasAsignadasIds()
    {
        try {
            $user = Auth::user();

            // Comprobar si la relación existe en la base de datos usando una consulta directa
            $hasZonas = DB::table('user_zona')
                ->where('user_id', $user->id)
                ->exists();

            if ($hasZonas) {
                // Usar consulta directa a la tabla pivote
                return DB::table('user_zona')
                    ->where('user_id', $user->id)
                    ->pluck('zona_id')
                    ->toArray();
            }

            // Si no hay relaciones (posiblemente admin), devolver array vacío
            return [];
        } catch (\Exception $e) {
            // Registrar el error pero continuar
            Log::error('Error al obtener zonas asignadas: ' . $e->getMessage());
            return [];
        }
    }

    private function getAreasAsignadasIds()
    {
        try {
            $user = Auth::user();

            // Comprobar si la relación existe en la base de datos usando una consulta directa
            $hasAreas = DB::table('area_user')
                ->where('user_id', $user->id)
                ->exists();

            if ($hasAreas) {
                // Usar consulta directa a la tabla pivote
                return DB::table('area_user')
                    ->where('user_id', $user->id)
                    ->pluck('area_id')
                    ->toArray();
            }

            // Si no hay relaciones (posiblemente admin), devolver array vacío
            return [];
        } catch (\Exception $e) {
            // Registrar el error pero continuar
            Log::error('Error al obtener áreas asignadas: ' . $e->getMessage());
            return [];
        }
    }

    private function tieneAccesoZona($zonaId)
    {
        $zonasIds = $this->getZonasAsignadasIds();

        // Si el usuario no tiene zonas asignadas, tiene acceso a todas (es admin)
        if (empty($zonasIds)) {
            return true;
        }

        return in_array($zonaId, $zonasIds);
    }
    private function tieneAccesoArea($areaId)
    {
        $areasIds = $this->getAreasAsignadasIds();

        // Si el usuario no tiene áreas asignadas, tiene acceso a todas (es admin)
        if (empty($areasIds)) {
            return true;
        }

        return in_array($areaId, $areasIds);
    }

    public function asignarPlato($comandaPlatoId)
    {
        try {
            // Obtener el comanda plato
            $comandaPlato = ComandaPlato::findOrFail($comandaPlatoId);

            // Verificar que el plato esté en estado "Listo"
            if ($comandaPlato->estado !== 'Listo') {
                Notification::make()
                    ->title('El plato no está listo para entregar')
                    ->danger()
                    ->send();
                return;
            }

            // Iniciar transacción para garantizar integridad de datos
            DB::beginTransaction();

            // Cambiar estado del comanda plato a "Entregando"
            $comandaPlato->update(['estado' => 'Entregando']);

            // Crear asignación del plato al mozo actual (corregido)
            AsignacionPlato::create([
                'comanda_plato_id' => $comandaPlatoId,
                'user_id' => Auth::id(), // Cambiado de usuario_id a user_id
                'estado' => 'Asignado'
            ]);

            DB::commit();

            Notification::make()
                ->title('Plato asignado correctamente')
                ->success()
                ->send();
        } catch (\Exception $e) {
            DB::rollback();

            Notification::make()
                ->title('Error al asignar el plato')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function marcarEntregado($asignacionId)
    {
        try {
            // Obtener la asignación del plato
            $asignacion = AsignacionPlato::with('comandaPlato')->findOrFail($asignacionId);

            // Verificar que el usuario actual sea quien tiene asignado el plato
            if ($asignacion->user_id !== Auth::id()) { // Cambiado de usuario_id a user_id
                Notification::make()
                    ->title('No tienes permiso para marcar este plato como entregado')
                    ->danger()
                    ->send();
                return;
            }

            // Iniciar transacción
            DB::beginTransaction();

            // Cambiar estado de la asignación a "Completado"
            $asignacion->update(['estado' => 'Completado']);

            // Cambiar estado del comanda plato a "Completado"
            $asignacion->comandaPlato->update(['estado' => 'Completado']);

            // Verificar si todos los platos de la comanda están completados o cancelados
            $this->verificarEstadoComanda($asignacion->comandaPlato->comanda_id);

            DB::commit();

            Notification::make()
                ->title('Plato entregado correctamente')
                ->success()
                ->send();
        } catch (\Exception $e) {
            DB::rollback();

            Notification::make()
                ->title('Error al marcar como entregado')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function cancelarAsignacion($asignacionId)
    {
        try {
            // Obtener la asignación del plato
            $asignacion = AsignacionPlato::with('comandaPlato')->findOrFail($asignacionId);

            // Verificar que el usuario actual sea quien tiene asignado el plato
            if ($asignacion->user_id !== Auth::id()) { // Cambiado de usuario_id a user_id
                Notification::make()
                    ->title('No tienes permiso para cancelar esta asignación')
                    ->danger()
                    ->send();
                return;
            }

            // Iniciar transacción
            DB::beginTransaction();

            // Cambiar estado del comanda plato a "Cancelado"
            $asignacion->comandaPlato->update(['estado' => 'Listo']);

            // Eliminar la asignación
            $asignacion->delete();

            // Verificar si todos los platos de la comanda están completados o cancelados
            $this->verificarEstadoComanda($asignacion->comandaPlato->comanda_id);

            DB::commit();

            Notification::make()
                ->title('Asignación cancelada correctamente')
                ->success()
                ->send();
        } catch (\Exception $e) {
            DB::rollback();

            Notification::make()
                ->title('Error al cancelar la asignación')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    private function verificarEstadoComanda($comandaId)
    {
        $comanda = Comanda::findOrFail($comandaId);

        // Contar los platos que no están completados ni cancelados
        $platosNoCompletados = ComandaPlato::where('comanda_id', $comandaId)
            ->whereNotIn('estado', ['Completado', 'Cancelado'])
            ->count();

        // Si todos los platos están completados o cancelados, marcar la comanda como Completada
        if ($platosNoCompletados === 0) {
            $comanda->update(['estado' => 'Completada']);
        }
    }

    public function render()
    {
        $zonasIds = $this->getZonasAsignadasIds();
        $areasIds = $this->getAreasAsignadasIds();

        // Obtener platos listos para entregar
        $platosListosQuery = ComandaPlato::with(['plato.area', 'comanda.cliente', 'comanda.zona', 'comanda.mesa'])
            ->where('estado', 'Listo');

        // Filtrar por zonas y áreas si el usuario tiene asignaciones
        if (!empty($zonasIds)) {
            $platosListosQuery->whereHas('comanda', function ($query) use ($zonasIds) {
                $query->whereIn('zona_id', $zonasIds);
            });
        }

        if (!empty($areasIds)) {
            $platosListosQuery->whereHas('plato', function ($query) use ($areasIds) {
                $query->whereIn('area_id', $areasIds);
            });
        }

        $platosListos = $platosListosQuery->get();

        // Obtener asignaciones de platos para el usuario actual
        $asignaciones = AsignacionPlato::with([
            'comandaPlato.plato',
            'comandaPlato.comanda.cliente',
            'comandaPlato.comanda.zona',
            'comandaPlato.comanda.mesa'
        ])
            ->where('user_id', Auth::id()) // Cambiado de usuario_id a user_id
            ->where('estado', 'Asignado')
            ->get();

        return view('livewire.mozos-platos', [
            'platosListos' => $platosListos,
            'asignaciones' => $asignaciones
        ]);
    }



    public function cancelarPlato($comandaPlatoId)
    {
        try {
            // Obtener el comanda plato
            $comandaPlato = ComandaPlato::findOrFail($comandaPlatoId);

            // Verificar que el plato esté en estado "Listo"
            if ($comandaPlato->estado !== 'Listo') {
                Notification::make()
                    ->title('Solo se pueden cancelar platos en estado Listo')
                    ->danger()
                    ->send();
                return;
            }

            // Iniciar transacción
            DB::beginTransaction();

            // Cambiar estado del comanda plato a "Cancelado"
            $comandaPlato->update(['estado' => 'Cancelado']);

            // Verificar si todos los platos de la comanda están completados o cancelados
            $this->verificarEstadoComanda($comandaPlato->comanda_id);

            DB::commit();

            Notification::make()
                ->title('Plato cancelado correctamente')
                ->success()
                ->send();
        } catch (\Exception $e) {
            DB::rollback();

            Notification::make()
                ->title('Error al cancelar el plato')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
