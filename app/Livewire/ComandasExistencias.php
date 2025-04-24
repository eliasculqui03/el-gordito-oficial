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

    public function marcarExistenciaLista($grupoKey)
    {
        try {
            // Obtener las partes de la clave del grupo
            $keyParts = explode('-', $grupoKey);
            $existenciaId = $keyParts[0];
            $esHelado = $keyParts[1] === 'helado';

            // Obtener todas las comandas existencias relacionadas que estén en estado 'Procesando'
            $comandaExistencias = ComandaExistencia::where('existencia_id', $existenciaId)
                ->where('helado', $esHelado)
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

    public function cancelarPreparacionExistencia($grupoKey)
    {
        try {
            // Obtener las partes de la clave del grupo
            $keyParts = explode('-', $grupoKey);
            $existenciaId = $keyParts[0];
            $esHelado = $keyParts[1] === 'helado';

            // Obtener todas las comandas existencias relacionadas que estén en estado 'Procesando'
            $comandaExistencias = ComandaExistencia::where('existencia_id', $existenciaId)
                ->where('helado', $esHelado)
                ->where('estado', 'Procesando')
                ->get();

            // Actualizar el estado de todas las existencias relacionadas a 'Pendiente'
            foreach ($comandaExistencias as $comandaExistencia) {
                $comandaExistencia->update(['estado' => 'Pendiente']);
            }

            // Verificar si hay otras comandas existencias de la misma comanda que siguen en proceso
            foreach ($comandaExistencias as $comandaExistencia) {
                $comanda = $comandaExistencia->comanda;
                $hayExistenciasEnProceso = $comanda->comandaExistencias()
                    ->where('estado', 'Procesando')
                    ->exists();

                // Si no hay más existencias en proceso, actualizar el estado de la comanda a 'Abierta'
                if (!$hayExistenciasEnProceso) {
                    $comanda->update(['estado' => 'Abierta']);
                }
            }

            // Notificar éxito
            Notification::make()
                ->title('Preparación cancelada')
                ->success()
                ->send();
        } catch (\Exception $e) {
            // Notificar error
            Notification::make()
                ->title('Error al cancelar la preparación')
                ->body('No se pudo actualizar el estado de la existencia')
                ->danger()
                ->send();
        }
    }

    public function getExistenciasAProcesarProperty()
    {
        $existenciasAgrupadas = [];

        $comandaExistencias = ComandaExistencia::query()
            ->where('estado', 'Procesando')
            ->whereHas('existencia', function ($query) {
                $query->where('area_existencia_id', $this->selectedArea);
            })
            ->with('existencia.unidadMedida')
            ->get();

        // Primero agrupamos por existencia_id y luego por helado (true/false)
        $grupos = $comandaExistencias->groupBy(function ($item) {
            return $item->existencia_id . '-' . ($item->helado ? 'helado' : 'normal');
        });

        foreach ($grupos as $key => $grupo) {
            $primerItem = $grupo->first();
            $keyParts = explode('-', $key);
            $esHelado = $keyParts[1] === 'helado';

            $existenciasAgrupadas[] = [
                'id' => $primerItem->existencia->id,
                'nombre' => $primerItem->existencia->nombre,
                'unidad' => $primerItem->existencia->unidadMedida->descripcion ?? '',
                'total' => $grupo->sum('cantidad'),
                'estado' => $primerItem->estado,
                'helado' => $esHelado,
                'grupoKey' => $key // Agregamos una clave única para cada grupo
            ];
        }

        return $existenciasAgrupadas;
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
