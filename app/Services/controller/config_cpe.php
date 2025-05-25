<?php

require_once('../api_cpe/CPESunat_UBL21.php');
require_once('../api_cpe/Signature.php');
require_once('../api_cpe/cpe_envio.php');
require_once('crearPDF.php');

function cpe(
    //===============DATOS DE LA EMPRESA===============
    $tipo_proceso,
    $ruc,
    $usuario_sol,
    $pass_sol,
    $pass_firma,
    //=============DATOS DEL COMPROBANTE============
    $cab,
    //=============detalle==============
    $detalle
) {

    //===============mensajes==============
    $mensaje_xml = "";
    $hash_cpe = ""; //hash_cpe
    $hash_cdr = "";
    //========================variables=======================
    $ruta_archivo = ""; //'../api_cpe/';
    $archivo = $ruc . '-' . $cab['txtCOD_TIPO_DOCUMENTO'] . '-' . $cab['txtNRO_COMPROBANTE'];
    $ruta = '';
    $ruta_cdr = '';
    $ruta_firma = '';
    $ruta_ws = '';

    //========================configuracion de(SERVIDOR)=======================
    if ($tipo_proceso == '1') {
        $ruta = $ruta_archivo . 'PRODUCCION/' . $ruc . "/" . $archivo;
        $ruta_cdr = $ruta_archivo . 'PRODUCCION/' . $ruc . "/";
        //$ruta_firma = $ruta_archivo . 'FIRMA/' . $ruc . '.pfx';
        $ruta_firma = $ruta_archivo . 'FIRMA/' . $ruc . '.pem';
        $ruta_ws = 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService';
    }
    if ($tipo_proceso == '2') {
        $ruta = $ruta_archivo . 'HOMOLOGACION/' . $ruc . "/" . $archivo;
        $ruta_cdr = $ruta_archivo . 'HOMOLOGACION/' . $ruc . "/";
        //$ruta_firma = $ruta_archivo . 'FIRMA/' . $ruc . '.pfx';
        $ruta_firma = $ruta_archivo . 'FIRMA/' . $ruc . '.pem';
        $ruta_ws = 'https://www.sunat.gob.pe/ol-ti-itcpgem-sqa/billService';
    }
    if ($tipo_proceso == '3') {
        $ruta = $ruta_archivo . 'BETA/' . $ruc . "/" . $archivo;
        $ruta_cdr = $ruta_archivo . 'BETA/' . $ruc . "/";
        //        if (file_exists('FIRMA/' . $ruc . '.pfx')) {
        //            $ruta_firma = 'FIRMA/' . $ruc . '.pfx';
        //        } else {
        //            $ruta_firma = 'FIRMABETA/FIRMABETA.pfx';
        //            $pass_firma = '123456';
        //        }
        if (file_exists('FIRMA/' . $ruc . '.pem')) {
            $ruta_firma = 'FIRMA/' . $ruc . '.pem';
        } else {
            $ruta_firma = 'FIRMABETA/FIRMABETA.pem';
            $pass_firma = '123456';
        }
        $ruta_ws = 'https://e-beta.sunat.gob.pe:443/ol-ti-itcpfegem-beta/billService';
    }
    /* CAMPOS NUEVO:
          TIPO_OPERACION
          FECHA_VTO
          POR_IGV
          CONTACTO_EMPRESA
          TOTAL_EXPORTACION
         * -------nuevos campos cliente (no obligatorios)-------
          COD_UBIGEO_CLIENTE
          DEPARTAMENTO_CLIENTE
          PROVINCIA_CLIENTE
          DISTRITO_CLIENTE
         */
    $cabecera = array(
        'TIPO_OPERACION' => (isset($cab['txtTIPO_OPERACION'])) ? $cab['txtTIPO_OPERACION'] : "",
        'TOTAL_GRAVADAS' => (isset($cab['txtTOTAL_GRAVADAS'])) ? $cab['txtTOTAL_GRAVADAS'] : "0",
        'TOTAL_INAFECTA' => (isset($cab['txtTOTAL_INAFECTA'])) ? $cab['txtTOTAL_INAFECTA'] : "0",
        'TOTAL_EXONERADAS' => (isset($cab['txtTOTAL_EXONERADAS'])) ? $cab['txtTOTAL_EXONERADAS'] : "0",
        'TOTAL_GRATUITAS' => (isset($cab['txtTOTAL_GRATUITAS'])) ? $cab['txtTOTAL_GRATUITAS'] : "0",
        //==========PERCEPCION==========
        //        'TOTAL_PERCEPCIONES' => (isset($cab['txtTOTAL_PERCEPCIONES'])) ? $cab['txtTOTAL_PERCEPCIONES'] : "0",
        //        'COD_PERCEPCION' => (isset($cab['txtCOD_PERCEPCION'])) ? $cab['txtCOD_PERCEPCION'] : "",
        //        'POR_PERCEPCION' => (isset($cab['txtPOR_PERCEPCION'])) ? $cab['txtPOR_PERCEPCION'] : "0",
        //        'BASE_IMP_PERCEPCION' => (isset($cab['txtBASE_IMP_PERCEPCION'])) ? $cab['txtBASE_IMP_PERCEPCION'] : "0",
        //        'TOTAL_COBRAR' => (isset($cab['txtTOTAL_COBRAR'])) ? $cab['txtTOTAL_COBRAR'] : "0", 
        //        
        'SUB_TOTAL_PERCEPCIONES' => (isset($cab['txtSUB_TOTAL_PERCEPCIONES'])) ? $cab['txtSUB_TOTAL_PERCEPCIONES'] : "0",
        'POR_PERCEPCIONES' => (isset($cab['txtPOR_PERCEPCIONES'])) ? $cab['txtPOR_PERCEPCIONES'] : "0",
        'BI_PERCEPCIONES' => (isset($cab['txtBI_PERCEPCIONES'])) ? $cab['txtBI_PERCEPCIONES'] : "0",
        'TOTAL_PERCEPCIONES' => (isset($cab['txtTOTAL_PERCEPCIONES'])) ? $cab['txtTOTAL_PERCEPCIONES'] : "0",
        //==========FIN PERCEPCION==========
        //==========RETENCION==========
        'POR_RETENCIONES' => (isset($cab['txtPOR_RETENCIONES'])) ? $cab['txtPOR_RETENCIONES'] : "0",
        'BI_RETENCIONES' => (isset($cab['txtBI_RETENCIONES'])) ? $cab['txtBI_RETENCIONES'] : "0",
        'TOTAL_RETENCIONES' => (isset($cab['txtTOTAL_RETENCIONES'])) ? $cab['txtTOTAL_RETENCIONES'] : "0",
        //==========FIN RETENCION==========
        //==========DETRACCION==========
        'COD_MEDIO_PAGO' => (isset($cab['txtCOD_MEDIO_PAGO'])) ? $cab['txtCOD_MEDIO_PAGO'] : "",
        'CTA_BANCARIA_BN' => (isset($cab['txtCTA_BANCARIA_BN'])) ? $cab['txtCTA_BANCARIA_BN'] : "",
        //----
        'CODIGO_DETRACCION' => (isset($cab['txtCODIGO_DETRACCION'])) ? $cab['txtCODIGO_DETRACCION'] : "",
        'POR_DETRACCION' => (isset($cab['txtPOR_DETRACCION'])) ? $cab['txtPOR_DETRACCION'] : "0",
        'TOTAL_DETRACCIONES' => (isset($cab['txtTOTAL_DETRACCIONES'])) ? $cab['txtTOTAL_DETRACCIONES'] : "0",
        //==========FIN DETRACCION==========
        'TOTAL_BONIFICACIONES' => (isset($cab['txtTOTAL_BONIFICACIONES'])) ? $cab['txtTOTAL_BONIFICACIONES'] : "0",
        'TOTAL_EXPORTACION' => (isset($cab['txtTOTAL_EXPORTACION'])) ? $cab['txtTOTAL_EXPORTACION'] : "0",
        'TOTAL_DESCUENTO' => (isset($cab['txtTOTAL_DESCUENTO'])) ? $cab['txtTOTAL_DESCUENTO'] : "0",
        'SUB_TOTAL' => (isset($cab['txtSUB_TOTAL'])) ? $cab['txtSUB_TOTAL'] : "0",
        'POR_IGV' => (isset($cab['txtPOR_IGV'])) ? $cab['txtPOR_IGV'] : "0", //campo nuevo
        'TOTAL_IGV' => (isset($cab['txtTOTAL_IGV'])) ? $cab['txtTOTAL_IGV'] : "0",
        'TOTAL_ISC' => (isset($cab['txtTOTAL_ISC'])) ? $cab['txtTOTAL_ISC'] : "0",
        'TOTAL_OTR_IMP' => (isset($cab['txtTOTAL_OTR_IMP'])) ? $cab['txtTOTAL_OTR_IMP'] : "0",
        'ICBP' => (isset($cab['ICBP'])) ? $cab['ICBP'] : "0",
        //==========RECARGO CONSUMO==========
        'TOTAL_RECARGO_CONSUMO' => (isset($cab['txtTOTAL_RECARGO_CONSUMO'])) ? $cab['txtTOTAL_RECARGO_CONSUMO'] : "0",
        'BI_RECARGO_CONSUMO' => (isset($cab['txtBI_RECARGO_CONSUMO'])) ? $cab['txtBI_RECARGO_CONSUMO'] : "0",
        //==========FIN RECARGO CONSUMO==========        
        'TOTAL' => (isset($cab['txtTOTAL'])) ? $cab['txtTOTAL'] : "0",
        'TOTAL_COBRAR' => (isset($cab['txtTOTAL_COBRAR'])) ? $cab['txtTOTAL_COBRAR'] : "0",
        'TOTAL_LETRAS' => $cab['txtTOTAL_LETRAS'],
        'NRO_GUIA_REMISION' => $cab['txtNRO_GUIA_REMISION'],
        'COD_GUIA_REMISION' => $cab['txtCOD_GUIA_REMISION'],
        'NRO_OTR_COMPROBANTE' => $cab['txtNRO_OTR_COMPROBANTE'],
        'COD_OTR_COMPROBANTE' => $cab['txtCOD_OTR_COMPROBANTE'],
        //==============================================
        'TIPO_COMPROBANTE_MODIFICA' => (isset($cab['txtTIPO_COMPROBANTE_MODIFICA'])) ? $cab['txtTIPO_COMPROBANTE_MODIFICA'] : "",
        'NRO_DOCUMENTO_MODIFICA' => (isset($cab['txtNRO_DOCUMENTO_MODIFICA'])) ? $cab['txtNRO_DOCUMENTO_MODIFICA'] : "",
        'COD_TIPO_MOTIVO' => (isset($cab['txtCOD_TIPO_MOTIVO'])) ? $cab['txtCOD_TIPO_MOTIVO'] : "",
        'DESCRIPCION_MOTIVO' => (isset($cab['txtDESCRIPCION_MOTIVO'])) ? $cab['txtDESCRIPCION_MOTIVO'] : "",
        //===============================================
        'NRO_COMPROBANTE' => $cab['txtNRO_COMPROBANTE'],
        'FECHA_DOCUMENTO' => $cab['txtFECHA_DOCUMENTO'],
        'FECHA_VTO' => $cab['txtFECHA_VTO'],
        'COD_TIPO_DOCUMENTO' => $cab['txtCOD_TIPO_DOCUMENTO'],
        'COD_MONEDA' => $cab['txtCOD_MONEDA'],
        //==========FORMA DE PAGO=========
        'detalle_forma_pago' => $cab['detalle_forma_pago'],
        //==============================FACTURA GUIA=============================
        'FLG_FACTURA_GUIA' => (isset($cab['txtFLG_FACTURA_GUIA'])) ? $cab['txtFLG_FACTURA_GUIA'] : "0",
        'COD_MOTIVO_TRASLADO' => $cab['txtCOD_MOTIVO_TRASLADO'],
        'COD_UND_PESO_BRUTO' => $cab['txtCOD_UND_PESO_BRUTO'],
        'PESO_BRUTO' => $cab['txtPESO_BRUTO'],
        'COD_MODALIDAD_TRASLADO' => $cab['txtCOD_MODALIDAD_TRASLADO'],
        'FECHA_INICIO' => $cab['txtFECHA_INICIO'],
        'PLACA_VEHICULO' => $cab['txtPLACA_VEHICULO'],
        'COD_TIPO_DOC_CHOFER' => $cab['txtCOD_TIPO_DOC_CHOFER'],
        'NRO_DOC_CHOFER' => $cab['txtNRO_DOC_CHOFER'],
        'COD_UBIGEO_DESTINO' => $cab['txtCOD_UBIGEO_DESTINO'],
        'DIRECCION_DESTINO' => $cab['txtDIRECCION_DESTINO'],
        'PLACA_CARRETA' => $cab['txtPLACA_CARRETA'],
        'COD_UBIGEO_ORIGEN' => $cab['txtCOD_UBIGEO_ORIGEN'],
        'DIRECCION_ORIGEN' => $cab['txtDIRECCION_ORIGEN'],
        //======================CLIENTE======================
        'NRO_DOCUMENTO_CLIENTE' => $cab['txtNRO_DOCUMENTO_CLIENTE'],
        'RAZON_SOCIAL_CLIENTE' => $cab['txtRAZON_SOCIAL_CLIENTE'],
        'TIPO_DOCUMENTO_CLIENTE' => $cab['txtTIPO_DOCUMENTO_CLIENTE'], //RUC
        'DIRECCION_CLIENTE' => $cab['txtDIRECCION_CLIENTE'],
        //--------------------nuevos campos cliente
        'COD_UBIGEO_CLIENTE' => (isset($cab['txtCOD_UBIGEO_CLIENTE'])) ? $cab['txtCOD_UBIGEO_CLIENTE'] : "",
        'DEPARTAMENTO_CLIENTE' => (isset($cab['txtDEPARTAMENTO_CLIENTE'])) ? $cab['txtDEPARTAMENTO_CLIENTE'] : "",
        'PROVINCIA_CLIENTE' => (isset($cab['txtPROVINCIA_CLIENTE'])) ? $cab['txtPROVINCIA_CLIENTE'] : "",
        'DISTRITO_CLIENTE' => (isset($cab['txtDISTRITO_CLIENTE'])) ? $cab['txtDISTRITO_CLIENTE'] : "",
        //--------------------fin nuevos campos cliente
        'CIUDAD_CLIENTE' => (isset($cab['txtCIUDAD_CLIENTE'])) ? $cab['txtCIUDAD_CLIENTE'] : "",
        'COD_PAIS_CLIENTE' => $cab['txtCOD_PAIS_CLIENTE'],
        //==========ACCIONISTAS=========
        'accionistas' => $cab['accionistas'],
        //======================EMPRESA======================
        'NRO_DOCUMENTO_EMPRESA' => $cab['txtNRO_DOCUMENTO_EMPRESA'],
        'TIPO_DOCUMENTO_EMPRESA' => $cab['txtTIPO_DOCUMENTO_EMPRESA'], //RUC
        'NOMBRE_COMERCIAL_EMPRESA' => $cab['txtNOMBRE_COMERCIAL_EMPRESA'],
        'CODIGO_UBIGEO_EMPRESA' => $cab['txtCODIGO_UBIGEO_EMPRESA'],
        'DIRECCION_EMPRESA' => $cab['txtDIRECCION_EMPRESA'],
        'DEPARTAMENTO_EMPRESA' => $cab['txtDEPARTAMENTO_EMPRESA'],
        'PROVINCIA_EMPRESA' => $cab['txtPROVINCIA_EMPRESA'],
        'DISTRITO_EMPRESA' => $cab['txtDISTRITO_EMPRESA'],
        'CODIGO_PAIS_EMPRESA' => $cab['txtCODIGO_PAIS_EMPRESA'],
        'RAZON_SOCIAL_EMPRESA' => $cab['txtRAZON_SOCIAL_EMPRESA'],
        'CONTACTO_EMPRESA' => $cab['txtCONTACTO_EMPRESA'], //nuevo campo
        'TELEFONO_EMPRESA' => $cab['txtTELEFONO_EMPRESA'],
        'FORMATO_IMPRESION' => $cab['txtFORMATO_IMPRESION'],
        //====================INFORMACION PARA ANTICIPO=====================//
        'FLG_ANTICIPO' => (isset($cab['txtFLG_ANTICIPO'])) ? $cab['txtFLG_ANTICIPO'] : "0",
        //====================REGULAR ANTICIPO=====================//
        'FLG_REGU_ANTICIPO' => (isset($cab['txtFLG_REGU_ANTICIPO'])) ? $cab['txtFLG_REGU_ANTICIPO'] : "0",
        'NRO_COMPROBANTE_REF_ANT' => (isset($cab['txtNRO_COMPROBANTE_REF_ANT'])) ? $cab['txtNRO_COMPROBANTE_REF_ANT'] : "",
        'MONEDA_REGU_ANTICIPO' => (isset($cab['txtMONEDA_REGU_ANTICIPO'])) ? $cab['txtMONEDA_REGU_ANTICIPO'] : "",
        'MONTO_REGU_ANTICIPO' => (isset($cab['txtMONTO_REGU_ANTICIPO'])) ? $cab['txtMONTO_REGU_ANTICIPO'] : "0",
        'MONTO_REGU_ANTICIPO_TOTAL' => (isset($cab['txtMONTO_REGU_ANTICIPO_TOTAL'])) ? $cab['txtMONTO_REGU_ANTICIPO_TOTAL'] : "0",
        'TIPO_DOCUMENTO_EMP_REGU_ANT' => (isset($cab['txtTIPO_DOCUMENTO_EMP_REGU_ANT'])) ? $cab['txtTIPO_DOCUMENTO_EMP_REGU_ANT'] : "",
        'NRO_DOCUMENTO_EMP_REGU_ANT' => (isset($cab['txtNRO_DOCUMENTO_EMP_REGU_ANT'])) ? $cab['txtNRO_DOCUMENTO_EMP_REGU_ANT'] : ""
    );

    //=======================creacion: factura, firma, envio======================
    $flg_firma = "1";
    if ($cab['txtCOD_TIPO_DOCUMENTO'] == '01') {
        $mensaje_xml = cpeFactura($ruta, $cabecera, $detalle);
        $flg_firma = "0";
    }
    if ($cab['txtCOD_TIPO_DOCUMENTO'] == '03') {
        $mensaje_xml = cpeFactura($ruta, $cabecera, $detalle);
        $flg_firma = "0";
    }
    if ($cab['txtCOD_TIPO_DOCUMENTO'] == '07') {
        $mensaje_xml = cpeNC($ruta, $cabecera, $detalle);
        $flg_firma = "0";
    }
    if ($cab['txtCOD_TIPO_DOCUMENTO'] == '08') {
        $mensaje_xml = cpeND($ruta, $cabecera, $detalle);
        $flg_firma = "0";
    }
    $hash_cpe = Signature($flg_firma, $ruta, $ruta_firma, $pass_firma);
    $mensaje_envio = cpeEnvio($ruc, $usuario_sol, $pass_sol, $ruta, $ruta_cdr, $archivo, $ruta_ws);
    //============CREAMOS PDF===========
    if ($cab['txtCOD_TIPO_DOCUMENTO'] == '01') {
        //dfFacBol($ruta, $cabecera, $detalle, $hash_cpe);  
        //pdfFacBolTicket($ruta, $cabecera, $detalle, $hash_cpe);
    }
    if ($cab['txtCOD_TIPO_DOCUMENTO'] == '03') {
        // pdfFacBolTicket($ruta, $cabecera, $detalle, $hash_cpe);    
    }

    $response['mensaje_xml'] = $mensaje_xml;
    $response['hash_cpe'] = $hash_cpe;
    $response['hash_cdr'] = $mensaje_envio;

    return $response;
}

