<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\CategoriaExistencia;
use App\Models\CategoriaPlato;
use App\Models\Cliente;
use App\Models\Comanda;
use App\Models\ComandaExistencia;
use App\Models\ComandaPlato;
use App\Models\ComprobantePago;
use App\Models\DisponibilidadPlato;
use App\Models\Empresa;
use App\Models\Existencia;
use App\Models\Inventario;
use App\Models\Mesa;
use App\Models\MovimientoCaja;
use App\Models\Plato;
use App\Models\SesionCaja;
use App\Models\TipoComprobante;
use App\Models\TipoExistencia;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use Livewire\Component;

class GestionVentas extends Component
{

    public $cajaId;

    public $caja;

    public $sesionCajaId = null;

    public function mount($cajaId = null)
    {
        $this->cajaId = $cajaId;
        $this->cargarCaja();
        $this->calcularNumeroPedido();
    }


    #[On('caja-seleccionada')]
    public function actualizarCajaId($cajaId)
    {
        $this->cajaId = $cajaId;
        $this->cargarCaja();
    }

    protected function cargarCaja()
    {
        if ($this->cajaId) {
            $this->caja = Caja::find($this->cajaId);

            // Buscar la sesión de caja activa para esta caja
            $sesionCaja = SesionCaja::where('caja_id', $this->cajaId)
                ->where('estado', true)
                ->latest()  // Para obtener la más reciente en caso de que haya más de una
                ->first();

            if ($sesionCaja) {
                $this->sesionCajaId = $sesionCaja->id;

                // Guardar en la sesión para que esté disponible en otras partes
            } else {
                // Si no hay sesión activa, podríamos lanzar un error o simplemente dejarlo como null
                $this->sesionCajaId = null;
                // Opcional: Mostrar una notificación de que no hay sesión activa

                Notification::make()
                    ->title('No hay sesión activa')
                    ->body('No se encontró una sesión activa para esta caja.')
                    ->warning()
                    ->send();
            }
        }
    }


    public function cerrarCaja()
    {
        // Buscar la sesión activa
        $sesionCaja = SesionCaja::where('caja_id', $this->cajaId)
            ->where('estado', true)
            ->latest()
            ->first();

        if (!$sesionCaja) {
            // No hay sesión activa, mostrar error con Filament
            Notification::make()
                ->title('No se encontró sesión activa')
                ->body('No hay una sesión de caja activa. Por favor, abra la caja primero.')
                ->danger()
                ->duration(5000)
                ->send();
            return;
        }

        $this->sesionCajaId = $sesionCaja->id;

        // Buscar todos los movimientos asociados a esta sesión
        $movimientos = MovimientoCaja::where('sesion_caja_id', $this->sesionCajaId)->get();

        // Calcular totales por tipo de transacción y motivo
        $totalIngresos = $movimientos->where('tipo_transaccion', 'Ingreso')->sum('monto');
        $totalEgresos = $movimientos->where('tipo_transaccion', 'Egreso')->sum('monto');

        // Calcular ingresos por motivo
        $ingresosPorVenta = $movimientos->where('tipo_transaccion', 'Ingreso')
            ->where('motivo', 'Venta')
            ->sum('monto');

        $ingresosPorTransferencia = $movimientos->where('tipo_transaccion', 'Ingreso')
            ->where('motivo', 'Transferencia')
            ->sum('monto');

        $ingresosPorAjuste = $movimientos->where('tipo_transaccion', 'Ingreso')
            ->where('motivo', 'Ajuste')
            ->sum('monto');

        // Calcular egresos por motivo
        $egresosPorTransferencia = $movimientos->where('tipo_transaccion', 'Egreso')
            ->where('motivo', 'Transferencia')
            ->sum('monto');

        $egresosPorAjuste = $movimientos->where('tipo_transaccion', 'Egreso')
            ->where('motivo', 'Ajuste')
            ->sum('monto');

        // Calcular saldo final = saldo inicial + ingresos - egresos
        $saldoInicial = $sesionCaja->saldo_inicial;
        $saldoFinal = $saldoInicial + $totalIngresos - $totalEgresos;

        try {
            // Iniciar transacción para garantizar que todas las operaciones se completen
            \Illuminate\Support\Facades\DB::beginTransaction();

            // 1. Actualizar el saldo final en la sesión de caja
            $sesionCaja->saldo_cierre = $saldoFinal;
            $sesionCaja->fecha_cierra = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            $sesionCaja->estado = false;
            $sesionCaja->save();

            // 2. Actualizar el saldo actual en la tabla de cajas
            $caja = Caja::find($this->cajaId);
            if ($caja) {
                $caja->saldo_actual = $saldoFinal;
                $caja->estado = 'Cerrada';
                $caja->save();
            }

            // Confirmar transacción
            DB::commit();

            // Mostrar resumen antes de cerrar la caja
            Notification::make()
                ->title('Resumen de Caja')
                ->body("Saldo Inicial: S/. " . number_format($saldoInicial, 2) . "\n" .
                    "Total Ingresos: S/. " . number_format($totalIngresos, 2) . "\n" .
                    "Total Egresos: S/. " . number_format($totalEgresos, 2) . "\n" .
                    "Saldo Final: S/. " . number_format($saldoFinal, 2))
                ->success()
                ->duration(8000)
                ->persistent()
                ->send();

            // Ejecuta el cierre de caja en el componente selector-caja
            $this->dispatch('cerrarCaja')->to('selector-caja');
        } catch (\Exception $e) {
            // Si algo falla, revertir la transacción
            DB::rollBack();

            // Notificar el error
            Notification::make()
                ->title('Error al cerrar caja')
                ->body('Ha ocurrido un error al cerrar la caja. Por favor, inténtelo de nuevo.' . $e->getMessage())
                ->danger()
                ->persistent()
                ->send();
        }
    }

