<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comprobante de Pago</title>
    <style>
        /* Reset y estilos base optimizados para ticketera */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            line-height: 1.3;
            color: #000;
            background: #fff;
            width: 72mm;
            text-align: left;
        }

        /* Contenedor principal */
        .ticket {
            width: 100%;
            padding: 3mm 2mm;
            max-width: 300px;
            margin: 0 auto;
            text-align: left;
        }

        /* Solo estos elementos específicos se mantienen centrados */
        .header-centered {
            text-align: center;
        }

        .company-logo {
            text-align: center;
            margin-bottom: 5px;
        }

        .company-logo img {
            max-width: 120px;
            height: auto;
        }

        .company-name {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .company-info {
            font-size: 9px;
            margin-bottom: 5px;
            line-height: 1.4;
        }

        .separator {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        /* Detalles del comprobante - centrados */
        .document-type {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .document-number {
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 5px;
        }

        /* Aviso no electrónico */
        .no-electronic {
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            margin: 5px 0;
            padding: 3px 0;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
        }

        /* Información del cliente - explícitamente a la izquierda */
        .customer-info {
            margin-bottom: 8px;
            text-align: left;
        }

        .info-row {
            display: flex;
            margin-bottom: 3px;
            text-align: left;
        }

        .info-label {
            font-weight: bold;
            min-width: 75px;
            text-align: left;
        }

        /* Tabla de items - explícitamente a la izquierda */
        .items-section {
            text-align: left;
        }

        .items-header {
            display: flex;
            font-weight: bold;
            border-bottom: 1px dashed #000;
            padding-bottom: 3px;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-size: 9px;
            text-align: left;
        }

        .items-header .qty {
            width: 15%;
            text-align: left;
        }

        .items-header .unit {
            width: 15%;
            text-align: left;
        }

        .items-header .desc {
            width: 45%;
            padding-left: 5px;
            text-align: left;
        }

        .items-header .total {
            width: 25%;
            text-align: right;
        }

        .item-row {
            display: flex;
            margin-bottom: 4px;
            text-align: left;
        }

        .item-row .qty {
            width: 15%;
            text-align: left;
        }

        .item-row .unit {
            width: 15%;
            text-align: left;
        }

        .item-row .desc {
            width: 45%;
            padding-left: 5px;
            word-wrap: break-word;
            text-align: left;
        }

        .item-row .total {
            width: 25%;
            text-align: right;
        }

        /* Totales - explícitamente a la izquierda */
        .totals {
            margin-top: 8px;
            text-align: left;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            text-align: left;
        }

        .total-row span:first-child {
            text-align: left;
        }

        .total-row span:last-child {
            text-align: right;
        }

        .bold-total {
            font-weight: bold;
            font-size: 12px;
        }

        /* Forma de pago - explícitamente a la izquierda */
        .payment-info {
            margin: 8px 0;
            text-align: left;
        }

        /* Pie de página - centrado */
        .footer {
            margin-top: 12px;
            text-align: center;
            font-size: 9px;
            line-height: 1.4;
        }

        /* Estilos para impresión */
        @media print {
            @page {
                size: 72mm auto;
                margin: 0;
            }

            body {
                padding: 0;
                margin: 0;
            }

            .ticket {
                padding-top: 5mm;
                padding-bottom: 10mm;
            }
        }
    </style>
</head>

<body>
    <div class="ticket">
        <!-- Encabezado con datos de empresa (centrado) -->
        <div class="header-centered">
            <div class="company-name">{{ $datos['empresa']['nombreComercial'] }}</div>
            <div class="company-info">
                {{ $datos['empresa']['razonSocial'] }}<br>
                <strong>RUC:</strong> {{ $datos['empresa']['ruc'] }}<br>
                {{ $datos['empresa']['direccion'] }}<br>
                {{ $datos['empresa']['distrito'] }}, {{ $datos['empresa']['provincia'] }}
            </div>
        </div>

        <div class="separator"></div>

        <div class="header-centered">
            <div class="document-type">
                @php
                    $tipoComprobante = '';
                    switch ($comprobante->tipo_comprobante_id) {
                        case '01':
                            $tipoComprobante = 'FACTURA';
                            break;
                        case '03':
                            $tipoComprobante = 'BOLETA DE VENTA';
                            break;
                        default:
                            $tipoComprobante = 'COMPROBANTE DE PAGO';
                    }
                @endphp
                {{ $tipoComprobante }}
            </div>
            <div class="document-number">{{ $datos['numeroComprobante'] }}</div>
        </div>

        <!-- Aviso no electrónico -->
        <div class="no-electronic">
            DOCUMENTO DE REFERENCIA - NO VÁLIDO COMO COMPROBANTE ELECTRÓNICO
        </div>

        <!-- Datos del cliente (alineado a la izquierda) -->
        <div class="customer-info">
            <div class="info-row">
                <span class="info-label">FECHA:</span>
                <span>{{ $datos['fechaEmision'] }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">{{ $datos['cliente']['tipoDoc'] == '6' ? 'RUC:' : 'DOC:' }}</span>
                <span>{{ $datos['cliente']['numDoc'] }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">CLIENTE:</span>
                <span>{{ $datos['cliente']['razonSocial'] }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">DIRECCIÓN:</span>
                <span>{{ $datos['cliente']['direccion'] }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">MONEDA:</span>
                <span>{{ $datos['moneda'] == 'PEN' ? 'SOLES' : $datos['moneda'] }}</span>
            </div>
        </div>

        <div class="separator"></div>

        <!-- Tabla de items (alineado a la izquierda) -->
        <div class="items-section">
            <div class="items-header">
                <span class="qty">Cant.</span>
                <span class="unit">Unid.</span>
                <span class="desc">Descripción</span>
                <span class="total">Total</span>
            </div>

            @foreach ($datos['items'] as $item)
                <div class="item-row">
                    <span class="qty">{{ $item['cantidad'] }}</span>
                    <span class="unit">{{ $item['unidad'] }}</span>
                    <span class="desc">{{ $item['descripcion'] }}</span>
                    <span class="total">{{ number_format($item['cantidad'] * $item['precioVenta'], 2) }}</span>
                </div>
            @endforeach
        </div>

        <div class="separator"></div>

        <!-- Totales (alineado a la izquierda, valores a la derecha) -->
        <div class="totals">
            <!-- Mostramos solo el total final sin desglosar el IGV -->
            <div class="total-row bold-total">
                <span>TOTAL {{ $datos['moneda'] == 'PEN' ? 'S/' : $datos['moneda'] }}:</span>
                <span>{{ number_format($datos['total'], 2) }}</span>
            </div>
        </div>

        <div class="separator"></div>

        <!-- Forma de pago (alineado a la izquierda) -->
        <div class="payment-info">
            <div class="info-row">
                <span class="info-label">FORMA DE PAGO:</span>
                <span>{{ $datos['formaPago'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">MEDIO DE PAGO:</span>
                <span>{{ $datos['medioPago'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">SON:</span>
                <span>{{ $datos['importeLetras'] }}</span>
            </div>
        </div>

        <div class="separator"></div>

        <!-- Pie de página (centrado) -->
        <div class="footer">
            <p>Este documento es una impresión de referencia.</p>
            <p>No tiene validez fiscal ni tributaria.</p>
            <p class="bold-total">¡GRACIAS POR SU PREFERENCIA!</p>
        </div>
    </div>

    <script>
        // Auto-imprimir inmediatamente
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>

</html>