function cpeBaja(
    //===============DATOS DE LA EMPRESA===============
    $tipo_proceso,
    $ruc,
    $usuario_sol,
    $pass_sol,
    $pass_firma,
    //=============DATOS DEL COMPROBANTE============
    $cab,
    //=============detalle==============
    $detalle
) {

    //===============mensajes==============
    $mensaje_xml = "";
    $hash_cpe = ""; //hash_cpe
    $hash_cdr = "";
    //========================variables=======================
    $ruta_archivo = '../api_cpe/';
    $archivo = $ruc . '-' . $cab['CODIGO'] . '-' . $cab['SERIE'] . '-' . $cab['SECUENCIA'];
    $ruta = '';
    $ruta_cdr = '';
    $ruta_firma = '';
    $ruta_ws = '';

    //========================configuracion de(SERVIDOR)=======================
    if ($tipo_proceso == '1') {
        $ruta = $ruta_archivo . 'PRODUCCION/' . $ruc . "/" . $archivo;
        $ruta_cdr = $ruta_archivo . 'PRODUCCION/' . $ruc . "/";
        $ruta_firma = $ruta_archivo . 'FIRMA/' . $ruc . '.pfx';
        $ruta_ws = 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService';
    }
    if ($tipo_proceso == '2') {
        $ruta = $ruta_archivo . 'HOMOLOGACION/' . $ruc . "/" . $archivo;
        $ruta_cdr = $ruta_archivo . 'HOMOLOGACION/' . $ruc . "/";
        $ruta_firma = $ruta_archivo . 'FIRMA/' . $ruc . '.pfx';
        $ruta_ws = 'https://www.sunat.gob.pe/ol-ti-itcpgem-sqa/billService';
    }
    if ($tipo_proceso == '3') {
        $ruta = $ruta_archivo . 'BETA/' . $ruc . "/" . $archivo;
        $ruta_cdr = $ruta_archivo . 'BETA/' . $ruc . "/";
        if (file_exists('FIRMA/' . $ruc . '.pfx')) {
            $ruta_firma = 'FIRMA/' . $ruc . '.pfx';
        } else {
            $ruta_firma = 'FIRMABETA/FIRMABETA.pfx';
            $pass_firma = '123456';
        }
        $ruta_ws = 'https://e-beta.sunat.gob.pe:443/ol-ti-itcpfegem-beta/billService';
    }
    //=======================creacion: factura, firma, envio======================
    $flg_firma = "0";
    if ($cab['CODIGO'] == 'RA') {
        $mensaje_xml = cpeBajaSunat($ruta, $cab, $detalle);
        $flg_firma = "0";
    }
    $hash_cpe = Signature($flg_firma, $ruta, $ruta_firma, $pass_firma);
    $mensaje_envio = cpeEnvioBaja($ruc, $usuario_sol, $pass_sol, $ruta, $ruta_cdr, $archivo, $ruta_ws);

    $response['mensaje_xml'] = $mensaje_xml;
    $response['hash_cpe'] = $hash_cpe;
    $response['hash_cdr'] = $mensaje_envio;

    return $response;
}


