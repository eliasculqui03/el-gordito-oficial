<div>
    @vite('resources/css/app.css')

    <div class="min-h-screen p-6 bg-gray-100 dark:bg-gray-900" wire:poll.{{ $refreshInterval }}ms>
        <!-- Área Tabs -->
        <div class="mb-6">
            <div class="sm:hidden">
                <label for="area-select" class="sr-only">Seleccionar Área</label>
                <select id="area-select" wire:model="selectedArea"
                    class="block w-full border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200">
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="hidden sm:block">
                <nav class="flex p-4 space-x-4 bg-white rounded-lg shadow-sm dark:bg-gray-800" aria-label="Áreas">
                    @foreach ($areas as $area)
                        <button wire:click="selectArea({{ $area->id }})"
                            class="{{ $selectedArea == $area->id
                                ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300'
                                : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700' }}
                            px-3 py-2 font-medium text-sm rounded-md transition-colors duration-150 ease-in-out">
                            {{ $area->nombre }}
                            <span
                                class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $selectedArea == $area->id ? 'bg-indigo-200 dark:bg-indigo-800' : 'bg-gray-100 dark:bg-gray-700' }}">
                                {{ $comandas->where('existencia.area_id', $area->id)->count() }}
                            </span>
                        </button>
                    @endforeach
                </nav>
            </div>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($comandas as $comanda)
                <div
                    class="overflow-hidden transition-shadow duration-300 bg-white rounded-lg shadow-md hover:shadow-lg dark:bg-gray-800">
                    <!-- Header with Order Number and Status -->
                    <div
                        class="flex items-center justify-between p-4 border-b bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                        <div class="flex items-center space-x-2">
                            <span class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                                Comanda #{{ $comanda->comanda->id }}
                            </span>
                        </div>
                        <div>
                            <span
                                class="px-3 py-1 text-sm font-medium text-yellow-800 bg-yellow-100 rounded-full dark:bg-yellow-900 dark:text-yellow-200">
                                Pendiente
                            </span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 space-y-3">
                        <!-- Customer Info -->
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $comanda->comanda->cliente->nombre }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Cliente</p>
                            </div>
                        </div>

                        <!-- Location Info -->
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $comanda->comanda->zona->nombre }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Zona - Mesa
                                    {{ $comanda->comanda->mesa->numero }}</p>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div>
                                <div class="flex justify-between">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $comanda->existencia->nombre }}</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        x{{ $comanda->cantidad }}</p>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Subtotal:
                                    ${{ number_format($comanda->subtotal, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="px-4 py-3 border-t bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                        <div class="flex justify-end space-x-3">
                            <button wire:click="procesarComanda({{ $comanda->id }})"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600 dark:focus:ring-offset-gray-800">
                                Procesar
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="py-12 text-center bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay comandas pendientes
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No se encontraron comandas pendientes
                            para esta área.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
