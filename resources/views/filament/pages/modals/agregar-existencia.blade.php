<div class="space-y-4">
    <div class="grid grid-cols-1 gap-4">
        @foreach ($existencias as $existencia)
            <div class="p-4 border rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-medium">{{ $existencia->nombre }}</h3>
                        <p class="text-sm text-gray-500">S/ {{ number_format($existencia->precio_venta, 2) }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="number" wire:model="cantidadExistencia" min="1"
                            class="w-20 border-gray-300 rounded-lg shadow-sm" />
                        <button wire:click="agregarExistencia({{ $existencia->id }}, cantidadExistencia)"
                            class="px-4 py-2 text-white rounded-lg bg-primary-600 hover:bg-primary-700">
                            Agregar
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
