<div>
    <!-- resources/views/livewire/ventas-component.blade.php -->
    @vite('resources/css/app.css')

    <div class="min-h-screen p-4 transition-colors duration-200 ">

        <!-- Cabecera con búsqueda de cliente -->
        <div
            class="mb-6 transition-colors duration-200 bg-white rounded-lg shadow-md dark:bg-gray-800 dark:shadow-gray-700/20">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Gestión de Ventas</h2>
            </div>
            <div class="p-6">
                <!-- Selector de caja según permisos -->
                <div class="pb-6 mb-6 border-b border-gray-200 dark:border-gray-700">
                    @if ($esAdmin || $tieneMultiplesCajas)
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                @if ($esAdmin)
                                    Seleccione una caja para operar (modo administrador)
                                @else
                                    Seleccione una de sus cajas asignadas
                                @endif
                            </label>
                            <select wire:model="cajaId" wire:change="seleccionarCaja($event.target.value)"
                                class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 block w-full p-2.5">
                                @foreach ($cajas as $caja)
                                    <option value="{{ $caja['id'] }}">{{ $caja['nombre'] }} -
                                        {{ $caja['sucursal_nombre'] }} ({{ $caja['empresa_nombre'] }})</option>
                                @endforeach
                            </select>
                            @error('cajaId')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    {{-- <!-- Información de la caja seleccionada -->
                    @if ($cajaInfo)
                        <div
                            class="p-4 border border-blue-200 rounded-lg bg-blue-50 dark:bg-blue-900/20 dark:border-blue-800">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 mr-2 text-blue-500 dark:text-blue-400" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm font-medium text-blue-700 dark:text-blue-300">
                                    Operando en: <strong>{{ $cajaInfo->nombre }}</strong> |
                                    Sucursal: <strong>{{ $sucursalInfo->nombre }}</strong> |
                                    Empresa: <strong>{{ $nombreEmpresa }}</strong>
                                </span>
                            </div>
                        </div>
                    @endif
                </div> --}}

                    <!-- Búsqueda de cliente -->
                    <div class="flex flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4">
                        <div class="flex-grow">
                            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Búsqueda por documento de cliente
                            </label>
                            <div class="relative flex-grow">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <input type="text" wire:model.debounce.500ms="searchTerm"
                                    wire:keydown.enter="buscarPorDNIRUC"
                                    class="block w-full p-3 pl-10 text-sm text-gray-900 transition-colors duration-200 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400"
                                    placeholder="Ingrese N° de documento" {{ empty($cajaId) ? 'disabled' : '' }}>
                            </div>
                        </div>
                        <div class="flex items-end">
                            <button type="button" wire:click="buscarPorDNIRUC"
                                class="w-full px-5 py-3 text-white transition-colors duration-200 bg-indigo-600 rounded-lg md:w-auto dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-800 disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ empty($cajaId) ? 'disabled' : '' }}>
                                Buscar Cliente
                            </button>
                        </div>
                    </div>

                    <!-- Indicador de comandas encontradas -->
                    @if (isset($totalComandasEncontradas) && $totalComandasEncontradas > 0)
                        <div
                            class="p-3 mt-4 border border-blue-200 rounded-lg bg-blue-50 dark:bg-blue-900/30 dark:border-blue-800">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500 dark:text-blue-400" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm font-medium text-blue-700 dark:text-blue-300">
                                    Se encontraron {{ $totalComandasEncontradas }} comanda(s) para este cliente.
                                    @if ($totalComandasEncontradas > 1)
                                        Se muestra la más reciente.
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Detalle venta -->
            <div
                class="transition-colors duration-200 bg-white rounded-lg shadow-md dark:bg-gray-800 dark:shadow-gray-700/20">
                @if (isset($detallesComanda))
                    <div class="px-6 py-4 transition-colors duration-200 bg-gray-800 dark:bg-gray-900">
                        <h2 class="text-lg font-semibold text-white">Detalle de venta</h2>
                    </div>

                    <div class="p-6">
                        <!-- Formato de comprobante -->
                        <div class="p-5 mb-6 border border-gray-300 rounded-lg dark:border-gray-700"
                            id="comprobante-content">
                            <!-- Información de la empresa - Diseño mejorado -->
                            <div class="pb-4 mb-6 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex flex-col md:flex-row md:justify-between md:items-start">

                                    <!-- Información de la empresa - lado izquierdo -->
                                    <div class="flex items-start mb-4 md:mb-0">
                                        <!-- Logo o icono (opcional) -->
                                        <div class="hidden mr-4 md:block">
                                            <div
                                                class="flex items-center justify-center p-3 bg-gray-100 rounded-lg dark:bg-gray-700">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="w-10 h-10 text-indigo-600 dark:text-indigo-400"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Datos de la empresa -->
                                        <div>
                                            <h2 class="text-xl font-bold tracking-tight text-gray-800 dark:text-white">
                                                {{ $nombreEmpresa }}
                                            </h2>
                                            <h3 class="mt-1 text-lg font-medium text-indigo-600 dark:text-indigo-400">
                                                {{ $nombreSurcursal }}
                                            </h3>
                                            <div class="mt-2 space-y-1">
                                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    {{ $direccion }}
                                                </div>
                                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                    </svg>
                                                    <span class="font-medium">RUC:</span> {{ $ruc }}
                                                </div>
                                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $correoEmpresa }}
                                                </div>
                                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    {{ Auth::user()->name }}
                                                </div>
                                                @if ($cajaInfo)
                                                    <div
                                                        class="flex items-center text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2" />
                                                        </svg>
                                                        Caja: {{ $cajaInfo->nombre }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Información del comprobante - lado derecho -->
                                    <div class="text-right">
                                        <div
                                            class="inline-block px-4 py-3 mb-3 font-bold text-center uppercase border-2 border-indigo-600 rounded-lg shadow-sm dark:border-indigo-500">
                                            <p class="mb-1 text-gray-800 dark:text-white">
                                                {{ $tipoComprobanteNombre ?: 'COMPROBANTE' }}
                                            </p>
                                            <p class="text-xl text-indigo-700 dark:text-indigo-400">
                                                N° {{ str_pad($detallesComanda['id'], 6, '0', STR_PAD_LEFT) }}
                                            </p>
                                        </div>

                                        <div class="space-y-1">
                                            <div
                                                class="flex items-center justify-end text-sm text-gray-600 dark:text-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Fecha: {{ now()->timezone('America/Lima')->format('d/m/Y') }}
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>

                            <!-- Información del cliente - Diseño mejorado -->
                            <div
                                class="p-5 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                                <div class="flex items-center mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">DATOS DEL CLIENTE
                                    </h3>
                                </div>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <!-- Columna izquierda -->
                                    <div class="space-y-3">
                                        <!-- Nombre del cliente -->
                                        <div class="flex items-start">
                                            <div class="min-w-8 mt-0.5">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-0.5">
                                                    NOMBRE</p>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white">
                                                    {{ $detallesComanda['cliente']['nombre'] }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Correo electrónico -->
                                        <div class="flex items-start">
                                            <div class="min-w-8 mt-0.5">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-0.5">
                                                    CORREO</p>
                                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $detallesComanda['cliente']['correo'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Columna derecha -->
                                    <div class="space-y-3">
                                        <!-- Número de documento -->
                                        <div class="flex items-start">
                                            <div class="min-w-8 mt-0.5">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-0.5">
                                                    N° DOCUMENTO</p>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white">
                                                    {{ $detallesComanda['cliente']['numero_documento'] }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Mesa -->
                                        <div class="flex items-start">
                                            <div class="min-w-8 mt-0.5">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-0.5">
                                                    MESA - ZONA</p>
                                                <div class="flex items-center">
                                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                                        Mesa {{ $detallesComanda['mesa']['numero'] }}
                                                    </span>
                                                    <span class="mx-2 text-gray-400 dark:text-gray-600">•</span>
                                                    <span
                                                        class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                                        {{ $detallesComanda['mesa']['zona'] }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Detalle de la comanda - Diseño mejorado -->
                            <div class="mb-6">
                                <div class="flex items-center mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">DETALLE DE CONSUMO
                                    </h3>
                                </div>

                                <div
                                    class="overflow-hidden border border-gray-200 rounded-lg shadow-sm dark:border-gray-700">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-indigo-50 dark:bg-indigo-900/30">
                                            <tr>
                                                <th scope="col"
                                                    class="px-4 py-3 text-xs font-medium text-left text-indigo-600 uppercase dark:text-indigo-400">
                                                    Descripción
                                                </th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-xs font-medium text-center text-indigo-600 uppercase dark:text-indigo-400">
                                                    Cant.
                                                </th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-xs font-medium text-right text-indigo-600 uppercase dark:text-indigo-400">
                                                    Precio Unit.
                                                </th>
                                                <th scope="col"
                                                    class="px-4 py-3 text-xs font-medium text-right text-indigo-600 uppercase dark:text-indigo-400">
                                                    Importe
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                            @foreach ($productosComanda as $producto)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                                    <td
                                                        class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $producto['nombre'] }}
                                                    </td>
                                                    <td
                                                        class="px-4 py-3 text-sm text-center text-gray-500 dark:text-gray-400">
                                                        {{ $producto['cantidad'] }}
                                                    </td>
                                                    <td
                                                        class="px-4 py-3 text-sm text-right text-gray-500 dark:text-gray-400">
                                                        S/ {{ number_format($producto['precio'], 2) }}
                                                    </td>
                                                    <td
                                                        class="px-4 py-3 text-sm text-right text-gray-500 dark:text-gray-400">
                                                        S/ {{ number_format($producto['subtotal'], 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Totales - Diseño mejorado -->
                            <div class="flex flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4">
                                <!-- Información de pago -->
                                <div class="md:w-1/2">
                                    <div
                                        class="p-5 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                                        <div class="flex items-center mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2" />
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">
                                                INFORMACIÓN DE PAGO</h3>
                                        </div>

                                        <div class="space-y-3">
                                            <div class="flex items-start">
                                                <div class="min-w-8 mt-0.5">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-0.5">
                                                        MÉTODO DE PAGO</p>
                                                    <p class="text-sm font-medium text-gray-800 dark:text-white">
                                                        {{ $metodoPago ?: 'Pendiente de selección' }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="flex items-start">
                                                <div class="min-w-8 mt-0.5">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-0.5">
                                                        TIPO DE COMPROBANTE</p>
                                                    <p class="text-sm font-medium text-gray-800 dark:text-white">
                                                        {{ $tipoComprobanteNombre ?: 'Pendiente de selección' }}
                                                    </p>
                                                </div>
                                            </div>

                                            @if (!empty($observaciones))
                                                <div
                                                    class="flex items-start pt-2 mt-2 border-t border-gray-100 dark:border-gray-700">
                                                    <div class="min-w-8 mt-0.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p
                                                            class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-0.5">
                                                            OBSERVACIONES</p>
                                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                                            {{ $observaciones }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Resumen de totales -->
                                <div class="md:w-1/2">
                                    <div
                                        class="p-5 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                                        <div class="flex items-center mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">RESUMEN DE
                                                TOTALES</h3>
                                        </div>

                                        <div class="mt-4 space-y-3">
                                            <div
                                                class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                                <span class="text-sm text-gray-600 dark:text-gray-300">Subtotal:</span>
                                                <span class="text-sm font-medium text-gray-800 dark:text-white">S/
                                                    {{ number_format($subtotal, 2) }}</span>
                                            </div>
                                            <div
                                                class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                                <span class="text-sm text-gray-600 dark:text-gray-300">IGV
                                                    (18%):</span>
                                                <span class="text-sm font-medium text-gray-800 dark:text-white">S/
                                                    {{ number_format($igv, 2) }}</span>
                                            </div>
                                            <div class="flex justify-between pt-3 pb-1">
                                                <span
                                                    class="text-base font-semibold text-gray-900 dark:text-white">TOTAL:</span>
                                                <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">S/
                                                    {{ number_format($total, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Formulario de venta - Diseño mejorado -->
                        <form wire:submit.prevent="generarVenta"
                            class="p-5 mt-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                            <!-- Título del formulario -->
                            <div class="flex items-center pb-4 mb-5 border-b border-gray-100 dark:border-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">DATOS DE VENTA</h3>
                            </div>

                            <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                                <!-- Tipo de comprobante -->
                                <div>
                                    <label
                                        class="flex items-center mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1.5 text-gray-600 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Tipo de comprobante <span class="ml-1 text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select wire:model="tipoComprobanteId" wire:change="$refresh"
                                            class="appearance-none bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 block w-full p-2.5 pr-8">
                                            <option value="">Seleccione un tipo de comprobante</option>
                                            @foreach ($tiposComprobante as $tipo)
                                                <option value="{{ $tipo->id }}">{{ $tipo->descripcion }}</option>
                                            @endforeach
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 pointer-events-none dark:text-gray-300">

                                        </div>
                                    </div>
                                    @error('tipoComprobanteId')
                                        <p class="flex items-center mt-1 text-sm text-red-500 dark:text-red-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Método de pago -->
                                <div>
                                    <label
                                        class="flex items-center mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1.5 text-gray-600 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        Método de pago <span class="ml-1 text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select wire:model="metodoPago" wire:change="$refresh"
                                            class="appearance-none bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 block w-full p-2.5 pr-8">
                                            <option value="">Seleccionar...</option>
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Tarjeta de Débito">Tarjeta de Débito</option>
                                            <option value="Tarjeta de Crédito">Tarjeta de Crédito</option>
                                            <option value="Transferencia">Transferencia</option>
                                            <option value="Yape/Plin">Yape/Plin</option>
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 pointer-events-none dark:text-gray-300">

                                        </div>
                                    </div>
                                    @error('metodoPago')
                                        <p class="flex items-center mt-1 text-sm text-red-500 dark:text-red-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Observaciones -->
                                <div class="md:col-span-2">
                                    <label
                                        class="flex items-center mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1.5 text-gray-600 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Observaciones
                                    </label>
                                    <textarea wire:model="observaciones" wire:change="$refresh"
                                        class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 block w-full p-2.5"
                                        rows="3" placeholder="Ingrese aquí cualquier observación adicional sobre la venta..."></textarea>
                                </div>
                            </div>

                            <!-- Botón de acción -->
                            <div class="flex justify-end mt-2">
                                <button type="submit"
                                    class="flex items-center justify-center w-full px-6 py-3 font-medium text-white transition-colors bg-indigo-600 rounded-lg shadow-sm md:w-auto hover:bg-indigo-700 dark:bg-indigo-600 dark:hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-800">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Guardar venta
                                </button>
                            </div>
                        </form>

                    </div>
                @else
                    <div class="flex flex-col items-center justify-center p-8 text-center">
                        <svg class="w-16 h-16 mb-4 text-gray-400 dark:text-gray-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>

                        @if (empty($cajaId))
                            <h3 class="mb-2 text-xl font-medium text-gray-900 dark:text-white">Seleccione una caja para
                                comenzar</h3>
                            <p class="mb-6 text-base text-gray-500 dark:text-gray-400">Por favor, seleccione una caja
                                para
                                poder buscar comandas y registrar ventas</p>
                        @else
                            <h3 class="mb-2 text-xl font-medium text-gray-900 dark:text-white">No hay pedido buscados
                            </h3>
                            <p class="mb-6 text-base text-gray-500 dark:text-gray-400">Busque a un cliente por su
                                número de
                                documento para cargar los detalles de su comanda</p>
                            <div
                                class="inline-flex items-center text-sm text-indigo-600 hover:underline dark:text-indigo-400">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                El primer pedido del cliente se cargará automáticamente
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

    </div>
