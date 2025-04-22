<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\SesionCaja;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Carbon\Carbon;

class SelectorCaja extends Component
{
    public $cajaSeleccionada = null;
    public $mostrarGestionVentas = false;
    public $sesionCajaId = null;

    public function mount()
    {
        // Verificar si ya hay una caja seleccionada en la sesión
        $cajaGuardada = Session::get('caja_seleccionada');
        $sesionCajaGuardada = Session::get('sesion_caja_id');

        if ($cajaGuardada) {
            $this->cajaSeleccionada = $cajaGuardada;
            $this->sesionCajaId = $sesionCajaGuardada;
            $this->mostrarGestionVentas = true;
        }
    }

    public function seleccionarCaja($cajaId)
    {
        $this->cajaSeleccionada = $cajaId;
        $this->mostrarGestionVentas = true;

        // Obtener información de la caja para la notificación
        $caja = Caja::find($cajaId);

        // Registrar la apertura de sesión en la tabla sesion_cajas
        try {
            $sesionCaja = SesionCaja::create([
                'user_id' => Auth::id(),
                'caja_id' => $cajaId,
                'fecha_apertura' => Carbon::now()->format('Y-m-d H:i:s'),
                'saldo_inicial' => $caja->saldo_actual,
                'estado' => true
            ]);

            // Guardar el ID de la sesión en session
            $this->sesionCajaId = $sesionCaja->id;
            Session::put('sesion_caja_id', $sesionCaja->id);

            // Actualizar el estado de la caja a 'Abierta'
            $caja->update(['estado' => 'Abierta']);

            // Guardar la selección en la sesión
            Session::put('caja_seleccionada', $cajaId);

            // Mostrar notificación con Filament
            Notification::make()
                ->title('Caja abierta correctamente')
                ->body('Has iniciado sesión en la caja: ' . $caja->nombre)
                ->success()
                ->duration(4000) // 4 segundos
                ->send();

            $this->dispatch('caja-seleccionada', cajaId: $cajaId);
        } catch (\Exception $e) {
            // Registrar el error en el log
            Log::error('Error al registrar apertura de caja: ' . $e->getMessage());

            // Mostrar notificación de error
            Notification::make()
                ->title('Error al abrir caja')
                ->body('No se pudo registrar la apertura de la caja. Por favor, inténtelo de nuevo.')
                ->danger()
                ->duration(4000)
                ->send();
        }
    }

    #[On('cerrarCaja')]
    public function cerrarCaja()
    {
        try {
            // Obtener información de la caja antes de cerrarla
            $caja = Caja::find($this->cajaSeleccionada);
            $nombreCaja = $caja ? $caja->nombre : 'Desconocida';

            // Obtener el saldo actual para el cierre
            // Asumiendo que tienes un método o lógica para calcular el saldo actual
            // Por ejemplo, podrías tener un método en el modelo Caja que calcule el saldo actual
            $saldoActual = $caja->saldo_actual ?? $caja->saldo_inicial;

            // Cerrar la sesión en la tabla sesion_cajas
            if ($this->sesionCajaId) {
                $sesionCaja = SesionCaja::find($this->sesionCajaId);
                if ($sesionCaja) {
                    $sesionCaja->update([
                        'fecha_cierra' => Carbon::now()->format('Y-m-d H:i:s'),
                        'saldo_cierre' => $saldoActual,
                        'estado' => false
                    ]);
                }

                // Actualizar el estado de la caja a 'Cerrada'
                $caja->update(['estado' => 'Cerrada']);
            }

            $this->cajaSeleccionada = null;
            $this->mostrarGestionVentas = false;
            $this->sesionCajaId = null;

            // Eliminar las variables de sesión
            Session::forget('caja_seleccionada');
            Session::forget('sesion_caja_id');

            // Mostrar notificación de cierre
            Notification::make()
                ->title('Caja cerrada')
                ->body('Has cerrado la sesión en la caja: ' . $nombreCaja)
                ->warning()
                ->duration(4000) // 4 segundos
                ->send();

            $this->dispatch('caja-cerrada');
        } catch (\Exception $e) {
            // Registrar el error en el log
            Log::error('Error al cerrar caja: ' . $e->getMessage());

            // Mostrar notificación de error
            Notification::make()
                ->title('Error al cerrar caja')
                ->body('No se pudo registrar el cierre de la caja. Por favor, inténtelo de nuevo.')
                ->danger()
                ->duration(4000)
                ->send();
        }
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
