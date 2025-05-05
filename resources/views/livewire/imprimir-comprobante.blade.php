<div>
    <!-- Modal -->
    @if ($mostrarModal)
        <div class="fixed inset-0 z-40 transition-opacity bg-gray-500 bg-opacity-75"></div>

        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div
                class="w-full max-w-md overflow-hidden transition-all transform bg-white shadow-xl dark:bg-gray-800 rounded-xl md:max-w-xl">
                <!-- Cabecera del modal -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Imprimir Comprobante
                    </h3>
                    <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Cuerpo del modal -->
                <div class="px-6 py-4 overflow-y-auto max-h-[80vh]">
                    <!-- Contenido del ticket -->
                    <div class="flex flex-col space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex space-x-2">
                                <button wire:click="imprimir"
                                    class="flex items-center px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-1"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                        <path
                                            d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2">
                                        </path>
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
                                            <p><span class="font-bold">RUC:</span> {{ $datosXml['cliente_documento'] }}
                                            </p>
                                        @else
                                            <p><span class="font-bold">DNI:</span> {{ $datosXml['cliente_documento'] }}
                                            </p>
                                        @endif

                                        <p><span class="font-bold">CLIENTE:</span> {{ $datosXml['cliente_nombre'] }}
                                        </p>
                                        @if (!empty($datosXml['cliente_direccion']))
                                            <p><span class="font-bold">DIRECCIÓN:</span>
                                                {{ $datosXml['cliente_direccion'] }}</p>
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
                                        <p><span class="font-bold">FORMA DE PAGO:</span> {{ $datosXml['forma_pago'] }}
                                        </p>
                                        <p><span class="font-bold">MONTO EN LETRAS:</span>
                                            {{ $datosXml['total_letras'] }}</p>
                                        <hr class="my-2 border-gray-300 dark:border-gray-600">
                                    </div>

                                    <!-- QR Code - TAMAÑO REDUCIDO -->
                                    <div class="mb-3 text-center">
                                        <div
                                            class="inline-block p-1 mb-2 border border-gray-300 rounded dark:border-gray-600">
                                            @if ($qrCode)
                                                <img src="{{ $qrCode }}" alt="QR Code"
                                                    class="w-16 h-16 mx-auto">
                                            @else
                                                <div
                                                    class="flex items-center justify-center w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700">
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">QR no
                                                        disponible</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Información adicional -->
                                    <div class="mb-3 text-center">
                                        <p>Representación impresa de la
                                            {{ $datosXml['tipo_documento'] == '03' ? 'Boleta de Venta' : 'Factura' }}
                                            Electrónica</p>
                                        <p>Consulte su comprobante en: www.sunat.gob.pe</p>
                                        <p class="mt-2 font-bold">¡GRACIAS POR SU PREFERENCIA!</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Pie del modal -->
                <div class="flex justify-end px-6 py-4 space-x-3 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="cerrarModal"
                        class="px-4 py-2 text-gray-800 bg-gray-200 rounded-md dark:bg-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Cerrar
                    </button>
                </div>



            </div>
        </div>
    @endif


    <!-- Estilos para impresión -->
    <style media="print">
        @page {
            size: 80mm auto;
            /* Ancho de 80mm, alto automático */
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: white;
            font-family: 'Courier New', monospace;
            font-size: 8pt;
            /* Reducido para mejor ajuste */
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        body * {
            visibility: hidden;
        }

        #ticket-container,
        #ticket-container * {
            visibility: visible;
        }

        #ticket-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 80mm;
            /* Ancho estándar para ticketeras térmicas */
            margin: 0;
            padding: 3mm;
            /* Reducido para mejor ajuste */
            box-sizing: border-box;
            overflow: visible;
            page-break-inside: avoid;
        }

        /* Asegurarse de que los elementos largos se ajusten correctamente */
        .truncate {
            white-space: normal !important;
            overflow: visible !important;
            text-overflow: clip !important;
            width: 100% !important;
        }

        /* Ajustes para el tamaño de fuente y espaciado */
        p {
            margin: 1mm 0;
            font-size: 8pt !important;
            line-height: 1.2 !important;
        }

        hr {
            margin: 2mm 0;
            border-width: 0.5px !important;
        }

        /* Asegurar que las imágenes se muestren correctamente */
        img {
            max-width: 100%;
            height: auto !important;
        }

        /* Reducir tamaño de elementos específicos */
        .ticket {
            width: 100% !important;
            max-width: 72mm !important;
            /* Ligeramente menor que el ancho del papel */
        }

        /* Garantizar que las tablas y divisiones se ajusten */
        div,
        table {
            width: 100% !important;
            max-width: 72mm !important;
        }

        /* Corregir espaciado entre elementos de detalle */
        .flex {
            display: block !important;
            width: 100% !important;
            margin-bottom: 1mm !important;
        }

        /* Ajustar elementos de texto para que no se corten */
        span {
            display: inline !important;
            width: auto !important;
        }

        /* Forzar quiebres de página adecuados */
        @media print {
            #ticket-container {
                page-break-after: always;
            }
        }
    </style>

    <!-- Script para manejar la impresión -->
    <script>
        // Este script se ejecutará cuando el documento esté cargado
        document.addEventListener('DOMContentLoaded', function() {
            // Asegúrate de que Livewire está inicializado
            if (typeof window.Livewire !== 'undefined') {
                // Escuchar el evento de imprimir
                window.Livewire.on('imprimirComprobante', function() {
                    // Ejecutar la impresión
                    window.print();
                });
            }
        });
    </script>



</div>
