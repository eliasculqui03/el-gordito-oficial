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
            text-align: center;
            /* Centramos todo el contenido por defecto */
        }

        /* Contenedor principal */
        .ticket {
            width: 100%;
            padding: 3mm 2mm;
            max-width: 300px;
            margin: 0 auto;
        }

        /* Logo de empresa */
        .company-logo {
            text-align: center;
            margin-bottom: 8px;
        }

        .company-logo img {
            width: 100px;
        }

        /* Datos de empresa */
        .company-name {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 3px;
            text-transform: uppercase;
            text-align: center;
        }

        .company-info {
            font-size: 9px;
            margin-bottom: 5px;
            line-height: 1.4;
            text-align: center;
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

        /* Información del cliente - alineada a la izquierda para legibilidad */
        .customer-info {
            margin-bottom: 8px;
            text-align: left;
            padding: 0 5px;
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

        .info-value {
            flex: 1;
        }

        /* TABLA DE ITEMS EN FORMATO COLUMNAS */
        .items-section {
            width: 100%;
            padding: 0 5px;
        }

        .items-header {
            display: table;
            width: 100%;
            font-weight: bold;
            border-bottom: 1px dashed #000;
            padding-bottom: 3px;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-size: 9px;
        }

        .items-header .table-row {
            display: table-row;
        }

        .items-header .qty,
        .items-header .desc,
        .items-header .unit,
        .items-header .total {
            display: table-cell;
            padding: 2px 3px;
        }

        .items-header .qty {
            width: 15%;
            text-align: center;
        }

        .items-header .desc {
            width: 40%;
            text-align: center;
        }

        .items-header .unit {
            width: 20%;
            text-align: center;
        }

        .items-header .total {
            width: 25%;
            text-align: center;
        }

        .items-body {
            display: table;
            width: 100%;
        }

        .item-row {
            display: table-row;
        }

        .item-row .qty,
        .item-row .desc,
        .item-row .unit,
        .item-row .total {
            display: table-cell;
            padding: 2px 3px;
            vertical-align: top;
        }

        .item-row .qty {
            width: 15%;
            text-align: center;
        }

        .item-row .desc {
            width: 40%;
            text-align: left;
            word-wrap: break-word;
        }

        .item-row .unit {
            width: 20%;
            text-align: right;
        }

        .item-row .total {
            width: 25%;
            text-align: right;
        }

        /* TOTALES ALINEADOS A LA DERECHA */
        .totals {
            margin-top: 8px;
            width: 100%;
            padding: 0 5px;
            text-align: right;
        }

        .total-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 3px;
            width: 100%;
        }

        .total-row span:first-child {
            text-align: left;
            margin-right: 5px;
        }

        .total-row span:last-child {
            text-align: right;
            min-width: 70px;
            font-weight: bold;
        }

        .bold-total {
            font-weight: bold;
            font-size: 12px;
        }

        /* Forma de pago - alineada a la izquierda */
        .payment-info {
            margin: 8px 0;
            text-align: left;
            padding: 0 5px;
        }

        /* Pie de página - centrado */
        .footer {
            margin-top: 12px;
            text-align: center;
            font-size: 9px;
            line-height: 1.4;
        }

        .thanks-message {
            font-weight: bold;
            font-size: 11px;
            margin-top: 8px;
        }

        /* QR Code para facturación electrónica */
        .qr-code {
            text-align: center;
            margin: 10px 0;
        }

        .qr-code img {
            width: 100px;
            height: 100px;
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
        <!-- Encabezado con logo y datos de empresa (centrado) -->
        <div class="company-logo">
            <!-- Reemplazar con la ruta correcta a tu logo -->
            <img src="{{ asset('public/images/logo_sucursal.jpg') }}" alt="Logo">
        </div>

        <div class="company-name">{{ $datos['empresa']['nombreComercial'] }}</div>
        <div class="company-info">
            {{ $datos['empresa']['razonSocial'] }}<br>
            <strong>RUC:</strong> {{ $datos['empresa']['ruc'] }}<br>
            {{ $datos['empresa']['direccion'] }}<br>
            {{ $datos['empresa']['distrito'] }} -
            {{ $datos['empresa']['provincia'] }} -
            {{ $datos['empresa']['departamento'] }}
        </div>

        <div class="separator"></div>

        <!-- Tipo y número de documento (centrado) -->
        <div class="document-type">
            TICKET DE VENTA
        </div>
        <div class="document-number">{{ $datos['numeroComprobante'] }}</div>

        <div class="separator"></div>

        <!-- Datos del cliente (alineado a la izquierda para mayor legibilidad) -->
        <div class="customer-info">
            <div class="info-row">
                <span class="info-label">FECHA:</span>
                <span class="info-value">{{ $datos['fechaEmision'] }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">{{ $datos['cliente']['tipoDoc'] == '6' ? 'RUC:' : 'DOC:' }}</span>
                <span class="info-value">{{ $datos['cliente']['numDoc'] }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">CLIENTE:</span>
                <span class="info-value">{{ $datos['cliente']['razonSocial'] }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">DIRECCIÓN:</span>
                <span class="info-value">{{ $datos['cliente']['direccion'] }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">MONEDA:</span>
                <span class="info-value">{{ $datos['moneda'] == 'PEN' ? 'SOLES' : $datos['moneda'] }}</span>
            </div>
        </div>

        <div class="separator"></div>

        <!-- TABLA DE ITEMS EN FORMATO COLUMNAS -->
        <div class="items-section">
            <div class="items-header">
                <div class="table-row">
                    <span class="qty">Cant.</span>
                    <span class="desc">Descripción</span>
                    <span class="unit">P.Unit</span>
                    <span class="total">Importe</span>
                </div>
            </div>

            <div class="items-body">
                @foreach ($datos['items'] as $item)
                    <div class="item-row">
                        <span class="qty">{{ $item['cantidad'] }}</span>
                        <span class="desc">{{ $item['descripcion'] }}</span>
                        <span class="unit">{{ number_format($item['precioVenta'] ?? '', 2) }}</span>
                        <span class="total">{{ number_format($item['cantidad'] * $item['precioVenta'], 2) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="separator"></div>

        <!-- TOTALES ALINEADOS A LA DERECHA -->
        <div class="totals">

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
                <span class="info-value">{{ $datos['formaPago'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">SON:</span>
                <span class="info-value">{{ $datos['importeLetras'] }}</span>
            </div>
        </div>

        <!-- QR Code para factura electrónica (si aplica) -->
        @if (isset($comprobante) && ($comprobante->tipo_comprobante_id == '01' || $comprobante->tipo_comprobante_id == '03'))
            <div class="qr-code">
                <img src="data:image/png;base64,{{ $datos['qrcode'] ?? '' }}" alt="QR Code">
            </div>
        @endif

        <div class="separator"></div>

        <!-- Pie de página (centrado) -->
        <div class="footer">
            <p class="thanks-message">¡GRACIAS POR SU PREFERENCIA!</p>
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
