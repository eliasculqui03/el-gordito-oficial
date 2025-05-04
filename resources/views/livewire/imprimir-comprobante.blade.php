<div>


    <div class="flex flex-col space-y-4">
        <div class="flex items-center justify-between">

            <div class="flex space-x-2">

                <button wire:click="imprimir"
                    class="flex items-center px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-1" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="8"></rect>
                    </svg>
                    Imprimir Ticket
                </button>
            </div>
        </div>

        @if ($loading)
            <div class="flex items-center justify-center p-8">
                <div class="w-12 h-12 border-b-2 border-blue-500 rounded-full animate-spin"></div>
            </div>
        @elseif ($error)
            <div class="relative px-4 py-3 text-red-700 bg-red-100 border border-red-400 rounded dark:bg-red-900 dark:border-red-700 dark:text-red-100"
                role="alert">
                <strong class="font-bold">Error:</strong>
                <span class="block sm:inline">{{ $error }}</span>
            </div>
        @elseif (isset($datosXml['error']))
            <div class="relative px-4 py-3 text-red-700 bg-red-100 border border-red-400 rounded dark:bg-red-900 dark:border-red-700 dark:text-red-100"
                role="alert">
                <strong class="font-bold">Error:</strong>
                <span class="block sm:inline">{{ $datosXml['error'] }}</span>
            </div>
        @else
            <div id="ticket-container"
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md p-4 max-w-[300px] mx-auto font-mono text-xs shadow-md">
                <!-- Ticket para impresora térmica -->
                <div class="ticket" style="width: 100%; max-width: 300px;">
                    <!-- Cabecera -->
                    <div class="mb-3 text-center">
                        <p class="text-sm font-bold">{{ $datosXml['emisor_nombre_comercial'] }}</p>
                        <p>{{ $datosXml['emisor_razon_social'] }}</p>
                        <p>RUC: {{ $datosXml['emisor_ruc'] }}</p>
                        <p>{{ $datosXml['emisor_direccion'] }}</p>
                        <p>{{ $datosXml['emisor_distrito'] }}, {{ $datosXml['emisor_provincia'] }}</p>
                        <hr class="my-2 border-gray-300 dark:border-gray-600">

                        @if ($datosXml['tipo_documento'] == '03')
                            <p class="font-bold">BOLETA DE VENTA ELECTRÓNICA</p>
                        @elseif($datosXml['tipo_documento'] == '01')
                            <p class="font-bold">FACTURA ELECTRÓNICA</p>
                        @else
                            <p class="font-bold">COMPROBANTE ELECTRÓNICO</p>
                        @endif

                        <p class="font-bold">{{ $datosXml['serie_numero'] }}</p>
                        <hr class="my-2 border-gray-300 dark:border-gray-600">
                    </div>

                    <!-- Datos del cliente -->
                    <div class="mb-3">
                        <p><span class="font-bold">FECHA:</span>
                            {{ date('d/m/Y', strtotime($datosXml['fecha_emision'])) }}</p>

                        @if (substr($datosXml['cliente_documento'], 0, 2) == '20')
                            <p><span class="font-bold">RUC:</span> {{ $datosXml['cliente_documento'] }}</p>
                        @else
                            <p><span class="font-bold">DNI:</span> {{ $datosXml['cliente_documento'] }}</p>
                        @endif

                        <p><span class="font-bold">CLIENTE:</span> {{ $datosXml['cliente_nombre'] }}</p>
                        @if (!empty($datosXml['cliente_direccion']))
                            <p><span class="font-bold">DIRECCIÓN:</span> {{ $datosXml['cliente_direccion'] }}</p>
                        @endif
                        <hr class="my-2 border-gray-300 dark:border-gray-600">
                    </div>

                    <!-- Detalles de la venta -->
                    <div class="mb-3">
                        <div
                            class="flex justify-between pb-1 mb-1 font-bold border-b border-gray-300 dark:border-gray-600">
                            <span class="w-10">CANT</span>
                            <span class="flex-1 px-1">DESCRIPCIÓN</span>
                            <span class="w-16 text-right">TOTAL</span>
                        </div>

                        @foreach ($datosXml['detalles'] as $detalle)
                            <div class="flex justify-between py-1">
                                <span class="w-10">{{ $detalle['cantidad'] }}</span>
                                <span class="flex-1 px-1 truncate">{{ $detalle['descripcion'] }}</span>
                                <span
                                    class="w-16 text-right">{{ number_format((float) $detalle['precio_venta'], 2) }}</span>
                            </div>
                        @endforeach

                        <hr class="my-2 border-gray-300 dark:border-gray-600">
                    </div>

                    <!-- Totales -->
                    <div class="mb-3">
                        <div class="flex justify-between">
                            <span>OP. GRAVADA:</span>
                            <span>S/ {{ number_format((float) $datosXml['total_gravado'], 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>I.G.V. 18%:</span>
                            <span>S/ {{ number_format((float) $datosXml['total_igv'], 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm font-bold">
                            <span>TOTAL:</span>
                            <span>S/ {{ number_format((float) $datosXml['total_venta'], 2) }}</span>
                        </div>
                        <hr class="my-2 border-gray-300 dark:border-gray-600">
                    </div>

                    <!-- Forma de pago -->
                    <div class="mb-3">
                        <p><span class="font-bold">FORMA DE PAGO:</span> {{ $datosXml['forma_pago'] }}</p>
                        <p><span class="font-bold">MONTO EN LETRAS:</span> {{ $datosXml['total_letras'] }}</p>
                        <hr class="my-2 border-gray-300 dark:border-gray-600">
                    </div>

                    <!-- QR Code - TAMAÑO REDUCIDO -->
                    <div class="mb-3 text-center">
                        <div class="inline-block p-1 mb-2 border border-gray-300 rounded dark:border-gray-600">
                            @if ($qrCode)
                                <img src="{{ $qrCode }}" alt="QR Code" class="w-16 h-16 mx-auto">
                            @else
                                <div
                                    class="flex items-center justify-center w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">QR no disponible</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Información adicional -->
                    <div class="mb-3 text-center">
                        <p>Representación impresa de la
                            {{ $datosXml['tipo_documento'] == '03' ? 'Boleta de Venta' : 'Factura' }} Electrónica</p>
                        <p>Consulte su comprobante en: www.sunat.gob.pe</p>
                        <p class="mt-2 font-bold">¡GRACIAS POR SU PREFERENCIA!</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('imprimirTicket', () => {
                const ticketContent = document.getElementById('ticket-container').innerHTML;
                const ventanaImpresion = window.open('', '_blank', 'height=600,width=800');

                ventanaImpresion.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Impresión de Ticket</title>
                        <meta charset="UTF-8">
                        <style>
                            @page {
                                size: 80mm auto;
                                margin: 0;
                            }
                            body {
                                font-family: 'Courier New', monospace;
                                font-size: 12px;
                                margin: 0;
                                padding: 8px;
                                width: 80mm;
                            }
                            .ticket {
                                width: 80mm;
                                max-width: 80mm;
                                margin: 0 auto;
                                padding: 5px;
                            }
                            hr {
                                border: 1px dashed #000;
                                border-width: 1px 0 0 0;
                                margin: 5px 0;
                            }
                            .text-center {
                                text-align: center;.text-center {
                                text-align: center;
                            }
                            .font-bold {
                                font-weight: bold;
                            }
                            .text-sm {
                                font-size: 14px;
                            }
                            .flex {
                                display: flex;
                            }
                            .justify-between {
                                justify-content: space-between;
                            }
                            .mb-1 {
                                margin-bottom: 4px;
                            }
                            .mb-2 {
                                margin-bottom: 8px;
                            }
                            .mb-3 {
                                margin-bottom: 12px;
                            }
                            .mt-2 {
                                margin-top: 8px;
                            }
                            .py-1 {
                                padding-top: 4px;
                                padding-bottom: 4px;
                            }
                            .px-1 {
                                padding-left: 4px;
                                padding-right: 4px;
                            }
                            .w-10 {
                                width: 40px;
                            }
                            .w-16 {
                                width: 64px;
                            }
                            .h-16 {
                                height: 64px;
                            }
                            .w-24 {
                                width: 96px;
                            }
                            .h-24 {
                                height: 96px;
                            }
                            .flex-1 {
                                flex: 1;
                            }
                            .truncate {
                                overflow: hidden;
                                text-overflow: ellipsis;
                                white-space: nowrap;
                            }
                            .text-right {
                                text-align: right;
                            }
                            .border-b {
                                border-bottom-width: 1px;
                                border-bottom-style: solid;
                            }
                            .border {
                                border-width: 1px;
                                border-style: solid;
                            }
                            .rounded {
                                border-radius: 0.25rem;
                            }
                            .p-1 {
                                padding: 4px;
                            }
                            .mx-auto {
                                margin-left: auto;
                                margin-right: auto;
                            }
                            .inline-block {
                                display: inline-block;
                            }

                            /* Estilos para impresión */
                            @media print {
                                body {
                                    width: 80mm;
                                    font-size: 10px;
                                }
                                .no-print {
                                    display: none;
                                }
                            }
                        </style>
                    </head>
                    <body onload="window.print(); window.setTimeout(function(){ window.close(); }, 500);">
                        <div class="ticket">
                            ${ticketContent}
                        </div>
                    </body>
                    </html>
                `);

                ventanaImpresion.document.close();
            });
        });
    </script>
</div>
