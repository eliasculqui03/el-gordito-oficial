<?php

//require_once("dompdf/dompdf_config.inc.php");


require_once 'lib/html5lib/Parser.php';
require_once 'lib/php-font-lib/src/FontLib/Autoloader.php';
require_once 'lib/php-svg-lib/src/autoload.php';
require_once 'src/Autoloader.php';
Dompdf\Autoloader::register();
use Dompdf\Dompdf;
use Dompdf\Options;
include "../phpqrcode/qrlib.php";
include "../../intranet/conectar.php";
include "../../intranet/perfil.php";

$ndoc=$_GET['ndoc'];
$ruta=$_GET['ruta'];
$fichero=$_GET['fichero'];
$ruc=$_GET['ruc'];

$sql=mysqli_query($mysqli, "SELECT *FROM CPE WHERE NRO_COMPROBANTE='$ndoc' ");
$row=mysqli_fetch_array($sql);

$sqlm=mysqli_query($mysqli, "SELECT *FROM MONEDA WHERE EMPRESA_ID='$row[ID_EMPRESA]' AND COD_ISO='$row[COD_MONEDA]' ");
$rowm=mysqli_fetch_array($sqlm);

$sqld = mysqli_query($mysqli, "SELECT * from CPE_DETALLE WHERE ID_CABECERA='$row[ID]'  ");

if($row['COD_TIPO_DOCUMENTO']=='03'){ $tdocumento='BOLETA ELECTRÓNICA'; }
if($row['COD_TIPO_DOCUMENTO']=='01'){ $tdocumento='FACTURA ELECTRÓNICA'; }


//QRcode::png("".$text);
$text='6767236723672367';
//DATOS OBLIGATORIOS DE LA SUNAT EN EL QR
/*RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION |TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE |*/


$doc  = $row['NRO_COMPROBANTE'];
$p = explode("-", $doc);

$text=$ruc.' | '.$tdocumento.' | '.$p[0].' | '.$p[1].' | '.$row['TOTAL_IGV'].' | '.$row['TOTAL'].' | '.$row['FECHA_DOCUMENTO'].' | '.$tdocumento.' | '.$row['NRO_DOCUMENTO_CLIENTE'].' |';
QRcode::png($text, $ruc.".png", 'Q',15, 0);

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
	
<td class="cabeza"><h1>ZAMBRANO YACHA JOSE LUIS</h1>
  <strong>SUCURSAL:</strong> MZA. B1 LOTE. 6 A.H. HUAMPANI ALTO ZONA I LIMA - LIMA -  PERÚ<br>
  <strong>TELF. PRINCIPAL:</strong> 9999999<br>
  <strong>TELF. SUCURSAL:</strong> 9999999<br>
      </td>
		
      <td width="30%">
        
        
        <table width="100%" class="cabecera2" cellspacing="0" >
          <tbody>
            <tr>
              <td >'.$ruc.'</td>
            </tr>
            <tr>
              <td class="nfactura">'.$tdocumento.'</td>
            </tr>
            <tr>
              <td >'.$ndoc.'</td>
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
      <td width="60%">'.$row['NRO_DOCUMENTO_CLIENTE'].'</td>
      <td width="10%">FECHA:</td>
      <td width="20%">'.$row['FECHA_DOCUMENTO'].'</td>
    </tr>
    <tr>
      <td>CLIENTE:</td>
      <td>'.$row['RAZON_SOCIAL_CLIENTE'].'</td>
      <td>NRO.GUIA:</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>DIRECCIÓN:</td>
      <td>'.$row['DIRECCION_CLIENTE'].'</td>
      <td>MONEDA:</td>
      <td>'.$rowm['DESCRIPCION'].'</td>
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
while($rowd=mysqli_fetch_array($sqld)) { 
$html.='
<tr>
      <td>'.$rowd['CODIGO'].'</td>
      <td>'.$rowd['DESCRIPCION'].'</td>
      <td>'.$rowd['PRECIO'].'</td>
      <td>'.$rowd['CANTIDAD'].'</td>
      <td>'.$rowd['IMPORTE'].'</td>
    </tr>';
}
$html.='
  </tbody>
</table>





</div> 




<table width="100%"  class="footer" border="0" cellspacing="0">
  <tbody>
    <tr>
<td colspan="3" class="fg"><strong>SON: '.$row['TOTAL_LETRAS'].'</strong></td>
    </tr>
    <tr>
<td width="64%">
Autorizado mediante Resolución de Intendencia N° 032-005-<br>
Representación impresa de la Factura Electrónica<br>

</td>

<td width="16%" rowspan="5"  class="fg fg2" >
<img src="'.$ruc.'.png" width="120" height="120" />
</td>


<td rowspan="5" class="fg fg2" width="20%" >


<table width="100%" border="0" cellspacing="0"  class="total"  >
        <tbody>
<tr><td class="total2" width="50%"><strong>SUB.TOTAL:</strong></td><td><strong>'.$row['SUB_TOTAL'].'</strong></td></tr>
<tr><td class="total2"><strong>GRAVADAS:</strong></td><td><strong>'.$row['TOTAL_GRAVADAS'].'</strong></td></tr>
<tr><td class="total2"><strong>INAFECTA:</strong></td><td><strong>'.$row['TOTAL_INAFECTA'].'</strong></td></tr>
<tr><td class="total2"><strong>EXONERADA:</strong></td><td><strong>'.$row['TOTAL_EXONERADAS'].'</strong></td></tr>
<tr><td class="total2"><strong>GRATUITA:</strong></td><td><strong>'.$row['TOTAL_GRATUITAS'].'</strong></td></tr>
<tr><td class="total2"><strong>DESCUENTO:</strong></td><td><strong>'.$row['TOTAL_DESCUENTO'].'</strong></td></tr>
<tr><td class="total2"><strong>IGV(18%):</strong></td><td><strong>'.$row['TOTAL_IGV'].'</strong></td></tr>
<tr><td class="total2"><strong>ISC:</strong></td><td><strong>'.$row['TOTAL_ISC'].'</strong></td></tr>
<tr><td class="total2"><strong>TOTAL:</strong></td><td><strong>'.$row['TOTAL'].'</strong></td></tr>

        </tbody>
      </table>

</td>

    </tr>
    <tr>
  <td >
    <strong>HASH: '.$row['HASH_CPE'].'</strong>
  </td>
  </tr>
<tr><td>LUIS ENRIQUE ZAMBRANO YACHA</td></tr>
<tr><td>DESCARGA TU COMNPROBANTE: http://www.facturacionelectronica.us/BETA-'.$ruc.'-B.html</td></tr>
<tr>  
<td>
Opración  sujeta al sistma de pago de obligaciones tributarios con el gobierno central SPOT, sujeta a detracción del 10% si esmayor a S/.700.00
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
file_put_contents('../../'.$ruta.$fichero.'.pdf', $pdf);


?>