<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\Zona;
use Livewire\Component;

class SelectMesaModal extends Component
{

    public $cajas;
    public $zonas;
    public $cajaActual = null;
    public $zonaActual = null;
    public $mesaSeleccionada = null;

    public function mount()
    {
        $this->cajas = Caja::where('estado', true)->get();
        $this->zonas = collect();
    }

    public function cambiarCaja($cajaId)
    {
        $this->cajaActual = $cajaId;
        $this->zonas = Zona::where('caja_id', $cajaId)->get();
        $this->zonaActual = null;
    }

    public function cambiarZona($zonaId)
    {
        $this->zonaActual = $zonaId;
    }

    public function seleccionarMesa($mesaId)
    {
        $this->mesaSeleccionada = $mesaId;
        $this->dispatch('mesa-seleccionada', mesaId: $mesaId);
        $this->dispatch('close-modal');
    }

    public function render()
    {
        return view('livewire.select-mesa-modal');
    }
}
