<?php

namespace App\Livewire;

use App\Models\AreaExistencia;
use App\Models\Comanda;
use App\Models\ComandaExistencia;
use Livewire\Component;

class ComandasExistencias extends Component
{
    public $selectedArea = null;
    public $refreshInterval = 30000; // 30 segundos

    protected $listeners = ['echo:comandas,ComandaCreated' => '$refresh'];

    public function mount()
    {
        $this->selectedArea = AreaExistencia::first()?->id;
    }

    public function selectArea($areaId)
    {
        $this->selectedArea = $areaId;
    }

    public function procesarComanda($comandaExistenciaId)
    {
        $comandaExistencia = ComandaExistencia::findOrFail($comandaExistenciaId);
        $comandaExistencia->update(['estado' => 'Entregando']);
    }

    public function render()
    {
        $areas = AreaExistencia::all();

        $comandas = ComandaExistencia::with([
            'comanda.cliente',
            'comanda.zona',
            'comanda.mesa',
            'existencia.areaExistencia'
        ])
            ->whereHas('existencia', function ($query) {
                $query->where('area_existencia_id', $this->selectedArea);
            })
            ->where('estado', 'Pendiente')
            ->latest()
            ->get();

        return view('livewire.comandas-existencias', [
            'areas' => $areas,
            'comandas' => $comandas
        ]);
    }
}
//
