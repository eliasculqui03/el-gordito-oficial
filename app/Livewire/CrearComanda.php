<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\CategoriaExistencia;
use App\Models\CategoriaPlato;
use App\Models\Cliente;
use App\Models\Existencia;
use App\Models\Mesa;
use App\Models\Plato;
use App\Models\TipoExistencia;
use App\Models\Zona;
use Filament\Notifications\Notification;
use Livewire\Component;

class CrearComanda extends Component
{

    public $categoria_plato_id = '';
    public $platos = [];
    public $tipo_existencia_id = '';
    public $categoria_existencia_id = '';
    public $existencias = [];

    public $numero_documento;
    public $nombre;

    public $itemsPlatos = [];
    public $itemsExistencias = [];
    public $total = 0;

    public function mount()
    {
        $this->cargarPlatos();
        $this->cargarExistencias();
        $this->cajas = Caja::where('estado', true)->get();
        $this->zonas = collect();
    }

    public function updatedCategoriaplatoId()
    {
        $this->cargarPlatos();
    }

    public function updatedTipoExistenciaId()
    {
        $this->categoria_existencia_id = ''; // Reset categoría al cambiar tipo
        $this->cargarExistencias();
    }

    public function updatedCategoriaExistenciaId()
    {
        $this->cargarExistencias();
    }

    private function cargarPlatos()
    {
        $this->platos = Plato::when($this->categoria_plato_id, function ($query) {
            return $query->where('categoria_plato_id', $this->categoria_plato_id);
        })->get();
    }

    private function cargarExistencias()
    {
        $this->existencias = Existencia::query()
            ->when($this->tipo_existencia_id, function ($query) {
                return $query->whereHas('categoriaExistencia', function ($q) {
                    $q->where('tipo_existencia_id', $this->tipo_existencia_id);
                });
            })
            ->when($this->categoria_existencia_id, function ($query) {
                return $query->where('categoria_existencia_id', $this->categoria_existencia_id);
            })
            ->get();
    }
    public function render()
    {
        return view('livewire.crear-comanda', [
            'categorias_platos' => CategoriaPlato::where('estado', 1)->get(),
            'platos' => $this->platos,
            'tipos_existencia' => TipoExistencia::where('estado', 1)->get(),
            'categorias_existencias' => CategoriaExistencia::when($this->tipo_existencia_id, function ($query) {
                return $query->where('tipo_existencia_id', $this->tipo_existencia_id);
            })->where('estado', 1)->get(),
            'existencias' => $this->existencias
        ]);
    }



    public function updatedNumeroDocumento($value)
    {

        $this->dispatch('numeroDocumentoActualizado', numero: $value);
    }

    public function buscar()
    {
        $cliente = Cliente::where('numero_documento', $this->numero_documento)->first();

        if ($cliente) {
            $this->nombre = $cliente->nombre;
            Notification::make()
                ->title('Cliente encontrado')
                ->success()
                ->send();
        } else {
            $this->nombre = '';
            Notification::make()
                ->title('Cliente no encontrado')
                ->danger()
                ->send();
        }
    }

    public function agregarPlato($platoId)
    {
        $plato = Plato::find($platoId);
        $this->agregarItemPlato([
            'id' => $plato->id,
            'nombre' => $plato->nombre,
            'precio' => $plato->precio,
            'cantidad' => 1,
            'subtotal' => $plato->precio
        ]);
    }

    public function agregarExistencia($existenciaId)
    {
        $existencia = Existencia::find($existenciaId);
        $this->agregarItemExistencia([
            'id' => $existencia->id,
            'nombre' => $existencia->nombre,
            'precio' => $existencia->precio_venta,
            'cantidad' => 1,
            'subtotal' => $existencia->precio
        ]);
    }

    private function agregarItemPlato($item)
    {
        $index = $this->buscarItem($item['id'], $this->itemsPlatos);
        if ($index !== false) {
            $this->itemsPlatos[$index]['cantidad']++;
            $this->itemsPlatos[$index]['subtotal'] = $this->itemsPlatos[$index]['cantidad'] * $this->itemsPlatos[$index]['precio'];
        } else {
            $this->itemsPlatos[] = $item;
        }
        $this->calcularTotal();
    }

    private function agregarItemExistencia($item)
    {
        $index = $this->buscarItem($item['id'], $this->itemsExistencias);
        if ($index !== false) {
            $this->itemsExistencias[$index]['cantidad']++;
            $this->itemsExistencias[$index]['subtotal'] = $this->itemsExistencias[$index]['cantidad'] * $this->itemsExistencias[$index]['precio'];
        } else {
            $this->itemsExistencias[] = $item;
        }
        $this->calcularTotal();
    }


    private function buscarItem($id, $items)
    {
        return array_search($id, array_column($items, 'id'));
    }

    public function eliminarPlato($index)
    {
        unset($this->itemsPlatos[$index]);
        $this->itemsPlatos = array_values($this->itemsPlatos);
        $this->calcularTotal();
    }

    public function eliminarExistencia($index)
    {
        unset($this->itemsExistencias[$index]);
        $this->itemsExistencias = array_values($this->itemsExistencias);
        $this->calcularTotal();
    }

    public function updatedItemsPlatos()
    {
        $this->actualizarSubtotales('itemsPlatos');
        $this->calcularTotal();
    }

    public function updatedItemsExistencias()
    {
        $this->actualizarSubtotales('itemsExistencias');
        $this->calcularTotal();
    }

    private function actualizarSubtotales($tipo)
    {
        foreach ($this->{$tipo} as $index => $item) {
            $this->{$tipo}[$index]['subtotal'] = $item['precio'] * $item['cantidad'];
        }
    }

    private function calcularTotal()
    {
        $totalPlatos = collect($this->itemsPlatos)->sum('subtotal');
        $totalExistencias = collect($this->itemsExistencias)->sum('subtotal');
        $this->total = $totalPlatos + $totalExistencias;
    }

    public function limpiarComanda()
    {
        $this->itemsPlatos = [];
        $this->itemsExistencias = [];
        $this->total = 0;
    }


    public $isOpen = false;
    public $cajas;
    public $zonas;
    public $cajaActual = null;
    public $zonaActual = null;
    public $mesaSeleccionada = null;
    public $mesaSeleccionadaId = null;

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
}