    public $numeroPedido;

    public function calcularNumeroPedido()
    {
        // Obtener la última comanda creada
        $ultimaComanda = Comanda::orderBy('id', 'desc')->first();

        // Si existe al menos una comanda, tomar su ID y sumar 1
        // De lo contrario, comenzar desde 1
        $proximoNumero = $ultimaComanda ? $ultimaComanda->id + 1 : 1;

        // Formatear el número con ceros a la izquierda
        $this->numeroPedido = str_pad($proximoNumero, 6, '0', STR_PAD_LEFT);

        return $this->numeroPedido;
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

            $this->clienteEncontrado = true;

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
        $this->serieComprobante = '';

        $this->clienteEncontrado = false;
    }

    public $tipoComprobantes = [];
    public $tipoComprobanteSeleccionado = null;
    public $serieComprobante = '';


    public $clienteEncontrado = false;

    public function render()
    {


        $tipos_existencia = TipoExistencia::where('estado', true)->get();

        $categorias_existencia = collect();
        if ($this->selectedTipoExistencia) {
            $categorias_existencia = CategoriaExistencia::where('tipo_existencia_id', $this->selectedTipoExistencia)
                ->where('estado', true)
                ->get();
        }

        $this->tipoComprobantes = TipoComprobante::where('estado', 1)->get();



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



    public function updatedTipoComprobanteSeleccionado()
    {
        $this->actualizarSerie();
    }

    public function actualizarSerie()
    {
        $comprobante = TipoComprobante::where('codigo', $this->tipoComprobanteSeleccionado)
            ->where('estado', 1)
            ->first();

        if ($comprobante) {
            // Tomar la primera letra de la descripción
            $primeraLetra = substr($comprobante->descripcion, 0, 1);
            // Formatear la serie con la letra y el ID de la caja
            $this->serieComprobante = $primeraLetra . '00' . ($this->caja->id ?? '');
        } else {
            $this->serieComprobante = '';
        }
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


        $this->tipoProductosId = TipoExistencia::whereRaw('LOWER(nombre) LIKE ?', ['%producto%'])
            ->orWhereRaw('LOWER(nombre) LIKE ?', ['%productos%'])
            ->first()?->id;

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
        $esProducto = in_array(
            strtolower($existencia->tipoExistencia->nombre),
            [
                'producto',
                'productos',
                'Producto',
                'Productos',
                'PRODUCTO',
                'PRODUCTOS'
            ]
        );

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
                'unidad_medida_codigo' => $existencia->unidadMedida->codigo ?? 'Sin U. de medida',
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
                'unidad_medida_codigo' => $plato->unidadMedida->codigo ?? 'Sin U. de medida',
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
        $this->totalGeneral = 0;

        // Sumar subtotales de existencias
        foreach ($this->existenciasComanda as $existencia) {
            $this->totalGeneral += $existencia['subtotal'];
        }

        // Sumar subtotales de platos
        foreach ($this->platosComanda as $plato) {
            $this->totalGeneral += $plato['subtotal'];
        }

        $this->subtotalGeneral = round($this->totalGeneral / 1.1, 2);

        // Calcular IGV (18%)
        $this->igvGeneral = round($this->subtotalGeneral * 0.1, 2);

        // Calcular total general
        $this->totalGeneral = round($this->subtotalGeneral + $this->igvGeneral);
    }


    public function guardarPedido()
    {

        $resultado = $this->guardarComanda();
        if ($resultado !== false) {
            $this->limpiarGeneral();
        }
    }

    public function guardarComanda()
    {
        // Validar que haya al menos un producto o plato
        if (count($this->platosComanda) == 0 && count($this->existenciasComanda) == 0) {
            Notification::make()
                ->title('Error al guardar')
                ->body('Debe agregar al menos un plato o existencia a la comanda.')
                ->danger()
                ->send();

            return false;
        }

        // Validar que si hay platos para mesa, debe tener mesa asignada
        $tienePlatosParaMesa = collect($this->platosComanda)->where('es_llevar', false)->count() > 0;

        if ($tienePlatosParaMesa && (empty($this->id_mesa) || empty($this->id_zona))) {
            Notification::make()
                ->title('Error de validación')
                ->body('Debe seleccionar una mesa y zona para los platos servidos en mesa.')
                ->danger()
                ->send();

            return false;
        }

        // Validar cliente (obligatorio)
        if (!$this->id_cliente) {
            Notification::make()
                ->title('Error de validación')
                ->body('Debe ingresar un cliente para la comanda.')
                ->danger()
                ->send();

            return false;
        }



        // NUEVA VALIDACIÓN: Verificar que la mesa esté libre si se está asignando una
        if ($this->id_mesa) {
            $mesa = Mesa::find($this->id_mesa);
            if (!$mesa) {
                Notification::make()
                    ->title('Error de validación')
                    ->body('La mesa seleccionada no existe.')
                    ->danger()
                    ->send();

                return false;
            }

            if ($mesa->estado != 'Libre') {
                Notification::make()
                    ->title('Mesa no disponible')
                    ->body('La mesa seleccionada no está libre actualmente.')
                    ->danger()
                    ->send();

                return false;
            }
        }


        // NUEVA VALIDACIÓN: Verificar disponibilidad de platos
        $platosNoDisponibles = [];
        // Agrupar platos por ID para sumar las cantidades totales (para llevar + no para llevar)
        $platosAgrupados = [];
        foreach ($this->platosComanda as $plato) {
            if (!isset($platosAgrupados[$plato['id']])) {
                $platosAgrupados[$plato['id']] = [
                    'id' => $plato['id'],
                    'nombre' => $plato['nombre'],
                    'cantidad_total' => 0
                ];
            }
            $platosAgrupados[$plato['id']]['cantidad_total'] += $plato['cantidad'];
        }

        // Verificar disponibilidad con cantidades agrupadas
        foreach ($platosAgrupados as $plato) {
            $disponibilidad = DisponibilidadPlato::where('plato_id', $plato['id'])->first();

            // Verificar si existe registro de disponibilidad
            if (!$disponibilidad) {
                $platosNoDisponibles[] = $plato['nombre'];
                continue;
            }

            // Verificar disponibilidad booleana
            if (!$disponibilidad->disponibilidad) {
                $platosNoDisponibles[] = $plato['nombre'];
                continue;
            }

            // Verificar cantidad disponible contra el total agrupado
            if ($disponibilidad->cantidad < $plato['cantidad_total']) {
                $platosNoDisponibles[] = $plato['nombre'] . " (solo hay " . $disponibilidad->cantidad . " disponibles)";
            }
        }

        if (count($platosNoDisponibles) > 0) {
            Notification::make()
                ->title('Platos no disponibles')
                ->body('Los siguientes platos no están disponibles: ' . implode(', ', $platosNoDisponibles))
                ->danger()
                ->send();

            return false;
        }

        // NUEVA VALIDACIÓN: Verificar stock de existencias
        $existenciasSinStock = [];
        // Agrupar existencias por ID para sumar las cantidades totales (helado + no helado)
        $existenciasAgrupadas = [];
        foreach ($this->existenciasComanda as $existencia) {
            if (!isset($existenciasAgrupadas[$existencia['id']])) {
                $existenciasAgrupadas[$existencia['id']] = [
                    'id' => $existencia['id'],
                    'nombre' => $existencia['nombre'],
                    'cantidad_total' => 0
                ];
            }
            $existenciasAgrupadas[$existencia['id']]['cantidad_total'] += $existencia['cantidad'];
        }

        // Verificar stock con cantidades agrupadas
        foreach ($existenciasAgrupadas as $existencia) {
            $inventario = Inventario::where('existencia_id', $existencia['id'])
                ->first();

            if (!$inventario || $inventario->stock < $existencia['cantidad_total']) {
                $stockDisponible = $inventario ? $inventario->stock : 0;
                $existenciasSinStock[] = $existencia['nombre'] . " (solo hay " . $stockDisponible . " en stock)";
            }
        }

        if (count($existenciasSinStock) > 0) {
            Notification::make()
                ->title('Stock insuficiente')
                ->body('Las siguientes existencias no tienen stock suficiente: ' . implode(', ', $existenciasSinStock))
                ->danger()
                ->send();

            return false;
        }

        try {
            DB::beginTransaction();

            // Crear la comanda utilizando el modelo Comanda
            $comanda = new Comanda();
            $comanda->cliente_id = $this->id_cliente;
            $comanda->zona_id = $this->id_zona ?: null;
            $comanda->mesa_id = $this->id_mesa ?: null;
            $comanda->subtotal = $this->subtotalGeneral;
            $comanda->igv = $this->igvGeneral;
            $comanda->total_general = $this->totalGeneral;
            $comanda->save();

            // Mapeo para controlar cuánto se ha reducido de cada plato
            $reduccionPlatos = [];

            // Guardar los platos utilizando el modelo ComandaPlato
            foreach ($this->platosComanda as $plato) {
                $comandaPlato = new ComandaPlato();
                $comandaPlato->comanda_id = $comanda->id;
                $comandaPlato->plato_id = $plato['id'];
                $comandaPlato->precio_unitario = $plato['precio_unitario'];
                $comandaPlato->cantidad = $plato['cantidad'];
                $comandaPlato->subtotal = $plato['subtotal'];
                $comandaPlato->llevar = $plato['es_llevar'];
                $comandaPlato->save();

                // Llevar un control de la cantidad total a reducir por plato
                if (!isset($reduccionPlatos[$plato['id']])) {
                    $reduccionPlatos[$plato['id']] = 0;
                }
                $reduccionPlatos[$plato['id']] += $plato['cantidad'];
            }

            // Actualizar disponibilidad de platos una sola vez por cada ID
            foreach ($reduccionPlatos as $platoId => $cantidadReducir) {
                $disponibilidad = DisponibilidadPlato::where('plato_id', $platoId)->first();
                if ($disponibilidad) {
                    $disponibilidad->cantidad -= $cantidadReducir;
                    // Si la cantidad llega a 0, marcar como no disponible
                    if ($disponibilidad->cantidad <= 0) {
                        $disponibilidad->disponibilidad = false;
                        $disponibilidad->cantidad = 0;
                    }
                    $disponibilidad->save();
                }
            }

            // Mapeo para controlar cuánto se ha reducido de cada existencia
            $reduccionExistencias = [];

            // Guardar las existencias utilizando el modelo ComandaExistencia
            foreach ($this->existenciasComanda as $existencia) {
                $comandaExistencia = new ComandaExistencia();
                $comandaExistencia->comanda_id = $comanda->id;
                $comandaExistencia->existencia_id = $existencia['id'];
                $comandaExistencia->precio_unitario = $existencia['precio_unitario'];
                $comandaExistencia->cantidad = $existencia['cantidad'];
                $comandaExistencia->subtotal = $existencia['subtotal'];
                $comandaExistencia->helado = $existencia['es_helado'];
                $comandaExistencia->save();

                // Llevar un control de la cantidad total a reducir por existencia
                if (!isset($reduccionExistencias[$existencia['id']])) {
                    $reduccionExistencias[$existencia['id']] = 0;
                }
                $reduccionExistencias[$existencia['id']] += $existencia['cantidad'];
            }

            // Actualizar inventario una sola vez por cada ID de existencia
            foreach ($reduccionExistencias as $existenciaId => $cantidadReducir) {
                $inventario = Inventario::where('existencia_id', $existenciaId)->first();

                if ($inventario) {
                    $inventario->stock -= $cantidadReducir;
                    // Evitar stock negativo
                    if ($inventario->stock < 0) {
                        $inventario->stock = 0;
                    }
                    $inventario->save();
                }
            }

            // CAMBIAR ESTADO DE LA MESA A OCUPADA
            if ($this->id_mesa) {
                $mesa = Mesa::find($this->id_mesa);
                if ($mesa) {
                    $mesa->estado = 'Ocupada';
                    $mesa->save();
                }
            }

            DB::commit();
            Notification::make()
                ->title('Comanda guardada')
                ->body('La comanda ha sido guardada exitosamente con número: ' . $this->numeroPedido)
                ->success()
                ->send();

            // Retornar el ID de la comanda creada
            return $comanda->id;
        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Error al guardar')
                ->body('Ocurrió un error al guardar la comanda: ' . $e->getMessage())
                ->danger()
                ->send();

            return false;
        }
    }


    public function limpiarGeneral()
    {


        // Limpiar los arrays después de guardar
        $this->platosComanda = [];
        $this->existenciasComanda = [];
        $this->calcularTotales();

        // Limpiar otros campos
        $this->limpiarCliente();
        $this->limpiarMesaZona();
        $this->calcularNumeroPedido();

        $this->tipoComprobanteSeleccionado = null;
        $this->formaPago = null;
        $this->monedaSelecionada = null;
    }
    //================================VENTAS=====================================



    public $serie;
    public $numeroSerie;
    public $fechaEmision;
    public $monedaSelecionada;
    public $formaPago;



    public function guardarComandaComprobante()
    {

        if ($this->tipoComprobanteSeleccionado == null) {
            Notification::make()
                ->title('Error de validación')
                ->body('Debe seleccionar un tipo de comprobante.')
                ->danger()
                ->send();
            return;
        }

        if ($this->monedaSelecionada == null) {
            Notification::make()
                ->title('Error de validación')
                ->body('Debe seleccionar una moneda.')
                ->danger()
                ->send();
            return;
        }
        if ($this->formaPago == null) {
            Notification::make()
                ->title('Error de validación')
                ->body('Debe seleccionar una forma de pago.')
                ->danger()
                ->send();
            return;
        }

        $cliente = Cliente::find($this->id_cliente);
        if ($cliente->tipoDocumento->tipo == '1') {

            if ($this->tipoComprobanteSeleccionado == '01') {
                Notification::make()
                    ->title('Error de validación')
                    ->body('El cliente no puede recibir factura.')
                    ->danger()
                    ->send();

                return;
            }
        }

        // Generar serie y correlativo
        $num = $this->calcularNumeroPedido();
        $nroComprobante = $this->serieComprobante . '-' . $num;

        // Obtener el ID de la comanda
        $comandaId = $this->guardarComanda();
        if ($comandaId == false) {
            Notification::make()
                ->title('Error')
                ->body('No se pudo guardar la comanda')
                ->danger()
                ->duration(5000)
                ->send();
            return;
        }


        // Recuperar serie y número del comprobante (lo movemos aquí para usarlo en ambas ramas)
        $serieNumero = explode('-', $nroComprobante);
        $serie = $serieNumero[0] ?? '';
        $numero = $serieNumero[1] ?? '';

        if ($this->tipoComprobanteSeleccionado != 00) {
            // Obtener información de la empresa
            $empresa = Empresa::first();


            // Calcular valores de factura
            $subtotal = $this->subtotalGeneral;
            $igv = $this->igvGeneral;
            $total = $this->totalGeneral;

            // Preparar array de detalle para factura electrónica
            $detalleFactura = [];
            $item = 1;

            // Agregar platos al detalle de factura
            foreach ($this->platosComanda as $plato) {
                $totalSinigv = round(($plato['cantidad'] * $plato['precio_unitario']) / 1.1, 2);
                $detalleFactura[] = [
                    "txtITEM" => (string)$item,
                    "txtUNIDAD_MEDIDA_DET" => (string)$plato['unidad_medida_codigo'],
                    "txtCANTIDAD_DET" => (string)$plato['cantidad'],
                    "txtPRECIO_DET" => (string)($plato['precio_unitario']),
                    "txtIMPORTE_DET" => (string)$totalSinigv,
                    "txtPRECIO_TIPO_CODIGO" => "01",
                    "txtIGV" => (string)round($totalSinigv * 0.1, 2),
                    "txtISC" => "0",
                    "txtCOD_TIPO_OPERACION" => "10",
                    "txtCODIGO_DET" => "PLA" . str_pad($plato['id'], 3, '0', STR_PAD_LEFT),
                    "txtDESCRIPCION_DET" => $plato['nombre'] . ($plato['es_llevar'] ? " - LLEVAR" : ""),
                    "txtPRECIO_SIN_IGV_DET" => (string)round($plato['precio_unitario'] / 1.1, 2),
                    "FLG_ICBPER" => "0",
                    "IMPUESTO_BP" => "0",
                    "IMPORTE_BP" => "0"
                ];

                $item++;
            }

            // Agregar existencias al detalle de factura
            foreach ($this->existenciasComanda as $existencia) {
                $totalSinigv = round(($existencia['cantidad'] * $existencia['precio_unitario']) / 1.1, 2);

                $detalleFactura[] = [
                    "txtITEM" => (string)$item,
                    "txtUNIDAD_MEDIDA_DET" => (string)$existencia['unidad_medida_codigo'],
                    "txtCANTIDAD_DET" => (string)$existencia['cantidad'],
                    "txtPRECIO_DET" => (string)($existencia['precio_unitario']),
                    "txtIMPORTE_DET" => (string)$totalSinigv,
                    "txtPRECIO_TIPO_CODIGO" => "01",
                    "txtIGV" => (string)round($totalSinigv * 0.1, 2),
                    "txtISC" => "0",
                    "txtCOD_TIPO_OPERACION" => "10",
                    "txtCODIGO_DET" => "EXI" . str_pad($existencia['id'], 3, '0', STR_PAD_LEFT),
                    "txtDESCRIPCION_DET" => (string)$existencia['nombre'] . ($existencia['es_helado'] ? " - HELADO" : ""),
                    "txtPRECIO_SIN_IGV_DET" => (string)round($existencia['precio_unitario'] / 1.1, 2),
                    "FLG_ICBPER" => "0",
                    "IMPUESTO_BP" => "0",
                    "IMPORTE_BP" => "0"
                ];

                $item++;
            }

            // Crear el objeto JSON para facturación electrónica
            $datos = [
                "ICBP" => "0",
                "txtTIPO_OPERACION" => "0101",
                "txtTOTAL_GRAVADAS" => (string)$subtotal,
                "txtSUB_TOTAL" => (string)$subtotal,
                "txtPOR_IGV" => "10.00",
                "txtTOTAL_IGV" => (string)$igv,
                "txtTOTAL" => (string)$total,
                "txtTOTAL_LETRAS" => $this->numeroALetras($total),
                "txtNRO_COMPROBANTE" => $nroComprobante,
                "txtFECHA_DOCUMENTO" => date('Y-m-d'),
                "txtFECHA_VTO" => date('Y-m-d'),
                "txtCOD_TIPO_DOCUMENTO" => (string)$this->tipoComprobanteSeleccionado,
                "txtCOD_MONEDA" => (string)$this->monedaSelecionada,
                "detalle_forma_pago" => [
                    [
                        "COD_FORMA_PAGO" => (string)$this->formaPago
                    ]
                ],
                "txtNRO_DOCUMENTO_CLIENTE" => $cliente->ruc ?? $cliente->numero_documento,
                "txtRAZON_SOCIAL_CLIENTE" => $cliente->nombre ?? ($cliente->nombres . ' ' . $cliente->apellidos),
                "txtTIPO_DOCUMENTO_CLIENTE" => $cliente->tipoDocumento->tipo,
                "txtDIRECCION_CLIENTE" => $cliente->direccion ?? " ",
                "txtCIUDAD_CLIENTE" => $cliente->ciudad ?? " ",
                "txtCOD_PAIS_CLIENTE" => "PE",
                "txtNRO_DOCUMENTO_EMPRESA" => $empresa->ruc ?? "11",
                "txtTIPO_DOCUMENTO_EMPRESA" => "6",
                "txtNOMBRE_COMERCIAL_EMPRESA" => $empresa->nombre_comercial ?? "NOMBRE COMERCIAL",
                "txtCODIGO_UBIGEO_EMPRESA" => $empresa->ubigeo ?? "111111",
                "txtDIRECCION_EMPRESA" => $empresa->direccion ?? "DIRECCIÓN COMERCIAL",
                "txtDEPARTAMENTO_EMPRESA" => $empresa->departamento ?? "DEPARTAMENTO",
                "txtPROVINCIA_EMPRESA" => $empresa->provincia ?? "PROVINCIA",
                "txtDISTRITO_EMPRESA" => $empresa->distrito ?? "DISTRITO",
                "txtCODIGO_PAIS_EMPRESA" => "PE",
                "txtRAZON_SOCIAL_EMPRESA" => $empresa->nombre ?? "RAZÓN SOCIAL EMPRESA",
                "txtUSUARIO_SOL_EMPRESA" => $empresa->usuario_sol ?? "MODDATOS",
                "txtPASS_SOL_EMPRESA" => $empresa->clave_sol ?? "moddatos",
                "txtPAS_FIRMA" => $empresa->clave_firma ?? "123456",
                "txtTIPO_PROCESO" => "3",
                "txtFLG_ANTICIPO" => "0",
                "txtFLG_REGU_ANTICIPO" => "0",
                "txtMONTO_REGU_ANTICIPO" => "0",
                "detalle" => $detalleFactura
            ];

            // Enviar el JSON a la API de facturación
            $response = Http::withHeaders([
                'Authorization' => 'Bearer 5|BSz4NaMp677HJ7gtOSHaL4hht6UzNluLBtsothM3',
                'Accept' => 'application/json'
            ])->post('http://localhost/v01-facvip/public/api/cpe', $datos);

            // Verificar si la respuesta fue exitosa
            if ($response->successful()) {
                $responseData = $response->json();

                // Extraer los datos de respuesta
                $hashCpe = $responseData['hash_cpe'] ?? null;
                $codSunat = $responseData['cod_sunat'] ?? null;
                $msjSunat = $responseData['msj_sunat'] ?? null;
                $hashCdr = $responseData['hash_cdr'] ?? null;
                $xmlCpeUrl = $responseData['xml_cpe'] ?? null;
                $xmlCdrUrl = $responseData['xml_cdr'] ?? null;

                // Variables para almacenar el contenido de los XML
                $xmlCpeContent = null;
                $xmlCdrContent = null;

                // Obtener el contenido del XML CPE
                if ($xmlCpeUrl) {
                    $xmlCpeResponse = Http::withHeaders([
                        'Authorization' => 'Bearer 5|BSz4NaMp677HJ7gtOSHaL4hht6UzNluLBtsothM3'
                    ])->get($xmlCpeUrl);

                    if ($xmlCpeResponse->successful()) {
                        $xmlCpeContent = $xmlCpeResponse->body();
                    }
                }

                // Obtener el contenido del XML CDR
                if ($xmlCdrUrl) {
                    $xmlCdrResponse = Http::withHeaders([
                        'Authorization' => 'Bearer 5|BSz4NaMp677HJ7gtOSHaL4hht6UzNluLBtsothM3'
                    ])->get($xmlCdrUrl);

                    if ($xmlCdrResponse->successful()) {
                        $xmlCdrContent = $xmlCdrResponse->body();
                    }
                }

                $tipoComprobanteId = $this->obtenerTipoComprobanteId($this->tipoComprobanteSeleccionado);

                // Crear el registro del comprobante en la base de datos
                try {
                    $comprobante = ComprobantePago::create([
                        'tipo_comprobante_id' => $tipoComprobanteId,
                        'serie' => $serie,
                        'numero' => $numero,
                        'cliente_id' => $this->id_cliente,
                        'comanda_id' => $comandaId,
                        'moneda' => $this->monedaSelecionada,
                        'medio_pago' => $this->formaPago,
                        'hash_cpe' => $hashCpe,
                        'hash_cdr' => $hashCdr,
                        'xml_cpe' => $xmlCpeContent,
                        'xml_cdr' => $xmlCdrContent,
                        'user_id' => Auth::id(),
                        'caja_id' => $this->cajaId,
                        'observaciones' => "Codigo: " . $codSunat . " " . $msjSunat,
                    ]);

                    // Notificar éxito
                    Notification::make()
                        ->title('Facturación exitosa')
                        ->body($msjSunat ?? 'El comprobante ha sido procesado correctamente')
                        ->success()
                        ->duration(5000)
                        ->send();
                } catch (\Exception $e) {
                    // En caso de error al guardar en la base de datos
                    Notification::make()
                        ->title('Error al guardar el comprobante')
                        ->body('Ocurrió un error al guardar el comprobante: ' . $e->getMessage())
                        ->danger()
                        ->duration(5000)
                        ->send();

                    return;
                }
            } else {
                // Error en la API de facturación
                Notification::make()
                    ->title('Error en facturación electrónica')
                    ->body('No se pudo conectar con el servicio de facturación: ' . ($response->body() ?? 'Error desconocido'))
                    ->danger()
                    ->duration(5000)
                    ->send();

                return;
            }
        } else {
            // Guardar comprobante interno sin facturación electrónica
            try {
                // Buscamos el ID del tipo de comprobante (nota de venta o interno, código 00)
                $tipoComprobanteId = $this->obtenerTipoComprobanteId('00');

                if (!$tipoComprobanteId) {
                    // Si no existe, intentar crearlo o usar un valor por defecto
                    // Puedes ajustar esto según tu lógica de negocio
                    $tipoComprobante = TipoComprobante::where('tipo', '00')
                        ->orWhere('nombre', 'NOTA DE VENTA')
                        ->orWhere('nombre', 'COMPROBANTE INTERNO')
                        ->first();

                    if ($tipoComprobante) {
                        $tipoComprobanteId = $tipoComprobante->id;
                    } else {
                        // Si no existe ningún tipo de comprobante compatible, puedes:
                        // 1. Crear uno nuevo (no implementado aquí)
                        // 2. Usar un valor por defecto (implementado abajo)
                        $tipoComprobanteId = 1; // Ajustar según tu base de datos

                        Notification::make()
                            ->title('Advertencia')
                            ->body('No se encontró el tipo de comprobante interno. Se usará un valor por defecto.')
                            ->warning()
                            ->duration(5000)
                            ->send();
                    }
                }

                // Crear el comprobante sin datos de facturación electrónica
                $comprobante = ComprobantePago::create([
                    'tipo_comprobante_id' => $tipoComprobanteId,
                    'serie' => $serie,
                    'numero' => $numero,
                    'cliente_id' => $this->id_cliente,
                    'comanda_id' => $comandaId,
                    'moneda' => $this->monedaSelecionada,
                    'medio_pago' => $this->formaPago,
                    'hash_cpe' => null,
                    'hash_cdr' => null,
                    'xml_cpe' => null,
                    'xml_cdr' => null,
                    'user_id' => Auth::id(),
                    'caja_id' => $this->cajaId,
                    'observaciones' => "Comprobante interno sin facturación electrónica",
                ]);

                // Notificar éxito
                Notification::make()
                    ->title('Comprobante interno registrado')
                    ->body('El comprobante interno ha sido registrado correctamente')
                    ->success()
                    ->duration(5000)
                    ->send();
            } catch (\Exception $e) {
                // En caso de error al guardar en la base de datos
                Notification::make()
                    ->title('Error al guardar el comprobante interno')
                    ->body('Ocurrió un error al guardar el comprobante: ' . $e->getMessage())
                    ->danger()
                    ->duration(5000)
                    ->send();

                return;
            }
        }

        // Validar que exista una sesión de caja activa
        if (!$this->sesionCajaId) {
            // Intentar encontrar la sesión activa
            $sesionCaja = SesionCaja::where('caja_id', $this->cajaId)
                ->where('estado', true)
                ->latest()
                ->first();

            if ($sesionCaja) {
                $this->sesionCajaId = $sesionCaja->id;
            } else {
                // No hay sesión activa, mostrar error con Filament
                Notification::make()
                    ->title('Error al registrar movimiento')
                    ->body('No hay una sesión de caja activa. Por favor, abra la caja primero.')
                    ->danger()
                    ->duration(5000)
                    ->send();
                return;
            }
        }

        // Validar el monto con notificación Filament
        if ($this->totalGeneral <= 0) {
            Notification::make()
                ->title('Monto inválido')
                ->body('El monto debe ser mayor a cero.')
                ->warning()
                ->duration(4000)
                ->send();
            return;
        }

        // Crear el movimiento
        MovimientoCaja::create([
            'user_id' => Auth::id(),
            'sesion_caja_id' => $this->sesionCajaId,
            'tipo_transaccion' => 'Ingreso',
            'motivo' => 'Venta',
            'monto' => $this->totalGeneral,
            'descripcion' => 'Factura: ' . $nroComprobante,
        ]);

        $mensaje = 'Se ha vendido S/. ' . number_format($this->totalGeneral, 2) . ' por concepto de venta. Factura: ' . $nroComprobante;

        // Mostrar notificación de éxito
        Notification::make()
            ->title('Movimiento registrado')
            ->body($mensaje)
            ->success()
            ->duration(4000)
            ->send();

        $this->limpiarGeneral();
    }

    /**
     * Convierte un número a su representación en letras
     */
    private function numeroALetras($numero)
    {
        $formatter = new \NumberFormatter('es', \NumberFormatter::SPELLOUT);
        $entero = floor($numero);
        $decimal = round(($numero - $entero) * 100);

        // Convertir a mayúsculas y formatear
        $texto = strtoupper($formatter->format($entero)) . ' CON ' .
            str_pad($decimal, 2, '0', STR_PAD_LEFT) . '/100 SOLES';

        return $texto;
    }

    private function obtenerTipoComprobanteId($tipoComprobante)
    {
        $tipo = TipoComprobante::where('codigo', $tipoComprobante)->first();
        return $tipo ? $tipo->id : null;
    }
}
