<?php
/* Demonstration of available options on the pdf417Code() command */
require 'autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

$connector = new FilePrintConnector("php://stdout");
$printer = new Printer($connector);

title($printer, "QR code demo\n");
$testStr = "Testing 123";
$printer -> qrCode($testStr);
$printer -> text("Most simple example\n");
$printer -> feed();

// Demo that alignment is the same as text
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> qrCode($testStr);
$printer -> text("Same example, centred\n");
$printer -> setJustification();
$printer -> feed();

$printer -> cut();
$printer -> close();

function title(Printer $printer, $str)
{
    $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
    $printer -> text($str);
    $printer -> selectPrintMode();
}

?>