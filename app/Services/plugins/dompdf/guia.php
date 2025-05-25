<?php

//require_once("dompdf/dompdf_config.inc.php");

$rutat=	'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$rutat= str_replace("plugins/dompdf/guia.php", "", $rutat);

require_once 'lib/html5lib/Parser.php';
require_once 'lib/php-font-lib/src/FontLib/Autoloader.php';
require_once 'lib/php-svg-lib/src/autoload.php';
require_once 'src/Autoloader.php';
Dompdf\Autoloader::register();
use Dompdf\Dompdf;
use Dompdf\Options;
include "../phpqrcode/qrlib.php";
require "../../modelos/resumen.php";
require "../../modelos/numeros-letras.php";

$resumen=new Resumen();

$id=$_GET['id'];

$sql="SELECT *FROM guia_guia WHERE id='$id' ";
$mostrar= ejecutarConsultaSimpleFila($sql);

$sqlv="SELECT *FROM venta WHERE idventa='$mostrar[iddoc_relacionado]' ";
$mostrav= ejecutarConsultaSimpleFila($sqlv);

$sql2="SELECT *FROM persona WHERE idpersona='$mostrav[txtID_CLIENTE]' ";
$mcliente= ejecutarConsultaSimpleFila($sql2);
//VEHICULO
$sqlve="SELECT *FROM guia_vehiculo WHERE id='$mostrar[idvehiculo]' ";
$veh= ejecutarConsultaSimpleFila($sqlve);
//CONDUCTOR
$sqlch="SELECT *FROM guia_chofer WHERE id='$mostrar[idchofer]' ";
$chof= ejecutarConsultaSimpleFila($sqlch);
//EMPRESA DE TRANSPORTE
$sqltr="SELECT *FROM guia_transportista WHERE id='$mostrar[emptrans_id]' ";
$trans= ejecutarConsultaSimpleFila($sqltr);

$sqls="SELECT *FROM sucursal WHERE id='$mostrar[sucursal]' ";
$suc= ejecutarConsultaSimpleFila($sqls);

$sqld="SELECT *FROM sucursal WHERE id='$mostrar[destino]' ";
$dest= ejecutarConsultaSimpleFila($sqld);

$sql3="SELECT *FROM config WHERE estado='1' ";
$mempresa= ejecutarConsultaSimpleFila($sql3);

if($mempresa['tipo']=='03'){ $tipop='BETA'; }else{ $tipop='PRODUCCION'; }

$ruta="../../api_cpe/".$tipop."/".$mempresa['ruc']."/";
$fichero=$mempresa['ruc'].'-'.$mostrar['tipodoc'].'-'.$mostrar['serie'].'-'.$mostrar['numero'];

if($mostrar['tipodoc']=='09'){ $tdocumento='GUÍA REMISIÓN - REMITENTE'; }
if($mostrar['tipodoc']=='23'){ $tdocumento='GUÍA REMISIÓN - TRANSPORTISTA'; }

if($mostrav['txtID_TIPO_DOCUMENTO']=='01'){ $tdocumentor='FACTURA ELECTRÓNICA'; }
if($mostrav['txtID_TIPO_DOCUMENTO']=='03'){ $tdocumentor='BOLETA ELECTRÓNICA'; }
if($mostrav['txtID_TIPO_DOCUMENTO']=='07'){ $tdocumentor='NOTA DE CRÉDITO ELECTROICA'; }
if($mostrav['txtID_TIPO_DOCUMENTO']=='08'){ $tdocumentor='NOTA DE DÉBITO ELECTRÓNICA'; }

if($mostrav['txtID_MONEDA']=='PEN'){ $valmoneda='SOL'; }
if($mostrav['txtID_MONEDA']=='USD'){ $valmoneda='DOLAR'; }
if($mostrav['txtID_MONEDA']=='EUR'){ $valmoneda='EURO'; }


//QRcode::png("".$text);
//DATOS OBLIGATORIOS DE LA SUNAT EN EL QR
//RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE //EMISION |TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE |

$text=$mempresa['ruc'].' | '.$tdocumento.' | '.$mostrar['serie'].' | '.$mostrar['numero'].' | '.$mostrav['txtIGV'].' | '.$mostrav['txtTOTAL'].' | '.date("Y-m-d", strtotime($mostrar['fecha'])).' | '.$mcliente['tipo_documento'].' | '.$mcliente['txtID_CLIENTE'].' |';
QRcode::png($text, $mempresa['ruc'].".png", 'Q',15, 0);

 $html =
   '
<html> 
   <head> 
   <style> 
