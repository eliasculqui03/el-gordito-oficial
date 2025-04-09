<?php

namespace App\Livewire;

use App\Models\Caja;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;

class SelectorCaja extends Component
{
    public $cajaSeleccionada = null;
    public $mostrarGestionVentas = false;


    public function mount()
    {
        // Verificar si ya hay una caja seleccionada en la sesión
        $cajaGuardada = Session::get('caja_seleccionada');

        if ($cajaGuardada) {
            $this->cajaSeleccionada = $cajaGuardada;
            $this->mostrarGestionVentas = true;
        }
    }

    public function seleccionarCaja($cajaId)
    {
        $this->cajaSeleccionada = $cajaId;
        $this->mostrarGestionVentas = true;

        // Guardar la selección en la sesión
        Session::put('caja_seleccionada', $cajaId);

        // Obtener información de la caja para la notificación
        $caja = Caja::find($cajaId);

        // Mostrar notificación con Filament
        Notification::make()
            ->title('Caja abierta correctamente')
            ->body('Has iniciado sesión en la caja: ' . $caja->nombre)
            ->success()
            ->duration(4000) // 4 segundos
            ->send();

        $this->dispatch('caja-seleccionada', cajaId: $cajaId);
    }

    #[On('cerrarCaja')]
    public function cerrarCaja()
    {
        // Obtener información de la caja antes de cerrarla
        $caja = Caja::find($this->cajaSeleccionada);
        $nombreCaja = $caja ? $caja->nombre : 'Desconocida';

        $this->cajaSeleccionada = null;
        $this->mostrarGestionVentas = false;

        // Eliminar la caja de la sesión
        Session::forget('caja_seleccionada');

        // Mostrar notificación de cierre
        Notification::make()
            ->title('Caja cerrada')
            ->body('Has cerrado la sesión en la caja: ' . $nombreCaja)
            ->warning()
            ->duration(4000) // 4 segundos
            ->send();

        $this->dispatch('caja-cerrada');
    }

    private function getCajasAsignadasIds()
    {
        try {
            $user = Auth::user();

            // Comprobar si la relación existe en la base de datos
            $hasCajas = DB::table('caja_user')
                ->where('user_id', $user->id)
                ->exists();

            if ($hasCajas) {
                // Obtener cajas asignadas al usuario
                return DB::table('caja_user')
                    ->where('user_id', $user->id)
                    ->pluck('caja_id')
                    ->toArray();
            }

            // Si no hay relaciones, asumimos que es admin y puede ver todas las cajas
            return Caja::pluck('id')->toArray();
        } catch (\Exception $e) {
            // Registrar el error en el log
            Log::error('Error al obtener cajas asignadas: ' . $e->getMessage());

            // Mostrar notificación de error con Filament
            Notification::make()
                ->title('Error al cargar cajas')
                ->body('No se pudieron cargar las cajas asignadas. Por favor, inténtelo de nuevo.')
                ->danger()
                ->persistent()
                ->send();

            return [];
        }
    }

    public function render()
    {
        // Obtener IDs de cajas asignadas (o todas si es admin)
        $cajasIds = $this->getCajasAsignadasIds();

        // Filtrar cajas por IDs y estado
        $cajas = Caja::whereIn('id', $cajasIds)
            ->where('estado', 'Cerrada')
            ->get();

        // Verificar si el usuario es admin (no tiene asignaciones específicas)
        $esAdmin = !DB::table('caja_user')
            ->where('user_id', Auth::id())
            ->exists();

        return view('livewire.selector-caja', [
            'cajas' => $cajas,
            'esAdmin' => $esAdmin
        ]);
    }
}
