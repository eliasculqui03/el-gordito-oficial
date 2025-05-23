<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\MovimientoCaja;
use App\Models\SesionCaja;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Filament\Notifications\Notification;

class RetirarComponente extends Component
{
    public $isOpen = false;
    public $cajaId;
    public $caja;

    // Propiedades para el formulario
    public $monto;
    public $descripcion;

    // Reglas de validación
    protected $rules = [
        'monto' => 'required|numeric|min:0.01',
        'descripcion' => 'required|string|min:3|max:255',
    ];

    protected $messages = [
        'monto.required' => 'El monto es obligatorio.',
        'monto.numeric' => 'El monto debe ser un número válido.',
        'monto.min' => 'El monto debe ser mayor a 0.',
        'descripcion.required' => 'Debes ingresar un motivo para el retiro.',
        'descripcion.min' => 'El motivo debe tener al menos 3 caracteres.',
    ];

    public function mount($cajaId = null)
    {
        $this->cajaId = $cajaId;
        $this->loadCajaInfo();
    }

    protected function loadCajaInfo()
    {
        if ($this->cajaId) {
            $this->caja = Caja::find($this->cajaId);
        }
    }

    public function openModal()
    {
        $this->loadCajaInfo();
        $this->resetValidation();
        $this->reset(['monto', 'descripcion']);
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    // Método para obtener la sesión activa de una caja
    protected function getSesionActiva($cajaId)
    {
        return SesionCaja::where('caja_id', $cajaId)
            ->where('estado', true)
            ->latest()
            ->first();
    }

    public function retirarSaldo()
    {
        $this->validate();

        // Verificar que la caja tenga suficiente saldo
        if ($this->caja->saldo_actual < $this->monto) {
            $this->addError('monto', 'La caja no tiene saldo suficiente para este retiro.');
            return;
        }

        // Verificar que la caja esté abierta
        if ($this->caja->estado != 'Abierta') {
            $this->addError('caja', 'La caja debe estar abierta para realizar retiros.');
            return;
        }

        // Obtener la sesión activa de la caja
        $sesionCaja = $this->getSesionActiva($this->cajaId);

        if (!$sesionCaja) {
            $this->addError('caja', 'No hay una sesión activa para la caja. Por favor, abra la caja primero.');
            return;
        }

        try {
            DB::beginTransaction();

            // Actualizar el saldo de la caja
            $this->caja->saldo_actual -= $this->monto;
            $this->caja->save();

            // Registrar el movimiento
            MovimientoCaja::create([
                'user_id' => Auth::id(),
                'sesion_caja_id' => $sesionCaja->id,
                'tipo_transaccion' => 'Egreso',
                'motivo' => 'Retiro',
                'monto' => $this->monto,
                'descripcion' => $this->descripcion,
                'caja_id' => $this->cajaId
            ]);

            DB::commit();

            // Notificar el éxito
            Notification::make()
                ->title('Retiro exitoso')
                ->body('Se ha realizado el retiro de S/. ' . number_format($this->monto, 2) . ' correctamente.')
                ->success()
                ->duration(5000)
                ->send();

            $this->closeModal();
        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Error al realizar el retiro')
                ->body('Ha ocurrido un error: ' . $e->getMessage())
                ->danger()
                ->duration(5000)
                ->send();
        }
    }

    public function render()
    {
        return view('livewire.retirar-componente', [
            'usuario' => Auth::user()
        ]);
    }
}