function ResumenBoleta(
    //===============DATOS DE LA EMPRESA===============
    $tipo_proceso,
    $ruc,
    $usuario_sol,
    $pass_sol,
    $pass_firma,
    //=============DATOS DEL COMPROBANTE============
    $cab,
    //=============detalle==============
    $detalle
) {

    //===============mensajes==============
    $mensaje_xml = "";
    $hash_cpe = ""; //hash_cpe
    $hash_cdr = "";
    //========================variables=======================
    $ruta_archivo = '../api_cpe/';
    $archivo = $ruc . '-' . $cab['CODIGO'] . '-' . $cab['SERIE'] . '-' . $cab['SECUENCIA'];
    $ruta = '';
    $ruta_cdr = '';
    $ruta_firma = '';
    $ruta_ws = '';

    //========================configuracion de(SERVIDOR)=======================
    if ($tipo_proceso == '1') {
        $ruta = $ruta_archivo . 'PRODUCCION/' . $ruc . "/" . $archivo;
        $ruta_cdr = $ruta_archivo . 'PRODUCCION/' . $ruc . "/";
        $ruta_firma = $ruta_archivo . 'FIRMA/' . $ruc . '.pfx';
        $ruta_ws = 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService';
    }
    if ($tipo_proceso == '2') {
        $ruta = $ruta_archivo . 'HOMOLOGACION/' . $ruc . "/" . $archivo;
        $ruta_cdr = $ruta_archivo . 'HOMOLOGACION/' . $ruc . "/";
        $ruta_firma = $ruta_archivo . 'FIRMA/' . $ruc . '.pfx';
        $ruta_ws = 'https://www.sunat.gob.pe/ol-ti-itcpgem-sqa/billService';
    }
    if ($tipo_proceso == '3') {
        $ruta = $ruta_archivo . 'BETA/' . $ruc . "/" . $archivo;
        $ruta_cdr = $ruta_archivo . 'BETA/' . $ruc . "/";
        if (file_exists('FIRMA/' . $ruc . '.pfx')) {
            $ruta_firma = 'FIRMA/' . $ruc . '.pfx';
        } else {
            $ruta_firma = 'FIRMABETA/FIRMABETA.pfx';
            $pass_firma = '123456';
        }
        $ruta_ws = 'https://e-beta.sunat.gob.pe:443/ol-ti-itcpfegem-beta/billService';
    }
    //=======================creacion: factura, firma, envio======================
    $flg_firma = "0";
    if ($cab['CODIGO'] == 'RC') {
        $mensaje_xml = cpeResumenBoleta($ruta, $cab, $detalle);
        $flg_firma = "0";
    }
    $hash_cpe = Signature($flg_firma, $ruta, $ruta_firma, $pass_firma);
    $mensaje_envio = cpeEnvioResumenBoleta($ruc, $usuario_sol, $pass_sol, $ruta, $ruta_cdr, $archivo, $ruta_ws);

    $response['mensaje_xml'] = $mensaje_xml;
    $response['hash_cpe'] = $hash_cpe;
    $response['hash_cdr'] = $mensaje_envio;

    return $response;
}


