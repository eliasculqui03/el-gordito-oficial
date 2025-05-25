<?php

include "../phpqrcode/qrlib.php";

$text='10447915125|01|F001|00000001|IGV|TOTAL|FECHA|6|RUC_CLIENTE|';
QRcode::png($text, "10447915125-F001-00000001.png", 'Q',15, 0);


?>