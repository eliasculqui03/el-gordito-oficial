<?php

namespace App\Livewire;

use App\Models\ComprobantePago;
use Livewire\Component;
use SimpleXMLElement;
use Exception;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter; // Import QR Code facade

class ImprimirComprobante extends Component
{
    public $comprobanteId;
    public $comprobante;
    public $datosXml = [];
    public $loading = true;
    public $error = null;
    public $qrCode = null; // Add property for QR code

    public function mount($id)
    {
        $this->comprobanteId = $id;
        try {
            $this->cargarComprobante();
            $this->procesarXml();
            $this->generarQrCode(); // Generate QR code after processing XML
            $this->loading = false;
        } catch (Exception $e) {
            $this->error = "Error al cargar el comprobante: " . $e->getMessage();
            $this->loading = false;
        }
    }

    protected function cargarComprobante()
    {
        $this->comprobante = ComprobantePago::with([
            'tipoComprobante',
            'cliente',
            'comanda',
            'user',
            'caja'
        ])->findOrFail($this->comprobanteId);

        if (!$this->comprobante) {
            throw new Exception("No se encontró el comprobante solicitado.");
        }
    }

    protected function procesarXml()
    {
        if (empty($this->comprobante->xml_cpe)) {
            throw new Exception("El comprobante no contiene datos XML.");
        }

        try {
            // Eliminar caracteres no válidos y espacios en blanco
            $xml = trim($this->comprobante->xml_cpe);

            // Validar que sea un XML válido
            if (!$this->esXmlValido($xml)) {
                throw new Exception("El formato XML no es válido.");
            }

            // Crear objeto SimpleXMLElement
            $xmlObj = new SimpleXMLElement($xml);

            // Registrar los namespaces
            $namespaces = $xmlObj->getNamespaces(true);

            // Verificar que los namespaces requeridos estén presentes
            if (!isset($namespaces['cbc']) || !isset($namespaces['cac'])) {
                throw new Exception("El XML no contiene los namespaces requeridos.");
            }

            // Extraer datos básicos del comprobante con valores por defecto en caso de que falten
            $this->datosXml = $this->extraerDatosBasicos($xmlObj, $namespaces);

            // Extraer detalles de los items
            $this->extraerDetallesItems($xmlObj, $namespaces);
        } catch (Exception $e) {
            $this->datosXml = [
                'error' => 'Error al procesar el XML: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Genera el código QR según estándar SUNAT
     */


    protected function generarQrCode()
    {
        if (!isset($this->datosXml['error']) && isset($this->datosXml['emisor_ruc'])) {
            try {
                // Formatear datos para el QR según especificación SUNAT
                $qrTexto = implode('|', [
                    $this->datosXml['emisor_ruc'],
                    $this->datosXml['tipo_documento'],
                    substr($this->datosXml['serie_numero'], 0, 4),
                    substr($this->datosXml['serie_numero'], 5),
                    $this->datosXml['total_igv'],
                    $this->datosXml['total_venta'],
                    $this->datosXml['fecha_emision'],
                    substr($this->comprobante->cliente->tipo_documento, 0, 1),
                    $this->datosXml['cliente_documento']
                ]);

                // Crear QR con endroid/qr-code
                $qrCode = new QrCode($qrTexto);
                $writer = new PngWriter();
                $result = $writer->write($qrCode);

                // Obtener la imagen como string base64
                $this->qrCode = $result->getDataUri();
            } catch (Exception $e) {
                $this->qrCode = null;
            }
        }
    }

    /**
     * Verifica si el XML es válido
     *
     * @param string $xml
     * @return bool
     */
    protected function esXmlValido($xml)
    {
        libxml_use_internal_errors(true);
        $doc = simplexml_load_string($xml);
        $errors = libxml_get_errors();
        libxml_clear_errors();

        return $doc !== false && empty($errors);
    }

    /**
     * Extrae los datos básicos del comprobante
     *
     * @param SimpleXMLElement $xmlObj
     * @param array $namespaces
     * @return array
     */
    protected function extraerDatosBasicos($xmlObj, $namespaces)
    {
        $cbcNS = $namespaces['cbc'];
        $cacNS = $namespaces['cac'];

        return [
            // Datos básicos del comprobante
            'serie_numero' => $this->obtenerValorXml($xmlObj, $cbcNS, 'ID'),
            'fecha_emision' => $this->obtenerValorXml($xmlObj, $cbcNS, 'IssueDate'),
            'tipo_documento' => $this->obtenerValorXml($xmlObj, $cbcNS, 'InvoiceTypeCode'),
            'moneda' => $this->obtenerValorXml($xmlObj, $cbcNS, 'DocumentCurrencyCode'),
            'total_letras' => $this->obtenerValorXml($xmlObj, $cbcNS, 'Note'),

            // Datos del emisor
            'emisor_ruc' => $this->obtenerValorEmisor($xmlObj, $namespaces, 'ID'),
            'emisor_razon_social' => $this->obtenerValorEmisor($xmlObj, $namespaces, 'RegistrationName'),
            'emisor_nombre_comercial' => $this->obtenerNombreComercial($xmlObj, $namespaces),
            'emisor_direccion' => $this->obtenerDireccionEmisor($xmlObj, $namespaces),
            'emisor_distrito' => $this->obtenerValorDireccionEmisor($xmlObj, $namespaces, 'District'),
            'emisor_provincia' => $this->obtenerValorDireccionEmisor($xmlObj, $namespaces, 'CountrySubentity'),
            'emisor_departamento' => $this->obtenerValorDireccionEmisor($xmlObj, $namespaces, 'CityName'),

            // Datos del cliente
            'cliente_documento' => $this->obtenerValorCliente($xmlObj, $namespaces, 'ID'),
            'cliente_nombre' => $this->obtenerValorCliente($xmlObj, $namespaces, 'RegistrationName'),
            'cliente_direccion' => $this->obtenerDireccionCliente($xmlObj, $namespaces),

            // Totales
            'total_gravado' => $this->obtenerValorTotal($xmlObj, $namespaces, 'LineExtensionAmount'),
            'total_igv' => $this->obtenerValorIGV($xmlObj, $namespaces),
            'total_venta' => $this->obtenerValorTotal($xmlObj, $namespaces, 'PayableAmount'),

            // Forma de pago
            'forma_pago' => $this->obtenerFormaPago($xmlObj, $namespaces),

            // Inicializar array de detalles
            'detalles' => []
        ];
    }

    /**
     * Obtiene un valor específico del XML
     *
     * @param SimpleXMLElement $xmlObj
     * @param string $namespace
     * @param string $tag
     * @return string
     */
    protected function obtenerValorXml($xmlObj, $namespace, $tag)
    {
        return isset($xmlObj->children($namespace)->{$tag}) ?
            (string)$xmlObj->children($namespace)->{$tag} : '';
    }

    /**
     * Obtiene un valor específico del emisor
     *
     * @param SimpleXMLElement $xmlObj
     * @param array $namespaces
     * @param string $tag
     * @return string
     */
    protected function obtenerValorEmisor($xmlObj, $namespaces, $tag)
    {
        $cbcNS = $namespaces['cbc'];
        $cacNS = $namespaces['cac'];

        if ($tag === 'ID' && isset($xmlObj->children($cacNS)->AccountingSupplierParty->Party->PartyIdentification)) {
            return (string)$xmlObj->children($cacNS)->AccountingSupplierParty->Party->PartyIdentification->children($cbcNS)->ID;
        } elseif ($tag === 'RegistrationName' && isset($xmlObj->children($cacNS)->AccountingSupplierParty->Party->PartyLegalEntity)) {
            return (string)$xmlObj->children($cacNS)->AccountingSupplierParty->Party->PartyLegalEntity->children($cbcNS)->RegistrationName;
        }

        return '';
    }

    /**
     * Obtiene el nombre comercial del emisor
     *
     * @param SimpleXMLElement $xmlObj
     * @param array $namespaces
     * @return string
     */
    protected function obtenerNombreComercial($xmlObj, $namespaces)
    {
        $cbcNS = $namespaces['cbc'];
        $cacNS = $namespaces['cac'];

        if (isset($xmlObj->children($cacNS)->AccountingSupplierParty->Party->PartyName)) {
            return (string)$xmlObj->children($cacNS)->AccountingSupplierParty->Party->PartyName->children($cbcNS)->Name;
        }

        return '';
    }

    /**
     * Obtiene la dirección del emisor
     *
     * @param SimpleXMLElement $xmlObj
     * @param array $namespaces
     * @return string
     */
    protected function obtenerDireccionEmisor($xmlObj, $namespaces)
    {
        $cbcNS = $namespaces['cbc'];
        $cacNS = $namespaces['cac'];

        if (isset($xmlObj->children($cacNS)->AccountingSupplierParty->Party->PartyLegalEntity->RegistrationAddress->AddressLine)) {
            return (string)$xmlObj->children($cacNS)->AccountingSupplierParty->Party->PartyLegalEntity->RegistrationAddress->AddressLine->children($cbcNS)->Line;
        }

        return '';
    }

    /**
     * Obtiene un valor específico de la dirección del emisor
     *
     * @param SimpleXMLElement $xmlObj
     * @param array $namespaces
     * @param string $tag
     * @return string
     */
    protected function obtenerValorDireccionEmisor($xmlObj, $namespaces, $tag)
    {
        $cbcNS = $namespaces['cbc'];
        $cacNS = $namespaces['cac'];

        if (isset($xmlObj->children($cacNS)->AccountingSupplierParty->Party->PartyLegalEntity->RegistrationAddress->children($cbcNS)->{$tag})) {
            return (string)$xmlObj->children($cacNS)->AccountingSupplierParty->Party->PartyLegalEntity->RegistrationAddress->children($cbcNS)->{$tag};
        }

        return '';
    }

    /**
     * Obtiene un valor específico del cliente
     *
     * @param SimpleXMLElement $xmlObj
     * @param array $namespaces
     * @param string $tag
     * @return string
     */
    protected function obtenerValorCliente($xmlObj, $namespaces, $tag)
    {
        $cbcNS = $namespaces['cbc'];
        $cacNS = $namespaces['cac'];

        if ($tag === 'ID' && isset($xmlObj->children($cacNS)->AccountingCustomerParty->Party->PartyIdentification)) {
            return (string)$xmlObj->children($cacNS)->AccountingCustomerParty->Party->PartyIdentification->children($cbcNS)->ID;
        } elseif ($tag === 'RegistrationName' && isset($xmlObj->children($cacNS)->AccountingCustomerParty->Party->PartyLegalEntity)) {
            return (string)$xmlObj->children($cacNS)->AccountingCustomerParty->Party->PartyLegalEntity->children($cbcNS)->RegistrationName;
        }

        return '';
    }

    /**
     * Obtiene la dirección del cliente
     *
     * @param SimpleXMLElement $xmlObj
     * @param array $namespaces
     * @return string
     */
    protected function obtenerDireccionCliente($xmlObj, $namespaces)
    {
        $cbcNS = $namespaces['cbc'];
        $cacNS = $namespaces['cac'];

        if (isset($xmlObj->children($cacNS)->AccountingCustomerParty->Party->PartyLegalEntity->RegistrationAddress->AddressLine)) {
            return (string)$xmlObj->children($cacNS)->AccountingCustomerParty->Party->PartyLegalEntity->RegistrationAddress->AddressLine->children($cbcNS)->Line;
        }

        return '';
    }

    /**
     * Obtiene un valor específico del total
     *
     * @param SimpleXMLElement $xmlObj
     * @param array $namespaces
     * @param string $tag
     * @return string
     */
    protected function obtenerValorTotal($xmlObj, $namespaces, $tag)
    {
        $cbcNS = $namespaces['cbc'];
        $cacNS = $namespaces['cac'];

        if (isset($xmlObj->children($cacNS)->LegalMonetaryTotal->children($cbcNS)->{$tag})) {
            return (string)$xmlObj->children($cacNS)->LegalMonetaryTotal->children($cbcNS)->{$tag};
        }

        return '0.00';
    }

    /**
     * Obtiene el valor del IGV
     *
     * @param SimpleXMLElement $xmlObj
     * @param array $namespaces
     * @return string
     */
    protected function obtenerValorIGV($xmlObj, $namespaces)
    {
        $cbcNS = $namespaces['cbc'];
        $cacNS = $namespaces['cac'];

        if (isset($xmlObj->children($cacNS)->TaxTotal->children($cbcNS)->TaxAmount)) {
            return (string)$xmlObj->children($cacNS)->TaxTotal->children($cbcNS)->TaxAmount;
        }

        return '0.00';
    }

    /**
     * Obtiene la forma de pago
     *
     * @param SimpleXMLElement $xmlObj
     * @param array $namespaces
     * @return string
     */
    protected function obtenerFormaPago($xmlObj, $namespaces)
    {
        $cbcNS = $namespaces['cbc'];
        $cacNS = $namespaces['cac'];

        if (isset($xmlObj->children($cacNS)->PaymentTerms->children($cbcNS)->PaymentMeansID)) {
            return (string)$xmlObj->children($cacNS)->PaymentTerms->children($cbcNS)->PaymentMeansID;
        }

        return 'Contado';
    }

    /**
     * Extrae los detalles de los items
     *
     * @param SimpleXMLElement $xmlObj
     * @param array $namespaces
     */
    protected function extraerDetallesItems($xmlObj, $namespaces)
    {
        $cbcNS = $namespaces['cbc'];
        $cacNS = $namespaces['cac'];

        if (isset($xmlObj->children($cacNS)->InvoiceLine)) {
            foreach ($xmlObj->children($cacNS)->InvoiceLine as $linea) {
                $cantidad = isset($linea->children($cbcNS)->InvoicedQuantity) ?
                    (string)$linea->children($cbcNS)->InvoicedQuantity : '0';

                $descripcion = isset($linea->children($cacNS)->Item->children($cbcNS)->Description) ?
                    (string)$linea->children($cacNS)->Item->children($cbcNS)->Description : '';

                $precioUnitario = isset($linea->children($cacNS)->Price->children($cbcNS)->PriceAmount) ?
                    (string)$linea->children($cacNS)->Price->children($cbcNS)->PriceAmount : '0.00';

                $precioVenta = isset($linea->children($cacNS)->PricingReference->AlternativeConditionPrice->children($cbcNS)->PriceAmount) ?
                    (string)$linea->children($cacNS)->PricingReference->AlternativeConditionPrice->children($cbcNS)->PriceAmount : '0.00';

                $subtotal = isset($linea->children($cbcNS)->LineExtensionAmount) ?
                    (string)$linea->children($cbcNS)->LineExtensionAmount : '0.00';

                $igv = isset($linea->children($cacNS)->TaxTotal->children($cbcNS)->TaxAmount) ?
                    (string)$linea->children($cacNS)->TaxTotal->children($cbcNS)->TaxAmount : '0.00';

                $this->datosXml['detalles'][] = [
                    'cantidad' => $cantidad,
                    'descripcion' => $descripcion,
                    'precio_unitario' => $precioUnitario,
                    'precio_venta' => $precioVenta,
                    'subtotal' => $subtotal,
                    'igv' => $igv,
                ];
            }
        }
    }

    /**
     * Imprime el ticket
     */
    public function imprimir()
    {
        $this->dispatch('imprimirTicket');
    }

    /**
     * Descarga el XML del comprobante
     */
    public function descargarXml()
    {
        if (!empty($this->comprobante->xml_cpe)) {
            $this->dispatch('descargarXml', [
                'xml' => $this->comprobante->xml_cpe,
                'filename' => $this->datosXml['serie_numero'] . '.xml'
            ]);
        }
    }

    /**
     * Renderiza la vista
     */
    public function render()
    {
        return view('livewire.imprimir-comprobante');
    }
}
