<?php

//require_once("dompdf/dompdf_config.inc.php");

$rutat=	'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$rutat= str_replace("plugins/dompdf/nota.php", "", $rutat);

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

$sql="SELECT *FROM venta WHERE idventa='$id' ";
$mostrar= ejecutarConsultaSimpleFila($sql);

$sql2="SELECT *FROM persona WHERE idpersona='$mostrar[txtID_CLIENTE]' ";
$mcliente= ejecutarConsultaSimpleFila($sql2);

$sql3="SELECT *FROM config WHERE estado='1' ";
$mempresa= ejecutarConsultaSimpleFila($sql3);

if($mempresa['tipo']=='03'){ $tipop='BETA'; }else{ $tipop='PRODUCCION'; }

$ruta="../../api_cpe/".$tipop."/".$mempresa['ruc']."/";
$fichero=$mempresa['ruc'].'-'.$mostrar['txtID_TIPO_DOCUMENTO'].'-'.$mostrar['txtSERIE'].'-'.$mostrar['txtNUMERO'];

if($mostrar['txtID_TIPO_DOCUMENTO']=='07'){ $tdocumento='NOTA DE CREDITO ELECTRÓNICA'; }
if($mostrar['txtID_TIPO_DOCUMENTO']=='08'){ $tdocumento='NOTA DE DEBITO ELECTRÓNICA'; }

if($mostrar['docmodifica_tipo']=='01'){ $tdocumentom='01 - FACTURA ELECTRÓNICA'; }
if($mostrar['docmodifica_tipo']=='03'){ $tdocumentom='03 - BOLETA ELECTRÓNICA'; }

if($mostrar['txtID_MONEDA']=='PEN'){ $valmoneda='SOLES'; }
if($mostrar['txtID_MONEDA']=='USD'){ $valmoneda='DOLARES'; }
if($mostrar['txtID_MONEDA']=='EUR'){ $valmoneda='EUROS'; }


//QRcode::png("".$text);
//DATOS OBLIGATORIOS DE LA SUNAT EN EL QR
//RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE //EMISION |TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE |

$text=$mempresa['ruc'].' | '.$tdocumento.' | '.$mostrar['txtSERIE'].' | '.$mostrar['txtNUMERO'].' | '.$mostrar['txtIGV'].' | '.$mostrar['txtTOTAL'].' | '.date("Y-m-d", strtotime($mostrar['txtFECHA_DOCUMENTO'])).' | '.$mcliente['tipo_documento'].' | '.$mcliente['txtID_CLIENTE'].' |';
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
.titdocu{ font-size:13px; text-align: center; }
table { width: 100%; color:black; }
  
tbody { background-color: #ffffff; }
th,td { padding: 3pt; }           
.celda_right{  border-right: 1px solid black;  }
.celda_left{  border-left: 1px solid black; }         

.footer th, .footer td { padding: 1pt; border: solid 1px #000000; }
.footer { position: fixed; bottom: 150px; font-size:10px;  width: 100%; border: solid 0px #000000; }
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
<td width="6%"><img src="../../images/tulogo.png" width="266" height="60" /></td>
<td class="cabeza"><h1>'.$mempresa['nombre_comercial'].'</h1>
  <strong>SUCURSAL:</strong> '.$mempresa['direccion'].'<br>
  <strong>TELF. PRINCIPAL:</strong> '.$mempresa['telefono'].'<br>
      </td>
		
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
              <td >'.$mostrar['txtSERIE'].'-'.$mostrar['txtNUMERO'].'</td>
            </tr>
          </tbody>
        </table>
        
        
        
        
      </td>
    </tr>
  </tbody>
</table>

<br>
<table width="100%" class="cuerpo" cellspacing="0">
<thead>
    <tr>
      <td width="10%">NRO.DOCU.:</td>
      <td width="60%">'.$mcliente['txtID_CLIENTE'].'</td>
      <td width="10%">FECHA:</td>
      <td width="20%">'.date("Y-m-d", strtotime($mostrar['txtFECHA_DOCUMENTO'])).'</td>
    </tr>
    <tr>
      <td>CLIENTE:</td>
      <td>'.$mcliente['txtRAZON_SOCIAL'].'</td>
      <td>NRO.GUIA:</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>DIRECCIÓN:</td>
      <td>'.$mcliente['direccion'].'</td>
      <td>MONEDA:</td>
      <td>'.$valmoneda.'</td>
    </tr>
  </thead>
</table>

<table width="100%" class="cuerpo" cellspacing="0">
<thead>
    <tr>
<td class="titdocu" >DOCUMENTO QUE MODIFICA</td>
    </tr>
  </thead>
</table>
<table width="100%" class="cuerpo" cellspacing="0">
<thead>
    <tr>
      <td>TIPO DOCUMENTO: '.$tdocumentom.'</td>
      <td>NUMERO: '.$mostrar['docmodifica'].'</td>
      <td>MOTIVO: '.$mostrar['modifica_motivo'].' - '.$mostrar['modifica_motivod'].'</td>
    </tr>
  </thead>
</table>


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
$rspta = $resumen->detfactura($mostrar['idventa']);
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




<table width="100%"  class="footer" border="0" cellspacing="0">
  <tbody>
    <tr>
<td colspan="3" class="fg"><strong>SON: '.numtoletras($mostrar['txtTOTAL']).'</strong></td>
    </tr>
    <tr>
<td width="64%">
<br>Representación impresa de la '.$tdocumento.'<br>

</td>

<td width="16%" rowspan="5"  class="fg fg2" >
<img src="'.$mempresa['ruc'].'.png" width="120" height="120" />
</td>


<td rowspan="5" class="fg fg2" width="20%" >


<table width="100%" border="0" cellspacing="0"  class="total"  >
        <tbody>
<tr><td class="total2" width="50%"><strong>SUB.TOTAL:</strong></td><td><strong>'.$mostrar['txtSUB_TOTAL'].'</strong></td></tr>
<tr><td class="total2"><strong>GRAVADAS:</strong></td><td><strong>'.$mostrar['txtSUB_TOTAL'].'</strong></td></tr>
<tr><td class="total2"><strong>INAFECTA:</strong></td><td><strong>0.00</strong></td></tr>
<tr><td class="total2"><strong>EXONERADA:</strong></td><td><strong>0.00</strong></td></tr>
<tr><td class="total2"><strong>GRATUITA:</strong></td><td><strong>0.00</strong></td></tr>
<tr><td class="total2"><strong>DESCUENTO:</strong></td><td><strong>0.00</strong></td></tr>
<tr><td class="total2"><strong>IGV(18%):</strong></td><td><strong>'.$mostrar['txtIGV'].'</strong></td></tr>
<tr><td class="total2"><strong>ISC:</strong></td><td><strong>0.00</strong></td></tr>
<tr><td class="total2"><strong>TOTAL:</strong></td><td><strong>'.$mostrar['txtTOTAL'].'</strong></td></tr>

        </tbody>
      </table>

</td>

    </tr>
    <tr>
  <td >
    <strong>HASH: '.$mostrar['hash_cpe'].'</strong>
  </td>
  </tr>
<tr><td>'.$mcliente['nombre'].'</td></tr>
<tr><td>---</td></tr>
<tr>  
<td>
Operación  sujeta al sistma de pago de obligaciones tributarios con el gobierno central SPOT, sujeta a detracción del 10% si es mayor a S/.700.00
  </td>
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