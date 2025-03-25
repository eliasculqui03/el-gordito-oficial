<div>

    <!-- Botón Asignar Mesa (nuevo icono de mesa) -->
    <button wire:click="openModalMesa"
        class="flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path
                d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z" />
        </svg>
        <span>Asignar Mesa</span>
    </button>


    <!-- Modal de Selección de Mesa -->
    @if ($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto bg-gray-950/50 dark:bg-gray-950/75"
            x-data x-init="$el.addEventListener('click', event => { if (event.target === $el) $wire.closeModalMesa() })">
            <div class="relative w-full max-w-4xl mx-auto bg-white shadow-lg rounded-xl dark:bg-gray-800 dark:ring-1 dark:ring-white/10
                transition-all duration-300 flex flex-col max-h-[90vh]"
                @click.outside="$wire.closeModalMesa()" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95">
                <!-- Cabecera del Modal -->
                <div
                    class="sticky top-0 z-10 flex items-center justify-between p-4 bg-white border-b sm:p-5 dark:border-gray-700 dark:bg-gray-800 rounded-t-xl">
                    <h2 class="text-lg font-bold text-gray-900 sm:text-xl dark:text-white">
                        Seleccionar Mesa</h2>
                    <button wire:click="closeModalMesa"
                        class="p-1 text-gray-500 transition-colors rounded-full hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                        aria-label="Cerrar">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </button>
                </div>

                <!-- Contenido con scroll -->
                <div class="flex-1 p-4 overflow-y-auto sm:p-5">
                    <!-- Zonas -->
                    <div class="mb-6" wire:poll.2s>
                        <h3 class="mb-4 text-sm font-semibold text-gray-600 dark:text-gray-400">
                            Zonas</h3>
                        <div class="flex flex-wrap gap-3">
                            @foreach ($zonas as $zona)
                                <button wire:click="seleccionarZona({{ $zona->id }})"
                                    class="px-4 py-2 text-sm font-medium transition-all duration-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
                {{ $zonaSeleccionada == $zona->id
                    ? 'bg-primary-600 text-white shadow-md hover:bg-primary-700 dark:bg-primary-700 dark:hover:bg-primary-600'
                    : 'text-gray-700 bg-gray-100 hover:bg-gray-200 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600' }}">
                                    {{ $zona->nombre }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Mesas -->
                    <div wire:poll.2s>
                        @if ($zonaSeleccionada)
                            <div
                                class="grid grid-cols-1 gap-3 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                                @foreach ($this->mesasZona as $mesa)
                                    <div wire:key="mesa-{{ $mesa->id }}"
                                        @if ($mesa->estado === 'Libre') wire:click="seleccionarMesa({{ $mesa }})"
                                        class="overflow-hidden transition-all duration-200 bg-white border border-gray-200 shadow-sm cursor-pointer dark:bg-gray-800 dark:border-gray-700 rounded-xl hover:shadow-md group {{ $mesaSeleccionadaId == $mesa->id ? 'ring-2 ring-primary-500 dark:ring-primary-400' : '' }}"
                                    @else
                                        class="overflow-hidden transition-all duration-200 bg-white border border-gray-200 shadow-sm opacity-75 cursor-not-allowed dark:bg-gray-800 dark:border-gray-700 rounded-xl group" @endif>
                                        <div class="p-3">
                                            <div class="flex flex-col items-center">
                                                <span class="mb-2 text-lg font-bold text-gray-800 dark:text-white">
                                                    Mesa {{ $mesa->numero }}
                                                </span>
                                                <div
                                                    class="flex items-center gap-2 px-3 py-1.5 rounded-lg w-full justify-center
        {{ $mesa->estado === 'Libre'
            ? 'bg-green-50 dark:bg-green-900/30'
            : ($mesa->estado === 'Ocupada'
                ? 'bg-red-50 dark:bg-red-900/30'
                : 'bg-gray-50 dark:bg-gray-700') }}">
                                                    <span>
                                                        @if ($mesa->estado === 'Libre')
                                                            <x-heroicon-o-check-circle
                                                                class="w-4 h-4 text-green-500 dark:text-green-400" />
                                                        @elseif($mesa->estado === 'Ocupada')
                                                            <x-heroicon-o-user
                                                                class="w-4 h-4 text-red-500 dark:text-red-400" />
                                                        @else
                                                            <x-heroicon-o-x-circle
                                                                class="w-4 h-4 text-gray-500 dark:text-gray-400" />
                                                        @endif
                                                    </span>
                                                    <span
                                                        class="text-xs font-medium
            {{ $mesa->estado === 'Libre'
                ? 'text-green-700 dark:text-green-400'
                : ($mesa->estado === 'Ocupada'
                    ? 'text-red-700 dark:text-red-400'
                    : 'text-gray-700 dark:text-gray-400') }}">
                                                        {{ ucfirst($mesa->estado) }}
                                                    </span>
                                                </div>
                                                <div
                                                    class="flex items-center mt-2 text-xs text-gray-600 dark:text-gray-400">

                                                    <span>{{ $mesa->capacidad }} personas</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="px-3 py-1.5 text-xs text-center text-gray-500 bg-gray-50 dark:bg-gray-700/50 dark:text-gray-400 group-hover:bg-gray-100 dark:group-hover:bg-gray-700">
                                            @if ($mesa->estado === 'Libre')
                                                Click para seleccionar mesa
                                            @else
                                                Mesa no disponible
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div
                                class="flex items-center justify-center h-32 text-gray-500 rounded-lg dark:text-gray-400 bg-gray-50 dark:bg-gray-700/30">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mb-2 opacity-60"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2m-6 0h6m-6 0v10m0 0H5a2 2 0 01-2-2V7m14 10a2 2 0 002-2V7" />
                                    </svg>
                                    <span>Selecciona una zona</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer fijo -->
                <div
                    class="sticky bottom-0 z-10 flex justify-end p-4 bg-white border-t sm:p-5 dark:border-gray-700 dark:bg-gray-800 rounded-b-xl">
                    <button wire:click="closeModalMesa"
                        class="px-4 py-2 text-sm font-medium text-white transition-colors bg-indigo-600 rounded-md hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>






<!-- Platos y Bebidas -->
{{-- <div
                        class="overflow-hidden transition-colors duration-200 bg-white rounded-lg shadow-md dark:bg-gray-800 dark:shadow-gray-700/30">
                        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-gray-700">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 mr-2 text-indigo-500 dark:text-indigo-400" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M3 2h18v10H3zM12 12v10M4 22h16"></path>
                                    <path d="M3 7h18"></path>
                                </svg>
                                <h2 class="text-base font-semibold text-gray-700 dark:text-gray-200">PLATOS Y BEBIDAS
                                </h2>
                            </div>
                            <button
                                class="flex items-center justify-center px-3 py-2 text-sm text-white bg-indigo-600 rounded-md hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="hidden sm:inline">Nuevo Plato</span>
                                <span class="sm:hidden">Nuevo</span>
                            </button>
                        </div>

                        <!-- Filtros por categoría con scroll horizontal en móviles -->
                        <div class="p-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex gap-2 pb-1 overflow-x-auto flex-nowrap scrollbar-none">
                                <button
                                    class="flex-none px-3 py-1.5 text-xs font-medium text-white transition-colors bg-indigo-600 rounded-full hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:outline-none">Todos</button>
                                <button
                                    class="flex-none px-3 py-1.5 text-xs font-medium text-gray-700 transition-colors bg-gray-200 rounded-full hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 focus:ring-2 focus:ring-gray-400 focus:outline-none">Entradas</button>
                                <button
                                    class="flex-none px-3 py-1.5 text-xs font-medium text-gray-700 transition-colors bg-gray-200 rounded-full hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 focus:ring-2 focus:ring-gray-400 focus:outline-none">Platos
                                    Principales</button>
                                <button
                                    class="flex-none px-3 py-1.5 text-xs font-medium text-gray-700 transition-colors bg-gray-200 rounded-full hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 focus:ring-2 focus:ring-gray-400 focus:outline-none">Bebidas</button>
                            </div>
                        </div>

                        <!-- Lista de platos con grid responsive y más espacio -->
                        <div class="p-3 overflow-y-auto max-h-[calc(100vh-220px)]">
                            <div class="grid grid-cols-1 gap-4 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
                                wire:loading.class="opacity-50">
                                <!-- Plato (disponible) -->
                                @foreach ($platos as $plato)
                                    @if ($plato->disponibilidad == 'Disponible' && $plato->estado)
                                        <div wire:key="plato-{{ $plato->id }}"
                                            wire:click="agregarPlato({{ $plato->id }})"
                                            class="overflow-hidden transition-all duration-200 bg-white border border-gray-200 rounded-lg cursor-pointer group dark:bg-gray-800 dark:border-gray-700 hover:shadow-lg hover:border-indigo-300 dark:hover:border-indigo-500 dark:hover:shadow-gray-700/20">
                                            <!-- Nombre del plato con más espacio -->
                                            <div
                                                class="p-3 pb-2 border-b border-gray-200 bg-gray-50 dark:bg-gray-700/50 dark:border-gray-700">
                                                <h3 class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    {{ $plato->nombre }}
                                                </h3>
                                            </div>

                                            <!-- Contenido principal con precio en una línea y disponibilidad+info en otra -->
                                            <div class="p-3">
                                                <div class="mb-2">
                                                    <span
                                                        class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                                        S/. {{ number_format($plato->precio, 2) }}
                                                    </span>
                                                </div>

                                                <!-- Disponibilidad y botón de información en la misma línea -->
                                                <div class="flex items-center justify-between">
                                                    <span
                                                        class="flex items-center px-2 py-0.5 text-xs font-semibold text-white bg-green-600 rounded-md">
                                                        <span
                                                            class="w-1.5 h-1.5 mr-1 bg-white rounded-full animate-pulse"></span>
                                                        {{ $disponibilidades[$plato->id] ?? 0 }}
                                                    </span>
                                                    <button wire:click.stop="mostrarInfo({{ $plato->id }})"
                                                        class="p-1.5 text-gray-500 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <!-- Plato (agotado) -->
                                @foreach ($platos as $plato)
                                    @if ($plato->disponibilidad == 'Agotado' || !$plato->estado)
                                        <div wire:key="plato-{{ $plato->id }}"
                                            class="overflow-hidden transition-all duration-200 bg-white border border-gray-200 rounded-lg cursor-not-allowed dark:bg-gray-800 dark:border-gray-700 opacity-60">
                                            <!-- Nombre del plato con más espacio -->
                                            <div
                                                class="p-3 pb-2 border-b border-gray-200 bg-gray-50 dark:bg-gray-700/50 dark:border-gray-700">
                                                <h3 class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    {{ $plato->nombre }}
                                                </h3>
                                            </div>

                                            <!-- Contenido principal con precio en una línea y disponibilidad+info en otra -->
                                            <div class="p-3">
                                                <div class="mb-2">
                                                    <span
                                                        class="text-sm font-medium text-gray-400 line-through dark:text-gray-500">
                                                        S/. {{ number_format($plato->precio, 2) }}
                                                    </span>
                                                </div>

                                                <!-- Disponibilidad y botón de información en la misma línea -->
                                                <div class="flex items-center justify-between">
                                                    <span
                                                        class="flex items-center px-2 py-0.5 text-xs font-semibold text-white bg-red-600 rounded-md">
                                                        <span class="w-1.5 h-1.5 mr-1 bg-white rounded-full"></span>
                                                        Agotado
                                                    </span>
                                                    <button wire:click.stop="mostrarInfo({{ $plato->id }})"
                                                        class="p-1.5 text-gray-500 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div> --}}
