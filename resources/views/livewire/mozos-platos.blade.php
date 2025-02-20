<div>
    @vite('resources/css/app.css')

    <div class="min-h-screen p-6 bg-gray-50" wire:poll.{{ $refreshInterval }}ms>
        <div class="mx-auto max-w-7xl">
            <!-- Encabezado Principal -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-800">Gestión de Platos</h1>
                <p class="mt-2 text-gray-600">Administra los platos listos y asignados para entregar</p>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <!-- Sección: Platos Listos -->
                <div>
                    <div class="overflow-hidden bg-white shadow-md rounded-xl">
                        <!-- Encabezado de sección -->
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600">
                            <div class="flex items-center justify-between">
                                <h2 class="text-xl font-bold text-white">Platos listos</h2>
                                <span
                                    class="px-3 py-1 text-sm font-medium text-white bg-white rounded-full bg-opacity-20">
                                    {{ count($platosListos) }} platos
                                </span>
                            </div>
                        </div>

                        <!-- Contenido: Grid de tarjetas -->
                        <div class="p-6">
                            @if (count($platosListos) > 0)
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    @foreach ($platosListos as $platoListo)
                                        <div
                                            class="overflow-hidden transition-shadow duration-300 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md">
                                            <!-- Header de la tarjeta con #comanda -->
                                            <div class="px-4 py-2 border-b border-gray-200 bg-gray-50">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-medium text-gray-700">
                                                        Comanda
                                                        #{{ str_pad($platoListo->comanda_id, 4, '0', STR_PAD_LEFT) }}
                                                    </span>
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Listo
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Contenido de la tarjeta -->
                                            <div class="p-4 space-y-3">
                                                <!-- Información del plato -->
                                                <div class="flex items-center justify-between">
                                                    <h3 class="text-lg font-semibold text-gray-900">
                                                        {{ $platoListo->plato->nombre }}</h3>
                                                    <div
                                                        class="flex items-center justify-center bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded-full">
                                                        x{{ $platoListo->cantidad }}
                                                    </div>
                                                </div>

                                                <!-- Información del cliente -->
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400 mr-1.5"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span>{{ $platoListo->comanda->cliente->nombre }}</span>
                                                </div>

                                                <!-- Ubicación -->
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400 mr-1.5"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span>{{ $platoListo->comanda->zona->nombre }} - Mesa
                                                        {{ $platoListo->comanda->mesa->numero }}</span>
                                                </div>
                                            </div>

                                            <!-- Footer con botón -->
                                            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                                                <button wire:click="asignarPlato({{ $platoListo->id }})"
                                                    class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white transition-colors duration-150 bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                    </svg>
                                                    Asignar para entregar
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="py-12 text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                        </path>
                                    </svg>
                                    <h3 class="mt-2 text-lg font-medium text-gray-900">No hay platos listos</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Cuando la cocina marque platos como listos, aparecerán aquí.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sección: Platos a Entregar -->
                <div>
                    <div class="overflow-hidden bg-white shadow-md rounded-xl">
                        <!-- Encabezado de sección -->
                        <div class="px-6 py-4 bg-gradient-to-r from-amber-500 to-orange-600">
                            <div class="flex items-center justify-between">
                                <h2 class="text-xl font-bold text-white">Platos a entregar</h2>
                                <span
                                    class="px-3 py-1 text-sm font-medium text-white bg-white rounded-full bg-opacity-20">
                                    {{ count($asignaciones) }} asignados
                                </span>
                            </div>
                        </div>

                        <!-- Contenido: Grid de tarjetas -->
                        <div class="p-6">
                            @if (count($asignaciones) > 0)
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    @foreach ($asignaciones as $asignacion)
                                        <div
                                            class="overflow-hidden transition-shadow duration-300 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md">
                                            <!-- Header de la tarjeta con #comanda -->
                                            <div class="px-4 py-2 border-b border-gray-200 bg-gray-50">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-medium text-gray-700">
                                                        Comanda
                                                        #{{ str_pad($asignacion->comandaPlato->comanda_id, 4, '0', STR_PAD_LEFT) }}
                                                    </span>
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                        Entregando
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Contenido de la tarjeta -->
                                            <div class="p-4 space-y-3">
                                                <!-- Información del plato -->
                                                <div class="flex items-center justify-between">
                                                    <h3 class="text-lg font-semibold text-gray-900">
                                                        {{ $asignacion->comandaPlato->plato->nombre }}</h3>
                                                    <div
                                                        class="flex items-center justify-center bg-amber-100 text-amber-800 text-sm font-medium px-2.5 py-0.5 rounded-full">
                                                        x{{ $asignacion->comandaPlato->cantidad }}
                                                    </div>
                                                </div>

                                                <!-- Información del cliente -->
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400 mr-1.5"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span>{{ $asignacion->comandaPlato->comanda->cliente->nombre }}</span>
                                                </div>

                                                <!-- Ubicación -->
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400 mr-1.5"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span>{{ $asignacion->comandaPlato->comanda->zona->nombre }} - Mesa
                                                        {{ $asignacion->comandaPlato->comanda->mesa->numero }}</span>
                                                </div>
                                            </div>

                                            <!-- Footer con botones -->
                                            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                                                <div class="grid grid-cols-2 gap-2">
                                                    <button wire:click="marcarEntregado({{ $asignacion->id }})"
                                                        class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                        <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Entregado
                                                    </button>
                                                    <button wire:click="cancelarAsignacion({{ $asignacion->id }})"
                                                        class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
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
                                <div class="py-12 text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <h3 class="mt-2 text-lg font-medium text-gray-900">No tienes platos asignados</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Cuando asignes platos para entregar, aparecerán aquí.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
