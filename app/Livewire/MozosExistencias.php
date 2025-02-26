<?php

namespace App\Livewire;

use App\Models\AreaExistencia;
use App\Models\AsignacionExistencia;
use App\Models\AsignacionExistencias;
use App\Models\Comanda;
use App\Models\ComandaExistencia;
use App\Models\ComandaPlato;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class MozosExistencias extends Component
{
    public $refreshInterval = 2000;
    public $mostrarConfirmacion = false;
    public $existenciaCancelarId = null;

    protected $listeners = [
        'echo:comandas,ComandaExistenciaActualizado' => '$refresh'
    ];

    public function confirmarCancelacion($comandaExistenciaId)
    {
        $this->existenciaCancelarId = $comandaExistenciaId;
        $this->mostrarConfirmacion = true;
    }

    public function cerrarConfirmacion()
    {
        $this->mostrarConfirmacion = false;
        $this->existenciaCancelarId = null;
    }

    public function procederCancelacion()
    {
        if ($this->existenciaCancelarId) {
            $this->cancelarExistencia($this->existenciaCancelarId);
            $this->mostrarConfirmacion = false;
            $this->existenciaCancelarId = null;
        }
    }

    private function getAreasAsignadasIds()
    {
        try {
            $user = Auth::user();

            // Comprobar si la relación existe en la base de datos
            $hasZonas = DB::table('area_existencia_user')
                ->where('user_id', $user->id)
                ->exists();

            if ($hasZonas) {
                // Obtener zonas asignadas al usuario
                return DB::table('area_existencia_user')
                    ->where('user_id', $user->id)
                    ->pluck('area_existencia_id')
                    ->toArray();
            }

            // Si no hay relaciones (posiblemente admin), devolver array vacío
            return [];
        } catch (\Exception $e) {
            Log::error('Error al obtener zonas asignadas: ' . $e->getMessage());
            return [];
        }
    }

    private function getZonasAsignadasIds()
    {
        try {
            $user = Auth::user();

            // Comprobar si la relación existe en la base de datos
            $hasZonas = DB::table('user_zona')
                ->where('user_id', $user->id)
                ->exists();

            if ($hasZonas) {
                // Obtener zonas asignadas al usuario
                return DB::table('user_zona')
                    ->where('user_id', $user->id)
                    ->pluck('zona_id')
                    ->toArray();
            }

            // Si no hay relaciones (posiblemente admin), devolver array vacío
            return [];
        } catch (\Exception $e) {
            Log::error('Error al obtener zonas asignadas: ' . $e->getMessage());
            return [];
        }
    }



    public function asignarExistencia($comandaExistenciaId)
    {
        try {
            // Obtener la comanda existencia
            $comandaExistencia = ComandaExistencia::findOrFail($comandaExistenciaId);

            // Verificar que la existencia esté en estado "Listo"
            if ($comandaExistencia->estado !== 'Listo') {
                Notification::make()
                    ->title('La existencia no está lista para entregar')
                    ->danger()
                    ->send();
                return;
            }


            // Iniciar transacción para garantizar integridad de datos
            DB::beginTransaction();

            // Cambiar estado de la comanda existencia a "Entregando"
            $comandaExistencia->update(['estado' => 'Entregando']);

            // Crear asignación de la existencia al mozo actual
            AsignacionExistencia::create([
                'comanda_existencia_id' => $comandaExistenciaId,
                'user_id' => Auth::id(),
                'estado' => 'Asignado'
            ]);

            DB::commit();

            Notification::make()
                ->title('Existencia asignada correctamente')
                ->success()
                ->send();
        } catch (\Exception $e) {
            DB::rollback();

            Notification::make()
                ->title('Error al asignar la existencia')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function marcarEntregado($asignacionId)
    {
        try {
            // Obtener la asignación de la existencia
            $asignacion = AsignacionExistencia::with('comandaExistencia')->findOrFail($asignacionId);

            // Verificar que el usuario actual sea quien tiene asignada la existencia
            if ($asignacion->user_id !== Auth::id()) {
                Notification::make()
                    ->title('No tienes permiso para marcar esta existencia como entregada')
                    ->danger()
                    ->send();
                return;
            }

            // Iniciar transacción
            DB::beginTransaction();

            // Cambiar estado de la asignación a "Completado"
            $asignacion->update(['estado' => 'Completado']);

            // Cambiar estado de la comanda existencia a "Completado"
            $asignacion->comandaExistencia->update(['estado' => 'Completado']);

            // Verificar si todas las existencias de la comanda están completadas o canceladas
            $this->verificarEstadoComanda($asignacion->comandaExistencia->comanda_id);

            DB::commit();

            Notification::make()
                ->title('Existencia entregada correctamente')
                ->success()
                ->send();
        } catch (\Exception $e) {
            DB::rollback();

            Notification::make()
                ->title('Error al marcar como entregada')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function cancelarAsignacion($asignacionId)
    {
        try {
            // Obtener la asignación de la existencia
            $asignacion = AsignacionExistencia::with('comandaExistencia')->findOrFail($asignacionId);

            // Verificar que el usuario actual sea quien tiene asignada la existencia
            if ($asignacion->user_id !== Auth::id()) {
                Notification::make()
                    ->title('No tienes permiso para cancelar esta asignación')
                    ->danger()
                    ->send();
                return;
            }

            // Iniciar transacción
            DB::beginTransaction();

            // Cambiar estado de la comanda existencia a "Listo"
            $asignacion->comandaExistencia->update(['estado' => 'Listo']);

            // Eliminar la asignación
            $asignacion->delete();

            $this->verificarEstadoComanda($asignacion->comandaExistencia->comanda_id);

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

    public function cancelarExistencia($comandaExistenciaId)
    {
        try {
            // Obtener la comanda existencia
            $comandaExistencia = ComandaExistencia::findOrFail($comandaExistenciaId);

            // Verificar que la existencia esté en estado "Listo"
            if ($comandaExistencia->estado !== 'Listo') {
                Notification::make()
                    ->title('Solo se pueden cancelar existencias en estado Listo')
                    ->danger()
                    ->send();
                return;
            }


            // Iniciar transacción
            DB::beginTransaction();

            // Cambiar estado de la comanda existencia a "Cancelado"
            $comandaExistencia->update(['estado' => 'Cancelado']);

            // Verificar si todas las existencias de la comanda están completadas o canceladas
            $this->verificarEstadoComanda($comandaExistencia->comanda_id);

            DB::commit();

            Notification::make()
                ->title('Existencia cancelada correctamente')
                ->success()
                ->send();
        } catch (\Exception $e) {
            DB::rollback();

            Notification::make()
                ->title('Error al cancelar la existencia')
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

        // Contar las existencias que no están completadas ni canceladas
        $existenciasNoCompletadas = ComandaExistencia::where('comanda_id', $comandaId)
            ->whereNotIn('estado', ['Completado', 'Cancelado'])
            ->count();

        // Verificar si hay platos o existencias en la comanda
        $tieneComandaPlatos = ComandaPlato::where('comanda_id', $comandaId)->exists();
        $tieneComandaExistencias = ComandaExistencia::where('comanda_id', $comandaId)->exists();

        // Si la comanda tiene platos y/o existencias, y todos están en estado completado o cancelado
        if (($tieneComandaPlatos || $tieneComandaExistencias) &&
            ($platosNoCompletados === 0 && $existenciasNoCompletadas === 0)
        ) {
            $comanda->update(['estado' => 'Completada']);
        }
    }

    public function render()
    {
        $areasIds = $this->getAreasAsignadasIds();
        $zonasIds = $this->getZonasAsignadasIds();



        // Obtener existencias listas para entregar
        $existenciasListasQuery = ComandaExistencia::with(['existencia.areaExistencia', 'comanda.cliente', 'comanda.zona', 'comanda.mesa'])
            ->where('estado', 'Listo');

        // Filtrar por zonas si el usuario tiene asignaciones
        if (!empty($areasIds)) {
            $existenciasListasQuery->whereHas('existencia', function ($query) use ($areasIds) {
                $query->whereIn('area_existencia_id', $areasIds);
            });
        }

        if (!empty($zonasIds)) {
            $existenciasListasQuery->whereHas('comanda', function ($query) use ($zonasIds) {
                $query->whereIn('zona_id', $zonasIds);
            });
        }


        $existenciasListas = $existenciasListasQuery->get();

        // Obtener asignaciones de existencias para el usuario actual
        $asignaciones = AsignacionExistencia::with([
            'comandaExistencia.existencia',
            'comandaExistencia.comanda.cliente',
            'comandaExistencia.comanda.zona',
            'comandaExistencia.comanda.mesa'
        ])
            ->where('user_id', Auth::id())
            ->where('estado', 'Asignado')
            ->get();

        return view('livewire.mozos-existencias', [
            'existenciasListas' => $existenciasListas,
            'asignaciones' => $asignaciones
        ]);
    }
}
