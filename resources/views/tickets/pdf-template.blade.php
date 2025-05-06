<!DOCTYPE html>
<html lang="en">

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
            text-align: left;
            /* Alineación global a la izquierda */
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

        .items-header .desc {
            width: 55%;
            padding-left: 5px;
            text-align: left;
        }

        .items-header .total {
            width: 30%;
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

        .item-row .desc {
            width: 55%;
            padding-left: 5px;
            word-wrap: break-word;
            text-align: left;
        }

        .item-row .total {
            width: 30%;
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

        /* QR Code - centrado */
        .qr-container {
            text-align: center;
            margin: 12px 0;
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
        }

        .qr-image img {
            width: 90px;
            height: 90px;
        }

        /* Pie de página - centrado */
        .footer {
            margin-top: 12px;
            text-align: center;
            font-size: 9px;
            line-height: 1.4;
        }

        /* Mensaje legal - centrado */
        .legal {
            margin-top: 8px;
            font-size: 8px;
            text-align: center;
            line-height: 1.3;
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
            <!-- Opcional: Logo de la empresa -->
            <div class="company-logo">
                @if (isset($logoUrl) && $logoUrl)
                    <img src="{{ $logoUrl }}" alt="Logo">
                @endif
            </div>

            <div class="company-name">{{ $xmlData['empresa']['razonSocial'] ?? 'Nombre de Empresa' }}</div>
            <div class="company-info">
                {{ $xmlData['empresa']['nombreComercial'] ?? ($xmlData['empresa']['razonSocial'] ?? 'Nombre Comercial') }}<br>
                <strong>RUC:</strong> {{ $xmlData['empresa']['ruc'] ?? '12345678901' }}<br>
                {{ $xmlData['empresa']['direccion'] ?? 'Dirección de la empresa' }}<br>
                {{ $xmlData['empresa']['distrito'] ?? 'Distrito' }},
                {{ $xmlData['empresa']['provincia'] ?? 'Provincia' }}
            </div>
        </div>

        <div class="separator"></div>

        <div class="header-centered">
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
        </div>

        <div class="separator"></div>

        <!-- Datos del cliente (alineado a la izquierda) -->
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
                <span>{{ $xmlData['cliente']['numDoc'] ?? '00000000' }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">CLIENTE:</span>
                <span>{{ $xmlData['cliente']['razonSocial'] ?? 'Cliente Generico' }}</span>
            </div>

            @if (!empty($xmlData['cliente']['direccion']))
                <div class="info-row">
                    <span class="info-label">DIRECCIÓN:</span>
                    <span>{{ $xmlData['cliente']['direccion'] }}</span>
                </div>
            @endif
        </div>

        <div class="separator"></div>

        <!-- Tabla de items (alineado a la izquierda) -->
        <div class="items-section">
            <div class="items-header">
                <span class="qty">Cant.</span>
                <span class="desc">Descripción</span>
                <span class="total">Total</span>
            </div>

            @foreach ($xmlData['items'] ?? [] as $item)
                <div class="item-row">
                    <span class="qty">{{ $item['cantidad'] ?? '1' }}</span>
                    <span class="desc">{{ $item['descripcion'] ?? '-' }}</span>
                    <span class="total">{{ number_format($item['subtotal'] ?? 0, 2) }}</span>
                </div>
            @endforeach
        </div>

        <div class="separator"></div>

        <!-- Totales (alineado a la izquierda, valores a la derecha) -->
        <div class="totals">
            <div class="total-row">
                <span>OP. GRAVADA:</span>
                <span>S/ {{ number_format(($xmlData['subtotal'] ?? 0) - ($xmlData['igv'] ?? 0), 2) }}</span>
            </div>
            <div class="total-row">
                <span>I.G.V. 18%:</span>
                <span>S/ {{ number_format($xmlData['igv'] ?? 0, 2) }}</span>
            </div>
            <div class="total-row bold-total">
                <span>TOTAL:</span>
                <span>S/ {{ number_format($xmlData['total'] ?? 0, 2) }}</span>
            </div>
        </div>

        <div class="separator"></div>

        <!-- Forma de pago (alineado a la izquierda) -->
        <div class="payment-info">
            <div class="info-row">
                <span class="info-label">FORMA DE PAGO:</span>
                <span>{{ $xmlData['formaPago'] ?? ($xmlData['medioPago'] ?? 'CONTADO') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">MONTO EN LETRAS:</span>
                <span>{{ $xmlData['importeLetras'] ?? 'CERO CON 00/100 SOLES' }}</span>
            </div>
        </div>

        <div class="separator"></div>

        <!-- Código QR (centrado) -->
        @if (isset($qrCode) && $qrCode)
            <div class="qr-container">
                <div class="qr-title">Código QR</div>
                <div class="qr-image">
                    <img src="{{ $qrCode }}" alt="QR Code">
                </div>
            </div>
            <div class="separator"></div>
        @endif

        <!-- Información adicional (centrado) -->
        <div class="footer">
            <p>Representación impresa del Comprobante Electrónico</p>
            <p>Consulte su comprobante en: www.sunat.gob.pe</p>
            <p class="bold-total">¡GRACIAS POR SU PREFERENCIA!</p>
        </div>

        <!-- Mensaje legal (centrado) -->
        <div class="legal">
            Autorizado mediante Resolución de Intendencia N° 032- 005<br>
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
