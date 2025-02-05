<div class="space-y-4">
    <div class="grid grid-cols-4 gap-4">
        <!-- Sección de Cajas -->
        <div class="col-span-1 space-y-2">
            <h3 class="text-sm font-medium text-gray-500">Cajas</h3>
            @foreach ($cajas as $caja)
                <button wire:click="cambiarCaja({{ $caja->id }})"
                    class="w-full px-3 py-2 text-left rounded-lg text-sm {{ $cajaActual == $caja->id ? 'bg-primary-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    {{ $caja->nombre }}
                </button>
            @endforeach
        </div>

        <!-- Sección de Zonas -->
        <div class="col-span-1 space-y-2">
            <h3 class="text-sm font-medium text-gray-500">Zonas</h3>
            @if ($zonas)
                @foreach ($zonas as $zona)
                    <button wire:click="cambiarZona({{ $zona->id }})"
                        class="w-full px-3 py-2 text-left rounded-lg text-sm {{ $zonaActual == $zona->id ? 'bg-primary-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        {{ $zona->nombre }}
                    </button>
                @endforeach
            @endif
        </div>

        <!-- Grid de Mesas -->
        <div class="col-span-2">
            @if ($zonaActual && $zonas->count())
                <div class="grid grid-cols-2 gap-4">
                    @foreach ($zonas->firstWhere('id', $zonaActual)->mesas as $mesa)
                        <div wire:click="seleccionarMesa({{ $mesa->id }})"
                            class="p-4 bg-white shadow-sm cursor-pointer rounded-xl hover:shadow-md">
                            <div class="flex flex-col items-center">
                                <span class="text-lg font-bold">Mesa {{ $mesa->numero }}</span>
                                <div
                                    class="flex items-center gap-2 mt-2 px-3 py-1 rounded-lg
                                    {{ $mesa->estado === 'libre' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                    <span>
                                        @if ($mesa->estado === 'libre')
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
                <div class="flex items-center justify-center h-full text-gray-500">
                    Selecciona una zona
                </div>
            @endif
        </div>
    </div>
</div>
