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

$fichero=$_GET['fichero'];
$ruta=$_GET['ruta'];
//echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" /><hr/>';
$text='6767236723672367';
//QRcode::png("".$text);
QRcode::png("".$text, $text.".png", 'H',15, 2);

/*
$dompdf = new DOMPDF();
$dompdf->load_html(file_get_contents('testing.html'));
$dompdf->render();
$dompdf->stream("ejemplo-basico.pdf", array('Attachment' => 0));
*/

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
    margin-top: 2em;
    text-align: center;
}
.cabecera h1 {
    font-size:17px; padding-bottom: 0px; margin-bottom: 0px;
}
.cabecera th, .cabecera td { text-align: center; } 

.cabecera2 table { border-collapse: collapse; border: solid 1px #000000;}
.cabecera2 th, .cabecera2 td { text-align: center; border-collapse: collapse; border: solid 1px #000000; font-size:12px; } 
.cuerpo table { border-collapse: collapse; margin-top:1px; border: solid 1px #000000; }
.cuerpo thead { border: solid 1px #000000; } 
.cuerpo2 thead { border: solid 1px #000000; } 

table { width: 100%; color:black; }
  
tbody { background-color: #ffffff; }
th,td { padding: 3pt; }           
.celda_right{  border-right: 1px solid black;  }
.celda_left{  border-left: 1px solid black; }         

.footer { position: fixed; top: 650px; font-size:8px;  width: 100%; border: solid 0px #000000; }
.footer th, .footer td { padding: 1pt; border: solid 1px #000000; }
.footer { position: fixed; top: 750px; font-size:8px;  width: 100%; border: solid 0px #000000; }
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
	
<td width="20%">
<img src="../../pag_cliente/img/tulogo.png" width="160" height="80" />
</td>
		
      <td width="60%"><h1>ZAMBRANO YACHA JOSE LUIS</h1>
        MZA. B1 LOTE. 6 A.H. HUAMPANI ALTO ZONA I LIMA - LIMA -  PERÚ</td>
      <td width="20%">
	  

	  <table width="100%" class="cabecera2" cellspacing="0" >
        <tbody>
          <tr>
            <td >10415898890</td>
          </tr>
          <tr>
            <td >FACTURA ELECTRONICA</td>
          </tr>
          <tr>
            <td >F001-000021</td>
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
      <td width="60%">10447915125</td>
      <td width="10%">FECHA:</td>
      <td width="20%">2/7/18 12:00 AM</td>
    </tr>
    <tr>
      <td>CLIENTE:</td>
      <td>ZAMBRANO YACHA JOSE LUIS</td>
      <td>NRO.GUIA:</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>DIRECCIÓN:</td>
      <td>MZA. B1 LOTE. 6 A.H. HUAMPANI ALTO</td>
      <td>MONEDA:</td>
      <td>SOLES </td>
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
    <tr>
      <td>000001</td>
      <td>prueba producto</td>
      <td>5.33</td>
      <td>45.000</td>
      <td>173.85</td>
    </tr>
  </tbody>
</table>





</div> 

<table width="100%"  class="footer" border="0" cellspacing="0">
  <tbody>
    <tr>
<td colspan="2" class="fg"><strong>SON: CIENTO SETENTA Y TRES 85/100 SOL.</strong></td>
    </tr>
    <tr>
<td>
Autorizado mediante Resolución de Intendencia N° 032-005-<br>
Representación impresa de la Factura Electrónica<br>
Consulte su documento electrónico en: http://www.camexperu.org.pe/v1/pag_cliente/index.php
</td>
<td rowspan="2" class="fg fg2" >


<table width="100%" border="0" cellspacing="0"  class="total"  >
        <tbody>
<tr><td class="total2" width="50%"><strong>SUB.TOTAL:</strong></td><td><strong>S/ 147.33</strong></td></tr>
<tr><td class="total2"><strong>GRAVADAS:</strong></td><td><strong>S/ 147.33</strong></td></tr>
<tr><td class="total2"><strong>INAFECTA:</strong></td><td><strong>S/ 0.00</strong></td></tr>
<tr><td class="total2"><strong>EXONERADA:</strong></td><td><strong>S/ 0.00</strong></td></tr>
<tr><td class="total2"><strong>GRATUITA:</strong></td><td><strong>S/ 0.00</strong></td></tr>
<tr><td class="total2"><strong>DESCUENTO:</strong></td><td><strong>S/ 66.00</strong></td></tr>
<tr><td class="total2"><strong>IGV(18%):</strong></td><td><strong>S/ S/ 26.52</strong></td></tr>
<tr><td class="total2"><strong>ISC:</strong></td><td><strong>S/ 0.00</strong></td></tr>
<tr><td class="total2"><strong>TOTAL:</strong></td><td><strong>S/ 173.85</strong></td></tr>

        </tbody>
      </table>

</td>

    </tr>
    <tr>
      <td class="fg2"><img src="'.$text.'.png" width="100" height="100" /></td>
    </tr>
    <tr>
      <td class="fg" width="65%"><strong>HASH: G1zk1iHVDuP1RhCFWAPgkYKU+ok=</strong></td>
      <td class="fg3" width="35%" style="border: solid 0px;">Opración  sujeta al sistma de pago de obligaciones tributarios con el gobierno central SPOT, sujeta a detracción del 10% si esmayor a S/.700.00</td>
    </tr>
  </tbody>
</table>




</body> </html>

   
   
 ';
   $dompdf = new DOMPDF();
   //$dompdf->set_paper('letter','landscape');
   //$dompdf->set_paper('legal','landscape');
   $dompdf->load_html($html);
   $dompdf->render();
   //$dompdf->stream("pdf".Date('Y-m-d').".pdf");
//$dompdf->stream("ejemplo-basico.pdf", array('Attachment' => 0));
$pdf = $dompdf->output();
file_put_contents('../'.$ruta.$fichero.'.pdf', $pdf);


?>