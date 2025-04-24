<div>
    <!-- Botón Asignar Mesa (nuevo icono de mesa) -->
    <button wire:click="openModalMesa"
        class="flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path
                d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z" />
        </svg>
        <span>Asignar Mesa</span>
    </button>

    <!-- Modal de Selección de Mesa -->
    @if ($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-2 overflow-y-auto sm:p-4 bg-gray-950/50 dark:bg-gray-950/75"
            x-data>
            <div
                class="relative w-full max-w-4xl mx-auto bg-white shadow-lg rounded-xl dark:bg-gray-800 dark:ring-1 dark:ring-white/10
            transition-all duration-300 flex flex-col max-h-[95vh] sm:max-h-[90vh]">

                <!-- Cabecera del Modal -->
                <div
                    class="sticky top-0 z-10 flex items-center justify-between p-3 bg-white border-b sm:p-4 dark:border-gray-700 dark:bg-gray-800 rounded-t-xl">
                    <h2 class="text-base font-bold text-gray-900 sm:text-lg dark:text-white">
                        Seleccionar Mesa</h2>
                    <button wire:click="closeModalMesa"
                        class="p-1 text-gray-500 transition-colors rounded-full hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                        aria-label="Cerrar">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </button>
                </div>

                <!-- Contenido principal con diseño responsivo -->
                <div class="flex flex-col flex-1 p-3 sm:p-4">
                    <!-- Zonas - Ahora con scroll horizontal en dispositivos pequeños y más margen -->
                    <div class="mb-4 sm:mb-6">
                        <h3 class="mb-2 text-sm font-semibold text-gray-600 dark:text-gray-400">
                            Zonas</h3>
                        <div class="flex flex-wrap gap-2 sm:gap-3">
                            @foreach ($zonas as $zona)
                                <button wire:click="seleccionarZona({{ $zona->id }})"
                                    class="px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-medium transition-all duration-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
                {{ $zonaSeleccionada == $zona->id
                    ? 'bg-primary-600 text-white shadow-md hover:bg-primary-700 dark:bg-primary-700 dark:hover:bg-primary-600'
                    : 'text-gray-700 bg-gray-100 hover:bg-gray-200 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600' }}">
                                    {{ $zona->nombre }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Contenedor de Mesas con altura fija de 400px -->
                    <div class="flex flex-col flex-1">
                        <div class="mb-2 text-sm font-semibold text-gray-600 dark:text-gray-400">
                            Mesas disponibles
                        </div>
                        <!-- Contenedor con altura fija de 400px -->
                        <div wire:poll.4s.visible
                            class="h-[400px] min-h-[400px] max-h-[400px] overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                            @if ($zonaSeleccionada)
                                <div
                                    class="grid grid-cols-2 gap-2 p-2 sm:p-3 md:grid-cols-3 lg:grid-cols-4 auto-rows-max">
                                    @foreach ($this->mesasZona as $mesa)
                                        <div wire:key="mesa-{{ $mesa->id }}"
                                            @if ($mesa->estado === 'Libre') wire:click="seleccionarMesa({{ $mesa }})"
                                        class="overflow-hidden transition-all duration-200 bg-white border border-gray-200 shadow-sm cursor-pointer dark:bg-gray-800 dark:border-gray-700 rounded-xl hover:shadow-md group {{ $mesaSeleccionadaId == $mesa->id ? 'ring-2 ring-primary-500 dark:ring-primary-400' : '' }}"
                                    @else
                                        class="overflow-hidden transition-all duration-200 bg-white border border-gray-200 shadow-sm opacity-75 cursor-not-allowed dark:bg-gray-800 dark:border-gray-700 rounded-xl group" @endif>
                                            <div class="p-2 sm:p-3">
                                                <div class="flex flex-col items-center">
                                                    <span
                                                        class="mb-1 text-base font-bold text-gray-800 sm:mb-2 sm:text-lg dark:text-white">
                                                        Mesa {{ $mesa->numero }}
                                                    </span>
                                                    <div
                                                        class="flex items-center gap-1 sm:gap-2 px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg w-full justify-center
                        {{ $mesa->estado === 'Libre'
                            ? 'bg-green-50 dark:bg-green-900/30'
                            : ($mesa->estado === 'Ocupada'
                                ? 'bg-red-50 dark:bg-red-900/30'
                                : 'bg-gray-50 dark:bg-gray-700') }}">
                                                        <span>
                                                            @if ($mesa->estado === 'Libre')
                                                                <x-heroicon-o-check-circle
                                                                    class="w-3 h-3 text-green-500 sm:w-4 sm:h-4 dark:text-green-400" />
                                                            @elseif($mesa->estado === 'Ocupada')
                                                                <x-heroicon-o-user
                                                                    class="w-3 h-3 text-red-500 sm:w-4 sm:h-4 dark:text-red-400" />
                                                            @else
                                                                <x-heroicon-o-x-circle
                                                                    class="w-3 h-3 text-gray-500 sm:w-4 sm:h-4 dark:text-gray-400" />
                                                            @endif
                                                        </span>
                                                        <span
                                                            class="text-xs font-medium
                            {{ $mesa->estado === 'Libre'
                                ? 'text-green-700 dark:text-green-400'
                                : ($mesa->estado === 'Ocupada'
                                    ? 'text-red-700 dark:text-red-400'
                                    : 'text-gray-700 dark:text-gray-400') }}">
                                                            {{ ucfirst($mesa->estado) }}
                                                        </span>
                                                    </div>
                                                    <div
                                                        class="flex items-center mt-1 text-xs text-gray-600 sm:mt-2 dark:text-gray-400">
                                                        <span>{{ $mesa->capacidad }} personas</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs text-center text-gray-500 bg-gray-50 dark:bg-gray-700/50 dark:text-gray-400 group-hover:bg-gray-100 dark:group-hover:bg-gray-700">
                                                @if ($mesa->estado === 'Libre')
                                                    Click para seleccionar
                                                @else
                                                    No disponible
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center p-4 text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-6 h-6 mb-2 sm:w-8 sm:h-8 opacity-60" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2m-6 0h6m-6 0v10m0 0H5a2 2 0 01-2-2V7m14 10a2 2 0 002-2V7" />
                                        </svg>
                                        <span>Selecciona una zona para ver las mesas disponibles</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>


                </div>

                <!-- Footer fijo -->
                <div
                    class="sticky bottom-0 z-10 flex justify-between p-3 bg-white border-t sm:p-4 dark:border-gray-700 dark:bg-gray-800 rounded-b-xl">
                    <button wire:click="limpiar"
                        class="px-3 py-1.5 sm:px-4 sm:py-2 text-sm font-medium text-gray-700 transition-colors bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400">
                        Limpiar selección
                    </button>
                    <button wire:click="closeModalMesa"
                        class="px-3 py-1.5 sm:px-4 sm:py-2 text-sm font-medium text-white transition-colors bg-indigo-600 rounded-md hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
