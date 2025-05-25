<?php
require_once '../plugins/dompdf/lib/html5lib/Parser.php';
require_once '../plugins/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once '../plugins/dompdf/lib/php-svg-lib/src/autoload.php';
require_once '../plugins/dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();

use Dompdf\Dompdf;
use Dompdf\Options;
include "../plugins/phpqrcode/qrlib.php";

function pdfFacBol($ruta, $cab, $detalle, $hash) {
    try {
        //===========TIPO COMPROBANTE EN TEXTO============
        if ($cab['COD_TIPO_DOCUMENTO']=="01"){
            $tipoDocumento="FACTURA ELECTRONICA";
        }
        if ($cab['COD_TIPO_DOCUMENTO']=="03"){
            $tipoDocumento="BOLETA VENTA ELECTRONICA";            
        }
        //===========TIPO MONEDA EN TEXTO============
        if ($cab['COD_MONEDA']=="PEN"){
            $tipoMoneda="SOL";
        }
        if ($cab['COD_MONEDA']=="USD"){
            $tipoMoneda="DOLARES AMERICANOS";            
        }
        if ($cab['COD_MONEDA']=="EUR"){
            $tipoMoneda="EUROS";            
        }
        
        $text = $cab['NRO_DOCUMENTO_EMPRESA'] . '|' . $cab['COD_TIPO_DOCUMENTO'] . '|' . str_replace('-', '|', $cab['NRO_COMPROBANTE']) . '|' . $cab['TOTAL_IGV'] . '|' . $cab['TOTAL'] . '|' . $cab['FECHA_DOCUMENTO'] . '|' . $cab['TIPO_DOCUMENTO_CLIENTE'] . '|' . $cab['NRO_DOCUMENTO_CLIENTE'] . '|' . $hash;
        QRcode::png($text, dirname(__FILE__) . '/' . $ruta . '.PNG', 'Q', 15, 0);


        $html = '
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
<td width="6%"><img src="../api_cpe/LOGO/'.$cab['NRO_DOCUMENTO_EMPRESA'].'.png" width="266" height="60" /></td>	
<td class="cabeza"><h1>' . $cab['NOMBRE_COMERCIAL_EMPRESA'] . '</h1>
  <strong>SUCURSAL:</strong> ' . $cab['DIRECCION_EMPRESA'] . '<br>
  <strong>TELF. PRINCIPAL:</strong>' . $cab['TELEFONO_EMPRESA'] . '<br>
</td>	
      <td width="30%">
        <table width="100%" class="cabecera2" cellspacing="0" >
          <tbody>
            <tr>
              <td >RUC N° ' . $cab['NRO_DOCUMENTO_EMPRESA'] . '</td>
            </tr>
            <tr>
              <td class="nfactura">' . $tipoDocumento . '</td>
            </tr>
            <tr>
              <td >' . $cab['NRO_COMPROBANTE'] . '</td>
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
      <td width="60%">' . $cab['NRO_DOCUMENTO_CLIENTE'] . '</td>
      <td width="10%">FECHA:</td>
      <td width="20%">' . date("Y-m-d", strtotime($cab['FECHA_DOCUMENTO'])) . '</td>
    </tr>
    <tr>
      <td>CLIENTE:</td>
      <td>' . $cab['RAZON_SOCIAL_CLIENTE'] . '</td>
      <td>NRO.GUIA:</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>DIRECCIÓN:</td>
      <td>' . $cab['DIRECCION_CLIENTE'] . '</td>
      <td>MONEDA:</td>
      <td>' . $tipoMoneda . '</td>
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
for ($i = 0; $i < count($detalle); $i++) {
            $html.='
<tr>
      <td>' . $detalle[$i]["txtCODIGO_DET"] . '</td>
      <td>' . $detalle[$i]["txtDESCRIPCION_DET"] . '</td>
      <td>' . $detalle[$i]["txtPRECIO_DET"] . '</td>
      <td>' . $detalle[$i]["txtCANTIDAD_DET"] . '</td>
      <td>' . $detalle[$i]["txtIMPORTE_DET"] . '</td>
    </tr>';
        }
        $html.='
  </tbody>
</table>
</div> 
<table width="100%"  class="footer" border="0" cellspacing="0">
  <tbody>
    <tr>
<td colspan="3" class="fg"><strong>SON: ' . $cab['TOTAL_LETRAS'] . '</strong></td>
    </tr>
    <tr>
<td width="64%">
<br>
Representación impresa de la ' . $tipoDocumento . '<br>

</td>

<td width="16%" rowspan="5"  class="fg fg2" >
<img src="' . $ruta . '.PNG" width="120" height="120" />
</td>


<td rowspan="5" class="fg fg2" width="20%" >


<table width="100%" border="0" cellspacing="0"  class="total"  >
        <tbody>
<tr><td class="total2" width="50%"><strong>SUB.TOTAL:</strong></td><td><strong>' . $cab['SUB_TOTAL'] . '</strong></td></tr>
<tr><td class="total2"><strong>GRAVADAS:</strong></td><td><strong>' . $cab['SUB_TOTAL'] . '</strong></td></tr>
<tr><td class="total2"><strong>INAFECTA:</strong></td><td><strong>' . $cab['SUB_TOTAL'] . '</strong></td></tr>
<tr><td class="total2"><strong>EXONERADA:</strong></td><td><strong>' . $cab['SUB_TOTAL'] . '</strong></td></tr>
<tr><td class="total2"><strong>GRATUITA:</strong></td><td><strong>' . $cab['SUB_TOTAL'] . '</strong></td></tr>
<tr><td class="total2"><strong>DESCUENTO:</strong></td><td><strong>0.00</strong></td></tr>
<tr><td class="total2"><strong>IGV(18%):</strong></td><td><strong>' . $cab['TOTAL_IGV'] . '</strong></td></tr>
<tr><td class="total2"><strong>ISC:</strong></td><td><strong>0.00</strong></td></tr>
<tr><td class="total2"><strong>TOTAL:</strong></td><td><strong>' . $cab['TOTAL'] . '</strong></td></tr>

        </tbody>
      </table>

</td>

    </tr>
    <tr>
  <td >
    <strong>HASH: ' . $hash . '</strong>
  </td>
  </tr>
<tr><td>' . $cab['RAZON_SOCIAL_CLIENTE'] . '</td></tr>
<tr><td>---</td></tr>
<tr>  
<td>
Operación  sujeta al sistma de pago de obligaciones tributarios con el gobierno central SPOT, sujeta a detracción del 10% si es mayor a S/.700.00
  </td>
</tr>
  </tbody>
</table>
</body> 
</html>';

        $dompdf = new DOMPDF();
        $dompdf->set_paper('letter', 'landscape');
        //$dompdf->set_paper('legal','landscape');
        $dompdf->load_html($html);
        $dompdf->render();
        $pdf = $dompdf->output();
        file_put_contents(dirname(__FILE__) . '/' . $ruta . '.PDF', $pdf);
    } catch (Exception $ex) {
        
    }
}

