<div>
    @vite('resources/css/app.css')

    <div class="p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
        <!-- Menú de Áreas -->
        <div class="mb-8">
            <h2 class="mb-4 text-xl font-bold text-gray-800 dark:text-white">Seleccione un Área</h2>
            <div class="flex flex-wrap gap-3">
                @foreach ($areas as $area)
                    <button wire:click="selectArea({{ $area->id }})"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium transition-colors duration-150
                        {{ $selectedArea == $area->id
                            ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300'
                            : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}
                        rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                        {{ $area->nombre }}
                        <span
                            class="ml-2 px-2 py-0.5 text-xs rounded-full
                            {{ $selectedArea == $area->id
                                ? 'bg-blue-200 text-blue-800 dark:bg-blue-800 dark:text-blue-200'
                                : 'bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-300' }}">
                            {{ $comandas->where('existencia.area_id', $area->id)->count() }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Lista de Comandas -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($comandas as $comanda)
                @php
                    $platosArea = $comanda->comandaPlatos->filter(function ($comandaPlato) {
                        return $comandaPlato->plato->area_id == $this->selectedArea;
                    });
                    $tiempoTranscurrido = now()->diffInMinutes($comanda->created_at);
                @endphp

                @if ($platosArea->isNotEmpty())
                    <div
                        class="overflow-hidden transition-all duration-300 bg-white rounded-lg shadow-md hover:shadow-lg dark:bg-gray-700
                        {{ $tiempoTranscurrido >= 10 ? 'ring-2 ring-red-500 dark:ring-red-400' : '' }}
                        {{ $tiempoTranscurrido >= 8 ? 'ring-2 ring-red-400 dark:ring-red-300' : '' }}
                        {{ $tiempoTranscurrido >= 6 ? 'ring-2 ring-red-300 dark:ring-red-200' : '' }}">

                        <!-- Header -->
                        <div
                            class="flex items-center justify-between p-4 border-b dark:border-gray-600 bg-gray-50 dark:bg-gray-800">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-semibold text-gray-800 dark:text-white">
                                    Comanda #{{ $comanda->id }}
                                </span>
                                <span
                                    class="px-2 py-1 text-xs font-medium {{ $tiempoTranscurrido >= 8
                                        ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                        : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }} rounded-full">
                                    {{ $tiempoTranscurrido }} min
                                </span>
                            </div>
                            <span
                                class="px-3 py-1 text-sm font-medium {{ $comanda->estado
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                    : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }} rounded-full">
                                {{ $comanda->estado ? 'Activa' : 'Cerrada' }}
                            </span>
                        </div>

                        <!-- Body -->
                        <div class="p-4 space-y-4">
                            <!-- Info Cliente -->
                            <div class="space-y-2">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span
                                        class="text-sm text-gray-600 dark:text-gray-300">{{ $comanda->cliente->nombre }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-300">
                                        {{ $comanda->zona->nombre }} - Mesa {{ $comanda->mesa->numero }}
                                    </span>
                                </div>
                            </div>

                            <!-- Lista de Platos -->
                            <div class="mt-4">
                                <h4 class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-200">Platos:</h4>
                                <div class="space-y-2">
                                    @foreach ($platosArea as $comandaPlato)
                                        <div
                                            class="flex items-center justify-between p-2 rounded-lg bg-gray-50 dark:bg-gray-600">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                                {{ $comandaPlato->plato->nombre }}
                                            </span>
                                            <span
                                                class="px-2 py-1 text-sm font-semibold text-blue-600 bg-blue-100 rounded dark:bg-blue-900 dark:text-blue-200">
                                                x{{ $comandaPlato->cantidad }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div
                            class="flex justify-end px-4 py-3 border-t bg-gray-50 dark:bg-gray-800 dark:border-gray-600">
                            <button
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-offset-gray-800">
                                Procesar
                            </button>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-span-full">
                    <div
                        class="flex flex-col items-center justify-center p-8 text-center bg-white rounded-lg shadow dark:bg-gray-700">
                        <svg class="w-12 h-12 mb-4 text-gray-400 dark:text-gray-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mb-1 text-lg font-medium text-gray-900 dark:text-white">No hay comandas pendientes
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No se encontraron comandas pendientes para
                            esta área.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