body{
font:10px Arial, Tahoma, Verdana, Helvetica, sans-serif;
color:#000;
}
.cabecera table {
	width: 100%;
    color:black;
    margin-top: 0em;
    text-align: left; font-size: 10px;
}
.cabecera h1 {
    font-size:17px; padding-bottom: 0px; margin-bottom: 0px; te
}

.cabecera2 table { border-collapse: collapse; border: solid 1px #000000;}
.cabecera2 th, .cabecera2 td { text-align: center; border-collapse: collapse; border: solid 1px #000000; font-size:12px; } 
.cabeza{ text-align: left; }
.nfactura{ background-color: #D8D8D8; }
.cuerpo table { border-collapse: collapse; margin-top:1px; border: solid 1px #000000; }
.cuerpo thead { border: solid 1px #000000; } 
.cuerpo2 thead { border: solid 1px #000000; } 
.titdocu{ font-size:11px; text-align: center; background-color: #A6A6A6; color:#FFFFFF; }
table { width: 100%; color:black; }
  
tbody { background-color: #ffffff; }
th,td { padding: 3pt; }           
.celda_right{  border-right: 1px solid black;  }
.celda_left{  border-left: 1px solid black; }         

.footer th, .footer td { padding: 1pt; }
.footer { font-size:10px;  width: 100%; border: solid 0px #000000; }
.fg { font-size: 11px;} 
.fg2 { text-align: center; }
.fg3 { border: solid 0px; } 
.total td { border: solid 0px; padding: 0px; } 
.total2 { text-align: right; } 

   </style>
    
   </head> 
    
   <body>        

   


<table width="100%" border="0" class="cabecera" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
<td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="cabecera">
  <tbody>
    <tr>
      <td width="6%"><img src="../../images/tulogo.png" width="266" height="60" /></td>
      <td class="cabeza"><h1>'.$mempresa['nombre_comercial'].'</h1>
        <strong>SUCURSAL:</strong> '.$mempresa['direccion'].'<br>
        <strong>TELF. PRINCIPAL:</strong> '.$mempresa['telefono'].'<br></td>
    </tr>

  </tbody>
</table>  </td>
<td width="30%">
        
           
        <table width="100%" class="cabecera2" cellspacing="0" >
          <tbody>

            <tr>
              <td >RUC N° '.$mempresa['ruc'].'</td>
            </tr>
            <tr>
              <td class="nfactura">'.$tdocumento.'</td>
            </tr>
            <tr>
              <td >'.$mostrar['serie'].'-'.$mostrar['numero'].'</td>
            </tr>
          </tbody>
        </table>

      </td>
    </tr>
  </tbody>
</table>

<table width="30%" cellspacing="0">
 <tr>
      <td width="30%">

<table width="30%" class="cuerpo" cellspacing="0">
  <thead>
    <tr>
      <td width="50%" class="titdocu celda_right" >FECHA DE EMISIÓN</td>
      <td width="50%" class="titdocu" >FECHA DE TRASLADO</td>
    </tr>
    <tr>
      <td class=" celda_right">'.date("Y-m-d", strtotime($mostrar['fecha'])).'</td>
      <td>'.date("Y-m-d", strtotime($mostrar['fecha_transporte'])).'</td>
    </tr>
	</thead>
	</table>
</td>
      <td width="70%"></td>
    </tr>

</thead>
</table>



<table width="100%" class="cuerpo" cellspacing="0">
  <thead>
    <tr>
      <td width="50%" class="titdocu celda_right" >DOMICILIO PARTIDA</td>
      <td width="50%" class="titdocu" >DOMICILIO LLEGADA</td>
    </tr>
    <tr>
      <td class=" celda_right">DIRECCIÓN: '.$suc['direccion'].'<br> UBIGEO: '.$suc['ubigeo'].'</td>
      <td>DIRECCIÓN: '.$dest['direccion'].'<br> UBIGEO: '.$dest['ubigeo'].'</td>
    </tr>
	
</thead>
</table>
<br>
<table width="100%" class="cuerpo" cellspacing="0">
  <thead>
    <tr>
      <td width="50%" class="titdocu celda_right" >DESTINATARIO</td>
      <td width="50%" class="titdocu" >UNIDAD DE TRANSPORTE/CONDUCTOR</td>
    </tr>
    <tr> 
      <td class=" celda_right">Apellidos y nombres/ Razón Social: '.$mcliente['nombre'].'<br>
        RUC/DNI: '.$mcliente['txtID_CLIENTE'].'</td>
      <td>Vehículo / Marca y Placa N°: '.$veh['marca'].' / '.$veh['placa'].'<br>
        Lisencia de conducir: '.$chof['lisencia'].'<br>Certificado de inscripción: '.$chof['certificado'].'</td>
    </tr>
  </thead>
</table>

<br>

<table width="100%" class="cuerpo2" border="0" cellspacing="0">
  <thead> 
    <tr>
      <td width="10%">CODIGO</td>
      <td width="60%">DESCRIPCION</td>
      <td width="10%">PRECIO</td>
      <td width="10%">CANTIDAD</td>
      <td width="10%">IMPORTE</td>
    </tr>
</thead>
<tbody>
';
$rspta = $resumen->detfactura($mostrav['idventa']);
while ($reg = $rspta->fetch_object()){	
$html.='
<tr>
      <td>'.$reg->codigoproducto.'</td>
      <td>'.$reg->nombreproducto.'</td>
      <td>'.$reg->precio.'</td>
      <td>'.$reg->txtCANTIDAD_ARTICULO.'</td>
      <td>'.$reg->importe.'</td>
    </tr>';
}
$html.='
  </tbody>
</table>





</div> 

<hr>


<table width="100%"  class="footer" border="0" cellspacing="0">
  <tbody>

    <tr>
<td width="64%">
<strong>SON: '.numtoletras($mostrav['txtTOTAL']).'</strong>
</td>

<td width="16%" rowspan="4"  class="fg fg2" >
<img src="'.$mempresa['ruc'].'.png" width="70" height="70" />
</td>


<td rowspan="4" class="fg fg2" width="20%" >


<table width="100%" border="0" cellspacing="0"  class="total"  >
        <tbody>
<tr><td class="total2" width="50%"><strong>SUB.TOTAL:</strong></td><td><strong>'.$mostrav['txtSUB_TOTAL'].'</strong></td></tr>
<tr><td class="total2"><strong>GRAVADAS:</strong></td><td><strong>'.$mostrav['txtSUB_TOTAL'].'</strong></td></tr>
<tr><td class="total2"><strong>IGV(18%):</strong></td><td><strong>'.$mostrav['txtIGV'].'</strong></td></tr>
<tr><td class="total2"><strong>TOTAL:</strong></td><td><strong>'.$mostrav['txtTOTAL'].'</strong></td></tr>

        </tbody>
      </table>

</td>

    </tr>
<tr><td >MOTIVO DEL TRASLADO: '.$mostrar['motivo'].'</td></tr>
<tr><td >OBSERVACIONES: '.$mostrar['observacion'].'</td></tr>
<tr><td>PESO:  '.$mostrar['peso'].' / CAJAS:  '.$mostrar['cajas'].'</td></tr>

  </tbody>
  

  
</table>


<table width="100%" class="cuerpo" cellspacing="0">
  <thead>	
  <tr>
      <td width="60%" class="titdocu celda_right" >EMPRESA DE TRANSPORTE</td>
      <td width="40%" class="titdocu" >COMPROBANTE DE PAGO</td>
    </tr>
    <tr>
      <td class="celda_right">NOMBRE: '.$trans['nombre'].'<br>
        DIRECCIÓN: '.$trans['direccion'].'<br>
        RUC: '.$trans['ruc'].'
      </td>
      <td>TIPO: '.$tdocumentor.'<br>
      NÚMERO:  '.$mostrav['txtSERIE'].'-'.$mostrav['txtNUMERO'].'</td>
    </tr>
  </thead>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cabecera">
  <tbody>
    <tr>
      <td width="35%" align="center"></td>
      <td width="30%" align="center">
       <br><br><br><br><br>
       
       <table width="300" border="0" cellspacing="0">
        <tbody>
          <tr>
            <td><hr></td>
          </tr>
          <tr>
            <td align="center">CONFORMIDAD DEL CLIENTE</td>
          </tr>
        </tbody>
      </table></td>
      <td width="35%" align="center">&nbsp;</td>
    </tr>
  </tbody>
</table>



</body> </html>

   
   
 ';
   $dompdf = new DOMPDF();
   $dompdf->set_paper('letter','landscape');
   //$dompdf->set_paper('legal','landscape');
   $dompdf->load_html($html);
   $dompdf->render();
   //$dompdf->stream("pdf".Date('Y-m-d').".pdf");
//$dompdf->stream("ejemplo-basico.pdf", array('Attachment' => 0));
$pdf = $dompdf->output();
//file_put_contents('../'.$ruta, $pdf);
file_put_contents($ruta.$fichero.'.pdf', $pdf);


?>