<div class="p-6 space-y-4">
    @if ($mensajeError)
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-900/30 dark:text-red-300">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                {{ $mensajeError }}
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Columna izquierda: Información del comprobante -->
        <div class="space-y-4">
            <!-- Datos del Cliente -->
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <h3 class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Datos del Cliente
                </h3>

                <div class="space-y-3">
                    @if ($cliente)
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Tipo de Documento
                                </label>
                                <div
                                    class="block w-full px-3 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-200">
                                    {{ $cliente->tipoDocumento->descripcion ?? ($cliente->ruc ? 'RUC' : 'DNI') }}
                                </div>
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Número de Documento
                                </label>
                                <div
                                    class="block w-full px-3 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-200">
                                    {{ $cliente->ruc ?? $cliente->numero_documento }}
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Razón Social / Nombre
                                </label>
                                <div
                                    class="block w-full px-3 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-200">
                                    {{ $cliente->razon_social ?? $cliente->nombre }}
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Dirección
                                </label>
                                <div
                                    class="block w-full px-3 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-200">
                                    {{ $cliente->direccion ?? 'No especificada' }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div
                            class="p-4 text-sm text-center rounded-md text-amber-600 bg-amber-100 dark:bg-amber-900/30 dark:text-amber-300">
                            No hay cliente asociado a esta comanda. No se podrá generar un comprobante electrónico.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Facturación Electrónica -->
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <h3 class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Facturación Electrónica
                </h3>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <!-- Tipo de Comprobante -->
                    <div>
                        <label for="tipo-comprobante"
                            class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                            Tipo de Comprobante
                        </label>
                        <select id="tipo-comprobante" wire:model.live="tipoComprobanteSeleccionado"
                            {{ !$clienteEncontrado ? 'disabled' : '' }}
                            class="block w-full p-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:focus:ring-blue-700">
                            <option value="" selected>Seleccione comprobante</option>
                            @foreach ($tipoComprobantes as $comprobante)
                                <option value="{{ $comprobante->codigo }}"
                                    {{ $comprobante->codigo == '01' && $cliente && $cliente->tipoDocumento && $cliente->tipoDocumento->tipo == '1' ? 'disabled' : '' }}>
                                    {{ $comprobante->descripcion }}
                                    {{ $comprobante->codigo == '01' && $cliente && $cliente->tipoDocumento && $cliente->tipoDocumento->tipo == '1' ? '(Cliente no elegible para factura)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @if ($cliente && $tipoComprobanteSeleccionado == '01' && $cliente->tipoDocumento && $cliente->tipoDocumento->tipo == '1')
                            <span class="text-xs text-red-600 dark:text-red-400">El cliente con DNI no puede recibir
                                factura</span>
                        @endif
                    </div>

                    <!-- Serie -->
                    <div>
                        <label for="serie-comprobante"
                            class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                            Serie
                        </label>
                        <input id="serie-comprobante" type="text" wire:model="serieComprobante" readonly disabled
                            class="block w-full px-3 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-200">
                    </div>

                    <!-- Número -->
                    <div>
                        <label for="numero-comprobante"
                            class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                            Número
                        </label>
                        <input id="numero-comprobante" type="text" wire:model="numeroPedido" readonly disabled
                            class="block w-full px-3 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-200">
                    </div>

                    <!-- Fecha de Emisión -->
                    <div>
                        <label for="fecha-emision"
                            class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                            Fecha de Emisión
                        </label>
                        <input type="text" id="fecha-emision" value="{{ now()->format('d/m/Y') }}" readonly disabled
                            class="block w-full px-3 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-200">
                    </div>

                    <!-- Moneda -->
                    <div>
                        <label for="moneda" class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                            Moneda
                        </label>
                        <select id="moneda" wire:model.live="monedaSelecionada"
                            {{ !$clienteEncontrado ? 'disabled' : '' }}
                            class="block w-full p-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:focus:ring-blue-700">
                            <option value="" selected>Seleccione moneda</option>
                            <option value="PEN">Soles (PEN)</option>
                            <option value="USD">Dólares (USD)</option>
                        </select>
                        @error('monedaSelecionada')
                            <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Forma de Pago -->
                    <div>
                        <label for="forma-pago" class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                            Forma de Pago
                        </label>
                        <select id="forma-pago" wire:model.live="formaPago" {{ !$clienteEncontrado ? 'disabled' : '' }}
                            class="block w-full p-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:focus:ring-blue-700">
                            <option value="" selected>Seleccione</option>
                            <option value="Contado">Contado</option>
                            <option value="Credito">Crédito</option>
                        </select>
                        @error('formaPago')
                            <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna derecha: Detalle y Totales -->
        <div class="space-y-4">
            <!-- Detalle de la comanda -->
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <h3 class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Detalle de la Comanda
                </h3>

                <div class="overflow-hidden border border-gray-200 rounded-lg shadow-sm dark:border-gray-700">
                    <div class="overflow-x-auto max-h-72">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400 sm:px-4">
                                        Descripción</th>
                                    <th
                                        class="px-3 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:px-4">
                                        Tipo</th>
                                    <th
                                        class="px-3 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:px-4">
                                        Cant.</th>
                                    <th
                                        class="px-3 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:px-4">
                                        Precio</th>
                                    <th
                                        class="px-3 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:px-4">
                                        Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                <!-- Platos -->
                                @forelse($platosComanda as $plato)
                                    <tr
                                        class="transition-colors bg-blue-50/30 hover:bg-blue-50/50 dark:bg-blue-900/10 dark:hover:bg-blue-900/20">
                                        <td class="px-3 py-3 text-sm text-gray-800 dark:text-gray-200 sm:px-4">
                                            {{ $plato['nombre'] }}
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $plato['unidad_medida'] }}</p>
                                        </td>
                                        <td
                                            class="px-3 py-3 text-sm text-center text-gray-800 dark:text-gray-200 sm:px-4">
                                            <span
                                                class="{{ $plato['es_llevar'] ? 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }} px-2 py-1 text-xs font-semibold rounded-full">
                                                {{ $plato['es_llevar'] ? 'LLEVAR' : 'MESA' }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-3 py-3 text-sm text-center text-gray-800 dark:text-gray-200 sm:px-4">
                                            {{ $plato['cantidad'] }}
                                        </td>
                                        <td
                                            class="px-3 py-3 text-sm text-center text-gray-800 dark:text-gray-200 sm:px-4">
                                            S/. {{ number_format($plato['precio_unitario'], 2) }}
                                        </td>
                                        <td
                                            class="px-3 py-3 text-sm font-medium text-center text-gray-800 dark:text-gray-200 sm:px-4">
                                            S/. {{ number_format($plato['subtotal'], 2) }}
                                        </td>
                                    </tr>
                                @empty
                                @endforelse

                                <!-- Existencias -->
                                @forelse($existenciasComanda as $existencia)
                                    <tr
                                        class="transition-colors bg-green-50/30 hover:bg-green-50/50 dark:bg-green-900/10 dark:hover:bg-green-900/20">
                                        <td class="px-3 py-3 text-sm text-gray-800 dark:text-gray-200 sm:px-4">
                                            {{ $existencia['nombre'] }}
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $existencia['unidad_medida'] }}</p>
                                        </td>
                                        <td
                                            class="px-3 py-3 text-sm text-center text-gray-800 dark:text-gray-200 sm:px-4">
                                            <span
                                                class="{{ $existencia['es_helado'] ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' }} px-2 py-1 text-xs font-semibold rounded-full">
                                                {{ $existencia['es_helado'] ? 'HELADO' : 'NORMAL' }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-3 py-3 text-sm text-center text-gray-800 dark:text-gray-200 sm:px-4">
                                            {{ $existencia['cantidad'] }}
                                        </td>
                                        <td
                                            class="px-3 py-3 text-sm text-center text-gray-800 dark:text-gray-200 sm:px-4">
                                            S/. {{ number_format($existencia['precio_unitario'], 2) }}
                                        </td>
                                        <td
                                            class="px-3 py-3 text-sm font-medium text-center text-gray-800 dark:text-gray-200 sm:px-4">
                                            S/. {{ number_format($existencia['subtotal'], 2) }}
                                        </td>
                                    </tr>
                                @empty
                                @endforelse

                                @if (count($platosComanda) == 0 && count($existenciasComanda) == 0)
                                    <tr>
                                        <td colspan="5"
                                            class="px-3 py-4 text-sm text-center text-gray-500 dark:text-gray-400 sm:px-4">
                                            No hay items en esta comanda
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Resumen de Totales -->
            <div
                class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <h3 class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Resumen de Totales
                </h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Subtotal:</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                            S/. {{ number_format($subtotalGeneral, 2) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">IGV (10%):</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                            S/. {{ number_format($igvGeneral, 2) }}
                        </span>
                    </div>
                    <div class="pt-2 mt-2 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex justify-between">
                            <span class="text-base font-bold text-gray-800 dark:text-gray-200">TOTAL:</span>
                            <span class="text-base font-bold text-blue-600 dark:text-blue-400">
                                S/. {{ number_format($totalGeneral, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
                <button wire:click="cancelar"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                    Cancelar
                </button>

                <!-- Botón de generar comprobante -->
                <button wire:click="generarComprobante" wire:loading.attr="disabled"
                    wire:loading.class="opacity-75 cursor-wait"
                    {{ $procesando || !$clienteEncontrado || !$sesionCajaId || $totalGeneral <= 0 ? 'disabled' : '' }}
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-800 disabled:opacity-50 disabled:cursor-not-allowed">
                    <div class="flex items-center justify-center">
                        <svg wire:loading wire:target="generarComprobante"
                            class="w-5 h-5 mr-2 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span wire:loading.remove wire:target="generarComprobante">
                            Generar Comprobante Electrónico
                        </span>
                        <span wire:loading wire:target="generarComprobante">
                            Procesando...
                        </span>
                    </div>
                </button>
            </div>
        </div>
    </div>
