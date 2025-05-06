<?php

namespace App\Filament\Pages;

use App\Models\ComprobantePago;
use App\Models\TipoComprobante;
use Barryvdh\DomPDF\Facade\Pdf;
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
use Torgodly\Html2Media\Tables\Actions\Html2MediaAction;

class Ventas extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Registro de Ventas';
    protected static ?string $title = 'Historial de Comprobantes';
    protected static ?string $navigationGroup = 'Facturación';
    protected static string $view = 'filament.pages.ventas';

    protected static ?int $navigationSort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ComprobantePago::query()->orderBy('created_at', 'desc')
            )
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('serie')
                    ->label('Serie')
                    ->searchable(),
                TextColumn::make('numero')
                    ->label('Número')
                    ->searchable(),
                TextColumn::make('cliente.nombre')
                    ->label('Cliente')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Usuario'),
                TextColumn::make('created_at')
                    ->label('Fecha Emisión')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([


                Action::make('preview_pdf')
                    ->label('Vista Previa PDF')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading('Vista previa del comprobante')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->modalContent(function (ComprobantePago $record) {
                        $xmlData = $this->parseXmlCpe($record->xml_cpe);
                        $pdf = Pdf::loadView('tickets.pdf-template', [
                            'comprobante' => $record,
                            'xmlData' => $xmlData,
                        ]);

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
                                $xmlData = $this->parseXmlCpe($record->xml_cpe);
                                $pdf = Pdf::loadView('tickets.pdf-template', [
                                    'comprobante' => $record,
                                    'xmlData' => $xmlData,
                                ]);

                                return Response::streamDownload(
                                    fn() => print($pdf->output()),
                                    "comprobante-{$record->serie}-{$record->numero}.pdf"
                                );
                            })
                    ])
                    ->visible(fn(ComprobantePago $record): bool => $record->xml_cpe !== null),

                Action::make('download')
                    ->label('Descargar PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('primary')
                    ->action(function (ComprobantePago $record) {
                        $xmlData = $this->parseXmlCpe($record->xml_cpe);
                        $pdf = Pdf::loadView('tickets.pdf-template', [
                            'comprobante' => $record,
                            'xmlData' => $xmlData,
                        ]);

                        return response()->streamDownload(
                            fn() => print($pdf->output()),
                            "comprobante-{$record->serie}-{$record->numero}.pdf"
                        );
                    })
                    ->visible(fn(ComprobantePago $record): bool => $record->xml_cpe !== null)
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


    protected function parseXmlCpe(?string $xmlContent): ?array
    {
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
        } catch (\Exception $e) {
            // Manejar errores de parseo
            return [
                'error' => 'Error al parsear el XML: ' . $e->getMessage(),
            ];
        }
    }
}
