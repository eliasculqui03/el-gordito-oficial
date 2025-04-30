<div class="min-h-screen m-2 border border-gray-200 bg-gray-50 dark:bg-gray-900 dark:border-gray-700 rounded-2xl">
    @vite('resources/css/app.css')

    <!-- Notificación Toast -->
    @if ($mostrarNotificacion)
        <div wire:key="notification-{{ $tiempoNotificacion }}"
            class="fixed top-12 right-4 z-50 px-4 py-3 rounded-lg shadow-lg {{ $notificacion['tipo'] === 'success' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
            <div class="flex items-center">
                @if ($notificacion['tipo'] === 'success')
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                @else
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                @endif
                <span>{{ $notificacion['mensaje'] }}</span>
                <button wire:click="ocultarNotificacion"
                    class="ml-auto text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Contenido Principal -->
    <div class="p-6">
        <!-- Menú de Zonas en la parte superior -->
        <div class="mb-6">
            <h3 class="mb-3 text-sm font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400">
                Zonas
            </h3>
            <div class="flex flex-wrap gap-2">
                @foreach ($zonas as $zona)
                    <button wire:click="seleccionarZona({{ $zona->id }})"
                        class="px-4 py-2 rounded-lg transition-colors text-sm
                            {{ $zonaSeleccionada == $zona->id
                                ? 'bg-primary-600 text-white dark:bg-primary-500'
                                : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        {{ $zona->nombre }}
                        <span
                            class="ml-1 px-1.5 py-0.5 text-xs rounded-full bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300">
                            {{ $zona->mesas->count() }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        @if ($zonaSeleccionada)
            <div class="mb-6">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                        {{ $zonas->firstWhere('id', $zonaSeleccionada)->nombre }}
                    </h2>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <!-- Filtro de estado -->
                        <div class="relative">
                            <select wire:model.live="estadoFiltro"
                                class="block w-full py-2 pl-3 pr-10 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                <option value="todos">Todos los estados</option>
                                <option value="Libre">Libre</option>
                                <option value="Ocupada">Ocupada</option>
                                <option value="Inhabilitada">Inhabilitada</option>
                            </select>
                        </div>

                        <!-- Búsqueda -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" wire:model.live.debounce.300ms="busqueda" placeholder="Buscar mesa..."
                                class="block w-full py-2 pl-10 pr-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid de Mesas -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5"
                wire:poll.1s>
                @if ($mesasFiltradas->count() > 0)
                    @foreach ($mesasFiltradas as $mesa)
                        <div wire:key="mesa-{{ $mesa->id }}" wire:click="cambiarEstadoMesa({{ $mesa->id }})"
                            class="overflow-hidden transition-all duration-200 bg-white shadow-sm cursor-pointer dark:bg-gray-800 rounded-xl hover:shadow-md group">
                            <div class="p-4">
                                <div class="flex flex-col items-center">
                                    <span class="mb-3 text-xl font-bold text-gray-800 dark:text-white">
                                        Mesa {{ $mesa->numero }}
                                    </span>
                                    <div
                                        class="flex items-center gap-2 px-3 py-2 rounded-lg w-full justify-center
                                        {{ $mesa->estado === 'Libre'
                                            ? 'bg-green-50 dark:bg-green-900/30'
                                            : ($mesa->estado === 'Ocupada'
                                                ? 'bg-red-50 dark:bg-red-900/30'
                                                : 'bg-gray-50 dark:bg-gray-700') }}">
                                        <span>
                                            @if ($mesa->estado === 'Libre')
                                                <x-heroicon-o-check-circle
                                                    class="w-5 h-5 text-green-500 dark:text-green-400" />
                                            @elseif($mesa->estado === 'Ocupada')
                                                <x-heroicon-o-user class="w-5 h-5 text-red-500 dark:text-red-400" />
                                            @else
                                                <x-heroicon-o-x-circle
                                                    class="w-5 h-5 text-gray-500 dark:text-gray-400" />
                                            @endif
                                        </span>
                                        <span
                                            class="text-sm font-medium
                                            {{ $mesa->estado === 'Libre'
                                                ? 'text-green-700 dark:text-green-400'
                                                : ($mesa->estado === 'Ocupada'
                                                    ? 'text-red-700 dark:text-red-400'
                                                    : 'text-gray-700 dark:text-gray-400') }}">
                                            {{ $mesa->estado }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="px-4 py-2 text-xs text-center text-gray-500 bg-gray-50 dark:bg-gray-700/50 dark:text-gray-400 group-hover:bg-gray-100 dark:group-hover:bg-gray-700">
                                @if ($mesa->estado === 'Libre')
                                    Click para cambiar a Ocupada
                                @elseif($mesa->estado === 'Ocupada')
                                    Click para cambiar a Inhabilitada
                                @else
                                    Click para cambiar a Libre
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div
                        class="flex items-center justify-center h-32 bg-white col-span-full dark:bg-gray-800 rounded-xl">
                        <div class="text-center text-gray-500 dark:text-gray-400">
                            <x-heroicon-o-magnifying-glass class="w-8 h-8 mx-auto mb-2" />
                            No se encontraron mesas con los filtros actuales
                        </div>
                    </div>
                @endif
            </div>

            <!-- Resumen de mesas -->
            <div class="p-4 mt-6 bg-white shadow-sm dark:bg-gray-800 rounded-xl">
                <h3 class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Resumen de mesas</h3>
                <div class="flex flex-wrap gap-3">
                    @php
                        $zona = $zonas->firstWhere('id', $zonaSeleccionada);
                        $totalMesas = $zona->mesas->count();
                        $mesasLibres = $zona->mesas->where('estado', 'Libre')->count();
                        $mesasOcupadas = $zona->mesas->where('estado', 'Ocupada')->count();
                        $mesasInhabilitadas = $zona->mesas->where('estado', 'Inhabilitada')->count();
                    @endphp

                    <div class="px-3 py-2 bg-gray-100 rounded-lg dark:bg-gray-700">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Total</span>
                        <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ $totalMesas }}</p>
                    </div>

                    <div class="px-3 py-2 rounded-lg bg-green-50 dark:bg-green-900/30">
                        <span class="text-xs text-green-600 dark:text-green-400">Libres</span>
                        <p class="text-lg font-semibold text-green-700 dark:text-green-400">{{ $mesasLibres }}</p>
                    </div>

                    <div class="px-3 py-2 rounded-lg bg-red-50 dark:bg-red-900/30">
                        <span class="text-xs text-red-600 dark:text-red-400">Ocupadas</span>
                        <p class="text-lg font-semibold text-red-700 dark:text-red-400">{{ $mesasOcupadas }}</p>
                    </div>

                    <div class="px-3 py-2 rounded-lg bg-gray-50 dark:bg-gray-700">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Inhabilitadas</span>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-400">{{ $mesasInhabilitadas }}</p>
                    </div>
                </div>
            </div>
        @else
            <div class="flex items-center justify-center h-64">
                <div class="text-center text-gray-500 dark:text-gray-400">
                    <x-heroicon-o-arrow-up class="w-8 h-8 mx-auto mb-2" />
                    Selecciona una zona del menú
                </div>
            </div>
        @endif
    </div>
</div>
