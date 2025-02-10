<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\Zona;
use App\Models\Mesa;
use App\Models\Plato;
use App\Models\Existencia;
use App\Models\Comanda;
use App\Models\ComandaExistencia;
use App\Models\ComandaPlato;
use Livewire\Component;

class PanelComandas extends Component
{
    // Propiedades para selección
    public $cajaId;
    public $zonaId;
    public $mesaId;

    // Propiedades para listas
    public $cajas;
    public $zonas = [];
    public $mesas = [];

    // Propiedades para comanda
    public $platos;
    public $existencias;
    public $platoSeleccionado;
    public $existenciaSeleccionada;
    public $cantidadPlato = 1;
    public $cantidadExistencia = 1;

    // Carrito de comanda
    public $itemsPlatos = [];
    public $itemsExistencias = [];
    public $total = 0;


    public $isOpen = false;
    public $cajasSelec;
    public $zonasSelec;
    public $cajaActual = null;
    public $zonaActual = null;
    public $mesaSeleccionada = null;
    public $mesaSeleccionadaId = null;


    public function mount()
    {
        $this->cajas = Caja::where('estado', true)->get();
        $this->platos = Plato::where('estado', true)->get();
        $this->existencias = Existencia::where('estado', true)->get();

        $this->cajasSelec = Caja::where('estado', true)->get();
        $this->zonasSelec = collect();
    }

    // Carga las zonas cuando se selecciona una caja
    public function updatedCajaId($value)
    {
        $this->zonas = Zona::where('caja_id', $value)
            ->where('estado', true)
            ->get();
        $this->zonaId = null;
        $this->mesas = [];
        $this->mesaId = null;
    }

    // Carga las mesas cuando se selecciona una zona
    public function updatedZonaId($value)
    {
        if ($value) {
            $this->mesas = Mesa::where('zona_id', $value)
                ->whereIn('estado', ['libre'])
                ->get();
            $this->mesaId = null;
        }
    }

    // Agregar plato a la comanda
    public function agregarPlato()
    {
        $plato = Plato::find($this->platoSeleccionado);
        if (!$plato) return;

        $this->itemsPlatos[] = [
            'id' => $plato->id,
            'nombre' => $plato->nombre,
            'precio' => $plato->precio,
            'cantidad' => $this->cantidadPlato,
            'subtotal' => $plato->precio * $this->cantidadPlato
        ];

        $this->platoSeleccionado = null;
        $this->cantidadPlato = 1;
        $this->calcularTotal();
    }

    // Agregar existencia a la comanda
    public function agregarExistencia()
    {
        $existencia = Existencia::find($this->existenciaSeleccionada);
        if (!$existencia) return;

        $this->itemsExistencias[] = [
            'id' => $existencia->id,
            'nombre' => $existencia->nombre,
            'precio' => $existencia->precio_venta,
            'cantidad' => $this->cantidadExistencia,
            'subtotal' => $existencia->precio_venta * $this->cantidadExistencia
        ];

        $this->existenciaSeleccionada = null;
        $this->cantidadExistencia = 1;
        $this->calcularTotal();
    }

    // Eliminar item de plato
    public function eliminarPlato($index)
    {
        unset($this->itemsPlatos[$index]);
        $this->itemsPlatos = array_values($this->itemsPlatos);
        $this->calcularTotal();
    }

    // Eliminar item de existencia
    public function eliminarExistencia($index)
    {
        unset($this->itemsExistencias[$index]);
        $this->itemsExistencias = array_values($this->itemsExistencias);
        $this->calcularTotal();
    }

    // Calcular total
    public function calcularTotal()
    {
        $totalPlatos = collect($this->itemsPlatos)->sum('subtotal');
        $totalExistencias = collect($this->itemsExistencias)->sum('subtotal');
        $this->total = $totalPlatos + $totalExistencias;
    }

    // Guardar comanda
    public function guardarComanda()
    {
        if (!$this->mesaId || (empty($this->itemsPlatos) && empty($this->itemsExistencias))) {
            session()->flash('error', 'Selecciona una mesa y agrega al menos un item.');
            return;
        }

        try {
            // Crear la comanda
            $comanda = Comanda::create([
                'mesa_id' => $this->mesaId,
                'zona_id' => $this->zonaId,
                'estado' => 'Abierta'
            ]);

            // Guardar los platos
            foreach ($this->itemsPlatos as $item) {
                ComandaPlato::create([
                    'comanda_id' => $comanda->id,
                    'plato_id' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'subtotal' => $item['subtotal'],
                    'estado' => 'Pendiente'
                ]);
            }

            // Guardar las existencias
            foreach ($this->itemsExistencias as $item) {
                ComandaExistencia::create([
                    'comanda_id' => $comanda->id,
                    'existencia_id' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'subtotal' => $item['subtotal'],
                    'estado' => 'Pendiente'
                ]);
            }

            // Actualizar estado de la mesa
            Mesa::find($this->mesaId)->update(['estado' => 'Ocupada']);

            // Limpiar formulario
            $this->reset([
                'itemsPlatos',
                'itemsExistencias',
                'total',
                'platoSeleccionado',
                'existenciaSeleccionada',
                'cantidadPlato',
                'cantidadExistencia'
            ]);

            session()->flash('success', 'Comanda creada exitosamente.');
            $this->dispatch('commandaGuardada');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear la comanda: ' . $e->getMessage());
        }
    }



    public function closeModalMesa()
    {
        $this->isOpen = false;
    }

    public function openModalMesa()
    {
        $this->isOpen = true;
    }



    public function cambiarCaja($cajaId)
    {
        $this->cajaActual = $cajaId;
        $this->zonas = Zona::where('caja_id', $cajaId)->get();
        $this->zonaActual = null;
    }

    public function cambiarZona($zonaId)
    {
        $this->zonaActual = $zonaId;
    }

    public function seleccionarMesa(Mesa $mesa)
    {
        $this->mesaSeleccionada = $mesa->numero;
        $this->mesaSeleccionadaId = $mesa->id;
        $this->closeModalMesa();
    }

    public function render()
    {
        return view('livewire.panel-comandas');
    }
}
