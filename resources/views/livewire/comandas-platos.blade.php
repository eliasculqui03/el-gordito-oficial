<div>
    @vite('resources/css/app.css')
    <div class="grid grid-cols-12 gap-4">
        <!-- Panel izquierdo (Comandas) -->
        <div class="col-span-9">
            <div class="min-h-screen pt-2" wire:poll.{{ $refreshInterval }}ms>
                <!-- Área Tabs -->
                <div class="sticky top-0 z-10 mb-4 bg-gray-50 dark:bg-gray-900">
                    @if (count($areas) > 1)
                        <div class="sm:hidden">
                            <select id="area-select" wire:model="selectedArea"
                                class="block w-full border-gray-300 rounded-lg shadow-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200">
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="hidden sm:block">
                            <nav class="flex justify-center py-2 space-x-2" aria-label="Áreas">
                                @foreach ($areas as $area)
                                    <button wire:click="selectArea({{ $area->id }})"
                                        class="{{ $selectedArea == $area->id
                                            ? 'bg-white shadow text-primary-600 dark:bg-gray-800 dark:text-primary-400'
                                            : 'text-gray-500 hover:text-gray-700 hover:bg-white/50 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800/50' }}
                                    px-4 py-2 text-sm font-medium rounded-lg transition-all duration-150">
                                        {{ $area->nombre }}
                                    </button>
                                @endforeach
                            </nav>
                        </div>
                    @endif
                </div>

                <!-- Grid de Comandas -->
                <div class="grid grid-cols-1 gap-3 lg:grid-cols-2">
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
                                        <span class="font-medium text-gray-700 dark:text-gray-300">
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
                                        <span class="text-gray-600 dark:text-gray-400">
                                            {{ $comanda->zona->nombre }} • Mesa {{ $comanda->mesa->numero }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Lista de Platos -->
                            <div class="px-3 py-2 space-y-1">
                                @foreach ($comanda->comandaPlatos->where('plato.area_id', $selectedArea) as $comandaPlato)
                                    <div
                                        class="flex items-center justify-between p-2 transition-colors rounded-lg bg-gray-50/50 dark:bg-gray-700/50">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $comandaPlato->plato->nombre }}
                                        </span>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="px-2 py-1 text-sm font-medium text-gray-900 bg-gray-100 rounded-lg dark:bg-gray-600 dark:text-white">
                                                x{{ $comandaPlato->cantidad }}
                                            </span>
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-lg
                                                {{ $comandaPlato->estado === 'Listo'
                                                    ? 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400'
                                                    : ($comandaPlato->estado === 'Procesando'
                                                        ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400'
                                                        : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300') }}">
                                                {{ $comandaPlato->estado }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Footer -->
                            @if ($comanda->estado === 'Abierta')
                                <div class="flex justify-end p-3 border-t dark:border-gray-700">
                                    <button wire:click="procesarComanda({{ $comanda->id }})"
                                        class="px-4 py-2 text-sm font-medium text-white transition-colors rounded-lg bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-primary-500 dark:hover:bg-primary-600">
                                        Procesar
                                    </button>
                                </div>
                            @endif
                        </div>
                    @empty
                        <!-- ... código estado vacío ... -->
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Panel derecho -->
        <div class="col-span-3">
            <div class="sticky p-4 overflow-y-auto bg-white border shadow-sm top-2 dark:bg-gray-800 dark:border-gray-700 rounded-xl"
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
                            class="flex items-center justify-between p-3 transition-colors rounded-lg bg-gray-50 hover:bg-gray-100 dark:bg-gray-700/50 dark:hover:bg-gray-700">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                    {{ $plato['nombre'] }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    En preparación
                                </p>
                            </div>
                            <div class="flex items-center gap-2 ml-4">
                                <span
                                    class="px-2.5 py-1 text-sm font-medium bg-primary-100 text-primary-700 rounded-lg dark:bg-primary-900/50 dark:text-primary-400">
                                    x{{ $plato['total'] }}
                                </span>
                                <button wire:click="marcarPlatoListo({{ $plato['id'] }})"
                                    class="px-3 py-1 text-sm font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:bg-green-500 dark:hover:bg-green-600">
                                    Listo
                                </button>
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
</div>
