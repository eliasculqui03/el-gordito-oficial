<div>

    <label for="openModalMesa" class="flex items-center gap-2 text-gray-700 dark:text-gray-200">
        <span>Nro. Mesa:</span>
        <input type="text" wire:model="mesaSeleccionada" readonly
            class="w-20 px-3 py-1 text-lg font-semibold text-gray-800 bg-gray-100 rounded-lg dark:text-gray-200 dark:bg-gray-700">
    </label>

    <input type="hidden" disabled wire:model="mesaSeleccionadaId">

    <button wire:click="openModalMesa"
        class="px-4 py-2 ml-4 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-600">
        Cambiar Mesa
    </button>

    @if ($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 dark:bg-gray-950/75">
            <div
                class="relative w-11/12 max-w-4xl p-4 bg-white shadow-lg rounded-xl dark:bg-gray-800 dark:ring-1 dark:ring-white/10">

                <div class="flex items-center justify-between pb-4 border-b dark:border-gray-700">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Seleccionar Mesa</h2>
                    <button wire:click="closeModalMesa"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4">
                    <div class="grid grid-cols-4 gap-4">

                        <!-- Cajas -->
                        <div class="col-span-1 space-y-2">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Cajas</h3>
                            @foreach ($cajas as $caja)
                                <button wire:click="cambiarCaja({{ $caja->id }})"
                                    class="w-full px-3 py-2 text-left text-sm rounded-lg
                {{ $cajaActual == $caja->id
                    ? 'bg-primary-600 text-white dark:bg-primary-700'
                    : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    {{ $caja->nombre }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Zonas -->
                        <div class="col-span-1 space-y-2">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Zonas</h3>
                            @if ($zonas)
                                @foreach ($zonas as $zona)
                                    <button wire:click="cambiarZona({{ $zona->id }})"
                                        class="w-full px-3 py-2 text-left text-sm rounded-lg
                  {{ $zonaActual == $zona->id
                      ? 'bg-primary-600 text-white dark:bg-primary-700'
                      : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        {{ $zona->nombre }}
                                    </button>
                                @endforeach
                            @endif
                        </div>

                        <!-- Mesas -->
                        <div class="col-span-2">
                            @if ($zonaActual && $zonas->count())
                                <div class="grid grid-cols-2 gap-4">
                                    @foreach ($zonas->firstWhere('id', $zonaActual)->mesas as $mesa)
                                        <div wire:click="seleccionarMesa({{ $mesa }})"
                                            class="p-4 cursor-pointer rounded-xl shadow-sm
    {{ $mesaSeleccionadaId == $mesa->id ? 'bg-blue-100 dark:bg-blue-800' : 'bg-white dark:bg-gray-700' }}">

                                            <div class="flex flex-col items-center">
                                                <span class="text-lg font-bold dark:text-gray-200">Mesa
                                                    {{ $mesa->numero }}</span>

                                                <div
                                                    class="flex items-center gap-2 px-3 py-1 mt-2 rounded-lg
      {{ $mesa->estado === 'Libre'
          ? 'bg-green-50 text-green-700 dark:bg-green-900 dark:text-green-400'
          : 'bg-red-50 text-red-700 dark:bg-red-900 dark:text-red-400' }}">
                                                    <span>
                                                        @if ($mesa->estado === 'Libre')
                                                            <x-heroicon-m-check-circle class="w-5 h-5" />
                                                        @else
                                                            <x-heroicon-m-x-circle class="w-5 h-5" />
                                                        @endif
                                                    </span>
                                                    <span class="text-sm font-medium">{{ $mesa->estado }}</span>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400">
                                    Selecciona una zona
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button wire:click="closeModalMesa"
                        class="px-4 py-2 text-sm font-medium text-white border border-transparent rounded-lg shadow-sm bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-primary-700 dark:hover:bg-primary-800 dark:focus:ring-primary-600 dark:focus:ring-offset-gray-800">
                        Cerrar
                    </button>
                </div>

            </div>
        </div>
    @endif



</div>



<div class="p-4">
    <div class="flex flex-col items-center">
        <span class="mb-3 text-xl font-bold text-gray-800 dark:text-white">
            Mesa {{ $mesa->numero }}
        </span>
        <div
            class="flex items-center justify-center w-full gap-2 px-3 py-2 rounded-lg
                               {{ $mesa->estado === 'Libre'
                                   ? 'bg-green-50 dark:bg-green-900/30'
                                   : ($mesa->estado === 'Ocupada'
                                       ? 'bg-red-50 dark:bg-red-900/30'
                                       : 'bg-gray-50 dark:bg-gray-700') }}">
            <span>
                @if ($mesa->estado === 'Libre')
                    <x-heroicon-o-check-circle class="w-5 h-5 text-green-500 dark:text-green-400" />
                @elseif($mesa->estado === 'Ocupada')
                    <x-heroicon-o-user class="w-5 h-5 text-red-500 dark:text-red-400" />
                @else
                    <x-heroicon-o-x-circle class="w-5 h-5 text-gray-500 dark:text-gray-400" />
                @endif
            </span>
            <span
                class="text-sm font-medium
                                   {{ $mesa->estado === 'Libre'
                                       ? 'text-green-700 dark:text-green-400'
                                       : ($mesa->estado === 'Ocupada'
                                           ? 'text-red-700 dark:text-red-400'
                                           : 'text-gray-700 dark:text-gray-400') }}">
                {{ $mesa->estado }}
            </span>
        </div>
    </div>
</div>
