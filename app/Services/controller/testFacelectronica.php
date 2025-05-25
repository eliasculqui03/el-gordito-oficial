<?php

///https://es.stackoverflow.com/questions/65698/consumir-servicio-web-con-php
class api_testCPE {

    public function sendPostCPE($data) {
        
        $headers = array(
            "Content-Type: application/json; charset=UTF-8",
            "Cache-Control: no-cache",
            "Pragma: no-cache"
        );
        $ch = curl_init("http://localhost:82/sunat/facElectronica/controller/controller_cpe.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //curl_setopt($ch, CURLOPT_USERPWD, "PRUEBA:LOG");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        // Se cierra el recurso CURL y se liberan los recursos del sistema
        curl_close($ch);
        if (!$response) {
            return false;
        } else {
            return $response;
        }
       
  
        //return $data;
    }

}
/*
$new = new api_testCPE();

$detalle= array(
                "ITEM"=>"1",
                "UNIDAD_MEDIDA"=>"NIU",
                "CANTIDAD"=>"1",
                "PRECIO"=>"625.0",
                "IMPORTE"=>"737.5",
                "PRECIO_TIPO_CODIGO"=>"01",
                "IGV"=>"112.5",
                "ISC"=>"0",
                "COD_TIPO_OPERACION"=>"10",
                "CODIGO"=>"00001",
                "DESCRIPCION"=>"PRODUCTO DE PRUEBA",
                "PRECIO_SIN_IMPUESTO"=>"625.0"
                );

$data = array(
   "TIPO_OPERACION"=>"",
   "TOTAL_GRAVADAS"=>"625.0",
   "SUB_TOTAL"=>"625.0",
   "TOTAL_IGV"=>"112.5",
   "TOTAL"=>"737.5",
   "TOTAL_LETRAS"=>"SETECIENTOS TREINTA Y SIETE CON 50/100 SOLES",
   "NRO_COMPROBANTE"=>"BB03-18",
   "FECHA_DOCUMENTO"=>"2018-01-11",
   "COD_TIPO_DOCUMENTO"=>"03",
   "COD_MONEDA"=>"PEN",
   "NRO_DOCUMENTO_CLIENTE"=>"44791512",
   "RAZON_SOCIAL_CLIENTE"=>"xxxx xxx xxx xxxx",
   "TIPO_DOCUMENTO_CLIENTE"=>"1",
   "DIRECCION_CLIENTE"=>"HUAMPANI ALTO",
   "CIUDAD_CLIENTE"=>"LIMA",
   "COD_PAIS_CLIENTE"=>"PE",
   "NRO_DOCUMENTO_EMPRESA"=>"20100066603",
   "TIPO_DOCUMENTO_EMPRESA"=>"6",
   "NOMBRE_COMERCIAL_EMPRESA"=>"xx xx xx xx",
   "CODIGO_UBIGEO_EMPRESA"=>"150101",
   "DIRECCION_EMPRESA"=>"DIRECCION DE PRUEBA",
   "DEPARTAMENTO_EMPRESA"=>"LIMA",
   "PROVINCIA_EMPRESA"=>"LIMA",
   "DISTRITO_EMPRESA"=>"LIMA",
   "CODIGO_PAIS_EMPRESA"=>"PE",
   "RAZON_SOCIAL_EMPRESA"=>"JOSE LUIS ZAMBRANO YACHA",
   "USUARIO_SOL_EMPRESA"=>"MODDATOS",
   "PASS_SOL_EMPRESA"=>"moddatos",
   "CONTRA"=>"123456",
   "TIPO_PROCESO"=>"3",
   "FLG_ANTICIPO"=>"0",
   "FLG_REGU_ANTICIPO"=>"0",
   "MONTO_REGU_ANTICIPO"=>"0",
   "Detalle"=>array($detalle)
);

$resultado = $new->sendPostCPE(json_encode($data));
var_dump($resultado);
 * */
?>