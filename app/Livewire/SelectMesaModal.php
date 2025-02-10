<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\Mesa;
use App\Models\Zona;
use Livewire\Component;

class SelectMesaModal extends Component
{

    public $isOpen = false;
    public $cajas;
    public $zonas;
    public $cajaActual = null;
    public $zonaActual = null;
    public $mesaSeleccionada = null;
    public $mesaSeleccionadaId = null;

    public function closeModalMesa()
    {
        $this->isOpen = false;
    }

    public function openModalMesa()
    {
        $this->isOpen = true;
    }

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

    public function seleccionarMesa(Mesa $mesa)
    {
        $this->mesaSeleccionada = $mesa->numero;
        $this->mesaSeleccionadaId = $mesa->id;
        $this->closeModalMesa();
    }

    public function render()
    {
        return view('livewire.select-mesa-modal');
    }
}
