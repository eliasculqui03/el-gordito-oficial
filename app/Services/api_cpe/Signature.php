<?php

use Greenter\XMLSecLibs\Sunat\SignedXml;

require 'xmldsig-master/vendor/autoload.php';

function Signature($flg_firma, $ruta, $ruta_firma, $pass_firma)
{
    $xmlPath = (__DIR__) . '/' . $ruta . '.XML';
    //$certPath = (_DIR_) . '/FIRMABETA/FIRMABETA.pem'; 
    //$certPath = (_DIR_) . '/FIRMABETA/FIRMABETA1.pem';
    $certPath = (__DIR__) . '/' . $ruta_firma;
    $signer = new SignedXml();
    $signer->setCertificateFromFile($certPath);
    $xmlSigned = $signer->signFromFile($xmlPath);
    file_put_contents($xmlPath, $xmlSigned);
    libxml_use_internal_errors(true);
    $xml = simplexml_load_file($xmlPath);

    $digestValueXPath = "//ext:UBLExtensions/ext:UBLExtension/ext:ExtensionContent/ds:Signature/ds:SignedInfo/ds:Reference/ds:DigestValue";
    $hash_cpe = $xml->xpath($digestValueXPath);
    $hash_cpe = $hash_cpe[0] . PHP_EOL;

    return $hash_cpe;
}
