<div>
    @vite('resources/css/app.css')

    <button wire:click="openModal"
        class="flex items-center justify-center px-3 py-2 text-sm text-white bg-indigo-600 rounded-md hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path
                d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
        </svg>
        <span>Nuevo </span>
    </button>

    @if ($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-950/50 dark:bg-gray-950/75">
            <div
                class="relative w-full max-w-md sm:max-w-xl md:max-w-2xl lg:max-w-3xl p-4 bg-white shadow-lg rounded-xl dark:bg-gray-800 dark:ring-1 dark:ring-white/10 overflow-y-auto max-h-[90vh]">
                <div class="flex items-center justify-between pb-4 border-b dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 sm:text-xl dark:text-white">Crear Cliente</h2>
                    <button wire:click="closeModal"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveClient" class="mt-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Tipo Documento -->
                        <div class="col-span-1">
                            <label for="tipo_documento_id"
                                class="block mb-2 text-xs font-medium text-gray-700 sm:text-sm dark:text-gray-200">
                                Tipo de Documento
                            </label>
                            <select id="tipo_documento_id" wire:model="tipo_documento_id"
                                class="w-full text-xs border-gray-300 rounded-lg shadow-sm sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Seleccione un tipo</option>
                                @foreach ($tipos_documentos as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->descripcion_corta }}</option>
                                @endforeach
                            </select>
                            @error('tipo_documento_id')
                                <span class="text-xs text-danger-600 dark:text-danger-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Número Documento con Botón de Búsqueda -->
                        <div class="relative col-span-1">
                            <label for="numero_documento"
                                class="block mb-2 text-xs font-medium text-gray-700 sm:text-sm dark:text-gray-200">
                                Número de Documento
                            </label>
                            <div class="flex">
                                <input type="text" id="numero_documento" wire:model="numero_documento"
                                    wire:loading.attr="disabled"
                                    class="w-full text-xs border-gray-300 rounded-l-lg sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500">
                                <button type="button" wire:click="buscarDocumento"
                                    class="flex items-center justify-center px-2 text-xs text-gray-500 bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg sm:px-4 sm:text-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-500">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </div>
                            @error('numero_documento')
                                <span class="text-xs text-danger-600 dark:text-danger-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Nombre -->
                        <div class="col-span-1">
                            <label for="nombre"
                                class="block mb-2 text-xs font-medium text-gray-700 sm:text-sm dark:text-gray-200">
                                Nombre
                            </label>
                            <input type="text" id="nombre" wire:model="nombre" required
                                class="w-full text-xs border-gray-300 rounded-lg shadow-sm sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500">
                            @error('nombre')
                                <span class="text-xs text-danger-600 dark:text-danger-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Edad -->
                        <div class="col-span-1">
                            <label for="edad"
                                class="block mb-2 text-xs font-medium text-gray-700 sm:text-sm dark:text-gray-200">
                                Edad
                            </label>
                            <input type="number" id="edad" wire:model="edad"
                                class="w-full text-xs border-gray-300 rounded-lg shadow-sm sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500">
                        </div>

                        <!-- Teléfono -->
                        <div class="col-span-1">
                            <label for="telefono"
                                class="block mb-2 text-xs font-medium text-gray-700 sm:text-sm dark:text-gray-200">
                                Teléfono
                            </label>
                            <input type="text" id="telefono" wire:model="telefono"
                                class="w-full text-xs border-gray-300 rounded-lg shadow-sm sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500">
                        </div>

                        <!-- Email -->
                        <div class="col-span-1">
                            <label for="email"
                                class="block mb-2 text-xs font-medium text-gray-700 sm:text-sm dark:text-gray-200">
                                Email
                            </label>
                            <input type="email" id="email" wire:model="email"
                                class="w-full text-xs border-gray-300 rounded-lg shadow-sm sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500">
                        </div>

                        <!-- Dirección - Span 2 columnas -->
                        <div class="col-span-1 sm:col-span-2">
                            <label for="direccion"
                                class="block mb-2 text-xs font-medium text-gray-700 sm:text-sm dark:text-gray-200">
                                Dirección
                            </label>
                            <textarea id="direccion" wire:model="direccion" rows="3"
                                class="w-full text-xs border-gray-300 rounded-lg shadow-sm sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500"></textarea>
                        </div>
                    </div>

                    <div class="flex flex-col justify-end gap-3 mt-6 sm:flex-row">
                        <button type="button" wire:click="closeModal"
                            class="w-full px-4 py-2 mb-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm sm:w-auto sm:mb-0 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="w-full px-4 py-2 text-xs font-medium text-white bg-indigo-600 rounded-md sm:w-auto sm:text-sm hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
