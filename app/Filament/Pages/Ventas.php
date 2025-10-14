<?php

namespace App\Filament\Pages;

use App\Http\Controllers\SunatController;
use App\Models\ComprobantePago;
use App\Models\TipoComprobante;
use App\Models\Comanda;
use App\Models\ComandaExistencia;
use App\Models\ComandaPlato;
use App\Models\Empresa;
use Barryvdh\DomPDF\Facade\Pdf;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Response;
use SimpleXMLElement;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Exception;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;

class Ventas extends Page implements HasTable
{
    use InteractsWithTable, HasPageShield;


    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Registro de Ventas';
    protected static ?string $title = 'Registro de Ventas';
    protected static ?string $navigationGroup = 'Ventas';
    //protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.ventas';

    protected static ?int $navigationSort = 2;

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ComprobantePago::query()->orderBy('created_at', 'desc')
            )
            ->columns([
                TextColumn::make('serie')
                    ->label('Serie')
                    ->searchable(),
                TextColumn::make('numero')
                    ->label('Número')
                    ->searchable(),
                TextColumn::make('cliente.nombre')
                    ->label('Cliente')
                    ->searchable(),
                TextColumn::make('cliente.numero_documento')
                    ->label('N° documento')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Fecha Emisión')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([

                // Action::make('convertir_a_electronico')
                //     ->label('Convertir a Electrónico')
                //     ->icon('heroicon-o-arrow-path-rounded-square')
                //     ->color('success')
                //     ->form([
                //         Select::make('tipo_comprobante_id')
                //             ->label('Tipo de Comprobante')
                //             ->options(function (ComprobantePago $record) {
                //                 return TipoComprobante::whereIn('codigo', ['01', '03'])->pluck('descripcion', 'id');
                //             })
                //             ->required(),
                //         TextInput::make('serie')
                //             ->label('Serie')
                //             ->placeholder('Ejemplo: F001 o B001')
                //             ->required()
                //             ->maxLength(10),
                //         TextInput::make('numero')
                //             ->label('Número')
                //             ->placeholder('Ejemplo: 00000123')
                //             ->required()
                //             ->maxLength(20),
                //     ])
                //     ->action(function (array $data, ComprobantePago $record): void {
                //         $this->convertirTicket($data, $record);
                //     })
                //     ->visible(function (ComprobantePago $record): bool {
                //         // Solo mostrar para tickets (comprobantes no electrónicos)
                //         return $record->tipoComprobante->codigo == '00';
                //     }),

                // Acción para vista previa de PDF usando XML
                Action::make('preview_pdf')
                    ->label('Vista Previa PDF')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading('Vista previa del comprobante')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->modalContent(function (ComprobantePago $record) {
                        if ($record->xml_cpe !== null) {
                            // Usar XML para generar el PDF
                            $xmlData = $this->parseXmlCpe($record->xml_cpe);
                            $xmlData['fechaEmision'] = $record->created_at->format('Y-m-d H:i:s');
                            $qrCode = $this->generarQrCode($xmlData);
                            $pdf = Pdf::loadView('tickets.pdf-template', [
                                'comprobante' => $record,
                                'xmlData' => $xmlData,
                                'qrCode' => $qrCode,
                            ]);
                        } else {
                            // Usar base de datos para generar el PDF (sin QR)
                            $datosComprobante = $this->obtenerDatosDeBaseDatos($record);
                            $pdf = Pdf::loadView('tickets.pdf-db-template', [
                                'comprobante' => $record,
                                'datos' => $datosComprobante,
                                'qrCode' => null, // No incluimos QR para comprobantes sin XML
                            ]);
                        }

                        // Convertir PDF a base64 para visualización
                        $pdfContent = $pdf->output();
                        $base64Pdf = base64_encode($pdfContent);

                        return view('tickets.pdf-preview', [
                            'base64Pdf' => $base64Pdf,
                            'record' => $record,
                        ]);
                    })
                    ->action(function () {
                        // No necesita acción, solo muestra el modal
                    })
                    ->extraModalFooterActions([
                        Action::make('download_pdf')
                            ->label('Descargar PDF')
                            ->color('primary')
                            ->action(function (ComprobantePago $record) {
                                if ($record->xml_cpe !== null) {
                                    // Usar XML para generar el PDF
                                    $xmlData = $this->parseXmlCpe($record->xml_cpe);
                                    $xmlData['fechaEmision'] = $record->created_at->format('Y-m-d H:i:s');
                                    $qrCode = $this->generarQrCode($xmlData);
                                    $pdf = Pdf::loadView('tickets.pdf-template', [
                                        'comprobante' => $record,
                                        'xmlData' => $xmlData,
                                        'qrCode' => $qrCode,
                                    ]);
                                } else {
                                    // Usar base de datos para generar el PDF (sin QR)
                                    $datosComprobante = $this->obtenerDatosDeBaseDatos($record);
                                    $pdf = Pdf::loadView('tickets.pdf-db-template', [
                                        'comprobante' => $record,
                                        'datos' => $datosComprobante,
                                        'qrCode' => null, // No incluimos QR para comprobantes sin XML
                                    ]);
                                }

                                return Response::streamDownload(
                                    fn() => print($pdf->output()),
                                    "comprobante-{$record->serie}-{$record->numero}.pdf"
                                );
                            })
                    ]),

                // Acción para descargar PDF
                Action::make('download')
                    ->label('Descargar PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('primary')
                    ->action(function (ComprobantePago $record) {
                        if ($record->xml_cpe !== null) {
                            // Usar XML para generar el PDF
                            $xmlData = $this->parseXmlCpe($record->xml_cpe);
                            $xmlData['fechaEmision'] = $record->created_at->format('Y-m-d H:i:s');
                            $qrCode = $this->generarQrCode($xmlData);
                            $pdf = Pdf::loadView('tickets.pdf-template', [
                                'comprobante' => $record,
                                'xmlData' => $xmlData,
                                'qrCode' => $qrCode,
                            ]);
                        } else {
                            // Usar base de datos para generar el PDF (sin QR)
                            $datosComprobante = $this->obtenerDatosDeBaseDatos($record);
                            $pdf = Pdf::loadView('tickets.pdf-db-template', [
                                'comprobante' => $record,
                                'datos' => $datosComprobante,
                                'qrCode' => null, // No incluimos QR para comprobantes sin XML
                            ]);
                        }

                        return response()->streamDownload(
                            fn() => print($pdf->output()),
                            "comprobante-{$record->serie}-{$record->numero}.pdf"
                        );
                    })
                    ->visible(fn(ComprobantePago $record): bool => $record->xml_cpe !== null || $record->xml_cpe === null)
            ])
            ->filters([
                SelectFilter::make('tipo_comprobante_id')
                    ->label('Tipo de Comprobante')
                    ->options(TipoComprobante::pluck('descripcion', 'id')),
            ])
            ->bulkActions([
                //
            ]);
    }

    /**
     * Obtiene los datos del comprobante desde la base de datos
     *
     * @param ComprobantePago $comprobante
     * @return array
     */
    protected function obtenerDatosDeBaseDatos(ComprobantePago $comprobante): array
    {
        // Cargar relaciones necesarias
        $comprobante->load(['cliente', 'comanda', 'user', 'caja', 'tipoComprobante']);

        // Obtener los detalles de la comanda (platos y existencias)
        $comanda = $comprobante->comanda;
        $comanda->load(['comandaPlatos.plato', 'comandaExistencias.existencia']);

        $empresa = Empresa::first();
        // Crear array con los datos en formato similar al XML
        $datosComprobante = [
            'numeroComprobante' => $comprobante->serie . '-' . $comprobante->numero,
            'fechaEmision' => $comprobante->created_at->format('Y-m-d H:i:s'),
            'tipoComprobante' => $comprobante->tipoComprobante->codigo_sunat ?? $comprobante->tipo_comprobante_id,
            'moneda' => $comprobante->moneda,
            'importeLetras' => $this->numeroALetras($comanda->total_general),

            // Información de la empresa (deberías obtenerla desde la configuración)
            'empresa' => [
                'ruc' => $empresa->ruc,
                'razonSocial' => $empresa->nombre,
                'nombreComercial' => $empresa->nombre_comercial,
                'direccion' => $empresa->direccion,
                'distrito' => $empresa->distrito,
                'provincia' => $empresa->provincia,
                'departamento' => $empresa->departamento,
            ],

            // Información del cliente
            'cliente' => [
                'tipoDoc' => $comprobante->cliente->tipo_documento, // Asume que existe este campo
                'numDoc' => $comprobante->cliente->numero_documento, // Asume que existe este campo
                'razonSocial' => $comprobante->cliente->nombre,
                'direccion' => $comprobante->cliente->direccion ?? '',
            ],

            // Información de pago
            'medioPago' => $comprobante->medio_pago,
            'formaPago' => 'Contado', // Asume que es contado por defecto, ajustar según necesidad

            // Totales
            'subtotal' => number_format($comanda->subtotal, 2, '.', ''),
            'igv' => number_format($comanda->igv, 2, '.', ''),
            'total' => number_format($comanda->total_general, 2, '.', ''),

            // Detalle de items
            'items' => [],
        ];

        // Agregar platos como items
        foreach ($comanda->comandaPlatos as $comandaPlato) {
            $datosComprobante['items'][] = [
                'cantidad' => $comandaPlato->cantidad,
                'unidad' => 'NIU', // NIU = Unidad (no en el Sistema Internacional de Unidades)
                'descripcion' => $comandaPlato->plato->nombre,
                'codigo' => $comandaPlato->plato->id,
                'precioUnitario' => number_format($comandaPlato->precio_unitario / 1.18, 2, '.', ''), // Valor sin IGV
                'precioVenta' => number_format($comandaPlato->precio_unitario, 2, '.', ''), // Valor con IGV
                'subtotal' => number_format($comandaPlato->subtotal / 1.18, 2, '.', ''), // Subtotal sin IGV
                'igv' => number_format($comandaPlato->subtotal - ($comandaPlato->subtotal / 1.18), 2, '.', ''), // IGV
            ];
        }

        // Agregar existencias como items
        foreach ($comanda->comandaExistencias as $comandaExistencia) {
            $datosComprobante['items'][] = [
                'cantidad' => $comandaExistencia->cantidad,
                'unidad' => 'NIU', // NIU = Unidad
                'descripcion' => $comandaExistencia->existencia->nombre,
                'codigo' => $comandaExistencia->existencia->id,
                'precioUnitario' => number_format($comandaExistencia->precio_unitario / 1.18, 2, '.', ''), // Valor sin IGV
                'precioVenta' => number_format($comandaExistencia->precio_unitario, 2, '.', ''), // Valor con IGV
                'subtotal' => number_format($comandaExistencia->subtotal / 1.18, 2, '.', ''), // Subtotal sin IGV
                'igv' => number_format($comandaExistencia->subtotal - ($comandaExistencia->subtotal / 1.18), 2, '.', ''), // IGV
            ];
        }

        return $datosComprobante;
    }

    /**
     * Esta función convierte un número a su representación en letras
     *
     * @param float $numero
     * @return string
     */
    protected function numeroALetras($numero): string
    {
        // Implementa la lógica para convertir números a letras
        // Esta es una implementación muy básica, deberías usar una librería más completa
        $formatter = new \NumberFormatter('es', \NumberFormatter::SPELLOUT);
        $letras = strtoupper($formatter->format($numero));
        return $letras . ' CON ' . substr(number_format($numero, 2, '.', ''), -2) . '/100 SOLES';
    }

    protected function parseXmlCpe(?string $xmlContent): ?array
    {
        // Código original sin cambios
        if (!$xmlContent) {
            return null;
        }

        try {
            $xml = new SimpleXMLElement($xmlContent);

            // Definir los namespaces para la búsqueda
            $namespaces = $xml->getNamespaces(true);
            $ns = $xml->getNamespaces(true);
            $xml->registerXPathNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
            $xml->registerXPathNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');

            // Extraer datos generales
            $data = [
                'numeroComprobante' => (string)$xml->xpath('//cbc:ID')[0] ?? '',
                'fechaEmision' => (string)$xml->xpath('//cbc:IssueDate')[0] ?? '',
                'tipoComprobante' => (string)$xml->xpath('//cbc:InvoiceTypeCode')[0] ?? '',
                'moneda' => (string)$xml->xpath('//cbc:DocumentCurrencyCode')[0] ?? '',
                'importeLetras' => (string)$xml->xpath('//cbc:Note[@languageLocaleID="1000"]')[0] ?? '',

                // Información de la empresa
                'empresa' => [
                    'ruc' => (string)$xml->xpath('//cac:AccountingSupplierParty//cbc:ID')[0] ?? '',
                    'razonSocial' => (string)$xml->xpath('//cac:AccountingSupplierParty//cbc:RegistrationName')[0] ?? '',
                    'nombreComercial' => (string)$xml->xpath('//cac:AccountingSupplierParty//cbc:Name')[0] ?? '',
                    'direccion' => (string)$xml->xpath('//cac:AccountingSupplierParty//cbc:Line')[0] ?? '',
                    'distrito' => (string)$xml->xpath('//cac:AccountingSupplierParty//cbc:District')[0] ?? '',
                    'provincia' => (string)$xml->xpath('//cac:AccountingSupplierParty//cbc:CountrySubentity')[0] ?? '',
                    'departamento' => (string)$xml->xpath('//cac:AccountingSupplierParty//cbc:CityName')[0] ?? '',
                ],

                // Información del cliente
                'cliente' => [
                    'tipoDoc' => (string)$xml->xpath('//cac:AccountingCustomerParty//cbc:ID/@schemeID')[0] ?? '',
                    'numDoc' => (string)$xml->xpath('//cac:AccountingCustomerParty//cbc:ID')[0] ?? '',
                    'razonSocial' => (string)$xml->xpath('//cac:AccountingCustomerParty//cbc:RegistrationName')[0] ?? '',
                    'direccion' => (string)$xml->xpath('//cac:AccountingCustomerParty//cbc:Line')[0] ?? '',
                ],

                // Información de pago
                'medioPago' => (string)$xml->xpath('//cac:PaymentMeans//cbc:PaymentMeansCode')[0] ?? '',
                'formaPago' => (string)$xml->xpath('//cac:PaymentTerms//cbc:PaymentMeansID')[0] ?? '',

                // Totales
                'subtotal' => (string)$xml->xpath('//cac:LegalMonetaryTotal//cbc:LineExtensionAmount')[0] ?? '',
                'igv' => (string)$xml->xpath('//cac:TaxTotal//cbc:TaxAmount')[0] ?? '',
                'total' => (string)$xml->xpath('//cac:LegalMonetaryTotal//cbc:PayableAmount')[0] ?? '',

                // Detalle de items
                'items' => [],
            ];

            // Extraer detalles de items
            $items = $xml->xpath('//cac:InvoiceLine');
            foreach ($items as $item) {
                $data['items'][] = [
                    'cantidad' => (string)$item->xpath('./cbc:InvoicedQuantity')[0] ?? '',
                    'unidad' => (string)$item->xpath('./cbc:InvoicedQuantity/@unitCode')[0] ?? '',
                    'descripcion' => (string)$item->xpath('./cac:Item/cbc:Description')[0] ?? '',
                    'codigo' => (string)$item->xpath('./cac:Item/cac:SellersItemIdentification/cbc:ID')[0] ?? '',
                    'precioUnitario' => (string)$item->xpath('./cac:Price/cbc:PriceAmount')[0] ?? '',
                    'precioVenta' => (string)$item->xpath('./cac:PricingReference/cac:AlternativeConditionPrice/cbc:PriceAmount')[0] ?? '',
                    'subtotal' => (string)$item->xpath('./cbc:LineExtensionAmount')[0] ?? '',
                    'igv' => (string)$item->xpath('./cac:TaxTotal/cbc:TaxAmount')[0] ?? '',
                ];
            }

            return $data;
        } catch (Exception $e) {
            // Manejar errores de parseo
            return [
                'error' => 'Error al parsear el XML: ' . $e->getMessage(),
            ];
        }
    }

    protected function generarQrCode($xmlData): ?string
    {
        // Código original sin cambios
        if (!isset($xmlData['error']) && isset($xmlData['empresa']['ruc'])) {
            try {
                // Obtener los componentes del número de comprobante (serie-correlativo)
                $numeroPartes = explode('-', $xmlData['numeroComprobante']);
                $serie = $numeroPartes[0] ?? '';
                $correlativo = $numeroPartes[1] ?? '';

                // Formatear datos para el QR según especificación SUNAT
                $qrTexto = implode('|', [
                    $xmlData['empresa']['ruc'] ?? '',                      // RUC emisor
                    $xmlData['tipoComprobante'] ?? '',                     // Tipo de comprobante
                    $serie,                                                // Serie
                    $correlativo,                                          // Correlativo
                    $xmlData['igv'] ?? '',                                 // Total IGV
                    $xmlData['total'] ?? '',                               // Total del comprobante
                    $xmlData['fechaEmision'] ?? '',                        // Fecha de emisión
                    substr($xmlData['cliente']['tipoDoc'] ?? '', 0, 1),    // Tipo de documento del cliente
                    $xmlData['cliente']['numDoc'] ?? ''                    // Número de documento del cliente
                ]);

                // Crear QR con endroid/qr-code
                $qrCode = new QrCode($qrTexto);
                $writer = new PngWriter();
                $result = $writer->write($qrCode);

                // Obtener la imagen como string base64
                return $result->getDataUri();
            } catch (Exception $e) {
                // Si hay un error, registrarlo y devolver null
                return null;
            }
        }

        return null;
    }

    public $ticketId;
    public $tipoComprobanteId;
    public $serie;
    public $numero;
    public $isLoading = false;

    protected function convertirTicket(array $data, ComprobantePago $ticket): void
    {
        try {
            // Mostrar notificación de proceso iniciado
            Notification::make()
                ->title('Enviando a SUNAT')
                ->body('El comprobante está siendo procesado...')
                ->info()
                ->send();

            // Obtener info del cliente
            $cliente = $ticket->cliente;

            // Obtener la comanda
            $comanda = $ticket->comanda;

            // Obtener empresa
            $empresa = Empresa::first();

            // Validar que el tipo de comprobante sea correcto según el cliente
            $tipoComprobanteId = $data['tipo_comprobante_id'];
            $tipoComprobante = TipoComprobante::find($tipoComprobanteId);

            // Si es factura, verificar que el cliente tenga RUC
            if ($tipoComprobante->codigo === '01' && substr($cliente->numero_documento, 0, 2) !== '20' && substr($cliente->numero_documento, 0, 2) !== '10') {
                Notification::make()
                    ->title('Validación fallida')
                    ->body('Para emitir una factura, el cliente debe tener RUC.')
                    ->danger()
                    ->persistent()
                    ->send();

                return;
            }

            // Preparar array de detalle para factura electrónica
            $detalleFactura = [];
            $item = 1;

            // Calcular valores
            $subtotal = 0;
            $igv = 0;
            $total = 0;

            // Agregar platos al detalle de factura
            foreach ($comanda->comandaPlatos as $plato) {
                $totalSinigv = round(($plato->cantidad * $plato->precio_unitario) / 1.1, 2);
                $igvItem = round($totalSinigv * 0.1, 2);

                $detalleFactura[] = [
                    "txtITEM" => (string)$item,
                    "txtUNIDAD_MEDIDA_DET" => "NIU", // Unidad estándar
                    "txtCANTIDAD_DET" => (string)$plato->cantidad,
                    "txtPRECIO_DET" => (string)($plato->precio_unitario),
                    "txtIMPORTE_DET" => (string)$totalSinigv,
                    "txtPRECIO_TIPO_CODIGO" => "01",
                    "txtIGV" => (string)$igvItem,
                    "txtISC" => "0",
                    "txtCOD_TIPO_OPERACION" => "10",
                    "txtCODIGO_DET" => "PLA" . str_pad($plato->plato->id, 3, '0', STR_PAD_LEFT),
                    "txtDESCRIPCION_DET" => $plato->plato->nombre . ($plato->es_llevar ? " - LLEVAR" : ""),
                    "txtPRECIO_SIN_IGV_DET" => (string)round($plato->precio_unitario / 1.1, 2),
                    "FLG_ICBPER" => "0",
                    "IMPUESTO_BP" => "0",
                    "IMPORTE_BP" => "0"
                ];

                $subtotal += $totalSinigv;
                $igv += $igvItem;
                $total += ($totalSinigv + $igvItem);

                $item++;
            }

            // Agregar existencias al detalle de factura
            foreach ($comanda->comandaExistencias as $existencia) {
                $totalSinigv = round(($existencia->cantidad * $existencia->precio_unitario) / 1.1, 2);
                $igvItem = round($totalSinigv * 0.1, 2);

                $detalleFactura[] = [
                    "txtITEM" => (string)$item,
                    "txtUNIDAD_MEDIDA_DET" => "NIU", // Unidad estándar
                    "txtCANTIDAD_DET" => (string)$existencia->cantidad,
                    "txtPRECIO_DET" => (string)($existencia->precio_unitario),
                    "txtIMPORTE_DET" => (string)$totalSinigv,
                    "txtPRECIO_TIPO_CODIGO" => "01",
                    "txtIGV" => (string)$igvItem,
                    "txtISC" => "0",
                    "txtCOD_TIPO_OPERACION" => "10",
                    "txtCODIGO_DET" => "EXI" . str_pad($existencia->existencia->id, 3, '0', STR_PAD_LEFT),
                    "txtDESCRIPCION_DET" => (string)$existencia->existencia->nombre . ($existencia->es_helado ? " - HELADO" : ""),
                    "txtPRECIO_SIN_IGV_DET" => (string)round($existencia->precio_unitario / 1.1, 2),
                    "FLG_ICBPER" => "0",
                    "IMPUESTO_BP" => "0",
                    "IMPORTE_BP" => "0"
                ];

                $subtotal += $totalSinigv;
                $igv += $igvItem;
                $total += ($totalSinigv + $igvItem);

                $item++;
            }

            // Crear el objeto para facturación electrónica
            $datos = [
                "ICBP" => "0",
                "txtTIPO_OPERACION" => "0101",
                "txtTOTAL_GRAVADAS" => (string)$subtotal,
                "txtSUB_TOTAL" => (string)$subtotal,
                "txtPOR_IGV" => "10.00",
                "txtTOTAL_IGV" => (string)$igv,
                "txtTOTAL" => (string)$total,
                "txtTOTAL_LETRAS" => $this->numeroALetras($total),
                "txtNRO_COMPROBANTE" => $data['serie'] . '-' . $data['numero'],
                "txtFECHA_DOCUMENTO" => date('Y-m-d'),
                "txtFECHA_VTO" => date('Y-m-d'),
                "txtCOD_TIPO_DOCUMENTO" => str_replace(' ', '', $tipoComprobante->codigo),
                "txtCOD_MONEDA" => "PEN", // Por defecto Sol Peruano
                "detalle_forma_pago" => [
                    [
                        "COD_FORMA_PAGO" => "Contado" // Por defecto contado
                    ]
                ],
                "txtNRO_DOCUMENTO_CLIENTE" => $cliente->ruc ?? $cliente->numero_documento,
                "txtRAZON_SOCIAL_CLIENTE" => $cliente->nombre ?? ($cliente->nombres . ' ' . $cliente->apellidos),
                "txtTIPO_DOCUMENTO_CLIENTE" => $cliente->tipo_documento ?? "1",
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

            // Instanciar el SunatController
            $sunatController = new SunatController();

            // dd($datos);
            // Enviar datos a SUNAT
            $resultado = $sunatController->enviarCpe($datos);

            // Verificar si la respuesta fue exitosa
            if ($resultado['success']) {
                // Extraer datos de respuesta
                $responseData = $resultado['data'];

                $hashCpe = $responseData['hash_cpe'] ?? null;
                $codSunat = $responseData['cod_sunat'] ?? null;
                $msjSunat = $responseData['msj_sunat'] ?? null;
                $hashCdr = $responseData['hash_cdr'] ?? null;
                $xmlCpeContent = $responseData['xml_cpe'] ?? null;
                $xmlCdrContent = $responseData['xml_cdr'] ?? null;

                // Crear nuevo comprobante electrónico
                $comprobante = ComprobantePago::create([
                    'tipo_comprobante_id' => $tipoComprobanteId,
                    'serie' => $data['serie'],
                    'numero' => $data['numero'],
                    'cliente_id' => $ticket->cliente_id,
                    'comanda_id' => $ticket->comanda_id,
                    'moneda' => 'PEN',
                    'medio_pago' => $ticket->medio_pago,
                    'hash_cpe' => $hashCpe,
                    'hash_cdr' => $hashCdr,
                    'xml_cpe' => $xmlCpeContent,
                    'xml_cdr' => $xmlCdrContent,
                    'user_id' => Auth::id(),
                    'caja_id' => $ticket->caja_id,
                    'observaciones' => "Convertido desde ticket {$ticket->serie}-{$ticket->numero}. Código: {$codSunat} {$msjSunat}",
                ]);

                // Notificar éxito
                Notification::make()
                    ->title('Conversión exitosa')
                    ->body('Comprobante electrónico generado: ' . $data['serie'] . '-' . $data['numero'] . '. ' . $msjSunat)
                    ->success()
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('view')
                            ->label('Ver Detalle')
                            ->url(route('filament.admin.pages.ventas'))
                    ])
                    ->persistent()
                    ->send();
            } else {
                // Notificar error de SUNAT
                Notification::make()
                    ->title('Error en SUNAT')
                    ->body($resultado['message'] ?? 'Ocurrió un error al procesar el comprobante en SUNAT')
                    ->danger()
                    ->persistent()
                    ->send();
            }
        } catch (Exception $e) {
            // Manejo de excepciones generales
            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->persistent()
                ->send();
        }
    }
}
