<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\Mesa;
use App\Models\Zona;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class PanelMesas extends Component
{
    public $zonas;
    public $zonaSeleccionada;
    public $mesasFiltradas;
    public $estadoFiltro;
    public $busqueda;
    public $notificacion;
    public $mostrarNotificacion = false;
    public $tiempoNotificacion;

    public function mount()
    {
        $this->zonas = Zona::with(['mesas' => function ($query) {
            $query->orderBy('numero', 'asc'); // Asegura que las mesas siempre estén en el mismo orden
        }])->get();
        $this->zonaSeleccionada = null;
        $this->estadoFiltro = 'todos';
        $this->busqueda = '';
        $this->mesasFiltradas = collect();
        $this->tiempoNotificacion = now();
    }

    public function seleccionarZona($zonaId)
    {
        $this->zonaSeleccionada = $zonaId;
        $this->aplicarFiltros();
    }

    public function cambiarEstadoMesa($mesaId)
    {
        try {
            $mesa = Mesa::find($mesaId);
            $estadoAnterior = $mesa->estado;

            if ($mesa->estado === 'Libre') {
                $mesa->estado = 'Ocupada';
            } elseif ($mesa->estado === 'Ocupada') {
                $mesa->estado = 'Inhabilitada';
            } else {
                $mesa->estado = 'Libre';
            }

            $mesa->save();

            // Actualizar la colección local de mesas manteniendo el orden
            $this->zonas = $this->zonas->map(function ($zona) use ($mesaId, $mesa) {
                if ($zona->id == $this->zonaSeleccionada) {
                    $zona->mesas = $zona->mesas->map(function ($m) use ($mesaId, $mesa) {
                        if ($m->id == $mesaId) {
                            return $mesa;
                        }
                        return $m;
                    })->sortBy('numero'); // Mantener el orden por número
                }
                return $zona;
            });

            $this->aplicarFiltros();

            // Mostrar notificación
            $this->mostrarNotificacion = true;
            $this->tiempoNotificacion = now();
            $this->notificacion = [
                'tipo' => 'success',
                'mensaje' => "Mesa {$mesa->numero} cambiada de {$estadoAnterior} a {$mesa->estado}"
            ];
        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de mesa: ' . $e->getMessage());
            $this->mostrarNotificacion = true;
            $this->tiempoNotificacion = now();
            $this->notificacion = [
                'tipo' => 'error',
                'mensaje' => "Error al cambiar el estado de la mesa"
            ];
        }
    }

    public function aplicarFiltros()
    {
        if (!$this->zonaSeleccionada) {
            $this->mesasFiltradas = collect();
            return;
        }

        $zona = $this->zonas->firstWhere('id', $this->zonaSeleccionada);
        $mesas = $zona->mesas;

        // Aplicar filtro por estado
        if ($this->estadoFiltro !== 'todos') {
            $mesas = $mesas->filter(function ($mesa) {
                return $mesa->estado === $this->estadoFiltro;
            });
        }

        // Aplicar búsqueda por número
        if (!empty($this->busqueda)) {
            $mesas = $mesas->filter(function ($mesa) {
                return stripos($mesa->numero, $this->busqueda) !== false;
            });
        }

        // Mantener el orden original por número de mesa
        $this->mesasFiltradas = $mesas->sortBy('numero')->values();
    }

    public function updatedEstadoFiltro()
    {
        $this->aplicarFiltros();
    }

    public function updatedBusqueda()
    {
        $this->aplicarFiltros();
    }

    public function ocultarNotificacion()
    {
        $this->mostrarNotificacion = false;
    }

    public function render()
    {
        // Ocultar notificación después de 3 segundos
        if ($this->mostrarNotificacion && now()->diffInSeconds($this->tiempoNotificacion) > 3) {
            $this->mostrarNotificacion = false;
        }

        return view('livewire.panel-mesas');
    }
}
