<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante {{ $comprobante->serie }}-{{ $comprobante->numero }}</title>
    <style>
        /* Reset y estilos base optimizados para ticketera */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: monospace;
            /* Fuente monoespaciada para mejor compatibilidad con ticketeras */
            font-size: 10px;
            line-height: 1.2;
            color: #000;
            background: #fff;
            width: 72mm;
            /* Ancho estándar para ticketeras */
            margin: 0 auto;
        }

        /* Contenedor principal */
        .ticket {
            width: 100%;
            padding: 2mm;
        }

        /* Encabezado */
        .header {
            text-align: center;
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 1px dotted #000;
        }

        .logo {
            display: none;
            /* Ocultamos logo para ticketera */
        }

        .company-name {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .company-info {
            font-size: 9px;
        }

        /* Detalles del comprobante */
        .invoice-info {
            margin-bottom: 5px;
            padding-bottom: 3px;
            border-bottom: 1px dotted #000;
        }

        .invoice-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .invoice-label {
            font-weight: bold;
        }

        /* Información del cliente */
        .customer-info {
            margin-bottom: 5px;
            padding-bottom: 3px;
            border-bottom: 1px dotted #000;
        }

        /* Tabla de items */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .items-table th {
            text-align: left;
            padding: 2px 0;
            border-bottom: 1px dotted #000;
            font-size: 9px;
        }

        .items-table td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 9px;
        }

        .items-table tr:last-child td {
            border-bottom: 1px dotted #000;
        }

        .item-description {
            max-width: 30mm;
            word-wrap: break-word;
        }

        /* Totales */
        .totals {
            margin-top: 5px;
            margin-left: auto;
            width: 100%;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .total-label {
            font-weight: bold;
        }

        .grand-total {
            font-weight: bold;
            font-size: 11px;
            border-top: 1px dotted #000;
            padding-top: 3px;
            margin-top: 3px;
        }

        /* Pie de página */
        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 8px;
            padding-top: 3px;
            border-top: 1px dotted #000;
        }

        /* Mensaje legal */
        .legal {
            margin-top: 5px;
            font-size: 7px;
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
        }
    </style>
</head>

<body>
    <div class="ticket">
        <!-- Encabezado con datos de empresa -->
        <div class="header">
            <div class="company-name">{{ $xmlData['empresa']['razonSocial'] ?? 'Nombre de Empresa' }}</div>
            <div class="company-info">
                RUC: {{ $xmlData['empresa']['ruc'] ?? '12345678901' }}<br>
                {{ $xmlData['empresa']['direccion'] ?? 'Dirección de la empresa' }}<br>
                {{ $xmlData['empresa']['distrito'] ?? 'Distrito' }},
                {{ $xmlData['empresa']['provincia'] ?? 'Provincia' }}
            </div>
        </div>

        <!-- Datos del comprobante -->
        <div class="invoice-info">
            <div class="invoice-row">
                <span class="invoice-label">Comprobante:</span>
                <span>{{ $xmlData['tipoComprobante'] ?? 'FACTURA' }} {{ $xmlData['numeroComprobante'] ?? '' }}</span>
            </div>
            <div class="invoice-row">
                <span class="invoice-label">Fecha:</span>
                <span>{{ date('d/m/Y H:i', strtotime($xmlData['fechaEmision'] ?? now())) }}</span>
            </div>
        </div>

        <!-- Datos del cliente -->
        <div class="customer-info">
            <div class="invoice-row">
                <span class="invoice-label">Cliente:</span>
                <span>{{ $xmlData['cliente']['razonSocial'] ?? 'Cliente Generico' }}</span>
            </div>
            <div class="invoice-row">
                <span class="invoice-label">Doc:</span>
                <span>{{ $xmlData['cliente']['tipoDoc'] ?? 'DNI' }}
                    {{ $xmlData['cliente']['numDoc'] ?? '00000000' }}</span>
            </div>
            <div class="invoice-row">
                <span class="invoice-label">Dir:</span>
                <span>{{ $xmlData['cliente']['direccion'] ?? '-' }}</span>
            </div>
        </div>

        <!-- Tabla de items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="10%">Cant</th>
                    <th width="50%">Descripción</th>
                    <th width="20%">P.Unit</th>
                    <th width="20%">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($xmlData['items'] ?? [] as $item)
                    <tr>
                        <td>{{ $item['cantidad'] ?? '1' }}</td>
                        <td class="item-description">{{ $item['descripcion'] ?? '-' }}</td>
                        <td>{{ number_format($item['precioUnitario'] ?? 0, 2) }}</td>
                        <td>{{ number_format($item['subtotal'] ?? 0, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totales -->
        <div class="totals">
            <div class="total-row">
                <span class="total-label">OP. GRAVADAS:</span>
                <span>{{ number_format(($xmlData['subtotal'] ?? 0) - ($xmlData['igv'] ?? 0), 2) }}</span>
            </div>
            <div class="total-row">
                <span class="total-label">IGV (18%):</span>
                <span>{{ number_format($xmlData['igv'] ?? 0, 2) }}</span>
            </div>
            <div class="total-row grand-total">
                <span class="total-label">TOTAL:</span>
                <span>{{ number_format($xmlData['total'] ?? 0, 2) }}</span>
            </div>
        </div>

        <!-- Importe en letras -->
        <div style="margin-top: 3px; font-size: 9px; text-align: center;">
            <strong>SON:</strong> {{ $xmlData['importeLetras'] ?? 'CERO CON 00/100 SOLES' }}
        </div>

        <!-- Pie de página -->
        <div class="footer">
            <div>¡Gracias por su compra!</div>
            <div>Atendido por: {{ $comprobante->user->name ?? 'Sistema' }}</div>
        </div>

        <!-- Mensaje legal -->
        <div class="legal">
            Representación impresa del comprobante electrónico<br>
            Consulte su comprobante en {{ config('app.url') }}
        </div>
    </div>

    <script>
        // Auto-imprimir inmediatamente
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 200);
        };
    </script>
</body>

</html>
