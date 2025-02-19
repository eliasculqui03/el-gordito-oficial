<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\Comanda;
use App\Models\ComandaPlato;
use App\Models\Plato;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ComandasPlatos extends Component
{
    public $selectedArea;
    public $refreshInterval = 2000;


    protected $listeners = ['echo:comandas,ComandaCreated' => '$refresh'];

    public function mount()
    {
        // Obtener las áreas del usuario actual usando la relación muchos a muchos
        $userAreas = auth()->user()->areas;

        if ($userAreas->count() > 0) {
            // Si el usuario tiene áreas asignadas, mostrar la primera
            $this->selectedArea = $userAreas->first()->id;
        } else {
            // Si no tiene áreas asignadas, mostrar la primera área (para admins)
            $this->selectedArea = Area::first()?->id;
        }
    }

    public function selectArea($areaId)
    {
        // Obtener las áreas del usuario
        $userAreas = auth()->user()->areas;

        if ($userAreas->count() > 0) {
            // Si tiene áreas asignadas, solo puede seleccionar entre sus áreas
            if ($userAreas->contains('id', $areaId)) {
                $this->selectedArea = $areaId;
            }
        } else {
            // Si no tiene áreas asignadas (admin), puede seleccionar cualquier área
            $this->selectedArea = $areaId;
        }
    }

    public function procesarComanda($comandaId)
    {
        $comanda = Comanda::findOrFail($comandaId);

        // // Verificar si el usuario tiene permiso para procesar la comanda
        // $userArea = Area::where('user_id', auth()->id())->first();
        // if ($userArea && $userArea->id != $this->selectedArea) {
        //     return;
        // }

        // Actualizar estado de la comanda
        $comanda->update(['estado' => 'Procesando']);

        // Obtener los IDs de los platos del área seleccionada
        $platosIds = Plato::where('area_id', $this->selectedArea)
            ->pluck('id');

        // Actualizar estado de los platos
        ComandaPlato::where('comanda_id', $comandaId)
            ->whereIn('plato_id', $platosIds)
            ->update([
                'estado' => 'Procesando'
            ]);

        Notification::make()
            ->title('Comanda en proceso')
            ->success()
            ->send();
    }

    public function render()
    {
        // Obtener las áreas según el usuario
        $userAreas = auth()->user()->areas;

        // Si el usuario tiene áreas asignadas, mostrar solo esas áreas
        // Si no tiene áreas asignadas (admin), mostrar todas las áreas
        $areas = $userAreas->count() > 0 ? $userAreas : Area::all();

        // Construir la consulta base
        $comandasQuery = Comanda::with([
            'cliente',
            'zona',
            'mesa',
            'comandaPlatos.plato' => function ($query) {
                $query->where('area_id', $this->selectedArea);
            }
        ])
            ->whereIn('estado', ['Abierta', 'Procesando'])
            ->whereHas('comandaPlatos.plato', function ($query) {
                $query->where('area_id', $this->selectedArea);
            })
            ->latest();

        // Si el usuario tiene áreas asignadas, filtrar solo sus comandas
        if ($userAreas->count() > 0) {
            $comandasQuery->whereHas('comandaPlatos.plato', function ($query) use ($userAreas) {
                $query->whereIn('area_id', $userAreas->pluck('id'));
            });
        }

        $comandas = $comandasQuery->get();

        return view('livewire.comandas-platos', [
            'areas' => $areas,
            'comandas' => $comandas,
            'platosACocinar' => $this->platosACocinar,

        ]);
    }

    public function marcarPlatoListo($platoId)
    {
        try {
            // Obtener todas las comandas platos relacionadas que estén en estado 'Procesando'
            $comandaPlatos = ComandaPlato::where('plato_id', $platoId)
                ->where('estado', 'Procesando')
                ->get();

            // Actualizar el estado de todos los platos relacionados a 'Listo'
            foreach ($comandaPlatos as $comandaPlato) {
                $comandaPlato->update(['estado' => 'Listo']);
            }

            // Notificar éxito
            Notification::make()
                ->title('Plato marcado como listo')
                ->success()
                ->send();
        } catch (\Exception $e) {
            // Notificar error
            Notification::make()
                ->title('Error al marcar el plato')
                ->body('No se pudo actualizar el estado del plato')
                ->danger()
                ->send();
        }
    }


    public function getPlatosACocinarProperty()
    {
        return ComandaPlato::query()
            ->where('estado', 'Procesando')
            ->whereHas('plato', function ($query) {
                $query->where('area_id', $this->selectedArea);
            })
            ->with('plato')
            ->get()
            ->groupBy('plato_id')
            ->map(function ($grupo) {
                $primerItem = $grupo->first();
                return [
                    'id' => $primerItem->plato->id,
                    'nombre' => $primerItem->plato->nombre,
                    'total' => $grupo->sum('cantidad'),
                    'estado' => $primerItem->estado
                ];
            })
            ->values()
            ->all();
    }
}