function cpeConsultaTicket($tipo_proceso, $ruc, $usuario_sol, $pass_sol, $ticket, $tipo_comprobante, $nro_comprobante)
{

    //===============mensajes==============
    $mensaje_xml = "";
    $hash_cpe = ""; //hash_cpe
    $hash_cdr = "";
    //========================variables=======================
    $ruta_archivo = '../api_cpe/';
    $archivo = $ruc . '-' . $tipo_comprobante . '-' . $nro_comprobante;
    $ruta = '';
    $ruta_cdr = '';
    $ruta_firma = '';
    $ruta_ws = '';

    //========================configuracion de(SERVIDOR)=======================
    if ($tipo_proceso == '1') {
        $ruta = $ruta_archivo . 'PRODUCCION/' . $ruc . "/" . $archivo;
        $ruta_cdr = $ruta_archivo . 'PRODUCCION/' . $ruc . "/";
        $ruta_firma = $ruta_archivo . 'FIRMA/' . $ruc . '.pfx';
        $ruta_ws = 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService';
    }
    if ($tipo_proceso == '2') {
        $ruta = $ruta_archivo . 'HOMOLOGACION/' . $ruc . "/" . $archivo;
        $ruta_cdr = $ruta_archivo . 'HOMOLOGACION/' . $ruc . "/";
        $ruta_firma = $ruta_archivo . 'FIRMA/' . $ruc . '.pfx';
        $ruta_ws = 'https://www.sunat.gob.pe/ol-ti-itcpgem-sqa/billService';
    }
    if ($tipo_proceso == '3') {
        $ruta = $ruta_archivo . 'BETA/' . $ruc . "/" . $archivo;
        $ruta_cdr = $ruta_archivo . 'BETA/' . $ruc . "/";
        if (file_exists('FIRMA/' . $ruc . '.pfx')) {
            $ruta_firma = 'FIRMA/' . $ruc . '.pfx';
        } else {
            $ruta_firma = 'FIRMABETA/FIRMABETA.pfx';
            $pass_firma = '123456';
        }
        $ruta_ws = 'https://e-beta.sunat.gob.pe:443/ol-ti-itcpfegem-beta/billService';
    }

    $mensaje_envio = consultaEnvioTicket($ruc, $usuario_sol, $pass_sol, $ticket, $archivo, $ruta_cdr, $ruta_ws);

    $response['mensaje_xml'] = $mensaje_xml;
    $response['hash_cpe'] = $hash_cpe;
    $response['hash_cdr'] = $mensaje_envio;

    return $response;
}

