<?php

require_once('CPESunat_UBL21.php');
require_once('Signature.php');
require_once('cpe_envio.php');

function cpe() {
    //===============mensajes==============
    $mensaje_xml = "";
    $hash_cpe = ""; //hash_cpe
    $hash_cdr = "";

    $ruta_ws = 'https://e-beta.sunat.gob.pe:443/ol-ti-itcpfegem-beta/billService';
    $archivo = "20536579746-01-F001-00004483";

    $mensaje_xml = cpeFacturaPrueba3('BETA/20536579746/20536579746-01-F001-00004483');
    $hash_cpe = Signature("0", 'BETA/20536579746/20536579746-01-F001-00004483', 'FIRMABETA/FIRMABETA.pfx', "123456");
    $mensaje_envio = cpeEnvio("20536579746", "MODDATOS", "moddatos", 'BETA/20536579746/20536579746-01-F001-00004483', 'BETA/20536579746/', $archivo, $ruta_ws);

    //$response['mensaje_xml'] = $mensaje_xml;
    //$response['hash_cpe'] = $hash_cpe;
    //$response['hash_cdr'] = $mensaje_envio;
    //echo $response;
}

cpe();
?>