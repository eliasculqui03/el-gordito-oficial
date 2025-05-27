<?php

namespace App\Livewire\Comanda;

use App\Models\Comanda;
use App\Models\ComandaExistencia;
use App\Models\ComandaPlato;
use App\Models\Cliente;
use App\Models\Zona;
use App\Models\Mesa;
use App\Models\Existencia;
use App\Models\Plato;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class EditarComanda extends Component
{
    public $comandaId;
    public $comanda;

    // Datos básicos de la comanda
    public $cliente_id;
    public $zona_id;
    public $mesa_id;
    public $estado;
    public $estado_pago;

    // Items de la comanda
    public $existencias = [];
    public $platos = [];
    public $platosOriginales = []; // Para controlar cambios en disponibilidad

    // Para agregar nuevos items
    public $showAgregarProducto = false;
    public $showAgregarPlato = false;
    public $nuevoProductoId = '';
    public $nuevoProductoCantidad = 1;
    public $nuevoProductoHelado = false;
    public $nuevoPlatoId = '';
    public $nuevoPlatoCantidad = 1;
    public $nuevoPlatoLlevar = false;
    public $nuevoPlatoPrecio = 0; // Para mostrar precio en tiempo real

    // Para búsquedas en selects
    public $searchCliente = '';
    public $searchProducto = '';
    public $searchPlato = '';
    public $showClientesList = false;
    public $showProductosList = false;
    public $showPlatosList = false;

    // Totales
    public $subtotal = 0;
    public $igv = 0;
    public $total_general = 0;

    // Listas para selects
    public $clientes = [];
    public $zonas = [];
    public $mesas = [];
    public $productosDisponibles = [];
    public $platosDisponibles = [];

    protected $rules = [
        'cliente_id' => 'nullable|exists:clientes,id',
        'zona_id' => 'required|exists:zonas,id',
        'mesa_id' => 'required|exists:mesas,id',
        'estado' => 'required|in:Abierta,Procesando,Completada,Cancelada',
        'estado_pago' => 'required|in:Pendiente,Pagada',
        'existencias.*.cantidad' => 'required|integer|min:1',
        'existencias.*.precio_unitario' => 'required|numeric|min:0',
        'platos.*.cantidad' => 'required|integer|min:1',
        'platos.*.precio_unitario' => 'required|numeric|min:0',
    ];

    public function mount($comandaId)
    {
        $this->comandaId = $comandaId;
        $this->loadComanda();
        $this->loadSelectOptions();

        // Inicializar búsqueda de cliente si existe
        if ($this->cliente_id) {
            $cliente = Cliente::find($this->cliente_id);
            $this->searchCliente = $cliente ? $cliente->nombre : '';
        }

        // Inicializar precio de nuevo plato
        $this->nuevoPlatoPrecio = 0;
    }

    public function loadComanda()
    {
        $this->comanda = Comanda::with([
            'cliente',
            'zona',
            'mesa',
            'comandaExistencias.existencia.inventario.almacen',
            'comandaPlatos.plato.disponibilidadPlato'
        ])->findOrFail($this->comandaId);

        // Cargar datos básicos
        $this->cliente_id = $this->comanda->cliente_id;
        $this->zona_id = $this->comanda->zona_id;
        $this->mesa_id = $this->comanda->mesa_id;
        $this->estado = $this->comanda->estado;
        $this->estado_pago = $this->comanda->estado_pago;

        // Cargar existencias
        $this->existencias = $this->comanda->comandaExistencias->map(function ($item) {
            $almacenInfo = '';
            // Obtener información del almacén desde el inventario
            if ($item->existencia->inventario && $item->existencia->inventario->almacen) {
                $almacenInfo = ' (' . $item->existencia->inventario->almacen->nombre . ')';
            }

            return [
                'id' => $item->id,
                'existencia_id' => $item->existencia_id,
                'nombre' => $item->existencia->nombre . $almacenInfo,
                'precio_unitario' => $item->precio_unitario,
                'cantidad' => $item->cantidad,
                'subtotal' => $item->subtotal,
                'helado' => $item->helado,
                'estado' => $item->estado,
                'almacen_nombre' => $item->existencia->inventario && $item->existencia->inventario->almacen ? $item->existencia->inventario->almacen->nombre : 'Sin almacén',
            ];
        })->toArray();

        // Cargar platos
        $this->platos = $this->comanda->comandaPlatos->map(function ($item) {
            return [
                'id' => $item->id,
                'plato_id' => $item->plato_id,
                'nombre' => $item->plato->nombre,
                'precio_unitario' => $item->precio_unitario,
                'cantidad' => $item->cantidad,
                'subtotal' => $item->subtotal,
                'llevar' => $item->llevar,
                'estado' => $item->estado,
            ];
        })->toArray();

        // Guardar cantidades originales para control de disponibilidad
        $this->platosOriginales = collect($this->platos)->mapWithKeys(function ($plato) {
            return [$plato['id'] => $plato['cantidad']];
        })->toArray();

        $this->calcularTotales();
    }

    public function loadSelectOptions()
    {
        $this->clientes = Cliente::orderBy('nombre')->get();
        $this->zonas = Zona::orderBy('nombre')->get();
        $this->mesas = Mesa::where('zona_id', $this->zona_id)->with('zona')->orderBy('numero')->get();

        // Cargar productos con stock disponible desde inventario
        $this->productosDisponibles = Existencia::whereHas('inventario', function ($query) {
            $query->where('stock', '>', 0);
        })->with('inventario.almacen')->orderBy('nombre')->get();

        // Cargar platos disponibles desde disponibilidad_platos
        $this->platosDisponibles = Plato::whereHas('disponibilidadPlato', function ($query) {
            $query->where('disponibilidad', true)
                ->where('cantidad', '>', 0);
        })->with('disponibilidadPlato')->orderBy('nombre')->get();
    }

    public function updatedZonaId()
    {
        $this->mesas = Mesa::where('zona_id', $this->zona_id)->with('zona')->orderBy('numero')->get();
        $this->mesa_id = '';
    }

    public function calcularTotales()
    {
        // Calcular total sumando todos los subtotales (precios ya incluyen IGV)
        $totalConIgv = 0;

        // Sumar subtotales de existencias
        foreach ($this->existencias as $existencia) {
            $totalConIgv += $existencia['subtotal'];
        }

        // Sumar subtotales de platos
        foreach ($this->platos as $plato) {
            $totalConIgv += $plato['subtotal'];
        }

        // Calcular subtotal sin IGV (dividir entre 1.1 porque los precios incluyen 10% de IGV)
        $this->subtotal = round($totalConIgv / 1.1, 2);

        // Calcular IGV (10% del subtotal)
        $this->igv = round($this->subtotal * 0.10, 2);

        // Calcular total general (subtotal + IGV)
        $this->total_general = round($this->subtotal + $this->igv, 2);
    }

    public function actualizarSubtotalExistencia($index)
    {
        if (isset($this->existencias[$index])) {
            $cantidad = $this->existencias[$index]['cantidad'];
            $precio = $this->existencias[$index]['precio_unitario'];

            // Validar stock disponible si es un item existente
            if (isset($this->existencias[$index]['id']) && $this->existencias[$index]['id']) {
                $comandaExistencia = ComandaExistencia::with('existencia.inventario')->find($this->existencias[$index]['id']);
                $stockDisponible = $comandaExistencia->existencia->inventario ? $comandaExistencia->existencia->inventario->stock : 0;

                if ($cantidad > $stockDisponible) {
                    $this->dispatch('notify', [
                        'message' => 'Stock insuficiente. Stock disponible: ' . $stockDisponible,
                        'type' => 'error'
                    ]);
                    $this->existencias[$index]['cantidad'] = $stockDisponible;
                    $cantidad = $stockDisponible;
                }
            }

            $this->existencias[$index]['subtotal'] = $cantidad * $precio;
            $this->calcularTotales();
        }
    }

    public function actualizarSubtotalPlato($index)
    {
        if (isset($this->platos[$index])) {
            $cantidad = $this->platos[$index]['cantidad'];
            $precio = $this->platos[$index]['precio_unitario'];

            // Validar disponibilidad si es un item existente
            if (isset($this->platos[$index]['id']) && $this->platos[$index]['id']) {
                $comandaPlato = ComandaPlato::with('plato.disponibilidadPlato')->find($this->platos[$index]['id']);
                $cantidadDisponible = $comandaPlato->plato->disponibilidadPlato->cantidad ?? 0;

                if ($cantidad > $cantidadDisponible) {
                    $this->dispatch('notify', [
                        'message' => 'Cantidad no disponible. Cantidad disponible: ' . $cantidadDisponible,
                        'type' => 'error'
                    ]);
                    $this->platos[$index]['cantidad'] = $cantidadDisponible;
                    $cantidad = $cantidadDisponible;
                }
            }

            $this->platos[$index]['subtotal'] = $cantidad * $precio;
            $this->calcularTotales();
        }
    }

    // Método para cambiar el estado para llevar y actualizar precio
    public function toggleLlevarPlato($index)
    {
        if (isset($this->platos[$index])) {
            $platoId = $this->platos[$index]['plato_id'];
            $nuevoEsLlevar = !$this->platos[$index]['llevar'];

            // Obtener el plato para conocer los precios
            $plato = Plato::find($platoId);
            if ($plato) {
                // Determinar el nuevo precio según si es para llevar o no
                $nuevoPrecio = $nuevoEsLlevar && $plato->precio_llevar > 0 ? $plato->precio_llevar : $plato->precio;

                // Actualizar estado y precio
                $this->platos[$index]['llevar'] = $nuevoEsLlevar;
                $this->platos[$index]['precio_unitario'] = $nuevoPrecio;

                // Recalcular subtotal con el nuevo precio
                $this->actualizarSubtotalPlato($index);

                $tipoServicio = $nuevoEsLlevar ? 'para llevar' : 'para mesa';
                $this->dispatch('notify', [
                    'message' => "Plato actualizado a {$tipoServicio}. Precio: S/ " . number_format($nuevoPrecio, 2),
                    'type' => 'success'
                ]);
            }
        }
    }

    public function eliminarExistencia($index)
    {
        if (isset($this->existencias[$index])) {
            // Si tiene ID, marcar para eliminar de la BD
            if (isset($this->existencias[$index]['id'])) {
                ComandaExistencia::find($this->existencias[$index]['id'])->delete();
            }
            unset($this->existencias[$index]);
            $this->existencias = array_values($this->existencias);
            $this->calcularTotales();
        }
    }

    public function eliminarPlato($index)
    {
        if (isset($this->platos[$index])) {
            // Si tiene ID, marcar para eliminar de la BD
            if (isset($this->platos[$index]['id'])) {
                ComandaPlato::find($this->platos[$index]['id'])->delete();
            }
            unset($this->platos[$index]);
            $this->platos = array_values($this->platos);
            $this->calcularTotales();
        }
    }

    public function agregarProducto()
    {
        $this->validate([
            'nuevoProductoId' => 'required|exists:existencias,id',
            'nuevoProductoCantidad' => 'required|integer|min:1',
        ]);

        // Verificar stock disponible
        $existencia = Existencia::with('inventario.almacen')->find($this->nuevoProductoId);

        if (!$existencia->inventario || $existencia->inventario->stock < $this->nuevoProductoCantidad) {
            $stockDisponible = $existencia->inventario ? $existencia->inventario->stock : 0;
            $this->dispatch('notify', [
                'message' => 'Stock insuficiente. Stock disponible: ' . $stockDisponible,
                'type' => 'error'
            ]);
            return;
        }

        $almacenNombre = $existencia->inventario->almacen ? $existencia->inventario->almacen->nombre : 'Sin almacén';

        $this->existencias[] = [
            'id' => null, // Nuevo item
            'existencia_id' => $existencia->id,
            'nombre' => $existencia->nombre,
            'precio_unitario' => $existencia->precio_venta,
            'cantidad' => $this->nuevoProductoCantidad,
            'subtotal' => $existencia->precio_venta * $this->nuevoProductoCantidad,
            'helado' => $this->nuevoProductoHelado,
            'estado' => 'Pendiente',
            'almacen_nombre' => $almacenNombre,
        ];

        $this->calcularTotales();
        $this->resetNuevoProducto();
    }

    public function agregarPlato()
    {
        $this->validate([
            'nuevoPlatoId' => 'required|exists:platos,id',
            'nuevoPlatoCantidad' => 'required|integer|min:1',
        ]);

        // Verificar disponibilidad del plato
        $disponibilidad = \App\Models\DisponibilidadPlato::where('plato_id', $this->nuevoPlatoId)->first();

        if (!$disponibilidad || !$disponibilidad->disponibilidad || $disponibilidad->cantidad < $this->nuevoPlatoCantidad) {
            $stockDisponible = $disponibilidad ? $disponibilidad->cantidad : 0;
            $this->dispatch('notify', [
                'message' => 'Plato no disponible o cantidad insuficiente. Cantidad disponible: ' . $stockDisponible,
                'type' => 'error'
            ]);
            return;
        }

        $plato = Plato::find($this->nuevoPlatoId);

        // Usar el precio correcto según el tipo de servicio
        $precioUnitario = $this->nuevoPlatoLlevar && $plato->precio_llevar > 0
            ? $plato->precio_llevar
            : $plato->precio;

        $this->platos[] = [
            'id' => null, // Nuevo item
            'plato_id' => $plato->id,
            'nombre' => $plato->nombre,
            'precio_unitario' => $precioUnitario,
            'cantidad' => $this->nuevoPlatoCantidad,
            'subtotal' => $precioUnitario * $this->nuevoPlatoCantidad,
            'llevar' => $this->nuevoPlatoLlevar,
            'estado' => 'Pendiente',
        ];

        $this->calcularTotales();
        $this->resetNuevoPlato();
    }

    public function resetNuevoProducto()
    {
        $this->nuevoProductoId = '';
        $this->nuevoProductoCantidad = 1;
        $this->nuevoProductoHelado = false;
        $this->showAgregarProducto = false;
    }

    public function resetNuevoPlato()
    {
        $this->nuevoPlatoId = '';
        $this->nuevoPlatoCantidad = 1;
        $this->nuevoPlatoLlevar = false;
        $this->nuevoPlatoPrecio = 0;
        $this->showAgregarPlato = false;
    }

    // Métodos para búsquedas en selects
    public function updatedSearchCliente()
    {
        $this->showClientesList = !empty($this->searchCliente);
    }

    public function updatedSearchProducto()
    {
        $this->showProductosList = !empty($this->searchProducto);
    }

    public function updatedSearchPlato()
    {
        $this->showPlatosList = !empty($this->searchPlato);
    }

    public function seleccionarCliente($clienteId, $nombre)
    {
        $this->cliente_id = $clienteId;
        $this->searchCliente = $nombre;
        $this->showClientesList = false;
    }

    public function seleccionarProducto($productoId, $nombre)
    {
        $this->nuevoProductoId = $productoId;
        $this->searchProducto = $nombre;
        $this->showProductosList = false;
    }

    public function seleccionarPlato($platoId, $nombre)
    {
        $this->nuevoPlatoId = $platoId;
        $this->searchPlato = $nombre;
        $this->showPlatosList = false;
    }

    public function limpiarCliente()
    {
        $this->cliente_id = '';
        $this->searchCliente = '';
        $this->showClientesList = false;
    }

    public function limpiarProducto()
    {
        $this->nuevoProductoId = '';
        $this->searchProducto = '';
        $this->showProductosList = false;
    }

    public function limpiarPlato()
    {
        $this->nuevoPlatoId = '';
        $this->searchPlato = '';
        $this->showPlatosList = false;
    }

    // Filtrar listas según búsqueda
    public function getClientesFiltradosProperty()
    {
        if (empty($this->searchCliente)) {
            return collect();
        }

        return collect($this->clientes)->filter(function ($cliente) {
            return stripos($cliente->nombre, $this->searchCliente) !== false ||
                stripos($cliente->numero_documento, $this->searchCliente) !== false;
        })->take(10);
    }

    public function getProductosFiltradosProperty()
    {
        if (empty($this->searchProducto)) {
            return collect();
        }

        return collect($this->productosDisponibles)->filter(function ($producto) {
            return stripos($producto->nombre, $this->searchProducto) !== false;
        })->take(10);
    }

    public function getPlatosFiltradosProperty()
    {
        if (empty($this->searchPlato)) {
            return collect();
        }

        return collect($this->platosDisponibles)->filter(function ($plato) {
            return stripos($plato->nombre, $this->searchPlato) !== false;
        })->take(10);
    }

    // Métodos para incrementar/decrementar cantidades
    public function incrementarCantidadExistencia($index)
    {
        if (isset($this->existencias[$index])) {
            $this->existencias[$index]['cantidad']++;
            $this->actualizarSubtotalExistencia($index);
        }
    }

    public function decrementarCantidadExistencia($index)
    {
        if (isset($this->existencias[$index]) && $this->existencias[$index]['cantidad'] > 1) {
            $this->existencias[$index]['cantidad']--;
            $this->actualizarSubtotalExistencia($index);
        }
    }

    public function incrementarCantidadPlato($index)
    {
        if (isset($this->platos[$index])) {
            // Verificar disponibilidad antes de incrementar
            $disponibilidad = \App\Models\DisponibilidadPlato::where('plato_id', $this->platos[$index]['plato_id'])->first();
            $cantidadDisponible = $disponibilidad ? $disponibilidad->cantidad : 0;

            if ($this->platos[$index]['cantidad'] < $cantidadDisponible) {
                $this->platos[$index]['cantidad']++;
                $this->actualizarSubtotalPlato($index);
            } else {
                $this->dispatch('notify', [
                    'message' => 'No hay más stock disponible para este plato',
                    'type' => 'error'
                ]);
            }
        }
    }

    public function decrementarCantidadPlato($index)
    {
        if (isset($this->platos[$index]) && $this->platos[$index]['cantidad'] > 1) {
            $this->platos[$index]['cantidad']--;
            $this->actualizarSubtotalPlato($index);
        }
    }

    // Actualizar disponibilidad de platos
    private function actualizarDisponibilidadPlatos()
    {
        foreach ($this->platos as $plato) {
            if (isset($plato['id']) && $plato['id']) {
                // Es un plato existente, verificar cambios
                $cantidadOriginal = $this->platosOriginales[$plato['id']] ?? 0;
                $cantidadActual = $plato['cantidad'];
                $diferencia = $cantidadOriginal - $cantidadActual;

                if ($diferencia != 0) {
                    // Actualizar disponibilidad
                    $disponibilidad = \App\Models\DisponibilidadPlato::where('plato_id', $plato['plato_id'])->first();
                    if ($disponibilidad) {
                        $disponibilidad->cantidad += $diferencia;
                        $disponibilidad->save();
                    }
                }
            } else {
                // Es un plato nuevo, reducir disponibilidad
                $disponibilidad = \App\Models\DisponibilidadPlato::where('plato_id', $plato['plato_id'])->first();
                if ($disponibilidad) {
                    $disponibilidad->cantidad -= $plato['cantidad'];
                    $disponibilidad->save();
                }
            }
        }

        // Verificar platos eliminados (aumentar disponibilidad)
        $platosActualesIds = collect($this->platos)->pluck('id')->filter()->toArray();
        $platosOriginalesData = ComandaPlato::where('comanda_id', $this->comandaId)->get();

        foreach ($platosOriginalesData as $platoOriginal) {
            if (!in_array($platoOriginal->id, $platosActualesIds)) {
                // Este plato fue eliminado, aumentar disponibilidad
                $disponibilidad = \App\Models\DisponibilidadPlato::where('plato_id', $platoOriginal->plato_id)->first();
                if ($disponibilidad) {
                    $disponibilidad->cantidad += $platoOriginal->cantidad;
                    $disponibilidad->save();
                }
            }
        }
    }

    public function guardarComanda()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Actualizar disponibilidad de platos antes de guardar
            $this->actualizarDisponibilidadPlatos();

            // Actualizar datos básicos de la comanda
            $this->comanda->update([
                'cliente_id' => $this->cliente_id,
                'zona_id' => $this->zona_id,
                'mesa_id' => $this->mesa_id,
                'estado' => $this->estado,
                'estado_pago' => $this->estado_pago,
                'subtotal' => $this->subtotal,
                'igv' => $this->igv,
                'total_general' => $this->total_general,
            ]);

            // Actualizar/crear existencias
            foreach ($this->existencias as $existencia) {
                if ($existencia['id']) {
                    // Actualizar existente
                    ComandaExistencia::where('id', $existencia['id'])->update([
                        'precio_unitario' => $existencia['precio_unitario'],
                        'cantidad' => $existencia['cantidad'],
                        'subtotal' => $existencia['subtotal'],
                        'helado' => $existencia['helado'],
                        'estado' => $existencia['estado'],
                    ]);
                } else {
                    // Crear nuevo
                    ComandaExistencia::create([
                        'comanda_id' => $this->comandaId,
                        'existencia_id' => $existencia['existencia_id'],
                        'precio_unitario' => $existencia['precio_unitario'],
                        'cantidad' => $existencia['cantidad'],
                        'subtotal' => $existencia['subtotal'],
                        'helado' => $existencia['helado'],
                        'estado' => $existencia['estado'],
                    ]);
                }
            }

            // Actualizar/crear platos
            foreach ($this->platos as $plato) {
                if ($plato['id']) {
                    // Actualizar existente
                    ComandaPlato::where('id', $plato['id'])->update([
                        'precio_unitario' => $plato['precio_unitario'],
                        'cantidad' => $plato['cantidad'],
                        'subtotal' => $plato['subtotal'],
                        'llevar' => $plato['llevar'],
                        'estado' => $plato['estado'],
                    ]);
                } else {
                    // Crear nuevo
                    ComandaPlato::create([
                        'comanda_id' => $this->comandaId,
                        'plato_id' => $plato['plato_id'],
                        'precio_unitario' => $plato['precio_unitario'],
                        'cantidad' => $plato['cantidad'],
                        'subtotal' => $plato['subtotal'],
                        'llevar' => $plato['llevar'],
                        'estado' => $plato['estado'],
                    ]);
                }
            }

            DB::commit();

            $this->dispatch('comandaActualizada');
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatch('notify', ['message' => 'Error al actualizar la comanda: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function render()
    {
        // Actualizar precio del nuevo plato si está seleccionado
        $this->actualizarPrecioNuevoPlato();

        return view('livewire.comanda.editar-comanda');
    }

    private function actualizarPrecioNuevoPlato()
    {
        if ($this->nuevoPlatoId) {
            $plato = Plato::find($this->nuevoPlatoId);
            $this->nuevoPlatoPrecio = $this->nuevoPlatoLlevar && $plato->precio_llevar > 0
                ? $plato->precio_llevar
                : $plato->precio;
        }
    }
}
