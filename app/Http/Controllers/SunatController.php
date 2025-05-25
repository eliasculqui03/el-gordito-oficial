<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;

class SunatController extends Controller
{
    /**
     * Enviar comprobante electrónico a SUNAT
     *
     * @param array $cab Datos de cabecera
     * @return array Resultado de la operación
     */
    public function enviarCpe($cab)
    {
        try {
            $detalle = $cab['detalle'];

            // Cambiar al directorio del archivo CPE
            $originalDir = getcwd();
            $cpeDir = app_path('Services/controller');
            chdir($cpeDir);

            // Incluir archivos necesarios con rutas correctas
            require_once('config_cpe.php');
            require_once('../funcionesGlobales/validaciones.php');

            // Procesar cabecera
            $cabecera = $this->procesarCabecera($cab);

            // Llamar a la función CPE
            $mensaje_cpe = cpe(
                $cab['txtTIPO_PROCESO'] ?? '3',
                $cab['txtNRO_DOCUMENTO_EMPRESA'],
                $cab['txtUSUARIO_SOL_EMPRESA'] ?? 'MODDATOS',
                $cab['txtPASS_SOL_EMPRESA'] ?? 'moddatos',
                $cab['txtPAS_FIRMA'] ?? '123456',
                $cabecera,
                $detalle
            );

            // Nombre del archivo
            $archivo = $cab['txtNRO_DOCUMENTO_EMPRESA'] . '-' . $cab['txtCOD_TIPO_DOCUMENTO'] . '-' . $cab['txtNRO_COMPROBANTE'];

            // Rutas a los archivos XML
            $rutaXmlCpe = app_path("Services/api_cpe/BETA/{$cab['txtNRO_DOCUMENTO_EMPRESA']}/{$archivo}.XML");
            $rutaXmlCdr = app_path("Services/api_cpe/BETA/{$cab['txtNRO_DOCUMENTO_EMPRESA']}/R-{$archivo}.XML");

            // Leer contenido de los XML si existen
            $xmlCpe = '';
            $xmlCdr = '';

            if (file_exists($rutaXmlCpe)) {
                $xmlCpe = file_get_contents($rutaXmlCpe);
            }

            if (file_exists($rutaXmlCdr)) {
                $xmlCdr = file_get_contents($rutaXmlCdr);
            }

            // Procesar resultado
            $resultado = [
                'hash_cpe' => $mensaje_cpe['hash_cpe'],
                'cod_sunat' => $mensaje_cpe['hash_cdr']['cod_sunat'],
                'msj_sunat' => str_replace("'", "", $mensaje_cpe['hash_cdr']['msj_sunat']),
                'hash_cdr' => $mensaje_cpe['hash_cdr']['hash_cdr'],
                'xml_cpe' => $xmlCpe,
                'xml_cdr' => $xmlCdr
            ];

            // Restaurar directorio original
            chdir($originalDir);

            return [
                'success' => $resultado['cod_sunat'] === '0',
                'data' => $resultado,
                'message' => $resultado['cod_sunat'] === '0' ?
                    'CPE generado exitosamente: ' . $resultado['msj_sunat'] :
                    'Error SUNAT: ' . $resultado['msj_sunat']
            ];
        } catch (Exception $e) {
            // Restaurar directorio en caso de error
            if (isset($originalDir)) {
                chdir($originalDir);
            }

            return [
                'success' => false,
                'data' => null,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Procesar los datos de la cabecera del CPE
     *
     * @param array $cab Datos de la cabecera
     * @return array Cabecera procesada
     */
    private function procesarCabecera($cab)
    {
        return [
            'txtTIPO_OPERACION' => (isset($cab['txtTIPO_OPERACION'])) ? $cab['txtTIPO_OPERACION'] : "",
            'txtTOTAL_GRAVADAS' => (isset($cab['txtTOTAL_GRAVADAS'])) ? $cab['txtTOTAL_GRAVADAS'] : "0",
            'txtTOTAL_INAFECTA' => (isset($cab['txtTOTAL_INAFECTA'])) ? $cab['txtTOTAL_INAFECTA'] : "0",
            'txtTOTAL_EXONERADAS' => (isset($cab['txtTOTAL_EXONERADAS'])) ? $cab['txtTOTAL_EXONERADAS'] : "0",
            'txtTOTAL_GRATUITAS' => (isset($cab['txtTOTAL_GRATUITAS'])) ? $cab['txtTOTAL_GRATUITAS'] : "0",
            'txtSUB_TOTAL_PERCEPCIONES' => (isset($cab['txtSUB_TOTAL_PERCEPCIONES'])) ? $cab['txtSUB_TOTAL_PERCEPCIONES'] : "0",
            'txtPOR_PERCEPCIONES' => (isset($cab['txtPOR_PERCEPCIONES'])) ? $cab['txtPOR_PERCEPCIONES'] : "0",
            'txtBI_PERCEPCIONES' => (isset($cab['txtBI_PERCEPCIONES'])) ? $cab['txtBI_PERCEPCIONES'] : "0",
            'txtTOTAL_PERCEPCIONES' => (isset($cab['txtTOTAL_PERCEPCIONES'])) ? $cab['txtTOTAL_PERCEPCIONES'] : "0",
            'txtPOR_RETENCIONES' => (isset($cab['txtPOR_RETENCIONES'])) ? $cab['txtPOR_RETENCIONES'] : "0",
            'txtBI_RETENCIONES' => (isset($cab['txtBI_RETENCIONES'])) ? $cab['txtBI_RETENCIONES'] : "0",
            'txtTOTAL_RETENCIONES' => (isset($cab['txtTOTAL_RETENCIONES'])) ? $cab['txtTOTAL_RETENCIONES'] : "0",
            'txtTOTAL_BONIFICACIONES' => (isset($cab['txtTOTAL_BONIFICACIONES'])) ? $cab['txtTOTAL_BONIFICACIONES'] : "0",
            'txtTOTAL_EXPORTACION' => (isset($cab['txtTOTAL_EXPORTACION'])) ? $cab['txtTOTAL_EXPORTACION'] : "0",
            'txtCOD_MEDIO_PAGO' => (isset($cab['txtCOD_MEDIO_PAGO'])) ? $cab['txtCOD_MEDIO_PAGO'] : "",
            'txtCTA_BANCARIA_BN' => (isset($cab['txtCTA_BANCARIA_BN'])) ? $cab['txtCTA_BANCARIA_BN'] : "",
            'txtCODIGO_DETRACCION' => (isset($cab['txtCODIGO_DETRACCION'])) ? $cab['txtCODIGO_DETRACCION'] : "",
            'txtPOR_DETRACCION' => (isset($cab['txtPOR_DETRACCION'])) ? $cab['txtPOR_DETRACCION'] : "0",
            'txtTOTAL_DETRACCIONES' => (isset($cab['txtTOTAL_DETRACCIONES'])) ? $cab['txtTOTAL_DETRACCIONES'] : "0",
            'txtTOTAL_DESCUENTO' => (isset($cab['txtTOTAL_DESCUENTO'])) ? $cab['txtTOTAL_DESCUENTO'] : "0",
            'txtSUB_TOTAL' => (isset($cab['txtSUB_TOTAL'])) ? $cab['txtSUB_TOTAL'] : "0",
            'txtPOR_IGV' => (isset($cab['txtPOR_IGV'])) ? $cab['txtPOR_IGV'] : "0",
            'txtTOTAL_IGV' => (isset($cab['txtTOTAL_IGV'])) ? $cab['txtTOTAL_IGV'] : "0",
            'txtTOTAL_ISC' => (isset($cab['txtTOTAL_ISC'])) ? $cab['txtTOTAL_ISC'] : "0",
            'txtTOTAL_OTR_IMP' => (isset($cab['txtTOTAL_OTR_IMP'])) ? $cab['txtTOTAL_OTR_IMP'] : "0",
            'ICBP' => (isset($cab['ICBP'])) ? $cab['ICBP'] : "0",
            'txtTOTAL_RECARGO_CONSUMO' => (isset($cab['txtTOTAL_RECARGO_CONSUMO'])) ? $cab['txtTOTAL_RECARGO_CONSUMO'] : "0",
            'txtBI_RECARGO_CONSUMO' => (isset($cab['txtBI_RECARGO_CONSUMO'])) ? $cab['txtBI_RECARGO_CONSUMO'] : "0",
            'txtTOTAL' => (isset($cab['txtTOTAL'])) ? $cab['txtTOTAL'] : "0",
            'txtTOTAL_COBRAR' => (isset($cab['txtTOTAL_COBRAR'])) ? $cab['txtTOTAL_COBRAR'] : "0",
            'txtTOTAL_LETRAS' => $cab['txtTOTAL_LETRAS'],
            'txtNRO_GUIA_REMISION' => (isset($cab['txtNRO_GUIA_REMISION'])) ? $cab['txtNRO_GUIA_REMISION'] : "",
            'txtCOD_GUIA_REMISION' => (isset($cab['txtCOD_GUIA_REMISION'])) ? $cab['txtCOD_GUIA_REMISION'] : "",
            'txtNRO_OTR_COMPROBANTE' => (isset($cab['txtNRO_OTR_COMPROBANTE'])) ? $cab['txtNRO_OTR_COMPROBANTE'] : "",
            'txtCOD_OTR_COMPROBANTE' => (isset($cab['txtCOD_OTR_COMPROBANTE'])) ? $cab['txtCOD_OTR_COMPROBANTE'] : "",
            'txtTIPO_COMPROBANTE_MODIFICA' => (isset($cab['txtTIPO_COMPROBANTE_MODIFICA'])) ? $cab['txtTIPO_COMPROBANTE_MODIFICA'] : "",
            'txtNRO_DOCUMENTO_MODIFICA' => (isset($cab['txtNRO_DOCUMENTO_MODIFICA'])) ? $cab['txtNRO_DOCUMENTO_MODIFICA'] : "",
            'txtCOD_TIPO_MOTIVO' => (isset($cab['txtCOD_TIPO_MOTIVO'])) ? $cab['txtCOD_TIPO_MOTIVO'] : "",
            'txtDESCRIPCION_MOTIVO' => (isset($cab['txtDESCRIPCION_MOTIVO'])) ? $cab['txtDESCRIPCION_MOTIVO'] : "",
            'txtNRO_COMPROBANTE' => $cab['txtNRO_COMPROBANTE'],
            'txtFECHA_DOCUMENTO' => $cab['txtFECHA_DOCUMENTO'],
            'txtFECHA_VTO' => $cab['txtFECHA_VTO'],
            'txtCOD_TIPO_DOCUMENTO' => $cab['txtCOD_TIPO_DOCUMENTO'],
            'txtCOD_MONEDA' => $cab['txtCOD_MONEDA'],
            'detalle_forma_pago' => $cab['detalle_forma_pago'],
            'txtFLG_FACTURA_GUIA' => (isset($cab['txtFLG_FACTURA_GUIA'])) ? $cab['txtFLG_FACTURA_GUIA'] : "0",
            'txtCOD_MOTIVO_TRASLADO' => (isset($cab['txtCOD_MOTIVO_TRASLADO'])) ? $cab['txtCOD_MOTIVO_TRASLADO'] : "",
            'txtCOD_UND_PESO_BRUTO' => (isset($cab['txtCOD_UND_PESO_BRUTO'])) ? $cab['txtCOD_UND_PESO_BRUTO'] : "",
            'txtPESO_BRUTO' => (isset($cab['txtPESO_BRUTO'])) ? $cab['txtPESO_BRUTO'] : "0",
            'txtCOD_MODALIDAD_TRASLADO' => (isset($cab['txtCOD_MODALIDAD_TRASLADO'])) ? $cab['txtCOD_MODALIDAD_TRASLADO'] : "",
            'txtFECHA_INICIO' => (isset($cab['txtFECHA_INICIO'])) ? $cab['txtFECHA_INICIO'] : "",
            'txtPLACA_VEHICULO' => (isset($cab['txtPLACA_VEHICULO'])) ? $cab['txtPLACA_VEHICULO'] : "",
            'txtCOD_TIPO_DOC_CHOFER' => (isset($cab['txtCOD_TIPO_DOC_CHOFER'])) ? $cab['txtCOD_TIPO_DOC_CHOFER'] : "",
            'txtNRO_DOC_CHOFER' => (isset($cab['txtNRO_DOC_CHOFER'])) ? $cab['txtNRO_DOC_CHOFER'] : "",
            'txtCOD_UBIGEO_DESTINO' => (isset($cab['txtCOD_UBIGEO_DESTINO'])) ? $cab['txtCOD_UBIGEO_DESTINO'] : "",
            'txtDIRECCION_DESTINO' => (isset($cab['txtDIRECCION_DESTINO'])) ? $cab['txtDIRECCION_DESTINO'] : "",
            'txtPLACA_CARRETA' => (isset($cab['txtPLACA_CARRETA'])) ? $cab['txtPLACA_CARRETA'] : "",
            'txtCOD_UBIGEO_ORIGEN' => (isset($cab['txtCOD_UBIGEO_ORIGEN'])) ? $cab['txtCOD_UBIGEO_ORIGEN'] : "",
            'txtDIRECCION_ORIGEN' => (isset($cab['txtDIRECCION_ORIGEN'])) ? $cab['txtDIRECCION_ORIGEN'] : "",
            'txtNRO_DOCUMENTO_CLIENTE' => $cab['txtNRO_DOCUMENTO_CLIENTE'],
            'txtRAZON_SOCIAL_CLIENTE' => ValidarCaracteresInv($cab['txtRAZON_SOCIAL_CLIENTE']),
            'txtTIPO_DOCUMENTO_CLIENTE' => $cab['txtTIPO_DOCUMENTO_CLIENTE'],
            'txtDIRECCION_CLIENTE' => ValidarCaracteresInv((isset($cab['txtDIRECCION_CLIENTE'])) ? $cab['txtDIRECCION_CLIENTE'] : ""),
            'txtCOD_UBIGEO_CLIENTE' => (isset($cab['txtCOD_UBIGEO_CLIENTE'])) ? $cab['txtCOD_UBIGEO_CLIENTE'] : "",
            'txtDEPARTAMENTO_CLIENTE' => (isset($cab['txtDEPARTAMENTO_CLIENTE'])) ? $cab['txtDEPARTAMENTO_CLIENTE'] : "",
            'txtPROVINCIA_CLIENTE' => (isset($cab['txtPROVINCIA_CLIENTE'])) ? $cab['txtPROVINCIA_CLIENTE'] : "",
            'txtDISTRITO_CLIENTE' => (isset($cab['txtDISTRITO_CLIENTE'])) ? $cab['txtDISTRITO_CLIENTE'] : "",
            'txtCIUDAD_CLIENTE' => ValidarCaracteresInv((isset($cab['txtCIUDAD_CLIENTE'])) ? $cab['txtCIUDAD_CLIENTE'] : ""),
            'txtCOD_PAIS_CLIENTE' => (isset($cab['txtCOD_PAIS_CLIENTE'])) ? $cab['txtCOD_PAIS_CLIENTE'] : "",
            'accionistas' => (isset($cab['accionistas'])) ? $cab['accionistas'] : null,
            'txtNRO_DOCUMENTO_EMPRESA' => $cab['txtNRO_DOCUMENTO_EMPRESA'],
            'txtTIPO_DOCUMENTO_EMPRESA' => $cab['txtTIPO_DOCUMENTO_EMPRESA'],
            'txtNOMBRE_COMERCIAL_EMPRESA' => ValidarCaracteresInv((isset($cab['txtNOMBRE_COMERCIAL_EMPRESA'])) ? $cab['txtNOMBRE_COMERCIAL_EMPRESA'] : ""),
            'txtCODIGO_UBIGEO_EMPRESA' => $cab['txtCODIGO_UBIGEO_EMPRESA'],
            'txtDIRECCION_EMPRESA' => (isset($cab['txtDIRECCION_EMPRESA'])) ? $cab['txtDIRECCION_EMPRESA'] : "",
            'txtDEPARTAMENTO_EMPRESA' => (isset($cab['txtDEPARTAMENTO_EMPRESA'])) ? $cab['txtDEPARTAMENTO_EMPRESA'] : "",
            'txtPROVINCIA_EMPRESA' => (isset($cab['txtPROVINCIA_EMPRESA'])) ? $cab['txtPROVINCIA_EMPRESA'] : "",
            'txtDISTRITO_EMPRESA' => (isset($cab['txtDISTRITO_EMPRESA'])) ? $cab['txtDISTRITO_EMPRESA'] : "",
            'txtCODIGO_PAIS_EMPRESA' => $cab['txtCODIGO_PAIS_EMPRESA'],
            'txtRAZON_SOCIAL_EMPRESA' => ValidarCaracteresInv($cab['txtRAZON_SOCIAL_EMPRESA']),
            'txtCONTACTO_EMPRESA' => ValidarCaracteresInv((isset($cab['txtCONTACTO_EMPRESA'])) ? $cab['txtCONTACTO_EMPRESA'] : ""),
            'txtTELEFONO_EMPRESA' => (isset($cab['txtTELEFONO_EMPRESA'])) ? $cab['txtTELEFONO_EMPRESA'] : "",
            'txtFORMATO_IMPRESION' => (isset($cab['txtFORMATO_IMPRESION'])) ? $cab['txtFORMATO_IMPRESION'] : "",
            'txtFLG_ANTICIPO' => (isset($cab['txtFLG_ANTICIPO'])) ? $cab['txtFLG_ANTICIPO'] : "0",
            'txtFLG_REGU_ANTICIPO' => (isset($cab['txtFLG_REGU_ANTICIPO'])) ? $cab['txtFLG_REGU_ANTICIPO'] : "0",
            'txtNRO_COMPROBANTE_REF_ANT' => (isset($cab['txtNRO_COMPROBANTE_REF_ANT'])) ? $cab['txtNRO_COMPROBANTE_REF_ANT'] : "",
            'txtMONEDA_REGU_ANTICIPO' => (isset($cab['txtMONEDA_REGU_ANTICIPO'])) ? $cab['txtMONEDA_REGU_ANTICIPO'] : "",
            'txtMONTO_REGU_ANTICIPO' => (isset($cab['txtMONTO_REGU_ANTICIPO'])) ? $cab['txtMONTO_REGU_ANTICIPO'] : "0",
            'txtMONTO_REGU_ANTICIPO_TOTAL' => (isset($cab['txtMONTO_REGU_ANTICIPO_TOTAL'])) ? $cab['txtMONTO_REGU_ANTICIPO_TOTAL'] : "0",
            'txtTIPO_DOCUMENTO_EMP_REGU_ANT' => (isset($cab['txtTIPO_DOCUMENTO_EMP_REGU_ANT'])) ? $cab['txtTIPO_DOCUMENTO_EMP_REGU_ANT'] : "",
            'txtNRO_DOCUMENTO_EMP_REGU_ANT' => (isset($cab['txtNRO_DOCUMENTO_EMP_REGU_ANT'])) ? $cab['txtNRO_DOCUMENTO_EMP_REGU_ANT'] : "",
            "txtUSUARIO_SOL_EMPRESA" => (isset($cab['txtUSUARIO_SOL_EMPRESA'])) ? $cab['txtUSUARIO_SOL_EMPRESA'] : "MODDATOS",
            "txtPASS_SOL_EMPRESA" => (isset($cab['txtPASS_SOL_EMPRESA'])) ? $cab['txtPASS_SOL_EMPRESA'] : "moddatos",
            "txtTIPO_PROCESO" => (isset($cab['txtTIPO_PROCESO'])) ? $cab['txtTIPO_PROCESO'] : "3",
            "txtPAS_FIRMA" => (isset($cab['txtPAS_FIRMA'])) ? $cab['txtPAS_FIRMA'] : "123456",
        ];
    }
}
