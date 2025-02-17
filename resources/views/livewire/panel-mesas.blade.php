<div class="min-h-screen m-2 border border-gray-200 bg-gray-50 dark:bg-gray-900 dark:border-gray-700 rounded-2xl">
    @vite('resources/css/app.css')

    <!-- Contenido Principal con Zonas en la parte superior -->
    <div class="p-6">
        <!-- Zonas en la parte superior -->
        <div class="mb-6">
            <h3 class="mb-3 text-sm font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400">
                Zonas
            </h3>
            <div class="flex flex-wrap gap-2">
                @foreach ($zonas as $zona)
                    <button wire:click="seleccionarZona({{ $zona->id }})"
                        class="px-4 py-2 rounded-lg transition-colors text-sm
                            {{ $zonaSeleccionada == $zona->id
                                ? 'bg-primary-600 text-white'
                                : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        {{ $zona->nombre }}
                    </button>
                @endforeach
            </div>
        </div>

        @if ($zonaSeleccionada)


            <!-- Grid de Mesas -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5" wire:poll.1s>
                @foreach ($zonas->firstWhere('id', $zonaSeleccionada)->mesas as $mesa)
                    <div wire:key="mesa-{{ $mesa->id }}" wire:click="cambiarEstadoMesa({{ $mesa->id }})"
                        class="overflow-hidden transition-all duration-200 bg-white shadow-sm cursor-pointer dark:bg-gray-800 rounded-xl hover:shadow-md group">
                        <div class="p-4">
                            <div class="flex flex-col items-center">
                                <span class="mb-3 text-xl font-bold text-gray-800 dark:text-white">
                                    Mesa {{ $mesa->numero }}
                                </span>
                                <div
                                    class="flex items-center gap-2 px-3 py-2 rounded-lg w-full justify-center
                                        {{ $mesa->estado === 'Libre'
                                            ? 'bg-green-50 dark:bg-green-900/30'
                                            : ($mesa->estado === 'Ocupada'
                                                ? 'bg-red-50 dark:bg-red-900/30'
                                                : 'bg-gray-50 dark:bg-gray-700') }}">
                                    <span>
                                        @if ($mesa->estado === 'Libre')
                                            <x-heroicon-o-check-circle
                                                class="w-5 h-5 text-green-500 dark:text-green-400" />
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
                                        {{ ucfirst($mesa->estado) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div
                            class="px-4 py-2 text-xs text-center text-gray-500 bg-gray-50 dark:bg-gray-700/50 dark:text-gray-400 group-hover:bg-gray-100 dark:group-hover:bg-gray-700">
                            Click para cambiar estado
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex items-center justify-center h-full">
                <div class="text-center text-gray-500 dark:text-gray-400">
                    <x-heroicon-o-arrow-up class="w-8 h-8 mx-auto mb-2" />
                    Selecciona una zona
                </div>
            </div>
        @endif
    </div>
</div>
