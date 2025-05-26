<?php

namespace App\Livewire\Comanda;

use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Comanda;
use App\Models\ComprobantePago;
use App\Models\Empresa;
use App\Models\TipoComprobante;
use App\Models\MovimientoCaja;
use App\Models\SesionCaja;
use App\Http\Controllers\SunatController;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class GenerarComprobante extends Component
{
    public $comandaId;
    public $comanda;
    public $cajaId;
    public $sesionCajaId;
    public $cliente;
    public $clienteEncontrado = false;

    // Datos del comprobante
    public $tipoComprobantes = [];
    public $tipoComprobanteSeleccionado = '';
    public $serieComprobante = '';
    public $numeroPedido = '';
    public $monedaSelecionada = 'PEN';
    public $formaPago = 'Contado';

    // Totales
    public $subtotalGeneral = 0;
    public $igvGeneral = 0;
    public $totalGeneral = 0;

    // Detalles de la comanda
    public $platosComanda = [];
    public $existenciasComanda = [];

    // Errores y estados
    public $procesando = false;
    public $mensajeError = '';

    public function mount($comandaId)
    {
        $this->comandaId = $comandaId;
        $this->cargarComanda();
        $this->cargarTipoComprobantes();
        $this->buscarSesionCajaActiva();
    }

    protected function cargarComanda()
    {
        $this->comanda = Comanda::with([
            'cliente',
            'cliente.tipoDocumento',
            'zona',
            'mesa',
            'comandaPlatos.plato.unidadMedida',
            'comandaExistencias.existencia.unidadMedida',
            'caja'
        ])->find($this->comandaId);

        if (!$this->comanda) {
            $this->dispatch('notify', ['message' => 'Comanda no encontrada', 'type' => 'error']);
            $this->dispatch('volverALista');
            return;
        }

        // Cargar datos básicos
        $this->cajaId = $this->comanda->caja_id;
        $this->cliente = $this->comanda->cliente;
        $this->clienteEncontrado = !!$this->cliente;
        $this->subtotalGeneral = $this->comanda->subtotal;
        $this->igvGeneral = $this->comanda->igv;
        $this->totalGeneral = $this->comanda->total_general;


        // Si hay cliente, seleccionar automáticamente el tipo de comprobante según tipo de documento
        if ($this->cliente) {
            if ($this->cliente->ruc || ($this->cliente->tipoDocumento && $this->cliente->tipoDocumento->tipo == '6')) {
                $this->tipoComprobanteSeleccionado = '01'; // Factura
            } else {
                $this->tipoComprobanteSeleccionado = '03'; // Boleta
            }
        }

        // Cargar platos de la comanda
        $this->platosComanda = $this->comanda->comandaPlatos->map(function ($item) {
            return [
                'id' => $item->plato_id,
                'nombre' => $item->plato->nombre,
                'unidad_medida' => $item->plato->unidadMedida->nombre ?? 'Unidad',
                'unidad_medida_codigo' => $item->plato->unidadMedida->codigo ?? 'NIU',
                'precio_unitario' => $item->precio_unitario,
                'cantidad' => $item->cantidad,
                'subtotal' => $item->subtotal,
                'es_llevar' => $item->llevar
            ];
        })->toArray();

        // Cargar existencias de la comanda
        $this->existenciasComanda = $this->comanda->comandaExistencias->map(function ($item) {
            return [
                'id' => $item->existencia_id,
                'nombre' => $item->existencia->nombre,
                'unidad_medida' => $item->existencia->unidadMedida->nombre ?? 'Unidad',
                'unidad_medida_codigo' => $item->existencia->unidadMedida->codigo ?? 'NIU',
                'precio_unitario' => $item->precio_unitario,
                'cantidad' => $item->cantidad,
                'subtotal' => $item->subtotal,
                'es_helado' => $item->helado
            ];
        })->toArray();
    }

    protected function cargarTipoComprobantes()
    {
        // Cargar solo tipos de comprobante activos
        $this->tipoComprobantes = TipoComprobante::where('estado', true)->get();
    }

    protected function buscarSesionCajaActiva()
    {
        if ($this->cajaId) {
            $sesionCaja = SesionCaja::where('caja_id', $this->cajaId)
                ->where('estado', true)
                ->latest()
                ->first();

            if ($sesionCaja) {
                $this->sesionCajaId = $sesionCaja->id;
            }
        }
    }

    public function updatedTipoComprobanteSeleccionado()
    {
        // Asignar serie según tipo de comprobante
        if ($this->tipoComprobanteSeleccionado == '01') { // Factura
            $this->serieComprobante = 'F00' . $this->cajaId;
        } elseif ($this->tipoComprobanteSeleccionado == '03') { // Boleta
            $this->serieComprobante = 'B00' . $this->cajaId;
        } elseif ($this->tipoComprobanteSeleccionado == '00') { // Nota de venta interna
            $this->serieComprobante = 'T00' . $this->cajaId;
        } else {
            $this->serieComprobante = '';
        }

        // Calcular y asignar el número de pedido
        if ($this->tipoComprobanteSeleccionado) {
            $this->numeroPedido = $this->calcularNumeroComprobante();
        } else {
            $this->numeroPedido = '';
        }
    }

    protected function obtenerTipoComprobanteId($codigo)
    {
        $tipoComprobante = TipoComprobante::where('codigo', $codigo)->first();
        return $tipoComprobante ? $tipoComprobante->id : null;
    }

    // Función para convertir número a letras (versión simplificada)
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
    // Método modificado para generar el número de comprobante correlativo
    protected function calcularNumeroComprobante()
    {
        // Obtener el ID del tipo de comprobante
        $tipoComprobanteId = $this->obtenerTipoComprobanteId($this->tipoComprobanteSeleccionado);

        // Contar cuántos comprobantes existen con el mismo tipo y serie
        $ultimoComprobante = ComprobantePago::where('tipo_comprobante_id', $tipoComprobanteId)
            ->where('serie', $this->serieComprobante)
            ->orderBy('numero', 'desc')
            ->first();

        if ($ultimoComprobante) {
            // Si existe al menos un comprobante, tomar su número y sumar 1
            $ultimoNumero = (int)$ultimoComprobante->numero;
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            // Si no hay comprobantes previos, comenzar en 1
            $nuevoNumero = 1;
        }

        // Formatear el número con ceros a la izquierda (8 dígitos)
        return str_pad($nuevoNumero, 6, '0', STR_PAD_LEFT);
    }

    public function generarComprobante()
    {
        // Validaciones básicas
        if ($this->tipoComprobanteSeleccionado == null || $this->tipoComprobanteSeleccionado == '') {
            Notification::make()
                ->title('Error de validación')
                ->body('Debe seleccionar un tipo de comprobante.')
                ->danger()
                ->send();
            return;
        }

        if ($this->monedaSelecionada == null || $this->monedaSelecionada == '') {
            Notification::make()
                ->title('Error de validación')
                ->body('Debe seleccionar una moneda.')
                ->danger()
                ->send();
            return;
        }

        if ($this->formaPago == null || $this->formaPago == '') {
            Notification::make()
                ->title('Error de validación')
                ->body('Debe seleccionar una forma de pago.')
                ->danger()
                ->send();
            return;
        }

        if (!$this->cliente) {
            Notification::make()
                ->title('Error de validación')
                ->body('Debe seleccionar un cliente para generar el comprobante.')
                ->danger()
                ->send();
            return;
        }

        // Validar tipo de documento para factura
        if ($this->tipoComprobanteSeleccionado == '01') { // Factura
            if ($this->cliente->tipoDocumento && $this->cliente->tipoDocumento->tipo == '1') { // DNI
                Notification::make()
                    ->title('Error de validación')
                    ->body('El cliente con DNI no puede recibir factura. Seleccione boleta u otro tipo de comprobante.')
                    ->danger()
                    ->send();
                return;
            }

            if (!$this->cliente->ruc && (!$this->cliente->tipoDocumento || $this->cliente->tipoDocumento->tipo != '6')) {
                Notification::make()
                    ->title('Error de validación')
                    ->body('El cliente debe tener RUC para recibir factura.')
                    ->danger()
                    ->send();
                return;
            }
        }

        // Validar monto
        if ($this->totalGeneral <= 0) {
            Notification::make()
                ->title('Monto inválido')
                ->body('El monto debe ser mayor a cero.')
                ->warning()
                ->send();
            return;
        }

        // Validar sesión de caja
        if (!$this->sesionCajaId) {
            Notification::make()
                ->title('Error al registrar movimiento')
                ->body('No hay una sesión de caja activa. Por favor, abra la caja primero.')
                ->danger()
                ->send();
            return;
        }

        $this->procesando = true;

        try {
            DB::beginTransaction();

            // Generar serie y número
            $numero = $this->calcularNumeroComprobante();
            $serie = $this->serieComprobante;
            $nroComprobante = $serie . '-' . $numero;

            // Actualizar el estado de la comanda a Pagada
            $this->comanda->update([
                'estado_pago' => 'Pagada'
            ]);

            // Determinar si es comprobante electrónico (Factura o Boleta) o interno
            $esComprobanteElectronico = in_array($this->tipoComprobanteSeleccionado, ['01', '03']);

            if ($esComprobanteElectronico) {
                // Proceso para comprobantes electrónicos (Factura/Boleta)
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
                        "txtUNIDAD_MEDIDA_DET" => (string)str_replace(' ', '', $plato['unidad_medida_codigo']),
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
                        "txtUNIDAD_MEDIDA_DET" => (string)str_replace(' ', '', $existencia['unidad_medida_codigo']),
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

                // Crear el objeto para enviar a SunatController
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
                    "txtCOD_TIPO_DOCUMENTO" => (string)str_replace(' ', '', $this->tipoComprobanteSeleccionado),
                    "txtCOD_MONEDA" => (string)$this->monedaSelecionada,
                    "detalle_forma_pago" => [
                        [
                            "COD_FORMA_PAGO" => (string)$this->formaPago
                        ]
                    ],
                    "txtNRO_DOCUMENTO_CLIENTE" => $this->cliente->ruc ?? $this->cliente->numero_documento,
                    "txtRAZON_SOCIAL_CLIENTE" => $this->cliente->razon_social ?? $this->cliente->nombre,
                    "txtTIPO_DOCUMENTO_CLIENTE" => $this->cliente->tipoDocumento->tipo ?? "1",
                    "txtDIRECCION_CLIENTE" => $this->cliente->direccion ?? " ",
                    "txtCIUDAD_CLIENTE" => $this->cliente->ciudad ?? " ",
                    "txtCOD_PAIS_CLIENTE" => "PE",
                    "txtNRO_DOCUMENTO_EMPRESA" => (string)$empresa->ruc ?? "11",
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

                // Instanciar el SunatController
                $sunatController = new SunatController();

                // Enviar datos al SunatController
                $resultado = $sunatController->enviarCpe($datos);

                // Verificar si la respuesta fue exitosa
                if ($resultado['success']) {
                    // Extraer los datos de respuesta
                    $responseData = $resultado['data'];

                    $hashCpe = $responseData['hash_cpe'] ?? null;
                    $codSunat = $responseData['cod_sunat'] ?? null;
                    $msjSunat = $responseData['msj_sunat'] ?? null;
                    $hashCdr = $responseData['hash_cdr'] ?? null;
                    $xmlCpeContent = $responseData['xml_cpe'] ?? null;
                    $xmlCdrContent = $responseData['xml_cdr'] ?? null;

                    $tipoComprobanteId = $this->obtenerTipoComprobanteId($this->tipoComprobanteSeleccionado);

                    // Crear el registro del comprobante en la base de datos
                    $comprobante = ComprobantePago::create([
                        'tipo_comprobante_id' => $tipoComprobanteId,
                        'serie' => $serie,
                        'numero' => $numero,
                        'cliente_id' => $this->cliente->id,
                        'comanda_id' => $this->comandaId,
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

                    // Crear el movimiento en caja
                    MovimientoCaja::create([
                        'user_id' => Auth::id(),
                        'sesion_caja_id' => $this->sesionCajaId,
                        'tipo_transaccion' => 'Ingreso',
                        'motivo' => 'Venta',
                        'monto' => $this->totalGeneral,
                        'descripcion' => ($this->tipoComprobanteSeleccionado == '01' ? 'Factura: ' : 'Boleta: ') . $nroComprobante,
                    ]);

                    DB::commit();

                    // Notificar éxito
                    Notification::make()
                        ->title('Facturación exitosa')
                        ->body($msjSunat ?? 'El comprobante ha sido procesado correctamente')
                        ->success()
                        ->send();

                    $this->procesando = false;
                    $this->dispatch('comprobanteGenerado');
                } else {
                    // Error en la respuesta del SunatController
                    DB::rollBack();

                    Notification::make()
                        ->title('Error en facturación electrónica')
                        ->body('Error al procesar el comprobante: ' . ($resultado['message'] ?? 'Error desconocido'))
                        ->danger()
                        ->send();

                    $this->procesando = false;
                }
            } else {
                // Comprobante interno (no electrónico)
                // Buscamos el ID del tipo de comprobante (nota de venta o interno, código 00)
                $tipoComprobanteId = $this->obtenerTipoComprobanteId($this->tipoComprobanteSeleccionado);

                if (!$tipoComprobanteId) {
                    // Si no existe el tipo de comprobante
                    DB::rollBack();

                    Notification::make()
                        ->title('Error')
                        ->body('Tipo de comprobante no encontrado.')
                        ->danger()
                        ->send();

                    $this->procesando = false;
                    return;
                }

                // Crear el comprobante sin datos de facturación electrónica
                $comprobante = ComprobantePago::create([
                    'tipo_comprobante_id' => $tipoComprobanteId,
                    'serie' => $serie,
                    'numero' => $numero,
                    'cliente_id' => $this->cliente->id,
                    'comanda_id' => $this->comandaId,
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

                // Crear el movimiento en caja
                MovimientoCaja::create([
                    'user_id' => Auth::id(),
                    'sesion_caja_id' => $this->sesionCajaId,
                    'tipo_transaccion' => 'Ingreso',
                    'motivo' => 'Venta',
                    'monto' => $this->totalGeneral,
                    'descripcion' => 'Nota de venta: ' . $nroComprobante,
                ]);

                DB::commit();

                // Notificar éxito
                Notification::make()
                    ->title('Comprobante interno registrado')
                    ->body('El comprobante interno ha sido registrado correctamente')
                    ->success()
                    ->send();

                $this->procesando = false;
                $this->dispatch('comprobanteGenerado');
            }
        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Error')
                ->body('Ocurrió un error: ' . $e->getMessage())
                ->danger()
                ->send();

            $this->procesando = false;
        }
    }

    public function cancelar()
    {
        $this->dispatch('comprobanteGenerado');
    }

    public function render()
    {
        return view('livewire.comanda.generar-comprobante');
    }
}
