<div>
    <form wire:submit.prevent="convertirTicketAElectronico">
        <!-- Datos del ticket -->
        <div class="p-4 mb-6 rounded bg-gray-50">
            <h3 class="mb-2 text-lg font-semibold">Información del Ticket</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p><span class="font-medium">Ticket:</span> {{ $record->serie }}-{{ $record->numero }}</p>
                    <p><span class="font-medium">Fecha:</span> {{ $record->created_at->format('d/m/Y H:i') }}</p>
                    <p><span class="font-medium">Cliente:</span> {{ $record->cliente->nombre }}</p>
                </div>
                <div>
                    <p><span class="font-medium">Total:</span> {{ $record->comanda->total_general ?? 'No disponible' }}
                    </p>
                    <p><span class="font-medium">Medio de pago:</span> {{ $record->medio_pago }}</p>
                </div>
            </div>
        </div>

        <!-- Formulario de conversión -->
        <div class="space-y-4">
            <input type="hidden" wire:model="ticketId" value="{{ $record->id }}" />

            <!-- Tipo de comprobante -->
            <div>
                <label for="tipo_comprobante_id" class="block mb-1 text-sm font-medium text-gray-700">Tipo de
                    Comprobante</label>
                <select id="tipo_comprobante_id" wire:model="tipoComprobanteId"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    required>
                    <option value="">Seleccione un tipo</option>
                    @foreach ($tiposComprobante as $id => $descripcion)
                        <option value="{{ $id }}">{{ $descripcion }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Serie -->
            <div>
                <label for="serie" class="block mb-1 text-sm font-medium text-gray-700">Serie</label>
                <input type="text" id="serie" wire:model="serie"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    required placeholder="Ejemplo: F001 o B001">
            </div>

            <!-- Número -->
            <div>
                <label for="numero" class="block mb-1 text-sm font-medium text-gray-700">Número</label>
                <input type="text" id="numero" wire:model="numero"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    required placeholder="Ejemplo: 00000123">
            </div>


            <!-- Botones de acción -->
            <div class="flex justify-end pt-4 space-x-3">
                <x-filament::button type="button" color="gray" x-on:click="$dispatch('close-modal')">
                    Cancelar
                </x-filament::button>

                <x-filament::button type="submit" color="success">
                    {{ $isLoading ? 'Procesando...' : 'Convertir y Enviar a SUNAT' }}
                </x-filament::button>
            </div>
        </div>
    </form>
</div>
