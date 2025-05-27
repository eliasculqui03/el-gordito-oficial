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


        // Actualizar estado de la comanda
        $comanda->update(['estado' => 'Procesando']);

        // Obtener los IDs de los platos del área seleccionada
        $platosIds = Plato::where('area_id', $this->selectedArea)
            ->pluck('id');

        // Actualizar estado de los platos
        ComandaPlato::where('comanda_id', $comandaId)
            ->whereIn('plato_id', $platosIds)
            ->where('estado', 'Pendiente')
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

    public function marcarPlatoListo($grupoKey)
    {
        try {
            // Obtener las partes de la clave del grupo
            $keyParts = explode('-', $grupoKey);
            $platoId = $keyParts[0];
            $esParaLlevar = $keyParts[1] === 'llevar';

            // Obtener todas las comandas platos relacionadas que estén en estado 'Procesando'
            $comandaPlatos = ComandaPlato::where('plato_id', $platoId)
                ->where('llevar', $esParaLlevar)
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

    // Método para cancelar el procesamiento de un plato
    public function cancelarProcesamiento($grupoKey)
    {
        try {
            // Obtener las partes de la clave del grupo
            $keyParts = explode('-', $grupoKey);
            $platoId = $keyParts[0];
            $esParaLlevar = $keyParts[1] === 'llevar';

            // Obtener todas las comandas platos relacionadas que estén en estado 'Procesando'
            $comandaPlatos = ComandaPlato::where('plato_id', $platoId)
                ->where('llevar', $esParaLlevar)
                ->where('estado', 'Procesando')
                ->get();

            // Actualizar el estado de todos los platos relacionados a 'Pendiente'
            foreach ($comandaPlatos as $comandaPlato) {
                $comandaPlato->update(['estado' => 'Pendiente']);
            }

            // Verificar si hay otras comandas platos de la misma comanda que siguen en proceso
            foreach ($comandaPlatos as $comandaPlato) {
                $comanda = $comandaPlato->comanda;
                $hayPlatosEnProceso = $comanda->comandaPlatos()
                    ->where('estado', 'Procesando')
                    ->exists();

                // Si no hay más platos en proceso, actualizar el estado de la comanda a 'Abierta'
                if (!$hayPlatosEnProceso) {
                    $comanda->update(['estado' => 'Abierta']);
                }
            }

            // Notificar éxito
            Notification::make()
                ->title('Procesamiento cancelado')
                ->success()
                ->send();
        } catch (\Exception $e) {
            // Notificar error
            Notification::make()
                ->title('Error al cancelar el procesamiento')
                ->body('No se pudo actualizar el estado del plato')
                ->danger()
                ->send();
        }
    }

    public function getPlatosACocinarProperty()
    {
        $platosAgrupados = [];

        $comandaPlatos = ComandaPlato::query()
            ->where('estado', 'Procesando')
            ->whereHas('plato', function ($query) {
                $query->where('area_id', $this->selectedArea);
            })
            ->with('plato')
            ->get();

        // Primero agrupamos por plato_id y luego por llevar (true/false)
        $grupos = $comandaPlatos->groupBy(function ($item) {
            return $item->plato_id . '-' . ($item->llevar ? 'llevar' : 'local');
        });

        foreach ($grupos as $key => $grupo) {
            $primerItem = $grupo->first();
            $keyParts = explode('-', $key);
            $esParaLlevar = $keyParts[1] === 'llevar';

            $platosAgrupados[] = [
                'id' => $primerItem->plato->id,
                'nombre' => $primerItem->plato->nombre,
                'total' => $grupo->sum('cantidad'),
                'estado' => $primerItem->estado,
                'paraLlevar' => $esParaLlevar,
                'grupoKey' => $key // Agregamos una clave única para cada grupo
            ];
        }

        return $platosAgrupados;
    }

    public function validarProcesar($comandaId)
    {
        $comanda = Comanda::find($comandaId);

        foreach ($comanda->comandaPlatos as $comandaPlato) {
            if ($comandaPlato->estado !== 'Pendiente') {
                return false;
            }
        }

        return true;
    }
}
