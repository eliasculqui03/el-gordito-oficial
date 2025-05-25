<?php

Class Revisar {

    //Implementamos nuestro constructor
    public function __construct() {
        
    }

//Implementamos un método para insertar registros
    public function statusCdrFactura($tipo_comprobante, $serie, $numero, $ruc, $usuario_sol, $pass_sol, $ruta_archivo_cdr) {
        $jsondata = array();
        $ruta_ws = 'https://e-factura.sunat.gob.pe/ol-it-wsconscpegem/billConsultService';
        try {
            $archivo=$ruc."-".$tipo_comprobante."-".$serie."-".$numero;
            //===================ENVIO FACTURACION=====================
            $soapUrl = $ruta_ws;
            $soapUser = "";
            $soapPassword = "";
            // xml post structure
            $xml_post_string = "<SOAP-ENV:Envelope
                        xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/'
                        xmlns:SOAP-ENC='http://schemas.xmlsoap.org/soap/encoding/'
                        xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'
                        xmlns:xsd='http://www.w3.org/2001/XMLSchema'
                        xmlns:wsse='http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd'>
                        <SOAP-ENV:Header
                            xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope'>
                            <wsse:Security>
                                <wsse:UsernameToken>
                                    <wsse:Username>" . $ruc . $usuario_sol . "</wsse:Username>
                                    <wsse:Password>" . $pass_sol . "</wsse:Password>
                                </wsse:UsernameToken>
                            </wsse:Security>
                        </SOAP-ENV:Header>
                        <SOAP-ENV:Body>
                            <m:getStatusCdr
                                xmlns:m='http://service.sunat.gob.pe'>
                                <rucComprobante>" . $ruc . "</rucComprobante>
                                <tipoComprobante>" . $tipo_comprobante . "</tipoComprobante>
                                <serieComprobante>" . $serie . "</serieComprobante>
                                <numeroComprobante>" . $numero . "</numeroComprobante>
                            </m:getStatusCdr>
                        </SOAP-ENV:Body>
                    </SOAP-ENV:Envelope>";

            $headers = array(
                "Content-type: text/xml;charset=\"utf-8\"",
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Pragma: no-cache",
                "SOAPAction: ",
                "Content-length: " . strlen($xml_post_string),
            ); //SOAPAction: your op URL
            //echo $xml_post_string;
            $url = $soapUrl;

            // PHP cURL  for https connection with auth
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // converting
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            //if ($httpcode == 200) {//======LA PAGINA SI RESPONDE
            //echo $httpcode.'----'.$response;
            //convertimos de base 64 a archivo fisico
            //echo $response;
            $doc = new DOMDocument();
            $doc->loadXML($response);

            //===================VERIFICAMOS SI HA ENVIADO CORRECTAMENTE EL COMPROBANTE=====================
            if (isset($doc->getElementsByTagName('faultcode')->item(0)->nodeValue)) {
                $mensaje['cod_sunat'] = str_replace("ns0:", "", $doc->getElementsByTagName('faultcode')->item(0)->nodeValue);
                $mensaje['msj_sunat'] = $doc->getElementsByTagName('faultstring')->item(0)->nodeValue;
            } else {
                $cod_sunat = isset($doc->getElementsByTagName('statusCode')->item(0)->nodeValue);
                if ($cod_sunat == "0004") {
                    //$mensaje['cod_sunat'] = $doc->getElementsByTagName('statusCode')->item(0)->nodeValue;
                    $xmlCDR = $doc->getElementsByTagName('content')->item(0)->nodeValue;

                    file_put_contents($ruta_archivo_cdr . 'R-' . $archivo . '.ZIP', base64_decode($xmlCDR));

                    //extraemos archivo zip a xml
                    $zip = new ZipArchive;
                    if ($zip->open($ruta_archivo_cdr . 'R-' . $archivo . '.ZIP') === TRUE) {
                        $zip->extractTo($ruta_archivo_cdr, 'R-' . $archivo . '.XML');
                        $zip->extractTo($ruta_archivo_cdr, 'R-' . $archivo . '.xml');
                        $zip->close();
                    }
                    //eliminamos los archivos Zipeados
                    unlink($ruta_archivo_cdr . 'R-' . $archivo . '.ZIP');
                    //=============hash CDR=================
                    $doc_cdr = new DOMDocument();
                    //$doc_cdr->load(dirname(__FILE__) . '/' . $ruta_archivo_cdr . 'R-' . $archivo . '.XML');
                    $doc_cdr->load($ruta_archivo_cdr . 'R-' . $archivo . '.XML');
                    $doc_cdr->load($ruta_archivo_cdr . 'R-' . $archivo . '.xml');

                    $mensaje['cod_sunat'] = $doc_cdr->getElementsByTagName('ResponseCode')->item(0)->nodeValue;
                    $mensaje['msj_sunat'] = $doc_cdr->getElementsByTagName('Description')->item(0)->nodeValue;
                    $mensaje['hash_cdr'] = $doc_cdr->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                }
            }
        } catch (Exception $e) {
            $mensaje['cod_sunat'] = "0000";
            $mensaje['msj_sunat'] = "SUNAT ESTA FUERA SERVICIO: " . $e->getMessage();
        }

        echo json_encode($mensaje, JSON_PRETTY_PRINT);

        exit();
    }

}

?>