<div class="p-6 bg-gray-50 dark:bg-gray-900 min-h-screen border border-gray-200 dark:border-gray-700 rounded-2xl m-2">
    @vite('resources/css/app.css')
    {{-- Header y Selector de Cajas --}}
    <div class="max-w-7xl mx-auto mb-8">

        @if ($cajas->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Seleccionar Caja</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach ($cajas as $caja)
                        @if ($caja->estado)
                            <button wire:click="cambiarCaja({{ $caja->id }})"
                                class="w-full px-6 py-3 rounded-lg font-medium transition-all duration-200
                                    {{ $cajaActual == $caja->id
                                        ? 'bg-primary-600 text-white dark:bg-primary-500 shadow-lg shadow-primary-100/50 dark:shadow-primary-900/50'
                                        : 'bg-white text-gray-700 dark:bg-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600' }}">
                                {{ $caja->nombre }}
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 text-center">
                <div class="text-gray-500 dark:text-gray-400 text-lg">
                    No hay cajas activas disponibles.
                </div>
            </div>
        @endif
    </div>

    {{-- Selector de Zonas y Grid de Mesas --}}
    @if ($zonas && $zonas->count() > 0)
        <div class="max-w-7xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Zonas Disponibles</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach ($zonas as $zona)
                        <button wire:click="cambiarZona({{ $zona->id }})"
                            class="w-full px-6 py-3 rounded-lg font-medium transition-all duration-200
                                {{ $zonaActual == $zona->id
                                    ? 'bg-primary-600 text-white dark:bg-primary-500 shadow-lg shadow-primary-100/50 dark:shadow-primary-900/50'
                                    : 'bg-white text-gray-700 dark:bg-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600' }}">
                            {{ $zona->nombre }}
                        </button>
                    @endforeach
                </div>
            </div>

            @if ($zonaActual)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($zonas->firstWhere('id', $zonaActual)->mesas as $mesa)
                        <div wire:click="cambiarEstadoMesa({{ $mesa->id }})"
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 cursor-pointer overflow-hidden group">
                            <div class="p-6">
                                <div class="flex flex-col items-center">
                                    <span class="text-xl font-bold text-gray-800 dark:text-white mb-3">Mesa
                                        {{ $mesa->numero }}</span>
                                    <div
                                        class="flex items-center gap-3 p-2 rounded-lg
                                        {{ $mesa->estado === 'libre'
                                            ? 'bg-green-50 dark:bg-green-900/30'
                                            : ($mesa->estado === 'ocupado'
                                                ? 'bg-red-50 dark:bg-red-900/30'
                                                : 'bg-gray-50 dark:bg-gray-700') }}">
                                        <span>
                                            @if ($mesa->estado === 'Libre')
                                                <x-heroicon-o-check-circle
                                                    class="w-7 h-7 text-green-500 dark:text-green-400" />
                                            @elseif($mesa->estado === 'Ocupada')
                                                <x-heroicon-o-user class="w-7 h-7 text-red-500 dark:text-red-400" />
                                            @else
                                                <x-heroicon-o-x-circle
                                                    class="w-7 h-7 text-gray-500 dark:text-gray-400" />
                                            @endif
                                        </span>
                                        <span
                                            class="capitalize font-medium
                                            {{ $mesa->estado === 'libre'
                                                ? 'text-green-700 dark:text-green-400'
                                                : ($mesa->estado === 'ocupado'
                                                    ? 'text-red-700 dark:text-red-400'
                                                    : 'text-gray-700 dark:text-gray-400') }}">
                                            {{ $mesa->estado }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-center text-sm text-gray-500 dark:text-gray-400 group-hover:bg-gray-100 dark:group-hover:bg-gray-700">
                                Click para cambiar estado
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div class="max-w-7xl mx-auto">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 text-center">
                    <div class="text-gray-500 dark:text-gray-400 text-lg">
                        No hay zonas disponibles para esta caja.
                    </div>
                </div>
            </div>
    @endif
</div>
</div>
