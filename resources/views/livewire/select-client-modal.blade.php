<div>


    @vite('resources/css/app.css')

    <button wire:click="openModal"
        class="px-5 py-2.5 font-semibold text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all">
        Crear
    </button>

    @if ($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="relative w-11/12 max-w-md p-6 bg-white rounded-lg shadow-xl md:max-w-lg">
                <div class="flex items-center justify-between pb-4 border-b">
                    <h2 class="text-xl font-bold text-gray-800">Seleccionar Cliente</h2>
                    <button wire:click="closeModal" class="text-gray-600 hover:text-gray-900 focus:outline-none">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mt-4">
                    <input type="text" placeholder="Buscar cliente..."
                        class="w-full px-4 py-2 transition-all border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        wire:model.debounce.500ms="search">
                </div>

                <ul class="mt-4 overflow-y-auto divide-y divide-gray-200 max-h-60">
                    @forelse ($clients as $client)
                        <li wire:click="selectClient({{ $client->id }})"
                            class="px-4 py-3 text-gray-700 transition-all cursor-pointer hover:bg-gray-100">
                            {{ $client->nombre }}
                        </li>
                    @empty
                        <li class="px-4 py-3 text-gray-500">No se encontraron clientes</li>
                    @endforelse
                </ul>
            </div>
        </div>
    @endif

    @if ($selectedClientId)
        <div class="p-3 mt-4 bg-gray-100 rounded-md shadow-md">
            <span class="font-semibold text-gray-700">Cliente seleccionado:</span>
            <span class="text-gray-900">{{ $selectedClientId }}</span>
        </div>
    @endif
</div>