function pdfFacBolTicket($ruta, $cab, $detalle, $hash) {
    //https://parzibyte.me/blog/2017/10/17/imprimir-ticket-en-impresora-termica-usando-javascript/
    try {
        //===========TIPO COMPROBANTE EN TEXTO============
        if ($cab['COD_TIPO_DOCUMENTO']=="01"){
            $tipoDocumento="FACTURA ELECTRONICA";
        }
        if ($cab['COD_TIPO_DOCUMENTO']=="03"){
            $tipoDocumento="BOLETA VENTA ELECTRONICA";            
        }
        //===========TIPO MONEDA EN TEXTO============
        if ($cab['COD_MONEDA']=="PEN"){
            $tipoMoneda="SOL";
        }
        if ($cab['COD_MONEDA']=="USD"){
            $tipoMoneda="DOLARES AMERICANOS";            
        }
        if ($cab['COD_MONEDA']=="EUR"){
            $tipoMoneda="EUROS";            
        }
        
        $hora=time();
        
        $text = $cab['NRO_DOCUMENTO_EMPRESA'] . '|' . $cab['COD_TIPO_DOCUMENTO'] . '|' . str_replace('-', '|', $cab['NRO_COMPROBANTE']) . '|' . $cab['TOTAL_IGV'] . '|' . $cab['TOTAL'] . '|' . $cab['FECHA_DOCUMENTO'] . '|' . $cab['TIPO_DOCUMENTO_CLIENTE'] . '|' . $cab['NRO_DOCUMENTO_CLIENTE'] . '|' . $hash;
        QRcode::png($text, dirname(__FILE__) . '/' . $ruta . '.PNG', 'Q', 15, 0);


        $html = '<!DOCTYPE html>
<html>

<head>
   <style type="text/css">
   * {
        font-size: 12px;
        font-family: "Times New Roman";
    }

    td,
    th,
    tr,
    table {
        border-top: 1px solid black;
        border-collapse: collapse;
    }

    td.producto,
    th.producto {
        width: 75px;
        max-width: 75px;
    }

    td.cantidad,
    th.cantidad {
        width: 40px;
        max-width: 40px;
        word-break: break-all;
    }

    td.precio,
    th.precio {
        width: 40px;
        max-width: 40px;
        word-break: break-all;
    }

    .centrado {
        text-align: center;
        align-content: center;
    }

    .ticket {
        width: 155px;
        max-width: 155px;
    }

    img {
        max-width: inherit;
        width: inherit;
    }
   </style>
</head>

<body>
    <div class="ticket">
        <img src="../api_cpe/LOGO/'.$cab['NRO_DOCUMENTO_EMPRESA'].'.png" alt="Logotipo">
        <p class="centrado">' . $tipoDocumento . '
            <br>' . $cab['NRO_DOCUMENTO_EMPRESA'] . '
            <br>' . $cab['NOMBRE_COMERCIAL_EMPRESA'] . '
            <br>' . $cab['NRO_COMPROBANTE'] .
            '<br>-------------------------------------------
        </p>   
        <p> 
            Fecha: ' . date("Y-m-d", strtotime($cab['FECHA_DOCUMENTO'])) . '
            <br>Moneda: ' . $tipoMoneda . '
            <br>Nro. Documento: ' . $cab['NRO_DOCUMENTO_CLIENTE'] . '
            <br>Cliente: ' . $cab['RAZON_SOCIAL_CLIENTE'] . '  
            <br>Direccion: ' . $cab['DIRECCION_CLIENTE'] . 
        '</p>    
        <table>
            <thead>
                <tr>
                    <th class="cantidad">CANT</th>
                    <th class="producto">PRODUCTO</th>
                    <th class="precio">TOTAL</th>
                </tr>
            </thead>
            <tbody>';
        for ($i = 0; $i < count($detalle); $i++) {
            $html.='
                <tr>
                    <td class="cantidad">' . $detalle[$i]["txtCANTIDAD_DET"] . '</td>
                    <td class="producto">' . $detalle[$i]["txtCODIGO_DET"].' - '.$detalle[$i]["txtDESCRIPCION_DET"] . '</td>
                    <td class="precio">' . $detalle[$i]["txtIMPORTE_DET"] . '</td>
                </tr>';
        }
            $html.='</tbody>
        </table>
        ----------------------------------------------  
        <p>SUB. TOTAL: '.$cab['SUB_TOTAL'].'
            <br>IGV(18%): '.$cab['TOTAL_IGV'].'
            <br>TOTAL: '.$cab['TOTAL'].
        '</p>
        <p class="centrado">¡GRACIAS POR SU COMPRA!
            <br>'.$hash.'</p>
        <img src="' . $ruta . '.PNG" width="80" height="90" />
    </div>
</body>

</html>';

        $dompdf = new DOMPDF();
        //$dompdf->set_paper('letter', 'landscape');
        $dompdf->set_paper('A4', 'portrait');
        //$dompdf->set_paper('legal','landscape');
        $dompdf->load_html($html);
        $dompdf->render();
        $pdf = $dompdf->output();
        file_put_contents(dirname(__FILE__) . '/' . $ruta . '.PDF', $pdf);
    } catch (Exception $ex) {
        
    }
}

?>