<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\MovimientoCaja;
use App\Models\SesionCaja;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Filament\Notifications\Notification;

class TransferirComponente extends Component
{
    public $cajaId;
    public $cajaOrigen;
    public $isOpen = false;

    // Propiedades para el formulario
    public $cajaDestinoId;
    public $monto;
    public $motivo;

    // Reglas de validación
    protected $rules = [
        'cajaDestinoId' => 'required|exists:cajas,id|different:cajaId',
        'monto' => 'required|numeric|min:0.01',
        'motivo' => 'required|string|min:3|max:255',
    ];

    protected $messages = [
        'cajaDestinoId.required' => 'Debes seleccionar una caja destino.',
        'cajaDestinoId.different' => 'La caja destino debe ser diferente a la caja origen.',
        'monto.required' => 'El monto es obligatorio.',
        'monto.numeric' => 'El monto debe ser un número válido.',
        'monto.min' => 'El monto debe ser mayor a 0.',
        'motivo.required' => 'Debes ingresar un motivo para la transferencia.',
    ];

    public function mount($cajaId = null)
    {
        $this->cajaId = $cajaId;
        // Cargar la información de la caja al montar el componente
        $this->loadCajaInfo();
    }

    // Método para cargar la información de la caja actual
    protected function loadCajaInfo()
    {
        if ($this->cajaId) {
            $this->cajaOrigen = Caja::find($this->cajaId);
        }
    }

    public function openModal()
    {
        // Asegurarse de tener la información actualizada
        $this->loadCajaInfo();
        $this->resetValidation();
        $this->reset(['cajaDestinoId', 'monto', 'motivo']);
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    // Método para obtener la sesión activa de una caja
    protected function getSesionActiva($cajaId)
    {
        $sesionCaja = SesionCaja::where('caja_id', $cajaId)
            ->where('estado', true)
            ->latest()
            ->first();

        return $sesionCaja;
    }

    public function transferir()
    {
        $this->validate();

        // Verificar que la caja origen tenga suficiente saldo
        if ($this->cajaOrigen->saldo_actual < $this->monto) {
            $this->addError('monto', 'La caja no tiene saldo suficiente para esta transferencia.');
            return;
        }

        // Verificar que ambas cajas estén abiertas
        $cajaDestino = Caja::find($this->cajaDestinoId);

        if ($this->cajaOrigen->estado != 'Abierta') {
            $this->addError('cajaId', 'La caja origen debe estar abierta para realizar transferencias.');
            return;
        }

        if ($cajaDestino->estado != 'Abierta') {
            $this->addError('cajaDestinoId', 'La caja destino debe estar abierta para recibir transferencias.');
            return;
        }

        // Obtener las sesiones activas de ambas cajas
        $sesionCajaOrigen = $this->getSesionActiva($this->cajaId);
        $sesionCajaDestino = $this->getSesionActiva($this->cajaDestinoId);

        if (!$sesionCajaOrigen) {
            $this->addError('cajaId', 'No hay una sesión activa para la caja origen. Por favor, abra la caja primero.');
            return;
        }

        if (!$sesionCajaDestino) {
            $this->addError('cajaDestinoId', 'No hay una sesión activa para la caja destino. Por favor, abra la caja primero.');
            return;
        }

        try {
            DB::beginTransaction();

            // Restar de la caja origen
            $this->cajaOrigen->saldo_actual -= $this->monto;
            $this->cajaOrigen->save();

            // Sumar a la caja destino
            $cajaDestino->saldo_actual += $this->monto;
            $cajaDestino->save();

            // Usuario actual
            $userId = Auth::id();

            // Descripción para el movimiento
            $descripcionEgreso = "Transferencia a caja {$cajaDestino->nombre} - {$this->motivo}";
            $descripcionIngreso = "Transferencia desde caja {$this->cajaOrigen->nombre} - {$this->motivo}";

            // Registrar EGRESO en la caja origen
            MovimientoCaja::create([
                'user_id' => $userId,
                'sesion_caja_id' => $sesionCajaOrigen->id,
                'tipo_transaccion' => 'Egreso',
                'motivo' => 'Transferencia',
                'monto' => $this->monto,
                'descripcion' => $descripcionEgreso,
                'caja_id' => $this->cajaId
            ]);

            // Registrar INGRESO en la caja destino
            MovimientoCaja::create([
                'user_id' => $userId,
                'sesion_caja_id' => $sesionCajaDestino->id,
                'tipo_transaccion' => 'Ingreso',
                'motivo' => 'Transferencia',
                'monto' => $this->monto,
                'descripcion' => $descripcionIngreso,
                'caja_id' => $this->cajaDestinoId
            ]);

            DB::commit();

            // Mostrar mensaje de éxito
            session()->flash('message', 'Transferencia realizada con éxito.');
            $this->closeModal();

            // Emitir evento para refrescar otros componentes si es necesario
            $this->dispatch('transferencia-completada');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al realizar la transferencia: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Obtener todas las cajas disponibles excepto la actual
        $cajasDisponibles = Caja::where('id', '!=', $this->cajaId)
            ->where('estado', 'Abierta')
            ->get();

        // Obtener el usuario actual
        $usuario = Auth::user();

        return view('livewire.transferir-componente', [
            'cajasDisponibles' => $cajasDisponibles,
            'usuario' => $usuario
        ]);
    }
}
