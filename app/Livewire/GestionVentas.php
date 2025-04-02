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
use App\Models\Plato;
use App\Models\TipoExistencia;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class GestionVentas extends Component
{

    public $userType;
    public $selectedCajaId;
    public $cajas;
    public $caja;


    public function mount()
    {
        $this->cargarCaja();
        $this->calcularNumeroPedido();
    }


    public function cargarCaja()
    {
        $user = Auth::user();
        $userCajas = $user->cajas;

        // Si el usuario no tiene cajas, se considera administrador
        if ($userCajas->count() == 0) {
            $this->userType = 'admin';
            $this->cajas = Caja::all();
            $this->selectedCajaId = session('selected_caja_id') ?? ($this->cajas->first()->id ?? null);
        } elseif ($userCajas->count() > 1) {
            $this->userType = 'multiple';
            $this->cajas = $userCajas;
            $this->selectedCajaId = session('selected_caja_id') ?? ($this->cajas->first()->id ?? null);
        } elseif ($userCajas->count() == 1) {
            $this->userType = 'single';
            $this->caja = $userCajas->first();
            $this->selectedCajaId = $this->caja->id;
        }
    }

    public function updatedSelectedCajaId($value)
    {
        session(['selected_caja_id' => $value]);

        $this->dispatch('cajaChanged', value: $value);
    }


    public $numeroPedido;

    public function calcularNumeroPedido()
    {
        $totalComandas = Comanda::count();


        $proximoNumero = $totalComandas + 1;

        $this->numeroPedido = str_pad($proximoNumero, 6, '0', STR_PAD_LEFT);
    }



    //Mesa

    public $id_mesa = '';
    public $id_zona = '';
    public $numero_mesa = '';
    public $nombre_zona = '';

    #[On('mesaZonaActualizada')]
    public function mesaZonaActualizacion($mesa, $zona, $numero, $nombre)
    {
        $this->id_mesa = $mesa;
        $this->id_zona = $zona;
        $this->numero_mesa = $numero;
        $this->nombre_zona = $nombre;
    }


    public function limpiarMesaZona()
    {
        // Encuentra el componente hijo por su ID y ejecuta su método
        $this->dispatch('limpiarMesaZona')->to('cliente.mesa-component');
    }


    public $numero_documento_buscar = '';
    public $razon_social = '';
    public $id_cliente = null;
    public $numero_documento = '';
    public $tipo_documentoNombre = '';
    public $tipo_documentoId = '';
    public $tipo_documentoCodigo = '';
    public $tipo_documento = '';
    public $fecha_cliente = '';
    public $direccion_cliente = '';

    public function buscar()
    {

        if (empty($this->numero_documento_buscar)) {
            Notification::make()
                ->title('Error de validación')
                ->body('El número de documento no puede estar vacío.')
                ->danger()
                ->send();
            return;
        }


        if (!preg_match('/^\d{8,}$/', $this->numero_documento_buscar)) {
            Notification::make()
                ->title('Error de validación')
                ->body('El número de documento debe tener al menos 8 dígitos numéricos.')
                ->danger()
                ->send();
            return;
        }

        $cliente = Cliente::where('numero_documento', $this->numero_documento_buscar)->first();

        if ($cliente) {
            $this->razon_social = $cliente->nombre;
            $this->id_cliente = $cliente->id;
            $this->numero_documento = $cliente->numero_documento;
            $this->tipo_documentoNombre = $cliente->tipoDocumento->descripcion_corta;
            $this->tipo_documento = $cliente->tipoDocumento->tipo;
            $this->tipo_documentoCodigo = $cliente->tipo_documento_id;
            $this->fecha_cliente = $cliente->created_at->format('Y/m/d');
            $this->direccion_cliente = $cliente->direccion;



            Notification::make()
                ->title('Cliente encontrado')
                ->success()
                ->send();
        } else {

            $numero_documento_buscar = $this->numero_documento_buscar;
            $this->dispatch('numeroDocumentoBuscar', numero: $numero_documento_buscar);
            $this->razon_social = '';
            $this->id_cliente = null;
            $this->numero_documento = '';
            $this->tipo_documentoNombre = '';
            $this->tipo_documento = '';
            $this->fecha_cliente = '';
            $this->direccion_cliente = '';

            Notification::make()
                ->title('Cliente no encontrado')
                ->danger()
                ->send();
        }
    }

    public function limpiarCliente()
    {
        $this->numero_documento_buscar = '';
        $this->razon_social = '';
        $this->id_cliente = null;
        $this->numero_documento = '';
        $this->tipo_documentoNombre = '';
        $this->tipo_documentoId = '';
        $this->tipo_documentoCodigo = '';
        $this->tipo_documento = '';
        $this->fecha_cliente = '';
        $this->direccion_cliente = '';
    }







    public function render()
    {


        $tipos_existencia = TipoExistencia::where('estado', true)->get();

        $categorias_existencia = collect();
        if ($this->selectedTipoExistencia) {
            $categorias_existencia = CategoriaExistencia::where('tipo_existencia_id', $this->selectedTipoExistencia)
                ->where('estado', true)
                ->get();
        }


        $categorias_plato = CategoriaPlato::where('estado', true)->get();
        $platos = $this->getPlatos();


        $existencias = $this->getExistencias();

        return view('livewire.gestion-ventas', [
            'tipos_existencia' => $tipos_existencia,
            'categorias_existencia' => $categorias_existencia,
            'existencias' => $existencias,

            'categorias_plato' => $categorias_plato,
            'platos' => $platos
        ]);
    }

    //===================================EXISTENCIA================================


    public $mostrarModalExistencia = false;
    public $selectedTipoExistencia = '';
    public $selectedCategoriaExistencia = '';
    public $tipoProductosId = '';



    public function abrirModalExistencia()
    {
        if (!$this->selectedTipoExistencia) {
            $primerTipo = TipoExistencia::where('estado', true)->first();
            if ($primerTipo) {
                $this->selectedTipoExistencia = $primerTipo->id;
            }
        }

        // Mantenemos categoría vacía
        $this->selectedCategoriaExistencia = '';

        $this->mostrarModalExistencia = true;
    }

    public function cerrarModalExistencia()
    {
        $this->mostrarModalExistencia = false;
        $this->selectedTipoExistencia = '';
        $this->selectedCategoriaExistencia = '';
    }



    public function getExistencias()
    {

        $query = Existencia::query()->where('estado', true);

        // Aplicar filtro por tipo de existencia
        if ($this->selectedTipoExistencia) {
            $query->where('tipo_existencia_id', $this->selectedTipoExistencia);
        }

        // Aplicar filtro por categoría de existencia
        if ($this->selectedCategoriaExistencia) {
            $query->where('categoria_existencia_id', $this->selectedCategoriaExistencia);
        }

        // Incluir relaciones necesarias
        $query->with(['unidadMedida', 'inventario' => function ($query) {
            // Por defecto, tomar el primer almacén
            $query->orderBy('almacen_id', 'asc');
        }, 'tipoExistencia', 'categoriaExistencia']);


        $this->tipoProductosId = TipoExistencia::where(
            'nombre',
            'like',
            '%producto%',
        )->first()?->id;

        // Obtener resultados sin paginación
        return $query->get();
    }


    public function selectTipoExistencia($tipoId)
    {
        $this->selectedTipoExistencia = $tipoId;
        // Al cambiar el tipo, no seleccionamos ninguna categoría específica
        $this->selectedCategoriaExistencia = '';
    }

    public function selectCategoriaExistencia($categoriaId)
    {
        $this->selectedCategoriaExistencia = $categoriaId;
    }





    //=================================PLATOS============================

    public $mostrarModalPlato = false;
    public $showConfirmation = false;
    public $selectedCategoriaPlato = '';

    public function abrirModalPlato()
    {
        // Al abrir el modal, aseguramos que se muestre la opción "Todas" (cadena vacía)
        $this->selectedCategoriaPlato = '';
        $this->mostrarModalPlato = true;
    }

    public function cerrarModalPlato()
    {
        $this->mostrarModalPlato = false;
        $this->selectedCategoriaPlato = '';
    }

    public function getPlatos()
    {
        // Crear la consulta base
        $query = Plato::query()->where('estado', true);

        // Filtrar por categoría si está seleccionada
        if ($this->selectedCategoriaPlato) {
            $query->where('categoria_plato_id', $this->selectedCategoriaPlato);
        }

        // Incluir relaciones necesarias
        $query->with(['categoriaPlato', 'disponibilidadPlato']);

        // Obtener todos los resultados sin paginación
        return $query->get();
    }

    public function selectCategoriaPlato($categoriaId)
    {
        $this->selectedCategoriaPlato = $categoriaId;
    }











    // Agrega estas propiedades al inicio de la clase
    public $platosComanda = [];
    public $existenciasComanda = [];
    public $subtotalGeneral = 0;
    public $igvGeneral = 0;
    public $descuentoGeneral = 0;
    public $totalGeneral = 0;

    // Método para agregar existencia desde el modal
    public function agregarExistencia($existenciaId, $esHelado)
    {
        $existencia = Existencia::with(['categoriaExistencia', 'tipoExistencia'])
            ->findOrFail($existenciaId);

        // Determinar si es producto basado en el tipo de existencia
        $esProducto = $existencia->tipoExistencia->nombre == 'Producto' ||
            strpos(strtolower($existencia->tipoExistencia->nombre), 'producto') !== false;

        $precioUnitario = $existencia->precio_venta;

        // Verificar si la existencia ya está en la lista
        $index = $this->buscarExistenciaEnComanda($existenciaId, $esHelado);

        if ($index !== false) {
            // Si ya existe, solo incrementamos la cantidad
            $this->incrementarCantidadExistencia($index);
        } else {
            // Si no existe, la agregamos
            $this->existenciasComanda[] = [
                'id' => $existenciaId,
                'nombre' => $existencia->nombre,
                'unidad_medida' => $existencia->unidadMedida->descripcion ?? 'Sin U. de medida',
                'precio_unitario' => $precioUnitario,
                'cantidad' => 1,
                'subtotal' => $precioUnitario,
                'es_producto' => $esProducto,
                'es_helado' => $esHelado,
                'estado' => 'Pendiente'
            ];
        }

        $this->calcularTotales();
    }

    // Método para agregar plato desde el modal
    public function agregarPlato($platoId, $esLlevar)
    {
        $plato = Plato::with('categoriaPlato')->findOrFail($platoId);

        // Determinar el precio según si es para llevar o no
        $precioUnitario = $esLlevar && $plato->precio_llevar > 0 ? $plato->precio_llevar : $plato->precio;

        // Verificar si el plato ya está en la lista con la misma modalidad (llevar o mesa)
        $index = $this->buscarPlatoEnComanda($platoId, $esLlevar);

        if ($index !== false) {
            // Si ya existe, solo incrementamos la cantidad
            $this->incrementarCantidadPlato($index);
        } else {
            // Si no existe, lo agregamos
            $this->platosComanda[] = [
                'id' => $platoId,
                'nombre' => $plato->nombre,
                'unidad_medida' => $plato->unidadMedida->descripcion ?? 'Sin U. de medida',
                'precio_unitario' => $precioUnitario,
                'cantidad' => 1,
                'subtotal' => $precioUnitario,
                'es_llevar' => $esLlevar,
                'estado' => 'Pendiente'
            ];
        }

        $this->calcularTotales();
    }

    // Método para buscar existencia en la comanda
    private function buscarExistenciaEnComanda($existenciaId, $esHelado)
    {
        foreach ($this->existenciasComanda as $index => $item) {
            if ($item['id'] == $existenciaId && $item['es_helado'] == $esHelado) {
                return $index;
            }
        }
        return false;
    }

    // Método para buscar plato en la comanda
    private function buscarPlatoEnComanda($platoId, $esLlevar)
    {
        foreach ($this->platosComanda as $index => $item) {
            if ($item['id'] == $platoId && $item['es_llevar'] == $esLlevar) {
                return $index;
            }
        }
        return false;
    }

    // Incrementar cantidad de existencia
    public function incrementarCantidadExistencia($index)
    {
        if (isset($this->existenciasComanda[$index])) {
            $this->existenciasComanda[$index]['cantidad']++;
            $this->actualizarSubtotalExistencia($index);
            $this->calcularTotales();
        }
    }

    // Decrementar cantidad de existencia
    public function decrementarCantidadExistencia($index)
    {
        if (isset($this->existenciasComanda[$index]) && $this->existenciasComanda[$index]['cantidad'] > 1) {
            $this->existenciasComanda[$index]['cantidad']--;
            $this->actualizarSubtotalExistencia($index);
            $this->calcularTotales();
        }
    }

    // Actualizar subtotal de existencia
    private function actualizarSubtotalExistencia($index)
    {
        if (isset($this->existenciasComanda[$index])) {
            $this->existenciasComanda[$index]['subtotal'] =
                $this->existenciasComanda[$index]['precio_unitario'] * $this->existenciasComanda[$index]['cantidad'];
        }
    }

    // Remover existencia
    public function removerExistencia($index)
    {
        if (isset($this->existenciasComanda[$index])) {
            $nombre = $this->existenciasComanda[$index]['nombre'];
            array_splice($this->existenciasComanda, $index, 1);
            $this->calcularTotales();

            Notification::make()
                ->title('Existencia eliminada')
                ->body("$nombre ha sido eliminado de la comanda.")
                ->success()
                ->send();
        }
    }

    // Cambiar tipo de existencia (helado/normal)
    public function toggleHeladoExistencia($index)
    {
        if (isset($this->existenciasComanda[$index]) && $this->existenciasComanda[$index]['es_producto']) {
            $existenciaId = $this->existenciasComanda[$index]['id'];
            $nuevoEsHelado = !$this->existenciasComanda[$index]['es_helado'];

            // Verificar si ya existe esa combinación
            $existingIndex = $this->buscarExistenciaEnComanda($existenciaId, $nuevoEsHelado);

            if ($existingIndex !== false) {
                // Si ya existe, incrementamos esa y eliminamos la actual
                $this->incrementarCantidadExistencia($existingIndex);
                $this->removerExistencia($index);
            } else {
                // Si no existe, cambiamos el valor
                $this->existenciasComanda[$index]['es_helado'] = $nuevoEsHelado;

                Notification::make()
                    ->title('Tipo de producto actualizado')
                    ->body('El producto ha sido actualizado a ' . ($nuevoEsHelado ? 'helado' : 'normal'))
                    ->success()
                    ->send();
            }
        }
    }

    // Incrementar cantidad de plato
    public function incrementarCantidadPlato($index)
    {
        if (isset($this->platosComanda[$index])) {
            $this->platosComanda[$index]['cantidad']++;
            $this->actualizarSubtotalPlato($index);
            $this->calcularTotales();
        }
    }

    // Decrementar cantidad de plato
    public function decrementarCantidadPlato($index)
    {
        if (isset($this->platosComanda[$index]) && $this->platosComanda[$index]['cantidad'] > 1) {
            $this->platosComanda[$index]['cantidad']--;
            $this->actualizarSubtotalPlato($index);
            $this->calcularTotales();
        }
    }

    // Actualizar subtotal de plato
    private function actualizarSubtotalPlato($index)
    {
        if (isset($this->platosComanda[$index])) {
            $this->platosComanda[$index]['subtotal'] =
                $this->platosComanda[$index]['precio_unitario'] * $this->platosComanda[$index]['cantidad'];
        }
    }

    // Remover plato
    public function removerPlato($index)
    {
        if (isset($this->platosComanda[$index])) {
            $nombre = $this->platosComanda[$index]['nombre'];
            array_splice($this->platosComanda, $index, 1);
            $this->calcularTotales();

            Notification::make()
                ->title('Plato eliminado')
                ->body("$nombre ha sido eliminado de la comanda.")
                ->success()
                ->send();
        }
    }

    // Cambiar tipo de plato (mesa/llevar)
    public function toggleLlevarPlato($index)
    {
        if (isset($this->platosComanda[$index])) {
            $platoId = $this->platosComanda[$index]['id'];
            $nuevoEsLlevar = !$this->platosComanda[$index]['es_llevar'];

            // Obtenemos el plato para conocer los precios
            $plato = Plato::find($platoId);
            $nuevoPrecio = $nuevoEsLlevar && $plato->precio_llevar > 0 ? $plato->precio_llevar : $plato->precio;

            // Verificar si ya existe esa combinación
            $existingIndex = $this->buscarPlatoEnComanda($platoId, $nuevoEsLlevar);

            if ($existingIndex !== false) {
                // Si ya existe, incrementamos esa y eliminamos la actual
                $this->incrementarCantidadPlato($existingIndex);
                $this->removerPlato($index);
            } else {
                // Si no existe, cambiamos el valor y actualizamos el precio
                $this->platosComanda[$index]['es_llevar'] = $nuevoEsLlevar;
                $this->platosComanda[$index]['precio_unitario'] = $nuevoPrecio;
                $this->actualizarSubtotalPlato($index);
                $this->calcularTotales();

                Notification::make()
                    ->title('Tipo de servicio actualizado')
                    ->body('El plato ha sido actualizado a ' . ($nuevoEsLlevar ? 'para llevar' : 'para mesa'))
                    ->success()
                    ->send();
            }
        }
    }

    // Método para calcular totales
    private function calcularTotales()
    {
        // Calcular subtotales
        $this->subtotalGeneral = 0;

        // Sumar subtotales de existencias
        foreach ($this->existenciasComanda as $existencia) {
            $this->subtotalGeneral += $existencia['subtotal'];
        }

        // Sumar subtotales de platos
        foreach ($this->platosComanda as $plato) {
            $this->subtotalGeneral += $plato['subtotal'];
        }

        // Calcular IGV (18%)
        $this->igvGeneral = $this->subtotalGeneral * 0.18;

        // Por ahora, el descuento está en 0
        $this->descuentoGeneral = 0;

        // Calcular total general
        $this->totalGeneral = $this->subtotalGeneral + $this->igvGeneral - $this->descuentoGeneral;
    }

    // Método para guardar la comanda en la base de datos
    public function guardarComanda()
    {
        // Validar que haya al menos un producto o plato
        if (count($this->platosComanda) == 0 && count($this->existenciasComanda) == 0) {
            Notification::make()
                ->title('Error al guardar')
                ->body('Debe agregar al menos un plato o existencia a la comanda.')
                ->danger()
                ->send();
            return;
        }

        // Validar que si hay platos para mesa, debe tener mesa asignada
        $tienePlatosParaMesa = collect($this->platosComanda)->where('es_llevar', false)->count() > 0;

        if ($tienePlatosParaMesa && (empty($this->id_mesa) || empty($this->id_zona))) {
            Notification::make()
                ->title('Error de validación')
                ->body('Debe seleccionar una mesa y zona para los platos servidos en mesa.')
                ->danger()
                ->send();
            return;
        }

        // Validar cliente (obligatorio)
        if (!$this->id_cliente) {
            Notification::make()
                ->title('Error de validación')
                ->body('Debe seleccionar un cliente para la comanda.')
                ->danger()
                ->send();
            return;
        }

        try {
            // Crear la comanda utilizando el modelo Comanda
            $comanda = new Comanda();
            $comanda->cliente_id = $this->id_cliente;
            $comanda->zona_id = $this->id_zona ?: null;
            $comanda->mesa_id = $this->id_mesa ?: null;
            $comanda->save();

            // Guardar los platos utilizando el modelo ComandaPlato
            foreach ($this->platosComanda as $plato) {
                $comandaPlato = new ComandaPlato();
                $comandaPlato->comanda_id = $comanda->id;
                $comandaPlato->plato_id = $plato['id'];
                $comandaPlato->cantidad = $plato['cantidad'];
                $comandaPlato->subtotal = $plato['subtotal'];
                $comandaPlato->llevar = $plato['es_llevar'];
                $comandaPlato->save();
            }

            // Guardar las existencias utilizando el modelo ComandaExistencia
            foreach ($this->existenciasComanda as $existencia) {
                $comandaExistencia = new ComandaExistencia();
                $comandaExistencia->comanda_id = $comanda->id;
                $comandaExistencia->existencia_id = $existencia['id'];
                $comandaExistencia->cantidad = $existencia['cantidad'];
                $comandaExistencia->subtotal = $existencia['subtotal'];
                $comandaExistencia->helado = $existencia['es_helado'];
                $comandaExistencia->save();
            }

            // Limpiar los arrays después de guardar
            $this->platosComanda = [];
            $this->existenciasComanda = [];
            $this->calcularTotales();

            // Limpiar otros campos
            $this->limpiarCliente();
            $this->limpiarMesaZona();

            // Calcular nuevo número de pedido


            Notification::make()
                ->title('Comanda guardada')
                ->body('La comanda ha sido guardada exitosamente con número: ' . $this->numeroPedido)
                ->success()
                ->send();

            $this->calcularNumeroPedido();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error al guardar')
                ->body('Ocurrió un error al guardar la comanda: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