function StatusFacturaIntegrada($Jsondata)
{
    return getStatusFacturaIntegrada($Jsondata);
}

function guiaremision($tipo_proceso, $ruc, $usuario_sol, $pass_sol, $pass_firma, $cab, $detalle)
{
    //===============mensajes==============
    $mensaje_xml = "";
    $hash_cpe = ""; //hash_cpe
    $hash_cdr = "";
    //========================variables=======================
    $ruta_archivo = '../api_cpe/';
    $archivo = $ruc . '-' . $cab['COD_TIPO_DOCUMENTO'] . '-' . $cab['NRO_COMPROBANTE'];
    $ruta = '';
    $ruta_cdr = '';
    $ruta_firma = '';
    $ruta_ws = '';

    //========================configuracion de(SERVIDOR)=======================
    if ($tipo_proceso == '1') {
        $ruta = $ruta_archivo . 'PRODUCCION/' . $ruc . "/" . $archivo;
        $ruta_cdr = $ruta_archivo . 'PRODUCCION/' . $ruc . "/";
        $ruta_firma = $ruta_archivo . 'FIRMA/' . $ruc . '.pfx';
        $ruta_ws = 'https://e-guiaremision.sunat.gob.pe/ol-ti-itemision-guia-gem/billService';
    }
    if ($tipo_proceso == '3') {
        $ruta = $ruta_archivo . 'BETA/' . $ruc . "/" . $archivo;
        $ruta_cdr = $ruta_archivo . 'BETA/' . $ruc . "/";
        if (file_exists('FIRMA/' . $ruc . '.pfx')) {
            $ruta_firma = 'FIRMA/' . $ruc . '.pfx';
        } else {
            $ruta_firma = 'FIRMABETA/FIRMABETA.pfx';
            $pass_firma = '123456';
        }
        $ruta_ws = 'https://e-beta.sunat.gob.pe/ol-ti-itemision-guia-gem-beta/billService';
    }
    //=======================creacion: factura, firma, envio======================
    $flg_firma = "0";
    if ($cab['COD_TIPO_DOCUMENTO'] == '09') {
        $mensaje_xml = cpeGuia($ruta, $cab, $detalle);
        $flg_firma = "0";
    }
    $hash_cpe = Signature($flg_firma, $ruta, $ruta_firma, $pass_firma);
    $mensaje_envio = cpeEnvioGuiaRemision($ruc, $usuario_sol, $pass_sol, $ruta, $ruta_cdr, $archivo, $ruta_ws);

    $response['mensaje_xml'] = $mensaje_xml;
    $response['hash_cpe'] = $hash_cpe;
    $response['hash_cdr'] = $mensaje_envio;

    return $response;
}

