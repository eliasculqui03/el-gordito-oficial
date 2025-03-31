<div>
    <button wire:click="openModal"
        class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white transition-colors duration-200 bg-green-600 rounded-md hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
            <path fill-rule="evenodd"
                d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"
                clip-rule="evenodd" />
        </svg>
        Agregar Existencia
    </button>


    <!-- Modal de Existencias -->
    @if ($showModal)
        <div wire:poll.2s
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-gray-900 bg-opacity-60">
            <div class="relative w-full max-w-5xl mx-auto bg-white rounded-lg shadow-xl dark:bg-gray-800">
                <!-- Encabezado del modal con gradiente -->
                <div
                    class="px-6 py-4 border-b border-gray-200 rounded-t-lg bg-gradient-to-r from-green-500 to-teal-600 dark:from-green-800 dark:to-teal-900 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-white">
                            Seleccionar Existencia
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
                        <h4 class="px-3 text-sm font-medium text-gray-500 uppercase dark:text-gray-400">Tipos de
                            Existencia</h4>
                        <div class="flex-grow h-px bg-gray-200 dark:bg-gray-700"></div>
                    </div>

                    <!-- Botones de tipos de existencia -->
                    <div class="flex flex-wrap gap-2 mb-6 overflow-x-auto">
                        @foreach ($tipos_existencia as $tipo)
                            <button
                                class="px-4 py-2 text-sm font-medium transition-colors duration-200 border rounded-md {{ $selectedTipoExistencia == $tipo->id ? 'bg-green-600 text-white border-green-700 shadow-sm dark:bg-green-700 dark:border-green-800' : 'text-green-700 bg-white border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-green-300 dark:border-gray-700 dark:hover:bg-gray-700' }}"
                                wire:click="selectTipo('{{ $tipo->id }}')">
                                {{ $tipo->nombre }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Separador con título de sección -->
                    <div class="flex items-center mb-4">
                        <div class="flex-grow h-px bg-gray-200 dark:bg-gray-700"></div>
                        <h4 class="px-3 text-sm font-medium text-gray-500 uppercase dark:text-gray-400">Categorías</h4>
                        <div class="flex-grow h-px bg-gray-200 dark:bg-gray-700"></div>
                    </div>

                    <!-- Botones de categorías -->
                    <div class="flex flex-wrap gap-2 mb-6 overflow-x-auto">
                        <button
                            class="px-4 py-2 text-sm font-medium transition-colors duration-200 border rounded-md {{ $selectedCategoriaExistencia == '' ? 'bg-green-600 text-white border-green-700 shadow-sm dark:bg-green-700 dark:border-green-800' : 'text-green-700 bg-white border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-green-300 dark:border-gray-700 dark:hover:bg-gray-700' }}"
                            wire:click="selectCategoria('')">
                            Todas
                        </button>
                        @foreach ($categorias_existencia as $categoria)
                            <button
                                class="px-4 py-2 text-sm font-medium transition-colors duration-200 border rounded-md {{ $selectedCategoriaExistencia == $categoria->id ? 'bg-green-600 text-white border-green-700 shadow-sm dark:bg-green-700 dark:border-green-800' : 'text-green-700 bg-white border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-green-300 dark:border-gray-700 dark:hover:bg-gray-700' }}"
                                wire:click="selectCategoria('{{ $categoria->id }}')">
                                {{ $categoria->nombre }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Grid de Existencias -->
                    <div class="overflow-y-auto" style="max-height: 400px;">
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                            @forelse($existencias as $existencia)
                                <div
                                    class="relative transition-all duration-200 bg-white border border-gray-200 rounded-lg shadow-sm {{ $existencia->inventario && $existencia->inventario->stock > 0 ? 'hover:border-green-500 hover:shadow' : 'opacity-70' }} dark:bg-gray-800 dark:border-gray-700 dark:hover:border-green-500">
                                    <div class="p-2.5">
                                        <div class="flex flex-col h-full">
                                            <div>
                                                <h5
                                                    class="text-sm font-medium text-gray-800 truncate dark:text-gray-200">
                                                    {{ $existencia->nombre }}
                                                </h5>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $existencia->unidadMedida->nombre }}
                                                </p>
                                            </div>
                                            <div class="mt-1.5">
                                                <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                    S/. {{ number_format($existencia->precio_venta, 2) }}
                                                </span>
                                            </div>
                                            <!-- Estado de disponibilidad -->
                                            <div class="mt-1.5 mb-1">
                                                @if ($existencia->inventario && $existencia->inventario->stock > 0)
                                                    <div class="flex items-center text-green-600 dark:text-green-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        <span class="text-xs font-medium">Disponible
                                                            ({{ $existencia->inventario->stock }})
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

                                            <div class="grid grid-cols-1 gap-1 mt-auto">
                                                @if ($existencia->inventario && $existencia->inventario->stock > 0)
                                                    @php
                                                        $tipoProductosId = \App\Models\TipoExistencia::where(
                                                            'nombre',
                                                            'like',
                                                            '%producto%',
                                                        )->first()?->id;
                                                    @endphp

                                                    @if ($existencia->tipo_existencia_id == $tipoProductosId)
                                                        <div class="grid grid-cols-2 gap-1">
                                                            <button
                                                                wire:click="agregarExistencia({{ $existencia->id }}, false)"
                                                                class="px-2 py-1 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-green-700 dark:hover:bg-green-800">
                                                                Normal
                                                            </button>
                                                            <button
                                                                wire:click="agregarExistencia({{ $existencia->id }}, true)"
                                                                class="px-2 py-1 text-xs font-medium text-white bg-indigo-600 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-indigo-700 dark:hover:bg-indigo-800">
                                                                Helado
                                                            </button>
                                                        </div>
                                                    @else
                                                        <button
                                                            wire:click="agregarExistencia({{ $existencia->id }}, false)"
                                                            class="px-2 py-1 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-green-700 dark:hover:bg-green-800">
                                                            Agregar
                                                        </button>
                                                    @endif
                                                @else
                                                    <button disabled
                                                        class="px-2 py-1 text-xs font-medium text-gray-500 bg-gray-200 rounded cursor-not-allowed dark:bg-gray-700 dark:text-gray-400">
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
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                            No hay existencias disponibles con los filtros seleccionados.
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
                                class="w-4 h-4 mr-1 text-green-500 dark:text-green-400 animate-spin" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Actualizando stock en tiempo real
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
