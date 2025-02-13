<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\CategoriaExistencia;
use App\Models\CategoriaPlato;
use App\Models\Cliente;
use App\Models\Comanda;
use App\Models\ComandaExistencia;
use App\Models\ComandaPlato;
use App\Models\Existencia;
use App\Models\Mesa;
use App\Models\Plato;
use App\Models\TipoExistencia;
use App\Models\Zona;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
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
    public $id_cliente;
    public $itemsPlatos = [];
    public $itemsExistencias = [];
    public $total = 0;

    public function mount()
    {
        $this->cargarPlatos();
        $this->cargarExistencias();
        $this->cajas = Caja::where('estado', true)->get();
        $this->zonas = collect();
        $primerTipo = TipoExistencia::where('estado', 1)->first();
        if ($primerTipo) {
            $this->tipo_existencia_id = $primerTipo->id;
        }
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

    public function getListaMesasProperty()
    {
        if ($this->zonaActual) {
            return Mesa::where('zona_id', $this->zonaActual)
                ->orderBy('numero')
                ->get();
        }
        return collect();
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
            $this->id_cliente = $cliente->id;
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
            'subtotal' => $existencia->precio_venta
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
    public $zonaSeleccionadaId = null;

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
        // Validar que la caja exista y esté activa
        $caja = Caja::where('id', $cajaId)
            ->where('estado', true)
            ->first();

        if (!$caja) {
            Notification::make()
                ->title('Error')
                ->body('La caja seleccionada no está disponible')
                ->danger()
                ->send();
            return;
        }

        $this->cajaActual = $cajaId;
        $this->zonas = Zona::where('caja_id', $cajaId)
            ->where('estado', true)
            ->get();
        $this->zonaActual = null;
        $this->zonaSeleccionadaId = null;
        $this->mesaSeleccionada = null;
        $this->mesaSeleccionadaId = null;
    }

    public function cambiarZona($zonaId)
    {
        // Validar que la zona pertenezca a la caja actual y esté activa
        $zona = Zona::where('id', $zonaId)
            ->where('caja_id', $this->cajaActual)
            ->where('estado', true)
            ->first();

        if (!$zona) {
            Notification::make()
                ->title('Error')
                ->body('La zona seleccionada no está disponible')
                ->danger()
                ->send();
            return;
        }

        $this->zonaActual = $zonaId;
        $this->zonaSeleccionadaId = $zonaId;
        $this->mesaSeleccionada = null;
        $this->mesaSeleccionadaId = null;
    }

    public function seleccionarMesa(Mesa $mesa)
    {
        // Validar que la mesa esté disponible
        if ($mesa->estado !== 'Libre') {
            Notification::make()
                ->title('Mesa no disponible')
                ->body('Esta mesa está ocupada o inhabilitada')
                ->danger()
                ->send();
            return;
        }

        // Validar que la mesa pertenezca a la zona actual
        if ($mesa->zona_id !== $this->zonaActual) {
            Notification::make()
                ->title('Error')
                ->body('La mesa no pertenece a la zona seleccionada')
                ->danger()
                ->send();
            return;
        }

        $this->mesaSeleccionada = $mesa->numero;
        $this->mesaSeleccionadaId = $mesa->id;
        $this->zonaSeleccionadaId = $mesa->zona_id;

        $this->closeModalMesa();

        Notification::make()
            ->title('Mesa seleccionada')
            ->body("Mesa {$mesa->numero} seleccionada correctamente")
            ->success()
            ->send();
    }



    public function incrementarPlato($index)
    {
        $this->itemsPlatos[$index]['cantidad']++;
        $this->actualizarSubtotalPlato($index);
        $this->calcularTotal();
    }

    public function decrementarPlato($index)
    {
        if ($this->itemsPlatos[$index]['cantidad'] > 1) {
            $this->itemsPlatos[$index]['cantidad']--;
            $this->actualizarSubtotalPlato($index);
            $this->calcularTotal();
        }
    }

    public function incrementarExistencia($index)
    {
        $this->itemsExistencias[$index]['cantidad']++;
        $this->actualizarSubtotalExistencia($index);
        $this->calcularTotal();
    }

    public function decrementarExistencia($index)
    {
        if ($this->itemsExistencias[$index]['cantidad'] > 1) {
            $this->itemsExistencias[$index]['cantidad']--;
            $this->actualizarSubtotalExistencia($index);
            $this->calcularTotal();
        }
    }

    private function actualizarSubtotalPlato($index)
    {
        $this->itemsPlatos[$index]['subtotal'] =
            $this->itemsPlatos[$index]['cantidad'] * $this->itemsPlatos[$index]['precio'];
    }

    private function actualizarSubtotalExistencia($index)
    {
        $this->itemsExistencias[$index]['subtotal'] =
            $this->itemsExistencias[$index]['cantidad'] * $this->itemsExistencias[$index]['precio'];
    }



    public function validarComanda()
    {

        $mesa = Mesa::find($this->mesaSeleccionadaId);

        if (!$mesa) {
            Notification::make()
                ->title('Error')
                ->body('La mesa seleccionada no existe')
                ->danger()
                ->send();
            return false;
        }

        if ($mesa->estado !== 'Libre') {
            Notification::make()
                ->title('Mesa Ocupada')
                ->body('La mesa ' . $mesa->numero . ' no está disponible')
                ->warning()
                ->send();
            return false;
        }

        if (empty($this->id_cliente)) {
            Notification::make()
                ->title('Campo requerido')
                ->body('Debe seleccionar un cliente')
                ->danger()
                ->send();
            return false;
        }

        if (empty($this->cajaActual)) {
            Notification::make()
                ->title('Campo requerido')
                ->body('Debe seleccionar una caja')
                ->danger()
                ->send();
            return false;
        }

        if (empty($this->zonaSeleccionadaId)) {
            Notification::make()
                ->title('Campo requerido')
                ->body('Debe seleccionar una zona')
                ->danger()
                ->send();
            return false;
        }

        if (empty($this->mesaSeleccionadaId)) {
            Notification::make()
                ->title('Campo requerido')
                ->body('Debe seleccionar una mesa')
                ->danger()
                ->send();
            return false;
        }

        if (count($this->itemsPlatos) == 0 && count($this->itemsExistencias) == 0) {
            Notification::make()
                ->title('Campo requerido')
                ->body('Debe agregar al menos un producto')
                ->danger()
                ->send();
            return false;
        }

        return true;
    }

    public function guardarComanda()
    {
        if (!$this->validarComanda()) {
            return;
        }

        try {
            DB::beginTransaction();

            // Crear la comanda
            $comanda = Comanda::create([
                'cliente_id' => $this->id_cliente,
                'zona_id' => $this->zonaSeleccionadaId,
                'mesa_id' => $this->mesaSeleccionadaId,
                'caja_id' => $this->cajaActual,
                'total' => $this->total,
                'estado' => true,
            ]);

            // Guardar los platos
            foreach ($this->itemsPlatos as $item) {
                ComandaPlato::create([
                    'comanda_id' => $comanda->id,
                    'plato_id' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio' => $item['precio'],
                    'subtotal' => $item['subtotal'],
                    'estado' => true,
                ]);
            }

            // Guardar las existencias
            foreach ($this->itemsExistencias as $item) {
                ComandaExistencia::create([
                    'comanda_id' => $comanda->id,
                    'existencia_id' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio' => $item['precio'],
                    'subtotal' => $item['subtotal'],
                    'estado' => true,
                ]);
            }

            // Actualizar estado de la mesa
            Mesa::where('id', $this->mesaSeleccionadaId)
                ->update(['estado' => 'Ocupada']);

            DB::commit();

            // Limpiar el formulario
            $this->limpiarComandaTotal();

            Notification::make()
                ->title('Éxito')
                ->body('Comanda guardada correctamente')
                ->success()
                ->send();
        } catch (\Exception $e) {
            DB::rollback();

            Notification::make()
                ->title('Error')
                ->body('Error al guardar la comanda: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function limpiarComandaTotal()
    {
        $this->itemsPlatos = [];
        $this->itemsExistencias = [];
        $this->total = 0;
        $this->id_cliente = '';
        $this->nombre = '';
        $this->numero_documento = '';
        // No limpiamos la mesa seleccionada para mantener el contexto
    }
}
