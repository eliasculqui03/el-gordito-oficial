<div>

    @vite('resources/css/app.css')

    <div>
        <div class="p-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
            <h2 class="mb-4 text-2xl font-semibold">Gestión de Comandas</h2>

            <!-- Filtros y búsqueda -->
            <div class="flex flex-col mb-6 space-y-4 md:flex-row md:items-center md:space-y-0 md:space-x-4">
                <!-- Búsqueda de cliente -->
                <div class="flex flex-col space-y-2 md:flex-row md:items-center md:space-y-0 md:space-x-2">
                    <select wire:model.live="searchBy"
                        class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="nombre">Nombre</option>
                        <option value="numero_documento">Nº Documento</option>
                    </select>
                    <div class="relative flex-grow">
                        <input type="text" wire:model.live.debounce.300ms="searchTerm" placeholder="Buscar cliente..."
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Filtro por estado -->
                <div class="flex items-center space-x-2">
                    <label for="filtroEstado" class="text-sm font-medium text-gray-700">Estado:</label>
                    <select id="filtroEstado" wire:model.live="filtroEstado"
                        class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Todos</option>
                        @foreach ($estados as $estado)
                            <option value="{{ $estado }}">{{ $estado }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Tabla de comandas -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                ID</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Cliente</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Mesa/Zona</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Total</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Estado</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Estado Pago</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Fecha</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($comandas as $comanda)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                    {{ $comanda->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    {{ $comanda->cliente ? $comanda->cliente->nombre : 'Cliente no registrado' }}
                                    @if ($comanda->cliente)
                                        <br><span
                                            class="text-xs text-gray-400">{{ $comanda->cliente->numero_documento }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    Mesa: {{ $comanda->mesa->numero ?? 'N/A' }}
                                    <br>
                                    Zona: {{ $comanda->zona->nombre ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    S/ {{ number_format($comanda->total_general, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $comanda->estado === 'Abierta' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $comanda->estado === 'Procesando' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $comanda->estado === 'Completada' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $comanda->estado === 'Cancelada' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ $comanda->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $comanda->estado_pago === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $comanda->estado_pago }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    {{ $comanda->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <!-- Botón de editar (solo disponible para comandas en estado "Abierta") -->
                                        @if ($comanda->estado === 'Abierta')
                                            <button wire:click="editarComanda({{ $comanda->id }})"
                                                class="flex items-center px-3 py-1 text-xs text-white bg-blue-500 rounded hover:bg-blue-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Editar
                                            </button>
                                        @else
                                            <button disabled
                                                class="flex items-center px-3 py-1 text-xs text-gray-500 bg-gray-300 rounded cursor-not-allowed">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Editar
                                            </button>
                                        @endif

                                        <!-- Botón de generar comprobante -->
                                        <button wire:click="generarComprobante({{ $comanda->id }})"
                                            class="flex items-center px-3 py-1 text-xs text-white bg-green-500 rounded hover:bg-green-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Generar Comprobante
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-sm text-center text-gray-500">
                                    No se encontraron comandas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-4">
                {{ $comandas->links() }}
            </div>
        </div>
    </div>
</div>
