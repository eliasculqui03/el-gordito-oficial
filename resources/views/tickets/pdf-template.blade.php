<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comprobante {{ $comprobante->serie }}-{{ $comprobante->numero }}</title>
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
            /* Base centrada para todo el documento */
            text-align: center;
        }

        /* Contenedor principal */
        .ticket {
            width: 100%;
            padding: 3mm 2mm;
            max-width: 300px;
            margin: 0 auto;
        }

        /* Logo de empresa mejorado */
        .company-logo {
            text-align: center;
            margin-bottom: 5px;
            padding: 5px 0;
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
            clear: both;
        }

        /* Detalles del comprobante - centrados y destacados */
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

        /* Sección de información del cliente - alineada a la izquierda pero en contenedor centrado */
        .customer-info {
            margin-bottom: 8px;
            text-align: left;
            display: inline-block;
            width: 100%;
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

        /* TABLA DE ITEMS MEJORADA CON COLUMNAS */
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

        /* TOTALES MEJORADOS Y ALINEADOS A LA DERECHA */
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
        }

        .bold-total {
            font-weight: bold;
            font-size: 12px;
        }

        /* Forma de pago - centrada pero texto alineado a la izquierda */
        .payment-info {
            margin: 8px 0;
            text-align: left;
            padding: 0 5px;
        }

        /* QR Code - centering mejorado */
        .qr-container {
            text-align: center;
            margin: 12px auto;
            width: 100%;
        }

        .qr-title {
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 5px;
            text-transform: uppercase;
            text-align: center;
        }

        .qr-image {
            display: inline-block;
            padding: 5px;
            border: 1px solid #ccc;
            background-color: white;
            border-radius: 4px;
            margin: 0 auto;
        }

        .qr-image img {
            width: 100px;
            height: 100px;
            display: block;
        }

        /* Pie de página - centrado y con mejor espacio */
        .footer {
            margin-top: 12px;
            text-align: center;
            font-size: 9px;
            line-height: 1.4;
            width: 100%;
        }

        .footer p {
            margin-bottom: 3px;
        }

        /* Mensaje legal - centrado con mejor legibilidad */
        .legal {
            margin-top: 8px;
            font-size: 8px;
            text-align: center;
            line-height: 1.3;
            width: 100%;
        }

        /* Mensaje de agradecimiento destacado */
        .thanks-message {
            font-weight: bold;
            font-size: 11px;
            margin: 8px 0;
            text-align: center;
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
        <!-- Logo de la empresa mejorado -->
        <div class="company-logo">
            @if (isset($logoUrl) && $logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo">
            @else
                <!-- Logo por defecto o espacio reservado -->
                <img src="{{ asset('public/images/logo_sucursal.jpg') }}" alt="Logo">
            @endif
        </div>

        <div class="company-name">{{ $xmlData['empresa']['razonSocial'] ?? '' }}</div>
        <div class="company-info">
            {{ $xmlData['empresa']['nombreComercial'] ?? ($xmlData['empresa']['razonSocial'] ?? '') }}<br>
            <strong>RUC:</strong> {{ $xmlData['empresa']['ruc'] ?? '' }}<br>
            {{ $xmlData['empresa']['direccion'] ?? '' }}<br>
            {{ $xmlData['empresa']['distrito'] ?? '' }} -
            {{ $xmlData['empresa']['provincia'] ?? '' }} -
            {{ $xmlData['empresa']['departamento'] ?? '' }}
        </div>

        <div class="separator"></div>

        <!-- Tipo y número de documento (centrado y destacado) -->
        <div class="document-type">
            @if (isset($xmlData['tipoComprobante']) && $xmlData['tipoComprobante'] == '01')
                Factura Electrónica
            @elseif(isset($xmlData['tipoComprobante']) && $xmlData['tipoComprobante'] == '03')
                Boleta de Venta Electrónica
            @else
                Comprobante Electrónico
            @endif
        </div>
        <div class="document-number">{{ $xmlData['numeroComprobante'] ?? '' }}</div>

        <div class="separator"></div>

        <!-- Datos del cliente (alineado a la izquierda pero en contenedor centrado) -->
        <div class="customer-info">
            <div class="info-row">
                <span class="info-label">FECHA:</span>
                <span>{{ date('d/m/Y H:i', strtotime($xmlData['fechaEmision'] ?? now())) }}</span>
            </div>

            @php
                $tipoDoc = isset($xmlData['cliente']['tipoDoc']) ? substr($xmlData['cliente']['tipoDoc'], 0, 1) : '';
                $etiquetaDoc = $tipoDoc == '6' ? 'RUC:' : 'DNI:';
            @endphp

            <div class="info-row">
                <span class="info-label">{{ $etiquetaDoc }}</span>
                <span>{{ $xmlData['cliente']['numDoc'] ?? '' }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">CLIENTE:</span>
                <span>{{ $xmlData['cliente']['razonSocial'] ?? '' }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">DIRECCIÓN:</span>
                <span>{{ $xmlData['cliente']['direccion'] }}</span>
            </div>
        </div>

        <div class="separator"></div>

        <!-- TABLA DE ITEMS MEJORADA CON ESTRUCTURA DE COLUMNAS -->
        <div class="items-section">
            <div class="items-header">
                <div class="table-row">
                    <span class="qty">CANT.</span>
                    <span class="desc">DESCRIPCIÓN</span>
                    <span class="unit">P.UNIT</span>
                    <span class="total">IMPORTE</span>
                </div>
            </div>

            <div class="items-body">
                @foreach ($xmlData['items'] ?? [] as $item)
                    <div class="item-row">
                        <span class="qty">{{ $item['cantidad'] ?? '1' }}</span>
                        <span class="desc">{{ $item['descripcion'] ?? '-' }}</span>
                        <span class="unit">{{ number_format($item['precioVenta'] ?? 0, 2) }}</span>
                        <span
                            class="total">{{ number_format(($item['precioVenta'] ?? 0) * ($item['cantidad'] ?? 1), 2) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="separator"></div>

        <!-- TOTALES ALINEADOS A LA DERECHA -->
        <div class="totals">
            <div class="total-row">
                <span>OP. GRAVADA:</span>
                <span>S/ {{ number_format($xmlData['subtotal'] ?? 0, 2) }}</span>
            </div>
            <div class="total-row">
                <span>I.G.V. 10%:</span>
                <span>S/ {{ number_format($xmlData['igv'] ?? 0, 2) }}</span>
            </div>
            <div class="total-row bold-total">
                <span>TOTAL:</span>
                <span>S/ {{ number_format($xmlData['total'] ?? 0, 2) }}</span>
            </div>
        </div>

        <div class="separator"></div>

        <!-- Forma de pago (mejorada y consistente) -->
        <div class="payment-info">
            <div class="info-row">
                <span class="info-label">FORMA DE PAGO:</span>
                <span>{{ $xmlData['formaPago'] ?? ($xmlData['medioPago'] ?? 'CONTADO') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">MONTO:</span>
                <span>{{ $xmlData['importeLetras'] ?? 'CERO CON 00/100 SOLES' }}</span>
            </div>
        </div>

        <div class="separator"></div>

        <!-- Código QR (mejorado y centrado) -->
        @if (isset($qrCode) && $qrCode)
            <div class="qr-container">
                <div class="qr-title">Código QR</div>
                <div class="qr-image">
                    <img src="{{ $qrCode }}" alt="QR Code">
                </div>
            </div>
            <div class="separator"></div>
        @endif

        <!-- Mensaje de agradecimiento destacado -->
        <div class="thanks-message">¡GRACIAS POR SU PREFERENCIA!</div>

        <!-- Información adicional (centrado y mejorado) -->
        <div class="footer">
            <p>Representación impresa del Comprobante Electrónico</p>
            <p>Consulte su comprobante en: www.sunat.gob.pe</p>
        </div>

        <!-- Mensaje legal (centrado) -->
        <div class="legal">
            Autorizado mediante Resolución de Intendencia N° 032-005<br>
            Representación impresa de la
            {{ isset($xmlData['tipoComprobante']) && $xmlData['tipoComprobante'] == '01' ? 'Factura' : 'Boleta de Venta' }}
            Electrónica
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
