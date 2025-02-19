<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\AreaExistencia;
use App\Models\Comanda;
use App\Models\ComandaExistencia;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ComandasExistencias extends Component
{
    public $selectedArea = null;
    public $refreshInterval = 2000;

    protected $listeners = ['echo:comandas,ComandaCreated' => '$refresh'];

    public function mount()
    {
        // Obtener las áreas de existencia asignadas al usuario actual
        $userAreasExistencias = Auth::user()->areaExistencias;

        if ($userAreasExistencias->count() > 0) {
            // Si el usuario tiene áreas asignadas, seleccionar la primera
            $this->selectedArea = $userAreasExistencias->first()->id;
        } else {
            // Si es admin o no tiene áreas asignadas, seleccionar la primera área disponible
            $this->selectedArea = AreaExistencia::first()?->id;
        }
    }

    public function selectArea($areaId)
    {
        // Obtener las áreas de existencia del usuario
        $userAreasExistencias = Auth::user()->areaExistencias;

        if ($userAreasExistencias->count() > 0) {
            // Verificar si el usuario tiene acceso al área seleccionada
            if ($userAreasExistencias->contains('id', $areaId)) {
                $this->selectedArea = $areaId;
            }
        } else {
            // Si es admin, puede seleccionar cualquier área
            $this->selectedArea = $areaId;
        }
    }

    public function procesarComanda($comandaId)
    {
        $comanda = Comanda::findOrFail($comandaId);

        // Actualizar estado de las existencias asociadas al área seleccionada
        $comanda->comandaExistencias()
            ->whereHas('existencia', function ($query) {
                $query->where('area_existencia_id', $this->selectedArea);
            })
            ->update(['estado' => 'Procesando']);

        $this->emit('comandaProcesada');
    }

    public function render()
    {
        // Obtener las áreas según los permisos del usuario
        $userAreasExistencias = Auth::user()->areaExistencias;

        // Si el usuario tiene áreas asignadas, mostrar solo esas. Si no, mostrar todas (admin)
        $areas = $userAreasExistencias->count() > 0
            ? $userAreasExistencias
            : AreaExistencia::all();

        // Construir la consulta de comandas
        $comandasQuery = Comanda::with([
            'cliente',
            'zona',
            'mesa',
            'comandaExistencias' => function ($query) {
                $query->whereHas('existencia', function ($q) {
                    $q->where('area_existencia_id', $this->selectedArea);
                });
            },
            'comandaExistencias.existencia',
            'comandaExistencias.existencia.unidadMedida'
        ])
            ->whereHas('comandaExistencias', function ($query) {
                $query->where('estado', 'Pendiente')
                    ->whereHas('existencia', function ($q) {
                        $q->where('area_existencia_id', $this->selectedArea);
                    });
            })
            ->latest();

        // Si el usuario tiene áreas asignadas, filtrar solo sus comandas
        if ($userAreasExistencias->count() > 0) {
            $comandasQuery->whereHas('comandaExistencias.existencia', function ($query) use ($userAreasExistencias) {
                $query->whereIn('area_existencia_id', $userAreasExistencias->pluck('id'));
            });
        }

        $comandasAgrupadas = $comandasQuery->get();

        return view('livewire.comandas-existencias', [
            'areas' => $areas,
            'comandasAgrupadas' => $comandasAgrupadas
        ]);
    }
}
//
