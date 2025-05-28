<div>
    @vite('resources/css/app.css')
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="container ">
            <!-- Facturación con nueva estructura -->
            <div class="overflow-hidden transition-colors duration-200 bg-white rounded-lg shadow dark:bg-gray-900 ">
                <div class="p-4">
                    <!-- Layout principal con grid responsivo -->
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">
                        <!-- Primera columna (3/5) - Orden inverso en móviles -->
                        <div class="order-1 lg:order-1 lg:col-span-3">
                            <!-- Detalle de productos -->
                            <div
                                class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                                <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Detalle de
                                        Productos</h3>

                                    <!-- Número de pedido integrado -->
                                    <div wire:poll.4s.visible
                                        class="px-3 py-2 text-center bg-white border-2 border-indigo-500 rounded-lg shadow-sm dark:bg-gray-800 dark:border-indigo-400">
                                        <div class="flex items-center">
                                            <span
                                                class="mr-2 text-xs font-bold text-gray-700 dark:text-gray-300">PEDIDO:</span>
                                            <span class="text-base font-bold text-indigo-600 dark:text-indigo-400">N°.
                                                {{ $numeroPedido }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botones de acción colocados sobre la tabla -->
                                <div class="flex flex-wrap gap-3 mb-4">

                                    <!-- Botón de Agregar Platos -->
                                    <button wire:click="abrirModalPlato"
                                        class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white transition-colors duration-200 bg-indigo-600 rounded-md hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                                        </svg>
                                        Agregar Platos
                                    </button>
                                    <!-- Botón de Agregar Existencia -->
                                    <button wire:click="abrirModalExistencia"
                                        class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white transition-colors duration-200 bg-green-600 rounded-md hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                                            <path fill-rule="evenodd"
                                                d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Agregar Existencia
                                    </button>


                                </div>

                                <!-- Tabla mejorada con responsividad -->
                                <div
                                    class="overflow-hidden border border-gray-200 rounded-lg shadow-sm dark:border-gray-700">
                                    <div class="overflow-x-auto min-h-[350px]">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <th
                                                        class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400 sm:px-4">
                                                        Descripción</th>
                                                    <th
                                                        class="hidden px-3 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:px-4 sm:table-cell">
                                                        Tipo</th>
                                                    <th
                                                        class="hidden px-3 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:px-4 sm:table-cell">
                                                        Precio</th>
                                                    <th
                                                        class="px-3 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:px-4">
                                                        Cant.</th>
                                                    <th
                                                        class="px-3 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:px-4">
                                                        Subtotal</th>
                                                    <th
                                                        class="px-3 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:px-4">
                                                        Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                                <!-- Platos -->
                                                @forelse($platosComanda as $index => $plato)
                                                    <tr
                                                        class="transition-colors bg-indigo-50/30 hover:bg-indigo-50/50 dark:bg-indigo-900/10 dark:hover:bg-indigo-900/20">
                                                        <td
                                                            class="px-3 py-3 text-sm text-gray-800 dark:text-gray-200 sm:px-4">
                                                            {{ $plato['nombre'] }}
                                                            <p class="text-xs text-gray-500">
                                                                {{ $plato['unidad_medida'] }}</p>
                                                            <!-- Información adicional para móviles -->
                                                            <div
                                                                class="flex flex-wrap items-center gap-2 mt-1 sm:hidden">
                                                                <span
                                                                    class="{{ $plato['es_llevar'] ? 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }} px-2 py-0.5 text-xs font-semibold rounded-full">
                                                                    {{ $plato['es_llevar'] ? 'LLEVAR' : 'MESA' }}
                                                                </span>
                                                                <span class="text-xs text-gray-600 dark:text-gray-400">
                                                                    S/.
                                                                    {{ number_format($plato['precio_unitario'], 2) }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="hidden px-3 py-3 text-sm text-center text-gray-800 dark:text-gray-200 sm:px-4 sm:table-cell">
                                                            <span
                                                                class="{{ $plato['es_llevar'] ? 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }} px-2 py-1 text-xs font-semibold rounded-full">
                                                                {{ $plato['es_llevar'] ? 'LLEVAR' : 'MESA' }}
                                                            </span>
                                                        </td>
                                                        <td
                                                            class="hidden px-3 py-3 text-sm text-center text-gray-800 dark:text-gray-200 sm:px-4 sm:table-cell">
                                                            S/. {{ number_format($plato['precio_unitario'], 2) }}
                                                        </td>
                                                        <td class="px-2 py-3 text-center sm:px-4">
                                                            <div class="flex items-center justify-center">
                                                                <button
                                                                    wire:click="decrementarCantidadPlato({{ $index }})"
                                                                    class="p-1 text-gray-500 transition bg-gray-200 rounded hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="w-3 h-3 sm:w-4 sm:h-4"
                                                                        viewBox="0 0 20 20" fill="currentColor">
                                                                        <path fill-rule="evenodd"
                                                                            d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                </button>
                                                                <span
                                                                    class="mx-1 text-sm font-medium text-gray-800 dark:text-gray-200 sm:mx-2">{{ $plato['cantidad'] }}</span>
                                                                <button
                                                                    wire:click="incrementarCantidadPlato({{ $index }})"
                                                                    class="p-1 text-white transition bg-indigo-500 rounded hover:bg-indigo-600 dark:bg-indigo-600 dark:hover:bg-indigo-700">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="w-3 h-3 sm:w-4 sm:h-4"
                                                                        viewBox="0 0 20 20" fill="currentColor">
                                                                        <path fill-rule="evenodd"
                                                                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="px-3 py-3 text-sm font-medium text-center text-gray-800 dark:text-gray-200 sm:px-4">
                                                            S/. {{ number_format($plato['subtotal'], 2) }}
                                                        </td>
                                                        <td class="px-2 py-3 text-center sm:px-4">
                                                            <div
                                                                class="flex items-center justify-center space-x-1 sm:space-x-2">
                                                                <button
                                                                    wire:click="toggleLlevarPlato({{ $index }})"
                                                                    class="p-1 text-orange-500 transition bg-orange-100 rounded hover:bg-orange-200 dark:bg-orange-900/20 dark:hover:bg-orange-800/30 dark:text-orange-400"
                                                                    title="Cambiar entre mesa/llevar">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="w-3 h-3 sm:w-4 sm:h-4"
                                                                        viewBox="0 0 20 20" fill="currentColor">
                                                                        <path
                                                                            d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8z" />
                                                                        <path
                                                                            d="M12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z" />
                                                                    </svg>
                                                                </button>
                                                                <button wire:click="removerPlato({{ $index }})"
                                                                    class="p-1 text-white transition bg-red-500 rounded hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700"
                                                                    title="Eliminar">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="w-3 h-3 sm:w-4 sm:h-4"
                                                                        viewBox="0 0 20 20" fill="currentColor">
                                                                        <path fill-rule="evenodd"
                                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <!-- No hay platos -->
                                                @endforelse

                                                <!-- Existencias -->
                                                @forelse($existenciasComanda as $index => $existencia)
                                                    <tr
                                                        class="transition-colors bg-green-50/30 hover:bg-green-50/50 dark:bg-green-900/10 dark:hover:bg-green-900/20">
                                                        <td
                                                            class="px-3 py-3 text-sm text-gray-800 dark:text-gray-200 sm:px-4">
                                                            {{ $existencia['nombre'] }}
                                                            <p class="text-xs text-gray-500">
                                                                {{ $existencia['unidad_medida'] }}</p>
                                                            <!-- Información adicional para móviles -->
                                                            <div
                                                                class="flex flex-wrap items-center gap-2 mt-1 sm:hidden">
                                                                @if ($existencia['es_producto'])
                                                                    <span
                                                                        class="{{ $existencia['es_helado'] ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' }} px-2 py-0.5 text-xs font-semibold rounded-full">
                                                                        {{ $existencia['es_helado'] ? 'HELADO' : 'NORMAL' }}
                                                                    </span>
                                                                @else
                                                                    <span
                                                                        class="px-2 py-0.5 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-200">
                                                                        INSUMO
                                                                    </span>
                                                                @endif
                                                                <span class="text-xs text-gray-600 dark:text-gray-400">
                                                                    S/.
                                                                    {{ number_format($existencia['precio_unitario'], 2) }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="hidden px-3 py-3 text-sm text-center text-gray-800 dark:text-gray-200 sm:px-4 sm:table-cell">
                                                            @if ($existencia['es_producto'])
                                                                <span
                                                                    class="{{ $existencia['es_helado'] ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' }} px-2 py-1 text-xs font-semibold rounded-full">
                                                                    {{ $existencia['es_helado'] ? 'HELADO' : 'NORMAL' }}
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-200">
                                                                    INSUMO
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td
                                                            class="hidden px-3 py-3 text-sm text-center text-gray-800 dark:text-gray-200 sm:px-4 sm:table-cell">
                                                            S/. {{ number_format($existencia['precio_unitario'], 2) }}
                                                        </td>
                                                        <td class="px-2 py-3 text-center sm:px-4">
                                                            <div class="flex items-center justify-center">
                                                                <button
                                                                    wire:click="decrementarCantidadExistencia({{ $index }})"
                                                                    class="p-1 text-gray-500 transition bg-gray-200 rounded hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="w-3 h-3 sm:w-4 sm:h-4"
                                                                        viewBox="0 0 20 20" fill="currentColor">
                                                                        <path fill-rule="evenodd"
                                                                            d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                </button>
                                                                <span
                                                                    class="mx-1 text-sm font-medium text-gray-800 dark:text-gray-200 sm:mx-2">{{ $existencia['cantidad'] }}</span>
                                                                <button
                                                                    wire:click="incrementarCantidadExistencia({{ $index }})"
                                                                    class="p-1 text-white transition bg-green-500 rounded hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="w-3 h-3 sm:w-4 sm:h-4"
                                                                        viewBox="0 0 20 20" fill="currentColor">
                                                                        <path fill-rule="evenodd"
                                                                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="px-3 py-3 text-sm font-medium text-center text-gray-800 dark:text-gray-200 sm:px-4">
                                                            S/. {{ number_format($existencia['subtotal'], 2) }}
                                                        </td>
                                                        <td class="px-2 py-3 text-center sm:px-4">
                                                            <div
                                                                class="flex items-center justify-center space-x-1 sm:space-x-2">
                                                                @if ($existencia['es_producto'])
                                                                    <button
                                                                        wire:click="toggleHeladoExistencia({{ $index }})"
                                                                        class="p-1 text-blue-500 transition bg-blue-100 rounded hover:bg-blue-200 dark:bg-blue-900/20 dark:hover:bg-blue-800/30 dark:text-blue-400"
                                                                        title="Cambiar entre normal/helado">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="w-3 h-3 sm:w-4 sm:h-4"
                                                                            viewBox="0 0 20 20" fill="currentColor">
                                                                            <path
                                                                                d="M8 2a1 1 0 000 2h2a1 1 0 100-2H8z" />
                                                                            <path
                                                                                d="M3 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v6h-4.586l1.293-1.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L10.414 13H15v3a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm12 6h2a1 1 0 110 2h-2v-2z" />
                                                                        </svg>
                                                                    </button>
                                                                @endif
                                                                <button
                                                                    wire:click="removerExistencia({{ $index }})"
                                                                    class="p-1 text-white transition bg-red-500 rounded hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700"
                                                                    title="Eliminar">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="w-3 h-3 sm:w-4 sm:h-4"
                                                                        viewBox="0 0 20 20" fill="currentColor">
                                                                        <path fill-rule="evenodd"
                                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <!-- No hay existencias -->
                                                @endforelse

                                                <!-- Mensaje cuando no hay items -->
                                                @if (count($platosComanda) == 0 && count($existenciasComanda) == 0)
                                                    <tr>
                                                        <td colspan="6"
                                                            class="px-3 py-4 text-sm text-center text-gray-500 dark:text-gray-400 sm:px-4">
                                                            No hay platos ni existencias seleccionadas.
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
                                class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                                <h3 class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Resumen de
                                    Totales</h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Subtotal:</span>
                                        <span class="text-sm font-medium text-gray-800 dark:text-gray-200">S/.
                                            {{ number_format($subtotalGeneral, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">IGV (10%):</span>
                                        <span class="text-sm font-medium text-gray-800 dark:text-gray-200">S/.
                                            {{ number_format($igvGeneral, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Descuento:</span>
                                        <span class="text-sm font-medium text-gray-800 dark:text-gray-200">S/.
                                            {{ number_format($descuentoGeneral, 2) }}</span>
                                    </div>
                                    <div class="pt-2 mt-2 border-t border-gray-200 dark:border-gray-600">
                                        <div class="flex justify-between">
                                            <span
                                                class="text-base font-bold text-gray-800 dark:text-gray-200">TOTAL:</span>
                                            <span class="text-base font-bold text-indigo-600 dark:text-indigo-400">S/.
                                                {{ number_format($totalGeneral, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Info de mesa -->
                            <div
                                class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                                <div class="flex flex-wrap items-center justify-between gap-4">
                                    <div class="flex flex-wrap items-center gap-4">
                                        <!-- Zona -->
                                        <div
                                            class="flex flex-col items-start space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-2">
                                            <label for="zona-input"
                                                class="text-sm font-medium tracking-wide text-gray-700 dark:text-gray-300 sm:mr-2">
                                                Zona:
                                            </label>
                                            <input id="zona-input" type="text" value="{{ $this->nombre_zona }}"
                                                readonly disabled
                                                class="w-full px-3 py-2 text-sm font-medium text-indigo-600 bg-gray-100 border border-gray-300 rounded-md shadow-sm sm:w-34 dark:bg-gray-600 dark:border-gray-600 dark:text-indigo-300">
                                        </div>

                                        <!-- Mesa -->
                                        <div class="flex items-center">
                                            <label for="mesa-input"
                                                class="mr-2 text-sm font-medium tracking-wide text-gray-700 dark:text-gray-300">
                                                Mesa:
                                            </label>
                                            <input id="mesa-input" type="text" value="{{ $this->numero_mesa }}"
                                                readonly disabled
                                                class="w-16 px-3 py-2 text-sm font-medium text-center text-indigo-600 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-indigo-300">
                                        </div>
                                    </div>

                                    <livewire:cliente.mesa-component>
                                </div>
                            </div>

                        </div>

                        <!-- Segunda columna (2/5) -->
                        <div class="order-2 space-y-4 lg:order-2 lg:col-span-2">

                            <!-- Datos del Cliente (Card con sombra suave) -->
                            <div
                                class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                                <h3 class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Datos del
                                    Cliente
                                </h3>

                                <!-- Barra de búsqueda con botones -->
                                <div class="flex flex-col items-stretch gap-2 mb-3 sm:flex-row sm:items-center">
                                    <div class="relative w-full">
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-4 h-4 text-gray-500 dark:text-gray-400" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="text" wire:model="numero_documento_buscar"
                                            wire:keydown.enter="buscar"
                                            class="block w-full h-9 py-1.5 pl-10 pr-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:text-gray-200"
                                            placeholder="Ingrese N.° de documento del cliente">
                                    </div>

                                    <div class="flex w-full gap-2 mt-2 sm:w-auto sm:mt-0">
                                        <!-- Botón de búsqueda -->
                                        <button wire:click="buscar"
                                            class="flex items-center justify-center h-9 px-3 py-1.5 text-sm font-medium text-white transition bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-indigo-700 dark:hover:bg-indigo-800">
                                            <svg wire:target='buscar' wire:loading.remove
                                                xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span wire:target='buscar' wire:loading.remove
                                                class="ml-1">Buscar</span> <span wire:target='buscar' wire:loading>
                                                Buscando...</span>
                                        </button>

                                        <!-- Botón de nuevo cliente -->
                                        <livewire:cliente.crear-cliente>
                                    </div>
                                </div>

                                <!-- Campos del cliente reorganizados -->
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    <!-- Tipo de Documento -->
                                    <div>
                                        <label for="tipo-doc-cliente"
                                            class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                            Tipo de Documento
                                        </label>
                                        <input id="tipo-doc-cliente" type="text" readonly disabled
                                            value="{{ $this->tipo_documentoNombre }}"
                                            class="block w-full px-3 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-200">
                                    </div>

                                    <!-- N° de Documento -->
                                    <div>
                                        <label for="nro-doc-cliente"
                                            class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                            N°. de Documento
                                        </label>
                                        <input id="nro-doc-cliente" type="text" readonly disabled
                                            value="{{ $this->numero_documento }}"
                                            class="block w-full px-3 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-200">
                                    </div>

                                    <!-- Razón Social (ocupando toda la fila) -->
                                    <div class="sm:col-span-2">
                                        <label for="razon-social"
                                            class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                            Razón Social
                                        </label>
                                        <input id="razon-social" type="text" readonly disabled
                                            value="{{ $this->razon_social }}"
                                            class="block w-full px-3 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-200">
                                    </div>


                                </div>

                                <!-- Información adicional y botones -->
                                <div class="flex flex-wrap items-center justify-end gap-2 mt-3">
                                    <span class="mr-5 text-xs text-gray-500 dark:text-gray-400">Cliente desde:
                                        {{ $this->fecha_cliente }}</span>
                                    <div class="flex space-x-2">
                                        <button wire:click="limpiarCliente"
                                            class="flex items-center px-2 py-1 text-xs text-red-500 transition-colors duration-200 bg-red-100 rounded-md hover:bg-red-200 dark:bg-red-900/20 dark:hover:bg-red-900/30 dark:text-red-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Limpiar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Facturación Electrónica con Grid Responsivo -->
                            <div
                                class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                                <h3 class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Facturación
                                    Electrónica</h3>

                                <!-- Grid con 2 elementos por fila en pantallas medianas y grandes -->
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-2">
                                    <!-- Tipo de Comprobante -->
                                    <div>
                                        <label for="tipo-comprobante"
                                            class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                            Tipo de Comprobante
                                        </label>
                                        <select id="tipo-comprobante" wire:model.live="tipoComprobanteSeleccionado"
                                            {{ !$clienteEncontrado ? 'disabled' : '' }}
                                            class="block w-full p-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:ring-indigo-600">
                                            <option value="" selected>Seleccione comprobante</option>
                                            @foreach ($tipoComprobantes as $comprobante)
                                                <option value="{{ $comprobante->codigo }}">
                                                    {{ $comprobante->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Serie -->
                                    <div>
                                        <label for="serie-comprobante"
                                            class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                            Serie
                                        </label>
                                        <input id="serie-comprobante" type="text" wire:model="serieComprobante"
                                            readonly disabled
                                            class="block w-full px-3 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-200">
                                    </div>

                                    <!-- Número -->
                                    <div>
                                        <label for="numero-comprobante"
                                            class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                            Número
                                        </label>
                                        <input id="numero-comprobante" type="text" wire:model="numeroComprobante"
                                            readonly disabled
                                            class="block w-full px-3 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-200">
                                    </div>

                                    <!-- Fecha de Emisión -->
                                    <div>
                                        <label for="fecha-emision"
                                            class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                            Fecha de Emisión
                                        </label>
                                        <input type="text" id="fecha-emision"
                                            value="{{ now()->format('d/m/Y') }}" readonly disabled
                                            class="block w-full px-3 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-200">
                                    </div>


                                    <!-- Moneda -->
                                    <div>
                                        <label for="moneda"
                                            class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                            Moneda
                                        </label>
                                        <select id="moneda" wire:model.live='monedaSelecionada'
                                            {{ !$clienteEncontrado ? 'disabled' : '' }}
                                            class="block w-full p-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:ring-indigo-600">
                                            <option value="" selected>Seleccione moneda
                                            </option>
                                            <option value="PEN">Soles (PEN)</option>
                                            <option value="USD">Dólares (USD)</option>
                                        </select>

                                    </div>

                                    <!-- Forma de Pago (Nuevo campo) -->
                                    <div>
                                        <label for="forma-pago"
                                            class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                            Forma de Pago
                                        </label>
                                        <select id="forma-pago" wire:model.live="formaPago"
                                            {{ !$clienteEncontrado ? 'disabled' : '' }}
                                            class="block w-full p-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:ring-indigo-600">
                                            <option value="" selected>Seleccione
                                            </option>
                                            <option value="Contado">Contado</option>
                                            <option value="Credito">Crédito</option>
                                        </select>

                                    </div>
                                </div>
                            </div>



                            <!-- Modificación de documento (Card con fondo sutil) -->
                            {{-- <div
                                class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                                <h3 class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Modificación
                                    de
                                    Documento</h3>
                                <!-- Grid para Modificación de Documento - 2 elementos por fila -->
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    <!-- Documento a Modificar -->
                                    <div>
                                        <label for="doc-modificar"
                                            class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                            Documento a Modificar
                                        </label>
                                        <select id="doc-modificar" {{ !$clienteEncontrado ? 'disabled' : '' }}
                                            class="block w-full p-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:ring-indigo-600">
                                            <option value="">Seleccionar documento</option>
                                            @foreach ($tipoComprobantes as $comprobante)
                                                <option value="{{ $comprobante->codigo }}">
                                                    {{ $comprobante->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Nro. Documento -->
                                    <div>
                                        <label for="nro-doc-modificar"
                                            class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                            Nro. Documento
                                        </label>
                                        <input id="nro-doc-modificar" type="text" placeholder="F001-000000"
                                            {{ !$clienteEncontrado ? 'disabled' : '' }}
                                            class="block w-full px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:text-gray-200">
                                    </div>

                                    <!-- Motivo (ocupando toda la fila) -->
                                    <div class="sm:col-span-2">
                                        <label for="motivo"
                                            class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                            Motivo
                                        </label>
                                        <select id="motivo" {{ !$clienteEncontrado ? 'disabled' : '' }}
                                            class="block w-full p-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:ring-indigo-600">
                                            <option value="">Seleccionar motivo</option>
                                            <option value="ANULACION">Anulación de la operación</option>
                                            <option value="CORRECCION">Corrección por error</option>
                                            <option value="DESCUENTO">Descuento global</option>
                                            <option value="DEVOLUCION">Devolución total</option>
                                        </select>
                                    </div>
                                </div>
                            </div> --}}



                            <!-- Método de Pago y Observaciones -->
                            <div
                                class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                                <!-- Botones de acción con diseño balanceado -->
                                <div class="grid grid-cols-12 gap-3">
                                    <!-- Botón para solo guardar pedido -->
                                    <div class="col-span-12 sm:col-span-4">
                                        <button wire:click="guardarPedido"
                                            class="w-full flex items-center px-3 py-2.5 rounded-md bg-white border border-gray-300 hover:bg-gray-50 hover:border-gray-400 dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:border-gray-500 transition-all duration-200">

                                            <!-- Icono con estado de carga -->
                                            <div class="p-1.5 mr-2 bg-gray-100 rounded-md dark:bg-gray-600">
                                                <!-- Icono normal -->
                                                <svg wire:loading.remove wire:target='guardarPedido'
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="w-5 h-5 text-gray-600 dark:text-gray-300"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                                    <path fill-rule="evenodd"
                                                        d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>

                                                <!-- Icono de carga -->
                                                <svg wire:loading wire:target='guardarPedido'
                                                    class="w-5 h-5 text-gray-600 animate-spin dark:text-gray-300"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </div>

                                            <!-- Texto del botón -->
                                            <div class="text-left">
                                                <span wire:loading.remove wire:target='guardarPedido'
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                                    Guardar Pedido
                                                </span>
                                                <span wire:loading.remove wire:target='guardarPedido'
                                                    class="block text-[9px] text-gray-500 dark:text-gray-400">
                                                    Sin procesar pago
                                                </span>
                                                <span wire:loading wire:target='guardarPedido'
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                                    Guardando...
                                                </span>
                                            </div>
                                        </button>
                                    </div>

                                    <!-- Botón para guardar pedido y generar comprobante -->
                                    <div class="col-span-12 sm:col-span-8">
                                        <button wire:click='guardarComandaComprobante'
                                            class="w-full flex items-center px-3 py-2.5 rounded-md bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 dark:from-indigo-600 dark:to-indigo-700 dark:hover:from-indigo-500 dark:hover:to-indigo-600 text-white shadow-sm hover:shadow transition-all duration-200 relative">

                                            <!-- Icono con estado de carga -->
                                            <div class="p-1.5 mr-2 bg-white/10 rounded-md">
                                                <!-- Icono normal -->
                                                <svg wire:loading.remove wire:target='guardarComandaComprobante'
                                                    xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm2.5 3a1.5 1.5 0 100 3 1.5 1.5 0 000-3zm6.207.293a1 1 0 00-1.414 0l-6 6a1 1 0 101.414 1.414l6-6a1 1 0 000-1.414zM12.5 10a1.5 1.5 0 100 3 1.5 1.5 0 000-3z"
                                                        clip-rule="evenodd" />
                                                </svg>

                                                <!-- Icono de carga -->
                                                <svg wire:loading wire:target='guardarComandaComprobante'
                                                    class="w-5 h-5 text-white animate-spin"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </div>

                                            <!-- Texto del botón -->
                                            <div class="text-left">
                                                <span wire:loading.remove wire:target='guardarComandaComprobante'
                                                    class="block text-sm font-medium text-white">
                                                    Registrar Venta y Generar CPE
                                                </span>
                                                <span wire:loading.remove wire:target='guardarComandaComprobante'
                                                    class="block text-[9px] text-indigo-100 dark:text-indigo-200">
                                                    Finaliza y registra el pago del pedido
                                                </span>
                                                <span wire:loading wire:target='guardarComandaComprobante'
                                                    class="block text-sm font-medium text-white">
                                                    Procesando Venta...
                                                </span>
                                            </div>

                                            <!-- Etiqueta discreta de acción recomendada con letra más pequeña -->
                                            <div
                                                class="absolute top-0 right-0 text-[9px] font-medium text-white bg-green-500 px-1 py-0.5 rounded-bl-md rounded-tr-md dark:bg-green-600">
                                                Recomendado
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Información de usuario y caja -->
                            <div
                                class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                                <!-- Encabezado con usuario y nombre de caja -->
                                <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                                    <!-- Usuario que inició sesión -->
                                    <div
                                        class="flex items-center px-2 py-1 bg-gray-100 rounded-full sm:px-3 dark:bg-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-3 h-3 mr-1 text-indigo-500 sm:w-4 sm:h-4 dark:text-indigo-400"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span
                                            class="text-xs font-medium text-gray-700 sm:text-sm dark:text-gray-300">{{ auth()->user()->name }}</span>
                                    </div>

                                    <!-- Estado y nombre de caja -->
                                    <div
                                        class="flex items-center px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full sm:px-3 sm:text-sm dark:bg-green-900/30 dark:text-green-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1 sm:w-4 sm:h-4"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M3 3a1 1 0 000 2h10a1 1 0 100-2H3zm0 4a1 1 0 000 2h6a1 1 0 100-2H3zm0 4a1 1 0 100 2h8a1 1 0 100-2H3z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $caja->nombre ?? '' }}
                                    </div>
                                </div>

                                <!-- Panel de saldo -->
                                <div
                                    class="p-4 mb-4 border border-blue-200 rounded-lg bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-indigo-900/20 dark:border-blue-800/40">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Saldo
                                            Actual:</span>
                                        <span class="text-xl font-bold text-blue-700 dark:text-blue-400">S/.
                                            {{ number_format($caja->saldo_actual, 2) }}</span>
                                    </div>
                                </div>

                                <!-- Botones de acción -->
                                <div class="grid grid-cols-4 gap-2">

                                    <div>
                                        <livewire:gestion-comandas :caja-id="$caja->id">
                                    </div>
                                    <!-- Botón Transferir -->
                                    <div>
                                        <livewire:transferir-componente :caja-id="$caja->id">
                                    </div>

                                    <!-- Botón Retirar -->
                                    <div>
                                        <livewire:retirar-componente :caja-id="$caja->id">
                                    </div>

                                    <!-- Botón Cerrar Caja -->
                                    <div>
                                        <button type="button" wire:click="cerrarCaja"
                                            class="flex flex-col items-center justify-center w-full px-2 py-3 text-sm font-medium text-white transition bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-red-700 dark:hover:bg-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-1"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-xs sm:text-sm" wire:loading.remove
                                                wire:target='cerrarCaja'>Cerrar Caja</span>
                                            <span class="text-xs sm:text-sm" wire:loading
                                                wire:target='cerrarCaja'>Cerrando...</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal de Existencias -->
    @if ($mostrarModalExistencia)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-gray-900 bg-opacity-60">
            <div class="relative w-full max-w-5xl mx-auto bg-white rounded-lg shadow-xl dark:bg-gray-800">
                <!-- Encabezado del modal con gradiente -->
                <div
                    class="px-6 py-4 border-b border-gray-200 rounded-t-lg bg-gradient-to-r from-green-500 to-teal-600 dark:from-green-800 dark:to-teal-900 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-white">
                            Seleccionar Existencia
                        </h3>
                        <button
                            class="p-1 text-white rounded-full hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/50"
                            wire:click="cerrarModalExistencia">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <!-- Separador con título de sección -->
                    <div class="flex items-center mb-4">
                        <div class="flex-grow h-px bg-gray-200 dark:bg-gray-700">
                        </div>
                        <h4 class="px-3 text-sm font-medium text-gray-500 uppercase dark:text-gray-400">
                            Tipos de
                            Existencia</h4>
                        <div class="flex-grow h-px bg-gray-200 dark:bg-gray-700">
                        </div>
                    </div>

                    <!-- Botones de tipos de existencia -->
                    <div class="flex flex-wrap gap-2 mb-6 overflow-x-auto">
                        @foreach ($tipos_existencia as $tipo)
                            <button
                                class="px-4 py-2 text-sm font-medium transition-colors duration-200 border rounded-md {{ $selectedTipoExistencia == $tipo->id ? 'bg-green-600 text-white border-green-700 shadow-sm dark:bg-green-700 dark:border-green-800' : 'text-green-700 bg-white border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-green-300 dark:border-gray-700 dark:hover:bg-gray-700' }}"
                                wire:click="selectTipoExistencia('{{ $tipo->id }}')">
                                {{ $tipo->nombre }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Separador con título de sección -->
                    <div class="flex items-center mb-4">
                        <div class="flex-grow h-px bg-gray-200 dark:bg-gray-700">
                        </div>
                        <h4 class="px-3 text-sm font-medium text-gray-500 uppercase dark:text-gray-400">
                            Categorías</h4>
                        <div class="flex-grow h-px bg-gray-200 dark:bg-gray-700">
                        </div>
                    </div>

                    <!-- Botones de categorías -->
                    <div class="flex flex-wrap gap-2 mb-6 overflow-x-auto">
                        <button
                            class="px-4 py-2 text-sm font-medium transition-colors duration-200 border rounded-md {{ $selectedCategoriaExistencia == '' ? 'bg-green-600 text-white border-green-700 shadow-sm dark:bg-green-700 dark:border-green-800' : 'text-green-700 bg-white border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-green-300 dark:border-gray-700 dark:hover:bg-gray-700' }}"
                            wire:click="selectCategoriaExistencia('')">
                            Todas
                        </button>
                        @foreach ($categorias_existencia as $categoria)
                            <button
                                class="px-4 py-2 text-sm font-medium transition-colors duration-200 border rounded-md {{ $selectedCategoriaExistencia == $categoria->id ? 'bg-green-600 text-white border-green-700 shadow-sm dark:bg-green-700 dark:border-green-800' : 'text-green-700 bg-white border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-green-300 dark:border-gray-700 dark:hover:bg-gray-700' }}"
                                wire:click="selectCategoriaExistencia('{{ $categoria->id }}')">
                                {{ $categoria->nombre }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Grid de Existencias -->
                    <div wire:poll.4s.visible class="overflow-y-auto min-h-[360px]" style="max-height: 360px;">
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                            @forelse($existencias as $existencia)
                                <div
                                    class="relative transition-all duration-200 bg-white border border-gray-200 rounded-lg shadow-sm {{ $existencia->inventario && $existencia->inventario->stock > 0 ? 'hover:border-green-500 hover:shadow' : 'opacity-70' }} dark:bg-gray-800 dark:border-gray-700 dark:hover:border-green-500">
                                    <div class="p-2.5">
                                        <div class="flex flex-col h-full">
                                            <div>
                                                <h5
                                                    class="text-sm font-medium text-gray-800 truncate dark:text-gray-200">
                                                    {{ $existencia->nombre }}
                                                </h5>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $existencia->unidadMedida->nombre }}
                                                </p>
                                            </div>
                                            <div class="mt-1.5">
                                                <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                    S/.
                                                    {{ number_format($existencia->precio_venta, 2) }}
                                                </span>
                                            </div>
                                            <!-- Estado de disponibilidad -->
                                            <div class="mt-1.5 mb-1">
                                                @if ($existencia->inventario && $existencia->inventario->stock > 0)
                                                    <div class="flex items-center text-green-600 dark:text-green-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        <span class="text-xs font-medium">Disponible
                                                            ({{ $existencia->inventario->stock }})
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="flex items-center text-red-600 dark:text-red-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        <span class="text-xs font-medium">Agotado</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="grid grid-cols-1 gap-1 mt-auto">
                                                @if ($existencia->inventario && $existencia->inventario->stock > 0)
                                                    @if ($existencia->tipo_existencia_id == $tipoProductosId)
                                                        <div class="grid grid-cols-2 gap-1">
                                                            <button
                                                                wire:click="agregarExistencia({{ $existencia->id }}, false)"
                                                                class="px-2 py-1 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-green-700 dark:hover:bg-green-800">
                                                                Normal
                                                            </button>
                                                            <button
                                                                wire:click="agregarExistencia({{ $existencia->id }}, true)"
                                                                class="px-2 py-1 text-xs font-medium text-white bg-indigo-600 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-indigo-700 dark:hover:bg-indigo-800">
                                                                Helado
                                                            </button>
                                                        </div>
                                                    @else
                                                        <button
                                                            wire:click="agregarExistencia({{ $existencia->id }}, false)"
                                                            class="px-2 py-1 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-green-700 dark:hover:bg-green-800">
                                                            Agregar
                                                        </button>
                                                    @endif
                                                @else
                                                    <button disabled
                                                        class="px-2 py-1 text-xs font-medium text-gray-500 bg-gray-200 rounded cursor-not-allowed dark:bg-gray-700 dark:text-gray-400">
                                                        No disponible
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @empty
                                <div class="col-span-5 p-6 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                            No hay existencias disponibles con los
                                            filtros seleccionados.
                                        </p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div
                    class="flex items-center justify-end px-6 py-3 border-t border-gray-200 rounded-b-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                    <div class="mr-auto text-xs text-gray-500 dark:text-gray-400">
                        <span class="inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4 mr-1 text-green-500 dark:text-green-400 animate-spin"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Actualizando stock en tiempo real
                        </span>
                    </div>
                    <button wire:click="cerrarModalExistencia"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif





    <!-- Modal de Platos -->
    @if ($mostrarModalPlato)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-gray-900 bg-opacity-60">
            <div class="relative w-full max-w-5xl mx-auto bg-white rounded-lg shadow-xl dark:bg-gray-800">
                <!-- Encabezado del modal con gradiente -->
                <div
                    class="px-6 py-4 border-b border-gray-200 rounded-t-lg bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-800 dark:to-purple-900 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-white">
                            Seleccionar Plato
                        </h3>
                        <button
                            class="p-1 text-white rounded-full hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/50"
                            wire:click="cerrarModalPlato">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <!-- Separador con título de sección -->
                    <div class="flex items-center mb-4">
                        <div class="flex-grow h-px bg-gray-200 dark:bg-gray-700"></div>
                        <h4 class="px-3 text-sm font-medium text-gray-500 uppercase dark:text-gray-400">Categorías
                            de Platos</h4>
                        <div class="flex-grow h-px bg-gray-200 dark:bg-gray-700"></div>
                    </div>

                    <!-- Botones de categorías de platos -->
                    <div class="flex flex-wrap gap-2 mb-6 overflow-x-auto">
                        <button
                            class="px-4 py-2 text-sm font-medium transition-colors duration-200 border rounded-md {{ $selectedCategoriaPlato == '' ? 'bg-indigo-600 text-white border-indigo-700 shadow-sm dark:bg-indigo-700 dark:border-indigo-800' : 'text-indigo-700 bg-white border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-indigo-300 dark:border-gray-700 dark:hover:bg-gray-700' }}"
                            wire:click="selectCategoriaPlato('')">
                            Todas
                        </button>
                        @foreach ($categorias_plato as $categoria)
                            <button
                                class="px-4 py-2 text-sm font-medium transition-colors duration-200 border rounded-md {{ $selectedCategoriaPlato == $categoria->id ? 'bg-indigo-600 text-white border-indigo-700 shadow-sm dark:bg-indigo-700 dark:border-indigo-800' : 'text-indigo-700 bg-white border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-indigo-300 dark:border-gray-700 dark:hover:bg-gray-700' }}"
                                wire:click="selectCategoriaPlato('{{ $categoria->id }}')">
                                {{ $categoria->nombre }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Grid de Platos disponibles -->
                    <div wire:poll.4s.visible class="overflow-y-auto min-h-[400px]" style="max-height: 400px;">
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                            @forelse($platos as $plato)
                                <div
                                    class="relative transition-all duration-200 bg-white border border-gray-200 rounded-lg shadow-sm {{ $plato->disponibilidadPlato && $plato->disponibilidadPlato->disponibilidad == 1 ? 'hover:border-indigo-500 hover:shadow' : 'opacity-70' }} dark:bg-gray-800 dark:border-gray-700 dark:hover:border-indigo-500">
                                    <div class="p-2.5">
                                        <div class="flex flex-col h-full">
                                            <div>
                                                <h5
                                                    class="text-sm font-medium text-gray-800 truncate dark:text-gray-200">
                                                    {{ $plato->nombre }}
                                                </h5>
                                            </div>
                                            <div class="mt-1.5 flex justify-between items-center">
                                                @if ($plato->cajas->isNotEmpty())
                                                    @php $cajaPlato = $plato->cajas->first(); @endphp
                                                    <span
                                                        class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                        S/. {{ number_format($cajaPlato->pivot->precio, 2) }}
                                                    </span>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        Llevar: S/.
                                                        {{ number_format($cajaPlato->pivot->precio_llevar, 2) }}
                                                    </span>
                                                @else
                                                    <span class="text-xs text-red-500">Sin precio configurado</span>
                                                @endif
                                            </div>
                                            <!-- Estado de disponibilidad -->
                                            <div class="mt-1.5 mb-1">
                                                @if ($plato->disponibilidadPlato && $plato->disponibilidadPlato->disponibilidad == 1)
                                                    <div class="flex items-center text-green-600 dark:text-green-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        <span class="text-xs font-medium">Disponible
                                                            ({{ $plato->disponibilidadPlato->cantidad }})
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="flex items-center text-red-600 dark:text-red-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        <span class="text-xs font-medium">Agotado</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="grid grid-cols-2 gap-1 mt-auto">
                                                @if ($plato->disponibilidadPlato && $plato->disponibilidadPlato->disponibilidad == 1)
                                                    <button wire:click="agregarPlato({{ $plato->id }}, false)"
                                                        class="px-2 py-1 text-xs font-medium text-white bg-indigo-600 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-indigo-700 dark:hover:bg-indigo-800">
                                                        Mesa
                                                    </button>
                                                    <button wire:click="agregarPlato({{ $plato->id }}, true)"
                                                        class="px-2 py-1 text-xs font-medium text-white rounded bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-400 dark:bg-amber-600 dark:hover:bg-amber-700">
                                                        Llevar
                                                    </button>
                                                @else
                                                    <button disabled
                                                        class="col-span-2 px-2 py-1 text-xs font-medium text-gray-500 bg-gray-200 rounded cursor-not-allowed dark:bg-gray-700 dark:text-gray-400">
                                                        No disponible
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-5 p-6 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                            No hay platos disponibles con los filtros seleccionados.
                                        </p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div
                    class="flex items-center justify-end px-6 py-3 border-t border-gray-200 rounded-b-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                    <div class="mr-auto text-xs text-gray-500 dark:text-gray-400">
                        <span class="inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4 mr-1 text-indigo-500 dark:text-indigo-400 animate-spin"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Actualizando disponibilidad en tiempo real
                        </span>
                    </div>
                    <button wire:click="cerrarModalPlato"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif


</div>
