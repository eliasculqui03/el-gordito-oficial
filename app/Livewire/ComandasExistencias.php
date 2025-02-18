<?php

namespace App\Livewire;

use App\Models\AreaExistencia;
use App\Models\Comanda;
use App\Models\ComandaExistencia;
use Livewire\Component;

class ComandasExistencias extends Component
{
    public $selectedArea = null;
    public $refreshInterval = 2000;

    protected $listeners = ['echo:comandas,ComandaCreated' => '$refresh'];

    public function mount()
    {
        // Obtener el área del usuario si tiene una asignada
        $userArea = AreaExistencia::where('user_id', auth()->id())->first();

        if ($userArea) {
            // Si el usuario tiene un área asignada, mostrar solo esa
            $this->selectedArea = $userArea->id;
        } else {
            // Si no tiene área asignada, mostrar la primera área
            $this->selectedArea = AreaExistencia::first()?->id;
        }
    }

    public function selectArea($areaId)
    {
        // Validar si el usuario puede seleccionar esta área
        $userArea = AreaExistencia::where('user_id', auth()->id())->first();

        if ($userArea) {
            // Si tiene área asignada, solo puede seleccionar su área
            if ($userArea->id == $areaId) {
                $this->selectedArea = $areaId;
            }
        } else {
            // Si no tiene área asignada, puede seleccionar cualquier área
            $this->selectedArea = $areaId;
        }
    }

    public function procesarComanda($comandaExistenciaId)
    {
        $comandaExistencia = ComandaExistencia::findOrFail($comandaExistenciaId);
        $comandaExistencia->update(['estado' => 'Procesando']);
        $this->emit('comandaProcesada');
    }

    public function render()
    {
        // Verificar si el usuario tiene un área asignada
        $userArea = AreaExistencia::where('user_id', auth()->id())->first();

        // Obtener áreas según el usuario
        $areas = $userArea
            ? AreaExistencia::where('id', $userArea->id)->get()
            : AreaExistencia::all();

        $comandasAgrupadas = Comanda::with(['cliente', 'zona', 'mesa', 'comandaExistencias.existencia'])
            ->whereHas('comandaExistencias', function ($query) {
                $query->where('estado', 'Pendiente')
                    ->whereHas('existencia', function ($q) {
                        $q->where('area_existencia_id', $this->selectedArea);
                    });
            })
            ->latest()
            ->get();

        return view('livewire.comandas-existencias', [
            'areas' => $areas,
            'comandasAgrupadas' => $comandasAgrupadas
        ]);
    }
}
//
