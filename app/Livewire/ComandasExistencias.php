<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\AreaExistencia;
use App\Models\Comanda;
use App\Models\ComandaExistencia;
use App\Models\Existencia;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ComandasExistencias extends Component
{
    public $selectedArea;
    public $refreshInterval = 2000;

    protected $listeners = ['echo:comandas,ComandaCreated' => '$refresh'];

    public function mount()
    {
        // Obtener las áreas del usuario actual usando la relación muchos a muchos
        $userAreas = auth()->user()->areaExistencias;

        if ($userAreas->count() > 0) {
            // Si el usuario tiene áreas asignadas, mostrar la primera
            $this->selectedArea = $userAreas->first()->id;
        } else {
            // Si no tiene áreas asignadas, mostrar la primera área (para admins)
            $this->selectedArea = AreaExistencia::first()?->id;
        }
    }

    public function selectArea($areaId)
    {
        // Obtener las áreas del usuario
        $userAreas = auth()->user()->areaExistencias;

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

        $comanda->update(['estado' => 'Procesando']);

        // Actualizar estado de las existencias del área seleccionada
        $existenciasIds = Existencia::where('area_existencia_id', $this->selectedArea)
            ->pluck('id');

        // Actualizar estado de las existencias
        ComandaExistencia::where('comanda_id', $comandaId)
            ->whereIn('existencia_id', $existenciasIds)
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
        $userAreas = auth()->user()->areaExistencias;

        // Si el usuario tiene áreas asignadas, mostrar solo esas áreas
        // Si no tiene áreas asignadas (admin), mostrar todas las áreas
        $areas = $userAreas->count() > 0 ? $userAreas : AreaExistencia::all();

        // Construir la consulta base
        $comandasQuery = Comanda::with([
            'cliente',
            'zona',
            'mesa',
            'comandaExistencias.existencia' => function ($query) {
                $query->where('area_existencia_id', $this->selectedArea);
            }
        ])
            ->whereIn('estado', ['Abierta', 'Procesando'])
            ->whereHas('comandaExistencias.existencia', function ($query) {
                $query->where('area_existencia_id', $this->selectedArea);
            })
            ->latest();

        // Si el usuario tiene áreas asignadas, filtrar solo sus comandas
        if ($userAreas->count() > 0) {
            $comandasQuery->whereHas('comandaExistencias.existencia', function ($query) use ($userAreas) {
                $query->whereIn('area_existencia_id', $userAreas->pluck('id'));
            });
        }

        $comandas = $comandasQuery->get();

        return view('livewire.comandas-existencias', [
            'areas' => $areas,
            'comandas' => $comandas,
            'existenciasAProcesar' => $this->existenciasAProcesar,
        ]);
    }

    public function marcarExistenciaLista($existenciaId)
    {
        try {
            // Obtener todas las comandas existencias relacionadas que estén en estado 'Procesando'
            $comandaExistencias = ComandaExistencia::where('existencia_id', $existenciaId)
                ->where('estado', 'Procesando')
                ->get();

            // Actualizar el estado de todas las existencias relacionadas a 'Listo'
            foreach ($comandaExistencias as $comandaExistencia) {
                $comandaExistencia->update(['estado' => 'Listo']);
            }

            // Notificar éxito
            Notification::make()
                ->title('Existencia marcada como lista')
                ->success()
                ->send();
        } catch (\Exception $e) {
            // Notificar error
            Notification::make()
                ->title('Error al marcar la existencia')
                ->body('No se pudo actualizar el estado de la existencia')
                ->danger()
                ->send();
        }
    }

    public function getExistenciasAProcesarProperty()
    {
        return ComandaExistencia::query()
            ->where('estado', 'Procesando')
            ->whereHas('existencia', function ($query) {
                $query->where('area_existencia_id', $this->selectedArea);
            })
            ->with('existencia.unidadMedida')
            ->get()
            ->groupBy('existencia_id')
            ->map(function ($grupo) {
                $primerItem = $grupo->first();
                return [
                    'id' => $primerItem->existencia->id,
                    'nombre' => $primerItem->existencia->nombre,
                    'unidad' => $primerItem->existencia->unidadMedida->nombre ?? '',
                    'total' => $grupo->sum('cantidad'),
                    'estado' => $primerItem->estado
                ];
            })
            ->values()
            ->all();
    }

    public function validarProcesar($comandaId)
    {
        $comanda = Comanda::find($comandaId);

        foreach ($comanda->comandaExistencias as $comandaExistencia) {
            if ($comandaExistencia->estado !== 'Pendiente') {
                return false;
            }
        }

        return true;
    }
}
//
