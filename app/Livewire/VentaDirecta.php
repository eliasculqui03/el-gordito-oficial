<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Comanda;
use App\Models\ComandaExistencia;
use App\Models\ComandaPlato;
use App\Models\DisponibilidadPlato;
use App\Models\Mesa;
use App\Models\Plato;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class VentaDirecta extends Component
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





    public function render()
    {

        return view('livewire.venta-directa');
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



    public $platosComanda = [];
    public $subtotalComanda = 0;
    public $totalComanda = 0;

    // Variables para existencias
    public $existenciasComanda = [];
    public $subtotalExistencias = 0;
    public $totalExistencias = 0;

    // Variables para totales generales
    public $subtotalGeneral = 0;
    public $igvGeneral = 0;
    public $descuentoGeneral = 0;
    public $totalGeneral = 0;

    // Método que recibe los platos desde el componente hijo
    #[On('platoAgregado')]
    public function procesarPlatoAgregado($plato)
    {
        // Verificar si ya existe un plato con el mismo ID y modalidad
        $platoExistente = false;
        foreach ($this->platosComanda as $indice => $item) {
            if ($item['plato_id'] == $plato['plato_id'] && $item['es_llevar'] == $plato['es_llevar']) {
                // Si existe, incrementar la cantidad
                $this->platosComanda[$indice]['cantidad']++;
                $this->platosComanda[$indice]['subtotal'] =
                    $this->platosComanda[$indice]['cantidad'] *
                    $this->platosComanda[$indice]['precio_unitario'];
                $platoExistente = true;
                break;
            }
        }

        // Si no existe, agregarlo como nuevo
        if (!$platoExistente) {
            $this->platosComanda[] = $plato;
        }

        // Recalcula los totales
        $this->actualizarTotalesDePlatos();
    }

    // Método para incrementar la cantidad de un plato
    public function incrementarCantidadPlato($indice)
    {
        if (!isset($this->platosComanda[$indice])) {
            return;
        }

        // Verificar stock disponible antes de incrementar
        $plato = Plato::with(['disponibilidadPlato'])->find($this->platosComanda[$indice]['plato_id']);

        if (
            $plato && $plato->disponibilidadPlato &&
            $plato->disponibilidadPlato->disponibilidad == 1 &&
            $this->platosComanda[$indice]['cantidad'] < $plato->disponibilidadPlato->cantidad
        ) {
            $this->platosComanda[$indice]['cantidad']++;
            $this->platosComanda[$indice]['subtotal'] =
                $this->platosComanda[$indice]['cantidad'] *
                $this->platosComanda[$indice]['precio_unitario'];

            $this->actualizarTotalesDePlatos();
        } else {
            Notification::make()
                ->title('Advertencia')
                ->body('No hay suficiente stock diario disponible')
                ->warning()
                ->send();
        }
    }

    // Método para decrementar la cantidad de un plato
    public function decrementarCantidadPlato($indice)
    {
        if (!isset($this->platosComanda[$indice])) {
            return;
        }

        if ($this->platosComanda[$indice]['cantidad'] > 1) {
            $this->platosComanda[$indice]['cantidad']--;
            $this->platosComanda[$indice]['subtotal'] =
                $this->platosComanda[$indice]['cantidad'] *
                $this->platosComanda[$indice]['precio_unitario'];
        } else {
            // Si la cantidad es 1, remover el plato
            $this->removerPlato($indice);
        }

        $this->actualizarTotalesDePlatos();
    }

    // Método para cambiar entre "para llevar" y "para mesa"
    public function toggleLlevarPlato($indice)
    {
        if (!isset($this->platosComanda[$indice])) {
            return;
        }

        $plato = Plato::find($this->platosComanda[$indice]['plato_id']);

        if (!$plato) {
            return;
        }

        // Cambiar el estado es_llevar
        $this->platosComanda[$indice]['es_llevar'] = !$this->platosComanda[$indice]['es_llevar'];

        // Actualizar el precio según el nuevo estado
        if ($this->platosComanda[$indice]['es_llevar'] && $plato->precio_llevar > 0) {
            $this->platosComanda[$indice]['precio_unitario'] = $plato->precio_llevar;
        } else {
            $this->platosComanda[$indice]['precio_unitario'] = $plato->precio;
        }

        // Actualizar subtotal
        $this->platosComanda[$indice]['subtotal'] =
            $this->platosComanda[$indice]['cantidad'] *
            $this->platosComanda[$indice]['precio_unitario'];

        $this->actualizarTotalesDePlatos();
    }

    // Método para remover un plato
    public function removerPlato($indice)
    {
        if (!isset($this->platosComanda[$indice])) {
            return;
        }

        unset($this->platosComanda[$indice]);
        // Reindexar el array
        $this->platosComanda = array_values($this->platosComanda);

        $this->actualizarTotalesDePlatos();
    }

    // Método para calcular los totales de platos (nombre cambiado para mayor claridad)
    private function actualizarTotalesDePlatos()
    {
        $this->subtotalComanda = 0;

        foreach ($this->platosComanda as $plato) {
            $this->subtotalComanda += $plato['subtotal'];
        }

        // Ya no calculamos IGV aquí, solo el subtotal
        $this->totalComanda = $this->subtotalComanda;

        // Actualizar totales generales
        $this->actualizarTotalesGenerales();
    }

    // Método que recibe una existencia desde el componente hijo
    #[On('existenciaAgregada')]
    public function procesarExistenciaAgregada($existencia)
    {
        // Verificar si la existencia ya está en la comanda con la misma configuración (helado/normal)
        $existenciaExistente = false;
        foreach ($this->existenciasComanda as $indice => $item) {
            if ($item['id'] == $existencia['id'] && $item['es_helado'] == $existencia['es_helado']) {
                // Si existe, incrementar cantidad
                $this->existenciasComanda[$indice]['cantidad']++;
                $this->existenciasComanda[$indice]['subtotal'] =
                    $this->existenciasComanda[$indice]['cantidad'] *
                    $this->existenciasComanda[$indice]['precio_unitario'];
                $existenciaExistente = true;
                break;
            }
        }

        // Si no existe, agregarla como nueva
        if (!$existenciaExistente) {
            $this->existenciasComanda[] = $existencia;
        }

        // Recalcula los totales
        $this->actualizarTotalesDeExistencias();
    }

    // Método para recibir y actualizar la lista completa de existencias
    #[On('existenciasComandaActualizada')]
    public function actualizarExistenciasComanda($existencias)
    {
        $this->existenciasComanda = $existencias;
        $this->actualizarTotalesDeExistencias();
    }

    // Método para incrementar la cantidad de una existencia
    public function incrementarCantidadExistencia($indice)
    {
        if (!isset($this->existenciasComanda[$indice])) {
            return;
        }

        // Verificar si hay suficiente stock
        if ($this->existenciasComanda[$indice]['cantidad'] >= $this->existenciasComanda[$indice]['stock_disponible']) {
            Notification::make()
                ->title('Error de stock')
                ->body("No hay suficiente stock para {$this->existenciasComanda[$indice]['nombre']}")
                ->danger()
                ->send();
            return;
        }

        $this->existenciasComanda[$indice]['cantidad']++;
        $this->existenciasComanda[$indice]['subtotal'] =
            $this->existenciasComanda[$indice]['cantidad'] *
            $this->existenciasComanda[$indice]['precio_unitario'];

        $this->actualizarTotalesDeExistencias();
    }

    // Método para decrementar la cantidad de una existencia
    public function decrementarCantidadExistencia($indice)
    {
        if (!isset($this->existenciasComanda[$indice])) {
            return;
        }

        if ($this->existenciasComanda[$indice]['cantidad'] > 1) {
            $this->existenciasComanda[$indice]['cantidad']--;
            $this->existenciasComanda[$indice]['subtotal'] =
                $this->existenciasComanda[$indice]['cantidad'] *
                $this->existenciasComanda[$indice]['precio_unitario'];
        } else {
            // Si la cantidad es 1, remover la existencia
            $this->removerExistencia($indice);
        }

        $this->actualizarTotalesDeExistencias();
    }

    // Método para cambiar una existencia de producto entre helado y normal
    public function toggleHeladoExistencia($indice)
    {
        if (!isset($this->existenciasComanda[$indice]) || !$this->existenciasComanda[$indice]['es_producto']) {
            return;
        }

        $this->existenciasComanda[$indice]['es_helado'] = !$this->existenciasComanda[$indice]['es_helado'];

        // Actualizar precios si fuera necesario en el futuro
        // Por ahora mantenemos el mismo precio

        $this->actualizarTotalesDeExistencias();
    }

    // Método para remover una existencia
    public function removerExistencia($indice)
    {
        if (!isset($this->existenciasComanda[$indice])) {
            return;
        }

        unset($this->existenciasComanda[$indice]);
        // Reindexar el array
        $this->existenciasComanda = array_values($this->existenciasComanda);

        $this->actualizarTotalesDeExistencias();
    }

    // Método para calcular los totales de existencias (nombre cambiado para mayor claridad)
    private function actualizarTotalesDeExistencias()
    {
        $this->subtotalExistencias = 0;

        foreach ($this->existenciasComanda as $existencia) {
            $this->subtotalExistencias += $existencia['subtotal'];
        }

        // Ya no calculamos IGV aquí, solo el subtotal
        $this->totalExistencias = $this->subtotalExistencias;

        // Actualizar totales generales
        $this->actualizarTotalesGenerales();
    }

    // Método para calcular los totales generales combinando platos y existencias (nombre cambiado para mayor claridad)
    private function actualizarTotalesGenerales()
    {
        // Suma de subtotales
        $this->subtotalGeneral = $this->subtotalComanda + $this->subtotalExistencias;

        // Aplicar descuento si existe
        $montoConDescuento = $this->subtotalGeneral - $this->descuentoGeneral;

        // Dividir para sacar base imponible y IGV (18%)
        // La base imponible es el monto sin IGV (montoConDescuento / 1.18)
        $baseImponible = $montoConDescuento / 1.18;

        // El IGV es la diferencia entre el monto con descuento y la base imponible
        $this->igvGeneral = $montoConDescuento - $baseImponible;

        // Total general ya incluye el IGV
        $this->totalGeneral = $montoConDescuento;
    }

    // Método para guardar la comanda con todos sus ítems

    /**
     * Valida los datos necesarios para guardar la comanda
     * @return array ['valido' => bool, 'mensaje' => string]
     */
    /**
     * Valida los datos necesarios para guardar la comanda
     * @return array ['valido' => bool, 'mensaje' => string]
     */
    private function validarDatosComanda()
    {
        $resultado = [
            'valido' => true,
            'mensaje' => ''
        ];

        // Validar que haya al menos un producto en la comanda (plato o existencia)
        if (count($this->platosComanda) == 0 && count($this->existenciasComanda) == 0) {
            $resultado['valido'] = false;
            $resultado['mensaje'] = 'No hay productos seleccionados para guardar la comanda';
            return $resultado;
        }

        // Validar cliente obligatorio para cualquier tipo de comanda
        if (empty($this->id_cliente)) {
            $resultado['valido'] = false;
            $resultado['mensaje'] = 'Debe seleccionar un cliente para guardar la comanda';
            return $resultado;
        }

        // Validar mesa y zona solo si hay platos para servir en restaurante
        if (count($this->platosComanda) > 0) {
            $hayPlatosParaMesa = false;
            foreach ($this->platosComanda as $plato) {
                if (!$plato['es_llevar']) {
                    $hayPlatosParaMesa = true;
                    break;
                }
            }

            if ($hayPlatosParaMesa && (empty($this->id_mesa) || empty($this->id_zona))) {
                $resultado['valido'] = false;
                $resultado['mensaje'] = 'Debe seleccionar una mesa cuando hay platos para servir en restaurante';
                return $resultado;
            }
        }

        return $resultado;
    }

    /**
     * Limpia solo los campos necesarios después de guardar la comanda
     */
    private function limpiarCamposComanda()
    {
        // Limpiar productos
        $this->platosComanda = [];
        $this->existenciasComanda = [];

        // Limpiar totales
        $this->subtotalComanda = 0;
        $this->totalComanda = 0;
        $this->subtotalExistencias = 0;
        $this->totalExistencias = 0;
        $this->subtotalGeneral = 0;
        $this->igvGeneral = 0;
        $this->descuentoGeneral = 0;
        $this->totalGeneral = 0;

        // Opcional: limpiar mesa/zona si se desea
        $this->id_mesa = '';
        $this->id_zona = '';
        $this->numero_mesa = '';
        $this->nombre_zona = '';

        // No limpiamos datos del cliente para facilitar múltiples comandas al mismo cliente

        // Calcular nuevo número de pedido
        $this->calcularNumeroPedido();
    }


    public function guardarComanda()
    {
        // Validar los datos
        $validacion = $this->validarDatosComanda();

        if (!$validacion['valido']) {
            Notification::make()
                ->title('Error de validación')
                ->body($validacion['mensaje'])
                ->danger()
                ->send();
            return;
        }

        try {
            // Iniciar transacción
            DB::beginTransaction();

            // Crear comanda
            $comanda = new Comanda();
            $comanda->cliente_id = $this->id_cliente;

            // Solo asignar zona y mesa si no es todo para llevar
            $todoParaLlevar = true;
            foreach ($this->platosComanda as $plato) {
                if (!$plato['es_llevar']) {
                    $todoParaLlevar = false;
                    break;
                }
            }

            if (!empty($this->id_mesa) && !empty($this->id_zona)) {
                // Verificar si la mesa está libre
                $mesa = Mesa::find($this->id_mesa);

                if (!$mesa) {
                    throw new \Exception('La mesa seleccionada no existe');
                }

                if ($mesa->estado !== 'Libre') {
                    Notification::make()
                        ->title('Mesa no disponible')
                        ->body('La mesa seleccionada no está disponible. Por favor seleccione otra mesa.')
                        ->warning()
                        ->send();

                    DB::rollBack();
                    return;
                }

                // Asignar mesa y zona a la comanda
                $comanda->zona_id = $this->id_zona;
                $comanda->mesa_id = $this->id_mesa;

                // Actualizar el estado de la mesa a "Ocupada"
                $mesa->estado = 'Ocupada';
                $mesa->save();
            }

            $comanda->save();

            // Guardar platos de la comanda, agrupando por plato_id y es_llevar
            $platosAgrupados = [];

            // Agrupar platos por plato_id y es_llevar
            foreach ($this->platosComanda as $plato) {
                $key = $plato['plato_id'] . '_' . ($plato['es_llevar'] ? '1' : '0');

                if (!isset($platosAgrupados[$key])) {
                    $platosAgrupados[$key] = [
                        'plato_id' => $plato['plato_id'],
                        'es_llevar' => $plato['es_llevar'],
                        'precio_unitario' => $plato['precio_unitario'],
                        'cantidad' => 0,
                        'subtotal' => 0
                    ];
                }

                // Sumamos la cantidad y subtotal al grupo correspondiente
                $platosAgrupados[$key]['cantidad'] += $plato['cantidad'];
                $platosAgrupados[$key]['subtotal'] += $plato['subtotal'];
            }

            // Guardar cada plato agrupado como un registro individual
            foreach ($platosAgrupados as $plato) {
                $comandaPlato = new ComandaPlato();
                $comandaPlato->comanda_id = $comanda->id;
                $comandaPlato->plato_id = $plato['plato_id'];
                $comandaPlato->cantidad = $plato['cantidad'];
                $comandaPlato->subtotal = $plato['subtotal'];
                $comandaPlato->llevar = $plato['es_llevar']; // Cambiado a 'llevar' según la estructura de tabla
                $comandaPlato->save();
            }

            // Guardar existencias de la comanda, agrupando por id y es_helado
            $existenciasAgrupadas = [];

            // Agrupar existencias por id y es_helado
            foreach ($this->existenciasComanda as $existencia) {
                $key = $existencia['id'] . '_' . ($existencia['es_helado'] ? '1' : '0');

                if (!isset($existenciasAgrupadas[$key])) {
                    $existenciasAgrupadas[$key] = [
                        'id' => $existencia['id'],
                        'es_helado' => $existencia['es_helado'],
                        'precio_unitario' => $existencia['precio_unitario'],
                        'cantidad' => 0,
                        'subtotal' => 0
                    ];
                }

                // Sumamos la cantidad y subtotal al grupo correspondiente
                $existenciasAgrupadas[$key]['cantidad'] += $existencia['cantidad'];
                $existenciasAgrupadas[$key]['subtotal'] += $existencia['subtotal'];
            }

            // Guardar cada existencia agrupada como un registro individual
            foreach ($existenciasAgrupadas as $existencia) {
                $comandaExistencia = new ComandaExistencia();
                $comandaExistencia->comanda_id = $comanda->id;
                $comandaExistencia->existencia_id = $existencia['id'];
                $comandaExistencia->cantidad = $existencia['cantidad'];
                $comandaExistencia->subtotal = $existencia['subtotal'];
                $comandaExistencia->helado = $existencia['es_helado'];
                $comandaExistencia->save();
            }

            // Confirmar transacción
            DB::commit();

            // Mostrar mensaje de éxito con Filament Notification
            Notification::make()
                ->title('Comanda guardada')
                ->body('La comanda N° ' . $this->numeroPedido . ' se ha guardado correctamente')
                ->success()
                ->send();

            // Limpiar solo los campos necesarios
            $this->limpiarCamposComanda();
        } catch (\Exception $e) {
            // Revertir transacción en caso de error
            DB::rollBack();

            // Mostrar mensaje de error con Filament Notification
            Notification::make()
                ->title('Error al guardar')
                ->body('Error al guardar la comanda: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
