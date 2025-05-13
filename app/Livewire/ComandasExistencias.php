<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\AreaExistencia;
use App\Models\Comanda;
use App\Models\ComandaExistencia;
use App\Models\Existencia;
use App\Models\Inventario;
use App\Models\SalidaAlmacen;
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
            DB::beginTransaction();

            // Obtener las partes de la clave del grupo
            $keyParts = explode('-', $grupoKey);
            $existenciaId = $keyParts[0];
            $esHelado = $keyParts[1] === 'helado';

            // Obtener la existencia
            $existencia = Existencia::findOrFail($existenciaId);

            // Obtener todas las comandas existencias relacionadas que estén en estado 'Procesando'
            $comandaExistencias = ComandaExistencia::where('existencia_id', $existenciaId)
                ->where('helado', $esHelado)
                ->where('estado', 'Procesando')
                ->get();

            // Actualizar el estado de todas las existencias relacionadas a 'Listo'
            foreach ($comandaExistencias as $comandaExistencia) {
                $comandaExistencia->update(['estado' => 'Listo']);

                // Obtener el inventario correspondiente a esta existencia
                $inventario = Inventario::where('existencia_id', $existenciaId)
                    ->where('almacen_id', $this->getAlmacenIdForExistencia($existenciaId))
                    ->first();

                if ($inventario) {
                    // Registrar la salida de almacén
                    SalidaAlmacen::create([
                        'user_id' => Auth::id(),
                        'existencia_id' => $existenciaId,
                        'almacen_id' => $inventario->almacen_id,
                        'cantidad' => $comandaExistencia->cantidad,
                        'motivo' => 'Preparación de comanda #' . $comandaExistencia->comanda_id
                    ]);
                }
            }

            DB::commit();

            // Notificar éxito
            Notification::make()
                ->title('Existencia marcada como lista')
                ->success()
                ->send();
        } catch (\Exception $e) {
            DB::rollBack();

            // Notificar error
            Notification::make()
                ->title('Error al marcar la existencia')
                ->body('No se pudo actualizar el estado de la existencia: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    // Método auxiliar para obtener el almacén asociado a una existencia
    protected function getAlmacenIdForExistencia($existenciaId)
    {
        // Obtener el inventario correspondiente a esta existencia
        $inventario = Inventario::where('existencia_id', $existenciaId)->first();

        return $inventario ? $inventario->almacen_id : null;
    }


    public $mostrarConfirmacion = false;
    public $grupoKeyAConfirmar = null;



    public function confirmarCancelacion($grupoKey)
    {
        $this->grupoKeyAConfirmar = $grupoKey;
        $this->mostrarConfirmacion = true;
    }

    public function cerrarConfirmacion()
    {
        $this->mostrarConfirmacion = false;
        $this->grupoKeyAConfirmar = null;
    }

    public function procederCancelacion()
    {
        if ($this->grupoKeyAConfirmar) {
            $this->cancelarPreparacionExistencia($this->grupoKeyAConfirmar);
            $this->mostrarConfirmacion = false;
            $this->grupoKeyAConfirmar = null;
        }
    }

    // Modificar el método actual para que no se ejecute directamente sino a través del modal
    public function cancelarPreparacionExistencia($grupoKey)
    {
        try {
            DB::beginTransaction();

            // Obtener las partes de la clave del grupo
            $keyParts = explode('-', $grupoKey);
            $existenciaId = $keyParts[0];
            $esHelado = $keyParts[1] === 'helado';

            // Obtener todas las comandas existencias relacionadas que estén en estado 'Procesando'
            $comandaExistencias = ComandaExistencia::where('existencia_id', $existenciaId)
                ->where('helado', $esHelado)
                ->where('estado', 'Procesando')
                ->get();

            // Actualizar el estado de todas las existencias relacionadas a 'Cancelado'
            foreach ($comandaExistencias as $comandaExistencia) {
                $comandaExistencia->update(['estado' => 'Cancelado']);
            }

            // Verificar si hay otras comandas existencias de la misma comanda que siguen en proceso
            foreach ($comandaExistencias as $comandaExistencia) {
                $comanda = $comandaExistencia->comanda;

                // Verificar si hay existencias en proceso
                $hayExistenciasEnProceso = $comanda->comandaExistencias()
                    ->where('estado', 'Procesando')
                    ->exists();

                // Verificar si hay comandaPlato en proceso (si la relación existe)
                $hayComandaPlatoEnProceso = false;
                if (method_exists($comanda, 'comandaPlatos')) {
                    $hayComandaPlatoEnProceso = $comanda->comandaPlatos()
                        ->where('estado', 'Procesando')
                        ->exists();
                }

                // Si no hay más existencias o platos en proceso, actualizar el estado de la comanda a 'Completada'
                if (!$hayExistenciasEnProceso && !$hayComandaPlatoEnProceso) {
                    // Verificamos adicionalmente si todas las existencias están en estado Cancelado o Listo
                    $todasFinalizadas = !$comanda->comandaExistencias()
                        ->whereNotIn('estado', ['Cancelado', 'Listo'])
                        ->exists();

                    // Verificamos también si todas las comandaPlatos están finalizadas (si aplica)
                    $todosPlatosFinalizados = true;
                    if (method_exists($comanda, 'comandaPlatos')) {
                        $todosPlatosFinalizados = !$comanda->comandaPlatos()
                            ->whereNotIn('estado', ['Cancelado', 'Listo'])
                            ->exists();
                    }

                    // Solo marcamos como completada si todas las existencias y platos están finalizados
                    if ($todasFinalizadas && $todosPlatosFinalizados) {
                        $comanda->update(['estado' => 'Completada']);
                    }
                }
            }

            DB::commit();

            // Notificar éxito
            Notification::make()
                ->title('Plato cancelado')
                ->success()
                ->send();
        } catch (\Exception $e) {
            DB::rollBack();

            // Notificar error
            Notification::make()
                ->title('Error al cancelar la preparación')
                ->body('No se pudo actualizar el estado de la existencia: ' . $e->getMessage())
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
