<?php

error_reporting(E_ALL ^ E_NOTICE);
// Permite la conexion desde cualquier origen
header("Access-Control-Allow-Origin: *");
// Permite la ejecucion de los metodos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
// Se incluye el archivo que contiene la clase generica
require_once('config_cpe.php');

//$array = explode("/", $_SERVER['REQUEST_URI']);
$bodyRequest = file_get_contents("php://input");

// Decodifica el cuerpo de la solicitud y lo guarda en un array de PHP
$cab = json_decode($bodyRequest, true);
$detalle = $cab['detalle'];

//($tipo_proceso, $ruc, $usuario_sol, $pass_sol, $ticket, $tipo_comprobante, $nro_comprobante)
$mensaje_cpe = cpeConsultaTicket($cab['TIPO_PROCESO'], $cab['NRO_DOCUMENTO_EMPRESA'], $cab['USUARIO_SOL_EMPRESA'], $cab['PASS_SOL_EMPRESA'], $cab['TICKET'], $cab['TIPO_DOCUMENTO'], $cab['NRO_DOCUMENTO']);

$resultado['hash_cpe'] = $mensaje_cpe['hash_cpe'];
$resultado['cod_sunat'] = $mensaje_cpe['hash_cdr']['cod_sunat'];//str_replace("SOAP-ENV:CLIENT.", "", $mensaje_cpe['hash_cdr']['cod_sunat']);
$resultado['msj_sunat'] = str_replace("'","",$mensaje_cpe['hash_cdr']['msj_sunat']);
$resultado['hash_cdr'] = $mensaje_cpe['hash_cdr']['hash_cdr'];

print_json($resultado);

function print_json($data) {
    header("HTTP/1.1");
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($data, JSON_PRETTY_PRINT);
}

?>