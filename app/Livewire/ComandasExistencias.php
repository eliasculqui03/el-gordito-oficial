<?php

namespace App\Livewire;

use App\Models\AreaExistencia;
use App\Models\Comanda;
use Livewire\Component;

class ComandasExistencias extends Component
{

    public $selectedArea;

    public function mount()
    {
        $this->selectedArea = null;
    }

    public function render()
    {
        $areas = AreaExistencia::all();
        $comandas = Comanda::with('cliente', 'zona', 'mesa', 'comandaExistencias.existencia')->get();

        return view('livewire.comandas-existencias', compact('areas', 'comandas'));
    }

    public function selectArea($areaId)
    {
        $this->selectedArea = $areaId;
    }
}
