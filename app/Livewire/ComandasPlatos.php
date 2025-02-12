<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\Comanda;
use Livewire\Component;

class ComandasPlatos extends Component
{
    public $selectedArea;

    public function mount()
    {
        $this->selectedArea = null;
    }

    public function render()
    {
        $areas = Area::all();
        $comandas = Comanda::with('cliente', 'zona', 'mesa', 'comandaPlatos.plato')->get();

        return view('livewire.comandas-platos', compact('areas', 'comandas'));
    }

    public function selectArea($areaId)
    {
        $this->selectedArea = $areaId;
    }

}
