<?php

namespace App\Livewire\Cliente;

use App\Models\Caja;
use App\Models\Mesa;
use App\Models\Zona;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;
use Livewire\Component;

class MesaComponent extends Component
{
    public $isOpen = false;
    public $zonas;

    public $zonaSeleccionada = null;
    public $mesaSeleccionada = '';
    public $mesaSeleccionadaId = null;
    public $zonaSeleccionadaId = null;
    public $zonaNombre = null;

    public function mount()
    {
        $this->zonas = Zona::where('estado', true)->with('mesas')->get();
    }

    public function render()
    {
        return view('livewire.cliente.mesa-component');
    }

    public function closeModalMesa()
    {
        $this->isOpen = false;
        // No limpiamos la selección aquí para mantenerla cuando se cierra el modal
    }

    public function openModalMesa()
    {
        $this->isOpen = true;
        // Si ya hay una zona seleccionada, mantenemos esa selección al abrir el modal
    }

    public function seleccionarZona($zonaId)
    {
        $this->zonaSeleccionada = $zonaId;
        // No limpiamos la mesa seleccionada si cambiamos de zona
        // Solo si la mesa actual no pertenece a la nueva zona
        if ($this->mesaSeleccionadaId) {
            $mesa = Mesa::find($this->mesaSeleccionadaId);
            if ($mesa && $mesa->zona_id != $zonaId) {
                $this->mesaSeleccionada = '';
                $this->mesaSeleccionadaId = null;
            }
        }
    }

    public function seleccionarMesa($mesa)
    {
        if ($mesa['estado'] !== 'Libre') {
            Notification::make()
                ->title('Mesa no disponible')
                ->body('Esta mesa se encuentra ocupada.')
                ->warning()
                ->send();
            return;
        }

        // Verificar que la mesa pertenezca a la zona seleccionada
        if ($mesa['zona_id'] != $this->zonaSeleccionada) {
            Notification::make()
                ->title('Error')
                ->body('Esta mesa no pertenece a la zona seleccionada.')
                ->danger()
                ->send();
            return;
        }

        // Guardamos los valores seleccionados
        $this->mesaSeleccionadaId = $mesa['id'];
        $this->mesaSeleccionada = $mesa['numero'];
        $this->zonaSeleccionadaId = $this->zonaSeleccionada;

        $zona = Zona::find($this->zonaSeleccionada);
        $this->zonaNombre = $zona ? $zona->nombre : null;

        // Enviamos los valores al componente padre
        $this->dispatch(
            'mesaZonaActualizada',
            mesa: $this->mesaSeleccionadaId,
            zona: $this->zonaSeleccionadaId,
            numero: $this->mesaSeleccionada,
            nombre: $this->zonaNombre,
        );

        // Cerramos el modal usando la función existente
        $this->closeModalMesa();

        Notification::make()
            ->title('Mesa seleccionada')
            ->body("Has seleccionado la mesa {$mesa['numero']}")
            ->success()
            ->send();
    }

    public function getMesasZonaProperty()
    {
        if (!$this->zonaSeleccionada) {
            return collect();
        }

        return Mesa::where('zona_id', $this->zonaSeleccionada)
            ->orderBy('numero')
            ->get();
    }

    #[On('limpiarMesaZona')]
    public function limpiar()
    {
        $this->zonaSeleccionada = null;
        $this->mesaSeleccionada = '';
        $this->mesaSeleccionadaId = null;
        $this->zonaSeleccionadaId = null;
        $this->zonaNombre = null;

        $this->dispatch(
            'mesaZonaActualizada',
            mesa: null,
            zona: null,
            numero: '',
            nombre: '',
        );
    }
}
