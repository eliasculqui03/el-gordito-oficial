<div>
    <button type="button" wire:click="openModal"
        class="flex flex-col items-center justify-center w-full px-2 py-3 text-sm font-medium text-white transition bg-indigo-600 rounded-md hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                d="M5 2a1 1 0 00-1 1v1h12V3a1 1 0 00-1-1H5zM4 6a1 1 0 00-1 1v10a1 1 0 001 1h12a1 1 0 001-1V7a1 1 0 00-1-1H4zm2 3a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1z"
                clip-rule="evenodd" />
        </svg>
        <span class="text-xs sm:text-sm">Retirar</span>
    </button>
    @if ($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <!-- Fondo oscuro -->
            <div class="fixed inset-0 bg-black/50 dark:bg-black/70" wire:click="closeModal"></div>

            <!-- Contenido del modal -->
            <div
                class="relative z-10 w-full max-w-md p-6 mx-auto bg-white rounded-lg shadow-xl dark:bg-gray-800 dark:border dark:border-gray-700">
                <!-- Botón de cerrar en la esquina superior derecha -->
                <button wire:click="closeModal"
                    class="absolute text-gray-500 top-3 right-3 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- Encabezado del modal -->
                <h2 class="mb-4 text-xl font-bold text-gray-800 dark:text-white">Retiro de Saldo</h2>

                <!-- Información de usuario y caja -->
                <div class="p-4 mb-4 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <!-- Información del usuario -->
                    <div class="flex items-center mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4 mr-2 text-indigo-500 dark:text-indigo-400" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Usuario: <span
                                class="font-semibold text-gray-900 dark:text-white">{{ $usuario->name }}</span>
                        </span>
                    </div>

                    <!-- Información de la caja -->
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4 mr-2 text-indigo-500 dark:text-indigo-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Caja:</span>
                        </div>
                        <span
                            class="text-sm font-semibold text-gray-900 dark:text-white">{{ $caja->nombre ?? 'N/A' }}</span>
                    </div>

                    <!-- Saldo disponible -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4 mr-2 text-green-500 dark:text-green-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Saldo disponible:</span>
                        </div>
                        <span class="text-sm font-semibold text-green-600 dark:text-green-400">S/.
                            {{ number_format($caja->saldo_actual ?? 0, 2) }}</span>
                    </div>
                </div>

                <!-- Formulario de retiro -->
                <form wire:submit.prevent="retirarSaldo">
                    <!-- Monto del retiro -->
                    <div class="mb-4">
                        <label for="monto" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Monto a retirar
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400">S/.</span>
                            </div>
                            <input type="number" id="monto" wire:model="monto" step="0.01"
                                class="block w-full py-2 pl-10 pr-4 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="0.00">
                        </div>
                        @error('monto')
                            <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Motivo del retiro -->
                    <div class="mb-6">
                        <label for="descripcion"
                            class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Motivo del retiro
                        </label>
                        <textarea id="descripcion" wire:model="descripcion" rows="2"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Ejemplo: Retiro para compra de insumos"></textarea>
                        @error('descripcion')
                            <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-400 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-indigo-500">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-700 dark:hover:bg-indigo-800">
                            <span wire:loading.remove wire:target="retirarSaldo">Confirmar Retiro</span>
                            <span wire:loading wire:target="retirarSaldo">Procesando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
