<?php

namespace App\Livewire;

use App\Models\AsignacionPlato;
use App\Models\Comanda;
use App\Models\ComandaPlato;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MozosPlatos extends Component
{
    public $refreshInterval = 2000;

    protected $listeners = [
        'echo:comandas,ComandaPlatoActualizado' => '$refresh'
    ];

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
            $asignacion->comandaPlato->update(['estado' => 'Cancelado']);

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
        // Obtener platos listos para entregar
        $platosListos = ComandaPlato::with(['plato', 'comanda.cliente', 'comanda.zona', 'comanda.mesa'])
            ->where('estado', 'Listo')
            ->get();

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
}
