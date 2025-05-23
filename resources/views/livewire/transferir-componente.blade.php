<div>

    <button type="button" wire:click="openModal"
        class="flex items-center justify-center px-3 py-2 text-sm font-medium text-white transition rounded-md bg-amber-500 hover:bg-amber-600 dark:bg-amber-600 dark:hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 dark:focus:ring-amber-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                clip-rule="evenodd" />
        </svg>
        Transferir Saldo
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
                <h2 class="mb-4 text-xl font-bold text-gray-800 dark:text-white">Transferencia de Saldo</h2>

                <!-- Información del usuario y caja origen -->
                <div class="p-4 mb-5 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-600 dark:text-gray-300"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Usuario:
                            {{ $usuario->name }}</span>
                    </div>

                    <div class="flex items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-600 dark:text-gray-300"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Caja Origen:
                            {{ $cajaOrigen->nombre }}</span>
                    </div>

                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-600 dark:text-gray-300"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Saldo Disponible:
                            <span class="font-semibold text-green-600 dark:text-green-400">S/.
                                {{ number_format($cajaOrigen->saldo_actual, 2) }}</span>
                        </span>
                    </div>
                </div>

                <!-- Formulario de transferencia -->
                <form wire:submit.prevent="transferir">
                    <!-- Mensaje de éxito -->
                    @if (session()->has('message'))
                        <div
                            class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded-md dark:bg-green-900/30 dark:text-green-400">
                            {{ session('message') }}
                        </div>
                    @endif

                    <!-- Mensaje de error -->
                    @if (session()->has('error'))
                        <div
                            class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-md dark:bg-red-900/30 dark:text-red-400">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Seleccionar caja destino -->
                    <div class="mb-4">
                        <label for="cajaDestinoId"
                            class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Caja Destino</label>
                        <select wire:model="cajaDestinoId" id="cajaDestinoId"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-amber-500 dark:focus:border-amber-500">
                            <option value="">Seleccionar caja destino</option>
                            @foreach ($cajasDisponibles as $caja)
                                <option value="{{ $caja->id }}">{{ $caja->nombre }} - Saldo: S/.
                                    {{ number_format($caja->saldo_actual, 2) }}</option>
                            @endforeach
                        </select>
                        @error('cajaDestinoId')
                            <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Monto a transferir -->
                    <div class="mb-4">
                        <label for="monto"
                            class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Monto a
                            transferir</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-gray-500 sm:text-sm dark:text-gray-400">S/. </span>
                            </div>
                            <input wire:model="monto" type="number" step="0.01" id="monto"
                                class="block w-full pr-12 border-gray-300 rounded-md pl-7 focus:ring-amber-500 focus:border-amber-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 dark:focus:ring-amber-500 dark:focus:border-amber-500"
                                placeholder="0.00">
                        </div>
                        @error('monto')
                            <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Motivo de la transferencia -->
                    <div class="mb-4">
                        <label for="motivo"
                            class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Motivo de la
                            transferencia</label>
                        <textarea wire:model="motivo" id="motivo" rows="2"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 dark:focus:ring-amber-500 dark:focus:border-amber-500"
                            placeholder="Ingrese el motivo de la transferencia"></textarea>
                        @error('motivo')
                            <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex justify-end mt-6 space-x-3">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-offset-gray-800">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white border border-transparent rounded-md shadow-sm bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 dark:bg-amber-600 dark:hover:bg-amber-700 dark:focus:ring-offset-gray-800">
                            Transferir Saldo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif


</div>