function guiaremisionToken($tipo_proceso, $ruc, $usuario_sol, $pass_sol, $pass_firma, $cab, $detalle)
{
    //===============mensajes==============
    $mensaje_xml = "";
    $hash_cpe = ""; //hash_cpe
    $hash_cdr = "";
    //========================variables=======================
    $ruta_archivo = '../api_cpe/';
    $archivo = $ruc . '-' . $cab['COD_TIPO_DOCUMENTO'] . '-' . $cab['NRO_COMPROBANTE'];
    //VARIABLES PARA TOKEN
    $cliente_id = $cab['ID_TOKEN'];
    $client_secret = $cab['CLAVE_TOKEN'];
    //FIN VARIABLES PARA TOKEN
    $ruta = '';
    $ruta_cdr = '';
    $ruta_firma = '';
    //rutas guita remision con token
    $rutaWS_token = '';
    $rutaWS_ticket = '';
    $ruta_ws = '';
    $scope = '';

    //========================configuracion de(SERVIDOR)=======================
    if ($tipo_proceso == '1' || $tipo_proceso == '3') {
        $ruta = $ruta_archivo . 'PRODUCCION/' . $ruc . "/" . $archivo;
        $ruta_cdr = $ruta_archivo . 'PRODUCCION/' . $ruc . "/";
        $ruta_firma = $ruta_archivo . 'FIRMA/' . $ruc . '.pfx';
        //rutas guita remision con token
        $scope = "https://api-cpe.sunat.gob.pe/";
        $rutaWS_token = "https://api-seguridad.sunat.gob.pe/v1/clientessol/" . $cliente_id . "/oauth2/token/"; //"https://api-seguridad.sunat.gob.pe/v1/clientessol/".urldecode($cliente_id)."/oauth2/token/";
        $rutaWS_ticket = "https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/envios/";
        $ruta_ws = "https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/" . $archivo;
    }
    //=======================creacion: factura, firma, envio======================
    $flg_firma = "0";
    if ($cab['COD_TIPO_DOCUMENTO'] == '09') {
        $mensaje_xml = cpeGuiaV2($ruta, $cab, $detalle);
        $flg_firma = "0";
    }

    if ($cab['COD_TIPO_DOCUMENTO'] == '31') {
        $mensaje_xml = cpeGuiaTransportistaV2($ruta, $cab, $detalle);
        $flg_firma = "0";
    }

    $hash_cpe = Signature($flg_firma, $ruta, $ruta_firma, $pass_firma);
    $mensaje_envio = cpeEnvioGuiaRemisionToken($ruc, $usuario_sol, $pass_sol, $ruta, $ruta_cdr, $archivo, $cliente_id, $client_secret, $scope, $rutaWS_token, $rutaWS_ticket, $ruta_ws);

    $response['mensaje_xml'] = $mensaje_xml;
    $response['hash_cpe'] = $hash_cpe;
    $response['hash_cdr'] = $mensaje_envio;

    return $response;
}

