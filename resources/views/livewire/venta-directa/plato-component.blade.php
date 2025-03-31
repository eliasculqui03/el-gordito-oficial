<div>
    <button wire:click="openModal"
        class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white transition-colors duration-200 bg-indigo-600 rounded-md hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path
                d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
        </svg>
        Agregar Platos
    </button>

    <!-- Modal de Platos -->
    @if ($showModal)
        <div wire:poll.2s
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-gray-900 bg-opacity-60">
            <div class="relative w-full max-w-5xl mx-auto bg-white rounded-lg shadow-xl dark:bg-gray-800">
                <!-- Encabezado del modal con gradiente -->
                <div
                    class="px-6 py-4 border-b border-gray-200 rounded-t-lg bg-gradient-to-r from-indigo-500 to-purple-600 dark:from-indigo-800 dark:to-purple-900 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-white">
                            Seleccionar Plato
                        </h3>
                        <button
                            class="p-1 text-white rounded-full hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/50"
                            wire:click="closeModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <!-- Separador con título de sección -->
                    <div class="flex items-center mb-4">
                        <div class="flex-grow h-px bg-gray-200 dark:bg-gray-700"></div>
                        <h4 class="px-3 text-sm font-medium text-gray-500 uppercase dark:text-gray-400">Categorías
                            de Platos</h4>
                        <div class="flex-grow h-px bg-gray-200 dark:bg-gray-700"></div>
                    </div>

                    <!-- Botones de categorías de platos -->
                    <div class="flex flex-wrap gap-2 mb-6 overflow-x-auto">
                        <button
                            class="px-4 py-2 text-sm font-medium transition-colors duration-200 border rounded-md {{ $selectedCategoria == '' ? 'bg-indigo-600 text-white border-indigo-700 shadow-sm dark:bg-indigo-700 dark:border-indigo-800' : 'text-indigo-700 bg-white border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-indigo-300 dark:border-gray-700 dark:hover:bg-gray-700' }}"
                            wire:click="selectCategoria('')">
                            Todas
                        </button>
                        @foreach ($categorias as $categoria)
                            <button
                                class="px-4 py-2 text-sm font-medium transition-colors duration-200 border rounded-md {{ $selectedCategoria == $categoria->id ? 'bg-indigo-600 text-white border-indigo-700 shadow-sm dark:bg-indigo-700 dark:border-indigo-800' : 'text-indigo-700 bg-white border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-indigo-300 dark:border-gray-700 dark:hover:bg-gray-700' }}"
                                wire:click="selectCategoria('{{ $categoria->id }}')">
                                {{ $categoria->nombre }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Grid de Platos disponibles -->
                    <div class="overflow-y-auto" style="max-height: 400px;">
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                            @forelse($platos as $plato)
                                <div
                                    class="relative transition-all duration-200 bg-white border border-gray-200 rounded-lg shadow-sm {{ $plato->disponibilidadPlato && $plato->disponibilidadPlato->disponibilidad == 1 ? 'hover:border-indigo-500 hover:shadow' : 'opacity-70' }} dark:bg-gray-800 dark:border-gray-700 dark:hover:border-indigo-500">
                                    <div class="p-2.5">
                                        <div class="flex flex-col h-full">
                                            <div>
                                                <h5
                                                    class="text-sm font-medium text-gray-800 truncate dark:text-gray-200">
                                                    {{ $plato->nombre }}
                                                </h5>
                                            </div>
                                            <div class="mt-1.5 flex justify-between items-center">
                                                <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                    S/. {{ number_format($plato->precio, 2) }}
                                                </span>
                                                @if ($plato->precio_llevar > 0 && $plato->precio_llevar != $plato->precio)
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        Llevar: S/. {{ number_format($plato->precio_llevar, 2) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <!-- Estado de disponibilidad -->
                                            <div class="mt-1.5 mb-1">
                                                @if ($plato->disponibilidadPlato && $plato->disponibilidadPlato->disponibilidad == 1)
                                                    <div class="flex items-center text-green-600 dark:text-green-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        <span class="text-xs font-medium">Disponible
                                                            ({{ $plato->disponibilidadPlato->cantidad }})
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="flex items-center text-red-600 dark:text-red-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        <span class="text-xs font-medium">Agotado</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="grid grid-cols-2 gap-1 mt-auto">
                                                @if ($plato->disponibilidadPlato && $plato->disponibilidadPlato->disponibilidad == 1)
                                                    <button wire:click="agregarPlato({{ $plato->id }}, false)"
                                                        class="px-2 py-1 text-xs font-medium text-white bg-indigo-600 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-indigo-700 dark:hover:bg-indigo-800">
                                                        Mesa
                                                    </button>
                                                    <button wire:click="agregarPlato({{ $plato->id }}, true)"
                                                        class="px-2 py-1 text-xs font-medium text-white rounded bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-400 dark:bg-amber-600 dark:hover:bg-amber-700">
                                                        Llevar
                                                    </button>
                                                @else
                                                    <button disabled
                                                        class="col-span-2 px-2 py-1 text-xs font-medium text-gray-500 bg-gray-200 rounded cursor-not-allowed dark:bg-gray-700 dark:text-gray-400">
                                                        No disponible
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-5 p-6 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                            No hay platos disponibles con los filtros seleccionados.
                                        </p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div
                    class="flex items-center justify-end px-6 py-3 border-t border-gray-200 rounded-b-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                    <div class="mr-auto text-xs text-gray-500 dark:text-gray-400">
                        <span class="inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4 mr-1 text-indigo-500 dark:text-indigo-400 animate-spin"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Actualizando disponibilidad en tiempo real
                        </span>
                    </div>
                    <button wire:click="closeModal"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
