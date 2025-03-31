<div>
    @vite('resources/css/app.css')

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Barra superior fija con logo e información crítica -->
        <div
            class="top-0 z-30 transition-colors duration-200 bg-white shadow-md dark:bg-gray-800 dark:shadow-gray-700/30">
            <div class="container p-2 mx-auto sm:p-3">
                <div class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                    <!-- Logo y nombre de empresa - siempre visible -->
                    <div class="flex items-center space-x-3">
                        <div class="p-1 bg-gray-100 rounded-lg sm:p-2 dark:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-6 h-6 text-indigo-500 sm:w-8 sm:h-8 dark:text-indigo-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 1h6v4H7V5zm8 8v2h1v1H4v-1h1v-2a1 1 0 011-1h8a1 1 0 011 1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-sm font-bold text-gray-800 sm:text-base dark:text-gray-100">CAYOTOPA
                                TANTALEAN E.I.R.L</h1>
                            <h2 class="text-xs font-medium text-indigo-500 sm:text-sm dark:text-indigo-400">RESTAURANT
                                EL GORDITO</h2>
                        </div>
                    </div>

                    <!-- Contenedor para elementos de información y control -->
                    <div class="flex flex-wrap items-center justify-between gap-2 sm:space-x-4">
                        <!-- Usuario que inició sesión -->
                        <div class="flex items-center px-2 py-1 bg-gray-100 rounded-full sm:px-3 dark:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-3 h-3 mr-1 text-indigo-500 sm:w-4 sm:h-4 dark:text-indigo-400"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span
                                class="text-xs font-medium text-gray-700 sm:text-sm dark:text-gray-300">ADMINISTRADOR</span>
                        </div>

                        <!-- Caja actual - versión dinámica con soporte para tema claro y oscuro -->
                        <div class="flex">
                            @if ($userType == 'single')
                                <!-- Usuario con una sola caja -->
                                <div
                                    class="flex items-center px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full sm:px-3 sm:text-sm dark:bg-green-900/30 dark:text-green-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1 sm:w-4 sm:h-4"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M3 3a1 1 0 000 2h10a1 1 0 100-2H3zm0 4a1 1 0 000 2h6a1 1 0 100-2H3zm0 4a1 1 0 100 2h8a1 1 0 100-2H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $caja->nombre }}
                                </div>
                            @else
                                <!-- Usuario con múltiples cajas o admin -->
                                <div class="relative flex items-center">
                                    <select id="caja-selector" wire:model.live="selectedCajaId"
                                        class="appearance-none px-2 py-1 sm:px-3 pr-8 text-xs sm:text-sm min-w-[140px] sm:min-w-[180px] font-medium
                text-green-700 bg-green-100 border-none rounded-full
                dark:bg-green-900/30 dark:text-green-300
                focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400">
                                        @foreach ($cajas as $caja)
                                            <option value="{{ $caja->id }}"
                                                class="text-gray-900 bg-white dark:bg-gray-800 dark:text-gray-100">
                                                {{ $caja->nombre }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            @endif
                        </div>

                        <!-- Número de pedido - Dinámico -->
                        <div wire:poll.1s
                            class="px-2 py-1 text-center border border-indigo-500 rounded-lg sm:border-2 dark:border-indigo-400">
                            <p class="text-xs font-bold text-gray-700 dark:text-gray-300">PEDIDO</p>
                            <p class="text-sm font-bold text-indigo-500 sm:text-base dark:text-indigo-400">N°.
                                {{ $numeroPedido }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container px-4 py-3 mx-auto">
            <!-- Facturación con nueva estructura -->
            <div
                class="overflow-hidden transition-colors duration-200 bg-white rounded-lg shadow dark:bg-gray-800 dark:shadow-gray-700/30">

                <div class="p-4">
                    <!-- Primera fila: Información básica -->
                    <div class="grid grid-cols-1 gap-3 mb-4 md:grid-cols-5">
                        <div>
                            <label for="tipo-comprobante"
                                class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                Tipo Comprobante
                            </label>
                            <select id="tipo-comprobante"
                                class="block w-full p-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:ring-indigo-600">
                                <option value="FACTURA">Factura</option>
                                <option value="BOLETA">Boleta</option>
                            </select>
                        </div>

                        <div>
                            <label for="serie-comprobante"
                                class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                Serie
                            </label>
                            <input id="serie-comprobante" type="text" value="F001" readonly disabled
                                class="block w-full px-3 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-200">
                        </div>

                        <div>
                            <label for="numero-comprobante"
                                class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                Número
                            </label>
                            <input id="numero-comprobante" type="text" value="000004" readonly disabled
                                class="block w-full px-3 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-200">
                        </div>

                        <div>
                            <label for="fecha-emision"
                                class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                Fecha Emisión
                            </label>
                            <div
                                class="px-3 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-200">
                                {{ now()->format('d/m/Y') }}
                            </div>
                        </div>

                        <div>
                            <label for="moneda"
                                class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                Moneda
                            </label>
                            <select id="moneda"
                                class="block w-full p-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:ring-indigo-600">
                                <option value="PEN" selected>Soles (PEN)</option>
                                <option value="USD">Dólares (USD)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Segunda fila: Modificación de documento -->
                    <div
                        class="p-3 mb-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700/30 dark:border-gray-700">
                        <h3 class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Modificación de Documento
                        </h3>
                        <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                            <div>
                                <label for="doc-modificar"
                                    class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Documento a Modificar
                                </label>
                                <select id="doc-modificar"
                                    class="block w-full p-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:ring-indigo-600">
                                    <option value="">Seleccionar documento</option>
                                    <option value="FACTURA">Factura</option>
                                    <option value="BOLETA">Boleta</option>
                                </select>
                            </div>
                            <div>
                                <label for="nro-doc-modificar"
                                    class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Nro. Documento
                                </label>
                                <input id="nro-doc-modificar" type="text" placeholder="F001-000000"
                                    class="block w-full px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:text-gray-200">
                            </div>
                            <div>
                                <label for="motivo"
                                    class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Motivo
                                </label>
                                <select id="motivo"
                                    class="block w-full p-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:ring-indigo-600">
                                    <option value="">Seleccionar motivo</option>
                                    <option value="ANULACION">Anulación de la operación</option>
                                    <option value="CORRECCION">Corrección por error</option>
                                    <option value="DESCUENTO">Descuento global</option>
                                    <option value="DEVOLUCION">Devolución total</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Tercera fila: Datos del Cliente -->
                    <div
                        class="p-3 mb-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700/30 dark:border-gray-700">
                        <h3 class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Datos del Cliente</h3>

                        <!-- Barra de búsqueda reorganizada con botones laterales -->
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
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

                            <!-- Botón de búsqueda -->
                            <button wire:click="buscar"
                                class="flex items-center justify-center h-9 px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-indigo-700 dark:hover:bg-indigo-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="ml-1">Buscar</span>
                            </button>

                            <!-- Botón de nuevo cliente -->
                            @livewire('cliente.crear-cliente')
                        </div>

                        <!-- Campos del cliente reorganizados -->
                        <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                            <div>
                                <label for="tipo-doc-cliente"
                                    class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Tipo de Documento
                                </label>
                                <input id="tipo-doc-cliente" type="text" readonly disabled
                                    value=" {{ $this->tipo_documentoNombre }}"
                                    class="block w-full px-3 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-200">
                            </div>
                            <div>
                                <label for="nro-doc-cliente"
                                    class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                    N°. de Documento
                                </label>
                                <input id="nro-doc-cliente" type="text" readonly disabled
                                    value="{{ $this->numero_documento }}"
                                    class="block w-full px-3 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-200">
                            </div>
                            <div class="md:col-span-2">
                                <label for="razon-social"
                                    class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Razón Social
                                </label>
                                <input id="razon-social" type="text" readonly disabled
                                    value="{{ $this->razon_social }}"
                                    class="block w-full px-3 py-2 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-200">
                            </div>
                            <div class="md:col-span-4">
                                <label for="direccion"
                                    class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Dirección
                                </label>
                                <input id="direccion" type="text" placeholder="Ejemplo: AV. PRINCIPAL 123, LIMA"
                                    value="{{ $this->direccion_cliente }}"
                                    class="block w-full px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:text-gray-200">
                            </div>
                        </div>

                        <!-- Información del cliente y botones de acción al final -->
                        <div class="flex flex-wrap items-center justify-end gap-2 mt-3">
                            <span class="mr-5 text-xs text-gray-500 dark:text-gray-400">Cliente desde:
                                15/01/2025</span>
                            <div class="flex space-x-2">
                                <button wire:click="limpiarCliente"
                                    class="flex items-center text-xs text-red-500 transition-colors duration-200 hover:text-red-700 dark:hover:text-red-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Limpiar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Cuarta fila: Detalle de productos con botones de agregar y campo de mesa -->
                    <div class="mb-4">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Detalle de Productos</h3>

                            <!-- Input de Mesa con botón de asignar -->
                            <div class="flex flex-wrap items-center gap-4">
                                <!-- Zona -->
                                <div class="flex items-center">
                                    <label for="zona-input"
                                        class="mr-2 text-sm font-medium tracking-wide text-gray-700 dark:text-gray-300">
                                        Zona:
                                    </label>
                                    <input id="zona-input" type="text" value="{{ $this->nombre_zona }}" readonly
                                        disabled
                                        class="px-3 py-2 text-sm font-medium text-indigo-600 bg-gray-100 border border-gray-300 rounded-md shadow-sm w-28 dark:bg-gray-600 dark:border-gray-600 dark:text-indigo-300">
                                </div>

                                <!-- Mesa -->
                                <div class="flex items-center">
                                    <label for="mesa-input"
                                        class="mr-2 text-sm font-medium tracking-wide text-gray-700 dark:text-gray-300">
                                        Mesa:
                                    </label>
                                    <input id="mesa-input" type="text" value="{{ $this->numero_mesa }}" readonly
                                        disabled
                                        class="w-16 px-3 py-2 text-sm font-medium text-center text-indigo-600 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-indigo-300">
                                </div>

                                @livewire('cliente.mesa-component')
                            </div>
                        </div>

                        <!-- Botones de acción colocados sobre la tabla (más grandes) -->
                        <div class="flex flex-wrap gap-2 mb-3">
                            <!-- Botones mejorados con nuevos iconos y estilos -->
                            <div class="flex flex-wrap gap-3">
                                <!-- Botones con estilo de enfoque consistente -->
                                <div class="flex flex-wrap gap-3">
                                    <!-- Botones con nuevos iconos -->
                                    <div class="flex flex-wrap gap-3">
                                        <!-- Botón de Agregar Existencia (nuevo icono de inventario) -->

                                        @livewire('venta-directa.existencia-component')

                                        <!-- Botón de Agregar Platos (nuevo icono de bandeja de comida) -->

                                        @livewire('venta-directa.plato-component')
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla combinada de platos y existencias -->
                        <div class="mb-4 overflow-x-auto border border-gray-200 rounded-lg dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-4 py-2 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                            Descripción</th>
                                        <th
                                            class="px-4 py-2 text-xs font-medium text-center text-gray-500 uppercase dark:text-gray-400">
                                            Tipo</th>
                                        <th
                                            class="px-4 py-2 text-xs font-medium text-center text-gray-500 uppercase dark:text-gray-400">
                                            Precio Unitario</th>
                                        <th
                                            class="px-4 py-2 text-xs font-medium text-center text-gray-500 uppercase dark:text-gray-400">
                                            Cantidad</th>
                                        <th
                                            class="px-4 py-2 text-xs font-medium text-center text-gray-500 uppercase dark:text-gray-400">
                                            Subtotal</th>
                                        <th
                                            class="px-4 py-2 text-xs font-medium text-center text-gray-500 uppercase dark:text-gray-400">
                                            Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    <!-- Primero mostramos los platos -->
                                    @forelse($platosComanda as $index => $plato)
                                        <tr class="bg-indigo-50/30 dark:bg-indigo-900/10">
                                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">
                                                {{ $plato['nombre'] }}
                                                <p class="text-xs text-gray-500">{{ $plato['categoria'] }}</p>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-center text-gray-800 dark:text-gray-200">
                                                <span
                                                    class="{{ $plato['es_llevar'] ? 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }} px-2 py-1 text-xs font-semibold rounded-full">
                                                    PLATO - {{ $plato['es_llevar'] ? 'LLEVAR' : 'MESA' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-center text-gray-800 dark:text-gray-200">
                                                S/. {{ number_format($plato['precio_unitario'], 2) }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="flex items-center justify-center">
                                                    <button wire:click="decrementarCantidadPlato({{ $index }})"
                                                        class="p-1 text-gray-500 bg-gray-200 rounded dark:bg-gray-700 dark:text-gray-300">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                    <span
                                                        class="mx-2 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $plato['cantidad'] }}</span>
                                                    <button wire:click="incrementarCantidadPlato({{ $index }})"
                                                        class="p-1 text-white bg-indigo-500 rounded dark:bg-indigo-600">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                            <td
                                                class="px-4 py-3 text-sm font-medium text-center text-gray-800 dark:text-gray-200">
                                                S/. {{ number_format($plato['subtotal'], 2) }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <button wire:click="toggleLlevarPlato({{ $index }})"
                                                        class="p-1 text-orange-500 bg-orange-100 rounded hover:bg-orange-200 dark:bg-orange-900/20 dark:hover:bg-orange-800/30 dark:text-orange-400"
                                                        title="Cambiar entre mesa/llevar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path
                                                                d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8z" />
                                                            <path
                                                                d="M12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z" />
                                                        </svg>
                                                    </button>
                                                    <button wire:click="removerPlato({{ $index }})"
                                                        class="p-1 text-white bg-red-500 rounded hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700"
                                                        title="Eliminar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
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

                                    <!-- Luego mostramos las existencias -->
                                    @forelse($existenciasComanda as $index => $existencia)
                                        <tr class="bg-green-50/30 dark:bg-green-900/10">
                                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">
                                                {{ $existencia['nombre'] }}
                                                <p class="text-xs text-gray-500">{{ $existencia['categoria'] }}</p>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-center text-gray-800 dark:text-gray-200">
                                                @if ($existencia['es_producto'])
                                                    <span
                                                        class="{{ $existencia['es_helado'] ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' }} px-2 py-1 text-xs font-semibold rounded-full">
                                                        PRODUCTO - {{ $existencia['es_helado'] ? 'HELADO' : 'NORMAL' }}
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-200">
                                                        INSUMO
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-center text-gray-800 dark:text-gray-200">
                                                S/. {{ number_format($existencia['precio_unitario'], 2) }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="flex items-center justify-center">
                                                    <button
                                                        wire:click="decrementarCantidadExistencia({{ $index }})"
                                                        class="p-1 text-gray-500 bg-gray-200 rounded dark:bg-gray-700 dark:text-gray-300">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                    <span
                                                        class="mx-2 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $existencia['cantidad'] }}</span>
                                                    <button
                                                        wire:click="incrementarCantidadExistencia({{ $index }})"
                                                        class="p-1 text-white bg-green-500 rounded dark:bg-green-600">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                            <td
                                                class="px-4 py-3 text-sm font-medium text-center text-gray-800 dark:text-gray-200">
                                                S/. {{ number_format($existencia['subtotal'], 2) }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="flex items-center justify-center space-x-2">
                                                    @if ($existencia['es_producto'])
                                                        <button
                                                            wire:click="toggleHeladoExistencia({{ $index }})"
                                                            class="p-1 text-blue-500 bg-blue-100 rounded hover:bg-blue-200 dark:bg-blue-900/20 dark:hover:bg-blue-800/30 dark:text-blue-400"
                                                            title="Cambiar entre normal/helado">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                                viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M8 2a1 1 0 000 2h2a1 1 0 100-2H8z" />
                                                                <path
                                                                    d="M3 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v6h-4.586l1.293-1.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L10.414 13H15v3a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm12 6h2a1 1 0 110 2h-2v-2z" />
                                                            </svg>
                                                        </button>
                                                    @endif
                                                    <button wire:click="removerExistencia({{ $index }})"
                                                        class="p-1 text-white bg-red-500 rounded hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700"
                                                        title="Eliminar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
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
                                                class="px-4 py-3 text-sm text-center text-gray-500 dark:text-gray-400">
                                                No hay platos ni existencias seleccionadas. Use los botones "Agregar
                                                Platos" o "Agregar Existencia" para añadir ítems a la comanda.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <!-- Quinta fila: Resumen de Totales y botones de acción -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="order-2 md:order-1">
                            <!-- Método de Pago -->
                            <div class="pt-2 mt-2 border-t border-gray-200 dark:border-gray-600">
                                <p class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Método de
                                    Pago</p>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="relative">
                                        <input type="radio" name="metodo-pago" id="efectivo" value="efectivo"
                                            class="sr-only peer" checked>
                                        <label for="efectivo"
                                            class="flex items-center justify-center py-2 text-sm text-gray-700 transition-colors duration-200 border border-gray-300 rounded-md cursor-pointer dark:border-gray-600 peer-checked:border-indigo-500 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 dark:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Efectivo
                                        </label>
                                    </div>
                                    <div class="relative">
                                        <input type="radio" name="metodo-pago" id="tarjeta" value="tarjeta"
                                            class="sr-only peer">
                                        <label for="tarjeta"
                                            class="flex items-center justify-center py-2 text-sm text-gray-700 transition-colors duration-200 border border-gray-300 rounded-md cursor-pointer dark:border-gray-600 peer-checked:border-indigo-500 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 dark:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                                <path fill-rule="evenodd"
                                                    d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Yape
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- Observaciones -->
                            <div class="mb-4">
                                <label for="observaciones"
                                    class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Observaciones
                                </label>
                                <textarea id="observaciones" rows="2"
                                    class="block w-full px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:text-gray-200"
                                    placeholder="Agregar observaciones o notas..."></textarea>
                            </div>

                            <!-- Nuevos botones de acción -->
                            <div class="grid grid-cols-3 gap-2">
                                <!-- Botón para solo guardar pedido -->
                                <button wire:click="guardarComanda"
                                    class="flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-700 transition-colors duration-200 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z" />
                                    </svg>
                                    Guardar Pedido
                                </button>

                                <!-- Botón para guardar pedido y generar comprobante -->
                                <button
                                    class="flex items-center justify-center col-span-2 px-3 py-2 text-sm font-medium text-white transition-colors duration-200 bg-indigo-600 rounded-md hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm4.707 3.707a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L8.414 9H10a3 3 0 013 3v1a1 1 0 102 0v-1a5 5 0 00-5-5H8.414l1.293-1.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Guardar y Generar Comprobante
                                </button>
                            </div>
                        </div>

                        <div class="order-1 md:order-2">
                            <!-- Resumen de totales -->
                            <div
                                class="p-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700/30 dark:border-gray-700">
                                <h3 class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Resumen de
                                    Totales</h3>
                                <div class="space-y-2">
                                    <!-- Sección de platos y existencias combinados -->
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Subtotal:</span>
                                        <span class="text-sm font-medium text-gray-800 dark:text-gray-200">S/.
                                            {{ number_format($subtotalGeneral, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">IGV (18%):</span>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>





{{--


    <!-- Estado de caja y acceso rápido -->
    <div
        class="overflow-hidden transition-colors duration-200 bg-white rounded-lg shadow dark:bg-gray-800 dark:shadow-gray-700/30">
        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-gray-700">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-indigo-500 dark:text-indigo-400"
                    viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                        clip-rule="evenodd" />
                </svg>
                <h2 class="text-base font-semibold text-gray-700 dark:text-gray-200">ESTADO DE CAJA
                </h2>
            </div>
            <div class="text-sm font-medium text-indigo-600 dark:text-indigo-400">CAJA 1</div>
        </div>

        <div class="p-3">
            <div class="grid grid-cols-2 gap-2 mb-3">
                <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-700/50">
                    <div class="text-xs text-gray-500 dark:text-gray-400">Apertura</div>
                    <div class="font-medium text-gray-800 dark:text-gray-200">S/. 500.00</div>
                </div>
                <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-700/50">
                    <div class="text-xs text-gray-500 dark:text-gray-400">Ventas</div>
                    <div class="font-medium text-green-600 dark:text-green-400">S/. 1,250.80</div>
                </div>
                <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-700/50">
                    <div class="text-xs text-gray-500 dark:text-gray-400">Efectivo</div>
                    <div class="font-medium text-gray-800 dark:text-gray-200">S/. 850.50</div>
                </div>
                <div class="p-2 rounded-md bg-gray-50 dark:bg-gray-700/50">
                    <div class="text-xs text-gray-500 dark:text-gray-400">Tarjeta</div>
                    <div class="font-medium text-gray-800 dark:text-gray-200">S/. 400.30</div>
                </div>
            </div>
            <!-- Barra superior de acciones rápidas -->
            <div
                class="p-2 transition-colors duration-200 bg-white rounded-lg shadow dark:bg-gray-800 dark:shadow-gray-700/30">
                <div class="grid grid-cols-5 gap-2">
                    <button
                        class="flex flex-col items-center justify-center p-2 text-green-700 transition-colors duration-200 rounded-lg bg-green-50 hover:bg-green-100 dark:bg-green-900/20 dark:hover:bg-green-800/30 dark:text-green-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="text-xs font-medium">Cobrar</span>
                    </button>

                    <button
                        class="flex flex-col items-center justify-center p-2 text-blue-700 transition-colors duration-200 rounded-lg bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-800/30 dark:text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-xs font-medium">Nueva</span>
                    </button>

                    <button
                        class="flex flex-col items-center justify-center p-2 text-purple-700 transition-colors duration-200 rounded-lg bg-purple-50 hover:bg-purple-100 dark:bg-purple-900/20 dark:hover:bg-purple-800/30 dark:text-purple-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span class="text-xs font-medium">Transferir</span>
                    </button>

                    <button
                        class="flex flex-col items-center justify-center p-2 text-orange-700 transition-colors duration-200 rounded-lg bg-orange-50 hover:bg-orange-100 dark:bg-orange-900/20 dark:hover:bg-orange-800/30 dark:text-orange-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span class="text-xs font-medium">Ver</span>
                    </button>

                    <button
                        class="flex flex-col items-center justify-center p-2 text-red-700 transition-colors duration-200 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-800/30 dark:text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span class="text-xs font-medium">Cancelar</span>
                    </button>
                </div>
            </div>

            <!-- Acciones rápidas -->
            <div class="grid grid-cols-3 gap-2">
                <button
                    class="flex flex-col items-center px-3 py-2 text-xs text-gray-700 transition-colors duration-200 bg-gray-100 rounded-md hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 dark:focus:ring-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mb-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Historial
                </button>
                <button
                    class="flex flex-col items-center px-3 py-2 text-xs text-gray-700 transition-colors duration-200 bg-gray-100 rounded-md hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 dark:focus:ring-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mb-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Imprimir
                </button>
                <button
                    class="flex flex-col items-center px-3 py-2 text-xs text-gray-700 transition-colors duration-200 bg-gray-100 rounded-md hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-400 dark:focus:ring-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mb-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Config
                </button>
            </div>
        </div>
    </div> --}}
