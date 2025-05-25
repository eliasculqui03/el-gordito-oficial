<?php

                    $doc_cdr = new DOMDocument();
                    $doc_cdr->load(dirname(__FILE__) . '/R-10447915125-09-T001-0003000.xml');

                    $mensaje['cod_sunat'] = $doc_cdr->getElementsByTagName('ResponseCode')->item(0)->nodeValue;
                    $mensaje['msj_sunat'] = $doc_cdr->getElementsByTagName('Description')->item(0)->nodeValue;
                    $mensaje['hash_cdr'] = $doc_cdr->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                    $mensaje['url_guia'] = $doc_cdr->getElementsByTagName('DocumentDescription')->item(0)->nodeValue;
                    
                    
 print_json($mensaje);

function print_json($data) {
    header("HTTP/1.1");
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($data, JSON_PRETTY_PRINT);
}                   
 ?>