//function TicketGuiaRemisionToken($tipo_proceso, $ruc, $usuario_sol, $pass_sol, $pass_firma, $cab, $detalle) {
function TicketGuiaRemisionToken($cab)
{
    //===============mensajes==============
    $mensaje_xml = "";
    $hash_cdr = "";
    //========================variables=======================
    $ruta_archivo = '../api_cpe/';
    $archivo = $cab['NRO_DOCUMENTO_EMPRESA'] . '-' . $cab['COD_TIPO_DOCUMENTO'] . '-' . $cab['NRO_COMPROBANTE'];
    //VARIABLES PARA TOKEN
    $cliente_id = $cab['ID_TOKEN'];
    $client_secret = $cab['CLAVE_TOKEN'];
    //FIN VARIABLES PARA TOKEN
    $ruta_cdr = '';
    //rutas guita remision con token
    $rutaWS_token = '';
    $rutaWS_ticket = '';
    $scope = '';

    //========================configuracion de(SERVIDOR)=======================
    if ($cab['TIPO_PROCESO'] == '1' || $cab['TIPO_PROCESO'] == '3') {
        $ruta_cdr = $ruta_archivo . 'PRODUCCION/' . $cab['NRO_DOCUMENTO_EMPRESA'] . "/";
        //rutas guita remision con token
        $scope = "https://api-cpe.sunat.gob.pe/";
        $rutaWS_token = "https://api-seguridad.sunat.gob.pe/v1/clientessol/" . $cliente_id . "/oauth2/token/";
        $rutaWS_ticket = "https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/envios/";
    }

    $mensaje_envio = ConsultaTicketGuiaRemisionToken($cab['NRO_DOCUMENTO_EMPRESA'], $cab['USUARIO_SOL_EMPRESA'], $cab['PASS_SOL_EMPRESA'], $ruta_cdr, $archivo, $cliente_id, $client_secret, $scope, $rutaWS_token, $rutaWS_ticket, $cab['TICKET']);

    $response['mensaje_xml'] = "";
    $response['hash_cpe'] = "";
    $response['hash_cdr'] = $mensaje_envio;

    return $response;
}
