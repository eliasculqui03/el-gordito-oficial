<div>
    @vite('resources/css/app.css')

    <div class="min-h-screen p-4 sm:p-6 " wire:poll.{{ $refreshInterval }}ms>
        <div class="mx-auto max-w-7xl">
            <!-- Encabezado Principal -->
            <div class="mb-6 text-center sm:mb-8">
                <h1 class="text-2xl font-bold text-gray-800 sm:text-3xl dark:text-white">Gestión de Platos</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300">
                    Bienvenido, <span
                        class="font-medium text-indigo-600 dark:text-indigo-400">{{ Auth::user()->name }}</span>
                </p>

                <div class="mt-3 space-y-1">
                    <!-- Áreas asignadas -->
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        @php
                            $areasIds = $this->getAreasAsignadasIds();
                            $areas = empty($areasIds)
                                ? []
                                : DB::table('areas')->whereIn('id', $areasIds)->pluck('nombre')->toArray();
                        @endphp

                        <span class="font-medium">Áreas:</span>
                        @if (count($areas) > 0)
                            <span class="inline-flex flex-wrap items-center gap-1 mt-1">
                                @foreach ($areas as $area)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                        {{ $area }}
                                    </span>
                                @endforeach
                            </span>
                        @else
                            <span class="text-xs italic dark:text-gray-400">Acceso a todas las áreas</span>
                        @endif
                    </p>

                    <!-- Zonas asignadas -->
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        @php
                            $zonasIds = $this->getZonasAsignadasIds();
                            $zonas = empty($zonasIds)
                                ? []
                                : DB::table('zonas')->whereIn('id', $zonasIds)->pluck('nombre')->toArray();
                        @endphp

                        <span class="font-medium">Zonas:</span>
                        @if (count($zonas) > 0)
                            <span class="inline-flex flex-wrap items-center gap-1 mt-1">
                                @foreach ($zonas as $zona)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-800 dark:text-amber-100">
                                        {{ $zona }}
                                    </span>
                                @endforeach
                            </span>
                        @else
                            <span class="text-xs italic dark:text-gray-400">Acceso a todas las zonas</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 lg:gap-8">

                <!-- Sección: Platos a Entregar -->
                <div>
                    <div class="overflow-hidden bg-white shadow-md dark:bg-gray-800 rounded-xl">
                        <!-- Encabezado de sección -->
                        <div class="px-4 py-4 sm:px-6 bg-gradient-to-r from-amber-500 to-orange-600">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-bold text-white sm:text-xl">Platos a entregar</h2>
                                <span
                                    class="px-3 py-1 text-xs font-medium text-white bg-white rounded-full sm:text-sm bg-opacity-20">
                                    {{ count($asignaciones) }} asignados
                                </span>
                            </div>
                        </div>

                        <!-- Contenido: Grid de tarjetas -->
                        <div class="p-4 sm:p-6">
                            @if (count($asignaciones) > 0)
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    @foreach ($asignaciones as $asignacion)
                                        <div
                                            class="overflow-hidden transition-shadow duration-300 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 hover:shadow-md">
                                            <!-- Header de la tarjeta con #comanda -->
                                            <div
                                                class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-medium text-gray-700 dark:text-gray-300">
                                                        #{{ str_pad($asignacion->comandaPlato->comanda_id, 4, '0', STR_PAD_LEFT) }}
                                                    </span>

                                                    @if ($asignacion->comandaPlato->llevar)
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full bg-blue-200 text-indigo-800 dark:bg-blue-900/40 dark:text-blue-300">

                                                            Para llevar
                                                        </span>
                                                    @endif
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-800 dark:text-amber-100">
                                                        Entregando
                                                    </span>

                                                </div>
                                            </div>

                                            <!-- Contenido de la tarjeta -->
                                            <div class="p-4 space-y-3">
                                                <!-- Información del plato -->
                                                <div class="flex items-center justify-between">
                                                    <h3
                                                        class="pr-2 text-base font-semibold text-gray-900 break-words sm:text-lg dark:text-white sm:truncate">
                                                        {{ $asignacion->comandaPlato->plato->nombre }}</h3>
                                                    <div
                                                        class="flex-shrink-0 flex items-center justify-center bg-amber-100 text-amber-800 dark:bg-amber-800 dark:text-amber-100 text-sm font-medium px-2.5 py-0.5 rounded-full">
                                                        x{{ $asignacion->comandaPlato->cantidad }}
                                                    </div>
                                                </div>

                                                <!-- Información del cliente -->
                                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400 dark:text-gray-500 mr-1.5"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span
                                                        class="truncate">{{ $asignacion->comandaPlato->comanda->cliente->nombre }}</span>
                                                </div>

                                                <!-- Ubicación -->
                                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400 dark:text-gray-500 mr-1.5"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span
                                                        class="truncate">{{ $asignacion->comandaPlato->comanda->zona->nombre }}
                                                        - Mesa
                                                        {{ $asignacion->comandaPlato->comanda->mesa->numero }}</span>
                                                </div>
                                            </div>

                                            <!-- Footer con botones -->
                                            <div
                                                class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                                <div class="grid grid-cols-2 gap-2">
                                                    <button wire:click="marcarEntregado({{ $asignacion->id }})"
                                                        class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800">
                                                        <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Entregado
                                                    </button>
                                                    <button wire:click="cancelarAsignacion({{ $asignacion->id }})"
                                                        class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800">
                                                        <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Cancelar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="py-10 text-center sm:py-12">
                                    <svg class="w-10 h-10 mx-auto text-gray-400 sm:w-12 sm:h-12 dark:text-gray-600"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <h3 class="mt-2 text-base font-medium text-gray-900 sm:text-lg dark:text-white">No
                                        tienes platos asignados</h3>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sección: Platos Listos -->
                <div>
                    <div class="overflow-hidden bg-white shadow-md dark:bg-gray-800 rounded-xl">
                        <!-- Encabezado de sección -->
                        <div class="px-4 py-4 sm:px-6 bg-gradient-to-r from-blue-500 to-indigo-600">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-bold text-white sm:text-xl">Platos listos</h2>
                                <span
                                    class="px-3 py-1 text-xs font-medium text-white bg-white rounded-full sm:text-sm bg-opacity-20">
                                    {{ count($platosListos) }} platos
                                </span>
                            </div>
                        </div>

                        <!-- Contenido: Grid de tarjetas -->
                        <div class="p-4 sm:p-6">
                            @if (count($platosListos) > 0)
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    @foreach ($platosListos as $platoListo)
                                        <div
                                            class="overflow-hidden transition-shadow duration-300 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 hover:shadow-md">
                                            <!-- Header de la tarjeta con #comanda -->
                                            <div
                                                class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-medium text-gray-700 dark:text-gray-300">
                                                        #{{ str_pad($platoListo->comanda_id, 4, '0', STR_PAD_LEFT) }}
                                                    </span>
                                                    @if ($platoListo->llevar)
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full bg-blue-200 text-indigo-800 dark:bg-blue-900/40 dark:text-blue-300">

                                                            Para llevar
                                                        </span>
                                                    @endif
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                        Listo
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Contenido de la tarjeta -->
                                            <div class="p-4 space-y-3">
                                                <!-- Información del plato -->
                                                <div class="flex items-center justify-between">
                                                    <h3
                                                        class="pr-2 text-base font-semibold text-gray-900 break-words sm:text-lg dark:text-white sm:truncate">
                                                        {{ $platoListo->plato->nombre }}</h3>
                                                    <div
                                                        class="flex-shrink-0 flex items-center justify-center bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 text-sm font-medium px-2.5 py-0.5 rounded-full">
                                                        x{{ $platoListo->cantidad }}
                                                    </div>
                                                </div>

                                                <!-- Información del cliente -->
                                                <div
                                                    class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400 dark:text-gray-500 mr-1.5"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span
                                                        class="truncate">{{ $platoListo->comanda->cliente->nombre }}</span>
                                                </div>

                                                <!-- Ubicación -->
                                                <div
                                                    class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400 dark:text-gray-500 mr-1.5"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="truncate">{{ $platoListo->comanda->zona->nombre }} -
                                                        Mesa
                                                        {{ $platoListo->comanda->mesa->numero }}</span>
                                                </div>
                                            </div>

                                            <!-- Footer con botones -->
                                            <div
                                                class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                                <div class="grid grid-cols-2 gap-2">
                                                    <button wire:click="asignarPlato({{ $platoListo->id }})"
                                                        class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white transition-colors duration-150 bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                        </svg>
                                                        Asignar
                                                    </button>
                                                    <button wire:click="confirmarCancelacion({{ $platoListo->id }})"
                                                        class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800">
                                                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Cancelar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="py-10 text-center sm:py-12">
                                    <svg class="w-10 h-10 mx-auto text-gray-400 sm:w-12 sm:h-12 dark:text-gray-600"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                        </path>
                                    </svg>
                                    <h3 class="mt-2 text-base font-medium text-gray-900 sm:text-lg dark:text-white">No
                                        hay platos listos</h3>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal de confirmación -->
        @if ($mostrarConfirmacion)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                aria-modal="true">
                <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Fondo oscuro -->
                    <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-800 dark:bg-opacity-75"
                        aria-hidden="true"></div>

                    <!-- Centrado del modal -->
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <!-- Contenido del modal -->
                    <div
                        class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl dark:bg-gray-800 sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="px-4 pt-5 pb-4 bg-white dark:bg-gray-800 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                    <!-- Ícono de alerta -->
                                    <svg class="w-6 h-6 text-red-600 dark:text-red-400"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white"
                                        id="modal-title">
                                        Cancelar plato
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            ¿Estás seguro de que deseas cancelar
                                            este plato? Esta acción no se puede
                                            revertir.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" wire:click="procederCancelacion"
                                class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 sm:ml-3 sm:w-auto sm:text-sm">
                                Sí, cancelar plato
                            </button>
                            <button type="button" wire:click="cerrarConfirmacion"
                                class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:text-gray-300 dark:bg-gray-600 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
