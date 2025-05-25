<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {  
		header('Content-type: application/json; charset=utf-8');
	    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");  
	    header('Access-Control-Allow-Credentials: true');  
	    header('Access-Control-Max-Age: 86400');   
	}  
	      
	if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {       
	    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))  
	        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");        
	    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))  
	        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");  
	}

//header('Content-Type: application/json');
header("HTTP/1.1");
header("Content-Type: application/json; charset=UTF-8");
$act=$_GET['act'];
if($act==1){
	
$ruta=$_GET['ruta'];
$ndoc=$_GET['ndoc'];
$fichero=$_GET['fichero'];
$ruc=$_GET['ruc'];
	
$xml=$ruta.$fichero.'.XML';
$pdf=$ruta.$fichero.'.pdf';

$result=array();
$result['error']=0;
$result['mensaje']='El Comprobante no Existe';
$result['ruta']=$ruta;
	
if (file_exists($xml)) {
$result['pdf']='El XML Existe';
if (!file_exists($pdf)) {
$result['pdf']='El PDF no Existe';

$rutapdf='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$rutapdf= str_replace("funcionesGlobales/valida-archivo.php", "", $rutapdf);
	
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $rutapdf."/plugins/dompdf/index.php?ruc=".$ruc."&ndoc=".$ndoc."&ruta=".$ruta."&fichero=".$fichero,
    CURLOPT_USERAGENT => "Codular Sample cURL Request"
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);
	
}	
$result['error']=1;
$result['mensaje']='Comprobante encontrado';
$result['ruta']=$ruta;
}
	
echo json_encode($result);
exit();
}

?>