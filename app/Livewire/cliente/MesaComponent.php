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
    }

    public function openModalMesa()
    {
        $this->isOpen = true;
    }

    public function seleccionarZona($zonaId)
    {
        $this->zonaSeleccionada = $zonaId;
        $this->mesaSeleccionada = '';
        $this->mesaSeleccionadaId = null;
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


        $mesaSeleccionadaId = $mesa['id'];
        $mesaSeleccionada = $mesa['numero'];
        $zonaSeleccionadaId = $this->zonaSeleccionada;

        $zona = Zona::find($this->zonaSeleccionada);
        $zonaNombre = $zona ? $zona->nombre : null;

        $this->dispatch(
            'mesaZonaActualizada',
            mesa: $mesaSeleccionadaId,
            zona: $zonaSeleccionadaId,
            numero: $mesaSeleccionada,
            nombre: $zonaNombre,

        );


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

        $mesaSeleccionadaId = null;
        $zonaSeleccionadaId = null;
        $mesaSeleccionada = '';
        $zonaNombre = '';


        $this->dispatch(
            'mesaZonaActualizada',
            mesa: $mesaSeleccionadaId,
            zona: $zonaSeleccionadaId,
            numero: $mesaSeleccionada,
            nombre: $zonaNombre,

        );
    }
}
