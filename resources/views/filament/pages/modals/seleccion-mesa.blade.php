<div class="flex gap-4">
    @vite('resources/css/app.css')
    <!-- Menú Lateral -->
    <div class="w-64 bg-white rounded-lg shadow-sm">
        <!-- Cajas -->
        <div class="p-4 border-b">
            <h3 class="mb-3 text-sm font-medium text-gray-500 uppercase">Cajas</h3>
            @foreach ($cajas as $caja)
                <div wire:click="selectCaja({{ $caja->id }})"
                    class="mb-2 p-2 rounded cursor-pointer hover:bg-gray-50 {{ $selectedCajaId == $caja->id ? 'bg-primary-100 text-primary-700' : '' }}">
                    {{ $caja->nombre }}
                </div>
            @endforeach
        </div>

        <!-- Zonas -->
        @if ($selectedCajaId)
            <div class="p-4">
                <h3 class="mb-3 text-sm font-medium text-gray-500 uppercase">Zonas</h3>
                @foreach ($zonas as $zona)
                    <div wire:click="selectZona({{ $zona->id }})"
                        class="mb-2 p-2 rounded cursor-pointer hover:bg-gray-50 {{ $selectedZonaId == $zona->id ? 'bg-primary-100 text-primary-700' : '' }}">
                        {{ $zona->nombre }}
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Contenido Principal (Mesas) -->
    <div class="flex-1">
        @if ($selectedZonaId)
            <div class="grid grid-cols-6 gap-4">
                @foreach ($mesas as $mesa)
                    <button wire:click="selectMesa({{ $mesa->id }})"
                        class="p-4 rounded-lg border {{ $mesa->estado ? 'hover:bg-green-50' : 'bg-red-50' }} {{ $selectedMesaId == $mesa->id ? 'ring-2 ring-primary-500' : '' }}">
                        <div class="text-center">
                            <p class="font-medium">Mesa {{ $mesa->numero }}</p>
                            <span
                                class="text-xs mt-1 px-2 py-1 rounded-full {{ $mesa->estado ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $mesa->estado ? 'Disponible' : 'Ocupada' }}
                            </span>
                        </div>
                    </button>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center text-gray-500">
                Selecciona una zona para ver las mesas
            </div>
        @endif
    </div>
</div>
