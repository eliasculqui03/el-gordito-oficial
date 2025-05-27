<div>
    @vite('resources/css/app.css')
    <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
        <!-- Panel izquierdo (Comandas) - Adaptativo para móviles -->
        <div class="order-2 col-span-1 md:col-span-12 lg:col-span-9 lg:order-1">
            <div class="min-h-screen pt-2" wire:poll.{{ $refreshInterval }}ms>
                <!-- Área Tabs - Con botones visibles en todas las pantallas -->
                <div class="sticky top-0 z-10 p-2 mb-4 rounded-lg bg-gray-50/80 dark:bg-gray-900/80 backdrop-blur-sm">
                    @if (count($areas) > 1)
                        <nav class="flex flex-wrap justify-center gap-2 py-2" aria-label="Áreas">
                            @foreach ($areas as $area)
                                <button wire:click="selectArea({{ $area->id }})"
                                    class="{{ $selectedArea == $area->id
                                        ? 'bg-white shadow text-primary-600 dark:bg-gray-800 dark:text-primary-400'
                                        : 'text-gray-500 hover:text-gray-700 hover:bg-white/50 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800/50' }}
                            px-3 py-2 sm:px-4 text-xs sm:text-sm font-medium rounded-lg transition-all duration-150 flex-grow sm:flex-grow-0 mb-1">
                                    {{ $area->nombre }}
                                </button>
                            @endforeach
                        </nav>
                    @endif
                </div>

                <!-- Grid de Comandas - Adaptativo para diferentes tamaños -->
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3">
                    @forelse($comandas as $comanda)
                        @php
                            $tiempoTranscurrido = now()->diffInMinutes($comanda->created_at);
                            $colorClase = match (true) {
                                $tiempoTranscurrido >= 6 => 'border-l-4 border-l-rose-500 dark:border-l-rose-400',
                                $tiempoTranscurrido >= 4 => 'border-l-4 border-l-amber-500 dark:border-l-amber-400',
                                $tiempoTranscurrido >= 2 => 'border-l-4 border-l-emerald-500 dark:border-l-emerald-400',
                                default => 'border-l-4 border-l-gray-300 dark:border-l-gray-600',
                            };
                        @endphp


                        <div
                            class="overflow-hidden transition-all duration-150 bg-white border shadow-sm hover:shadow dark:bg-gray-800 dark:border-gray-700 rounded-xl {{ $colorClase }}">
                            <!-- Header -->
                            <div class="flex items-center justify-between p-3 border-b dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    <span class="text-base font-bold text-gray-900 dark:text-white">
                                        N° {{ str_pad($comanda->id, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <span
                                        class="px-2 py-0.5 text-xs font-medium rounded-full
                                {{ $tiempoTranscurrido >= 6
                                    ? 'bg-rose-100 text-rose-700 dark:bg-rose-900 dark:text-rose-300'
                                    : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $tiempoTranscurrido }} min
                                    </span>
                                </div>
                                <div
                                    class="px-2 py-1 text-xs font-medium rounded-lg {{ $comanda->estado === 'Abierta'
                                        ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/50 dark:text-primary-400'
                                        : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400' }}">
                                    {{ $comanda->estado }}
                                </div>
                            </div>

                            <!-- Info Cliente y Ubicación -->
                            <div class="p-3 bg-gray-50/50 dark:bg-gray-800/50">
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-2 text-sm">
                                        <span class="p-1 rounded-md bg-gray-100/80 dark:bg-gray-700/80">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </span>
                                        <span class="font-medium text-gray-700 truncate dark:text-gray-300">
                                            {{ $comanda->cliente->nombre }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1 text-sm">
                                        <span class="p-1 rounded-md bg-gray-100/80 dark:bg-gray-700/80">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                        </span>
                                        <span class="text-gray-600 truncate dark:text-gray-400">
                                            {{ $comanda->zona->nombre }} • Mesa {{ $comanda->mesa->numero }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Lista de Platos - Diseño mejorado con mejor experiencia móvil -->
                            <div class="p-3 space-y-2">
                                @foreach ($comanda->comandaPlatos->where('plato.area_id', $selectedArea) as $comandaPlato)
                                    <div
                                        class="flex flex-col justify-between p-3 transition-all rounded-lg bg-gray-50 hover:bg-gray-100 sm:flex-row sm:items-center hover:shadow-md dark:bg-gray-700/50 dark:hover:bg-gray-700">

                                        <div class="flex items-start justify-between w-full sm:w-auto">
                                            <div>
                                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $comandaPlato->plato->nombre }}
                                                </h3>

                                                @if ($comandaPlato->llevar)
                                                    <span
                                                        class="inline-flex items-center mt-1 px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Para llevar
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="flex items-center gap-2 sm:hidden">
                                                <span
                                                    class="flex items-center justify-center w-8 h-8 text-sm font-bold rounded-full
                    {{ $comandaPlato->estado === 'Listo'
                        ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300'
                        : ($comandaPlato->estado === 'Procesando'
                            ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300'
                            : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                                    {{ $comandaPlato->cantidad }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between gap-2 mt-2 sm:mt-0">
                                            <div class="sm:hidden">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-md
                    {{ $comandaPlato->estado === 'Listo'
                        ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300'
                        : ($comandaPlato->estado === 'Procesando'
                            ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300'
                            : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                                    {{ $comandaPlato->estado }}
                                                </span>
                                            </div>

                                            <div class="hidden gap-3 sm:flex sm:items-center">
                                                <span
                                                    class="flex items-center justify-center w-8 h-8 text-sm font-bold rounded-full
                    {{ $comandaPlato->estado === 'Listo'
                        ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300'
                        : ($comandaPlato->estado === 'Procesando'
                            ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300'
                            : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                                    {{ $comandaPlato->cantidad }}
                                                </span>

                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-md
                    {{ $comandaPlato->estado === 'Listo'
                        ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300'
                        : ($comandaPlato->estado === 'Procesando'
                            ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300'
                            : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                                    {{ $comandaPlato->estado }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Footer -->
                            @if ($comanda->comandaPlatos->where('plato.area_id', $selectedArea)->some(fn($comandaPlato) => $comandaPlato->estado === 'Pendiente'))
                                <div class="flex justify-end p-3 border-t dark:border-gray-700">
                                    <button wire:click="procesarComanda({{ $comanda->id }})"
                                        class="px-4 py-2 text-sm font-medium text-white transition-colors rounded-lg bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-primary-500 dark:hover:bg-primary-600">
                                        Procesar
                                    </button>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full">
                            <div
                                class="flex flex-col items-center gap-2 p-8 text-center bg-white rounded-xl dark:bg-gray-800">
                                <div class="p-3 bg-gray-100 rounded-full dark:bg-gray-700">
                                    <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <h3 class="text-base font-medium text-gray-900 dark:text-white">No hay comandas
                                    pendientes</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">No se encontraron comandas
                                    pendientes para esta área.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

        <!-- Panel derecho - Adaptado para móviles y desktop -->
        <div class="order-1 col-span-1 md:col-span-12 lg:col-span-3 lg:order-2">
            <div class="sticky p-4 mb-4 overflow-y-auto bg-white border shadow-sm top-2 dark:bg-gray-800 dark:border-gray-700 rounded-xl lg:mb-0"
                style="max-height: calc(100vh - 2rem)">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Lista de Preparación
                    </h2>
                    <span
                        class="px-2.5 py-1 text-xs font-medium bg-primary-100 text-primary-700 rounded-lg dark:bg-primary-900/50 dark:text-primary-400">
                        {{ count($platosACocinar) }} platos
                    </span>
                </div>
                <div class="space-y-2">
                    @forelse ($platosACocinar as $plato)
                        <div
                            class="flex flex-col justify-between p-3 transition-colors rounded-lg sm:flex-row sm:items-center bg-gray-50 hover:bg-gray-100 dark:bg-gray-700/50 dark:hover:bg-gray-700 {{ $plato['paraLlevar'] ? 'border-l-4 border-blue-500 dark:border-blue-400' : '' }}">
                            <div class="flex-1 min-w-0 mb-2 sm:mb-0">
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $plato['nombre'] }}
                                        </p>
                                        @if ($plato['paraLlevar'])
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Llevar
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            En preparación
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-between w-full gap-2 sm:justify-end sm:w-auto sm:ml-4">
                                <span
                                    class="px-2.5 py-1 text-sm font-medium bg-primary-100 text-primary-700 rounded-lg dark:bg-primary-900/50 dark:text-primary-400">
                                    x{{ $plato['total'] }}
                                </span>
                                <div class="flex space-x-2">
                                    {{-- <button wire:click="cancelarProcesamiento('{{ $plato['grupoKey'] }}')"
                                        class="p-2 text-sm font-medium text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:bg-red-500 dark:hover:bg-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button> --}}
                                    <button wire:click="marcarPlatoListo('{{ $plato['grupoKey'] }}')"
                                        class="px-3 py-1 text-sm font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:bg-green-500 dark:hover:bg-green-600">
                                        Listo
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                No hay platos en preparación
                            </span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
