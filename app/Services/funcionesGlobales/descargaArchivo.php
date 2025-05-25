<?php

$ruta = (isset($_GET['ruta'])) ? $_GET['ruta'] : "";
$archivo = (isset($_GET['archivo'])) ? $_GET['archivo'] : "";
 
header("Content-type: application/octet-stream");
header("Content-Type: application/force-download");
header("Content-Disposition: attachment; filename=\"$archivo\"\n");
readfile($ruta);

?>