<div>
    @vite('resources/css/app.css')
    <div class="min-h-screen p-6 " wire:poll.{{ $refreshInterval }}ms>

        <!-- Área Tabs -->
        <div class="mb-2">
            @if (count($areas) > 1)
                <div class="sm:hidden">
                    <select id="area-select" wire:model="selectedArea"
                        class="block w-full border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="hidden sm:block">
                    <nav class="flex justify-center p-4 space-x-4 rounded-lg" aria-label="Áreas">
                        @foreach ($areas as $area)
                            <button wire:click="selectArea({{ $area->id }})"
                                class="{{ $selectedArea == $area->id
                                    ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300'
                                    : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700' }}
                       px-3 py-2 font-medium text-sm rounded-md transition-colors duration-150 ease-in-out">
                                {{ $area->nombre }}
                            </button>
                        @endforeach
                    </nav>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($comandasAgrupadas as $comanda)
                @php
                    $tiempoTranscurrido = now()->diffInMinutes($comanda->created_at);
                    $colorClase = match (true) {
                        $tiempoTranscurrido >= 6 => 'bg-rose-50 dark:bg-rose-950',
                        $tiempoTranscurrido >= 4 => 'bg-amber-50 dark:bg-amber-950',
                        $tiempoTranscurrido >= 2 => 'bg-emerald-50 dark:bg-emerald-950',
                        default => 'bg-white dark:bg-gray-800',
                    };
                @endphp

                <div
                    class="overflow-hidden transition-all duration-300 rounded-xl shadow-sm hover:shadow-md {{ $colorClase }}">
                    <!-- Header -->
                    <div
                        class="flex items-center justify-between p-4 bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <span class="text-base font-semibold text-gray-900 dark:text-white">
                                Comanda #{{ str_pad($comanda->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                            <div
                                class="flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full
                        {{ $tiempoTranscurrido >= 6
                            ? 'text-rose-700 bg-rose-50 dark:text-rose-200 dark:bg-rose-900/50'
                            : 'text-slate-700 bg-slate-100 dark:text-slate-300 dark:bg-slate-800' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $tiempoTranscurrido }} min
                            </div>
                        </div>
                        <span
                            class="px-2.5 py-1 text-xs font-medium rounded-full
                    {{ $comanda->estado
                        ? 'text-emerald-700 bg-emerald-50 dark:text-emerald-300 dark:bg-emerald-900/50'
                        : 'text-rose-700 bg-rose-50 dark:text-rose-300 dark:bg-rose-900/50' }}">
                            {{ $comanda->estado ? 'Activa' : 'Cerrada' }}
                        </span>
                    </div>

                    <!-- Body -->
                    <div class="p-4 space-y-4">
                        <!-- Info Cliente y Mesa -->
                        <div class="p-3 space-y-2 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                            <div class="flex items-center gap-2">
                                <span class="p-1 rounded-md bg-slate-100 dark:bg-slate-700">
                                    <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </span>
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                    {{ $comanda->cliente->nombre }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="p-1 rounded-md bg-slate-100 dark:bg-slate-700">
                                    <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </span>
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                    {{ $comanda->zona->nombre }} • Mesa {{ $comanda->mesa->numero }}
                                </span>
                            </div>
                        </div>

                        <!-- Lista de Productos -->
                        <div class="space-y-2">
                            @foreach ($comanda->comandaExistencias->where('existencia.area_existencia_id', $selectedArea) as $item)
                                <div class="flex items-center gap-3 p-3 rounded-lg bg-white/80 dark:bg-gray-800/50">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            {{ $item->existencia->nombre }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $item->existencia->unidadMedida->nombre }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="px-2.5 py-1 text-sm font-medium text-gray-900 bg-gray-100 rounded-lg dark:bg-gray-700 dark:text-white">
                                            x{{ $item->cantidad }}
                                        </span>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Footer -->
                    <div
                        class="flex items-center justify-between px-4 py-3 bg-white border-t dark:bg-gray-800 dark:border-gray-700">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            Total:
                            <span class="text-gray-900 dark:text-white">
                                S/
                                {{ number_format($comanda->comandaExistencias->where('existencia.area_existencia_id', $selectedArea)->sum('subtotal'), 2) }}
                            </span>
                        </div>
                        <button wire:click="procesarComanda({{ $comanda->id }})"
                            class="px-4 py-2 text-sm font-medium text-white transition-colors rounded-lg bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600">
                            Procesar Todo
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="flex flex-col items-center gap-2 p-8 text-center bg-white rounded-xl dark:bg-gray-800">
                        <div class="p-3 rounded-full bg-slate-100 dark:bg-slate-700">
                            <svg class="w-6 h-6 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="text-base font-medium text-gray-900 dark:text-white">No hay comandas pendientes</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No se encontraron comandas pendientes para
                            esta área.</p>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</div>
