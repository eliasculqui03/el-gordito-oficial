<div>
    @vite('resources/css/app.css')

    <button wire:click="abrirModalComandas"
        class="flex flex-col items-center justify-center w-full px-2 py-3 text-sm font-medium text-white transition bg-blue-600 rounded-md hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-1" viewBox="0 0 20 20" fill="currentColor">
            <path
                d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
        </svg>
        <span class="text-xs sm:text-sm">Comandas</span>
    </button>

    <!-- Modal de Lista de Comandas -->
    @if ($modalComandas)
        <div
            class="fixed inset-0 z-40 flex items-center justify-center overflow-auto bg-gray-900 bg-opacity-60 dark:bg-gray-900 dark:bg-opacity-80">
            <div class="relative w-full mx-auto bg-white rounded-lg shadow-xl dark:bg-gray-800 max-w-7xl">
                <!-- Cabecera del modal -->
                <div
                    class="px-4 py-4 border-b border-gray-200 rounded-t-lg bg-gradient-to-r from-blue-500 to-blue-700 dark:from-blue-700 dark:to-blue-900 dark:border-gray-700 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <h3 class="text-base font-semibold text-white sm:text-xl">
                                Gestión de Comandas
                            </h3>
                            @if ($caja)
                                <span
                                    class="px-2 py-1 ml-3 text-xs font-medium text-white bg-blue-800 rounded-full dark:bg-blue-900">
                                    Caja: {{ $caja->nombre }}
                                </span>
                            @endif
                        </div>
                        <button
                            class="p-1 text-white rounded-full hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/50"
                            wire:click="cerrarModalComandas">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Contenido del modal - Lista de Comandas -->
                <div class="p-4 overflow-y-auto sm:p-6">
                    <!-- Filtros y búsqueda -->
                    <div
                        class="flex flex-col mb-4 space-y-3 md:flex-row md:items-center md:space-y-0 md:space-x-4 sm:mb-6">
                        <!-- Búsqueda de cliente -->
                        <div class="flex flex-col space-y-2 md:flex-row md:items-center md:space-y-0 md:space-x-2">
                            <select wire:model.live="searchBy"
                                class="border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:focus:ring-blue-700">
                                <option value="nombre">Nombre</option>
                                <option value="numero_documento">Nº Documento</option>
                            </select>
                            <div class="relative flex-grow">
                                <input type="text" wire:model.live.debounce.300ms="searchTerm"
                                    placeholder="Buscar cliente..."
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:placeholder-gray-400 dark:focus:ring-blue-700">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5 text-gray-400 dark:text-gray-500" viewBox="0 0 20 20"
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
                            <label for="filtroEstado"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300">Estado:</label>
                            <select id="filtroEstado" wire:model.live="filtroEstado"
                                class="border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:focus:ring-blue-700">
                                <option value="">Todos</option>
                                @foreach ($estados as $estado)
                                    <option value="{{ $estado }}">{{ $estado }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tabla de comandas - Optimizada para móvil -->
                    <div class="overflow-x-auto min-h-[450px]" style="max-height: 450px;">
                        <div class="inline-block min-w-full align-middle">
                            <div
                                class="overflow-hidden border border-gray-200 rounded-lg shadow-sm dark:border-gray-700">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col"
                                                class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400 sm:px-6">
                                                ID</th>
                                            <th scope="col"
                                                class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400 sm:px-6">
                                                Cliente</th>
                                            <th scope="col"
                                                class="hidden px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400 sm:table-cell sm:px-6">
                                                Mesa/Zona</th>
                                            <th scope="col"
                                                class="px-3 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:px-6">
                                                Total</th>
                                            <th scope="col"
                                                class="hidden px-3 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:table-cell sm:px-6">
                                                Estado</th>
                                            <th scope="col"
                                                class="hidden px-3 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:table-cell sm:px-6">
                                                Estado Pago</th>
                                            <th scope="col"
                                                class="hidden px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400 md:table-cell md:px-6">
                                                Fecha</th>
                                            <th scope="col"
                                                class="px-3 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:px-6">
                                                Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                        @forelse($comandas as $comanda)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td
                                                    class="px-3 py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-gray-200 sm:px-6">
                                                    #{{ $comanda->id }}
                                                </td>
                                                <td
                                                    class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400 sm:px-6">
                                                    {{ $comanda->cliente ? $comanda->cliente->nombre : 'Cliente no registrado' }}
                                                    @if ($comanda->cliente)
                                                        <br><span
                                                            class="text-xs text-gray-400 dark:text-gray-500">{{ $comanda->cliente->numero_documento }}</span>
                                                    @endif

                                                    <!-- Info móvil: Mesa/Zona -->
                                                    <div class="mt-1 sm:hidden">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            Mesa: {{ $comanda->mesa->numero ?? 'N/A' }} |
                                                            Zona: {{ $comanda->zona->nombre ?? 'N/A' }}
                                                        </span>
                                                    </div>

                                                    <!-- Info móvil: Estados -->
                                                    <div class="flex flex-wrap gap-1 mt-1 sm:hidden">
                                                        <span
                                                            class="px-1.5 py-0.5 text-xs font-semibold rounded-full
                                                        {{ $comanda->estado === 'Abierta' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                                        {{ $comanda->estado === 'Procesando' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                                        {{ $comanda->estado === 'Completada' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                                        {{ $comanda->estado === 'Cancelada' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                                            {{ $comanda->estado }}
                                                        </span>
                                                        <span
                                                            class="px-1.5 py-0.5 text-xs font-semibold rounded-full
                                                        {{ $comanda->estado_pago === 'Pendiente' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                                            {{ $comanda->estado_pago }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td
                                                    class="hidden px-3 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400 sm:table-cell sm:px-6">
                                                    Mesa: {{ $comanda->mesa->numero ?? 'N/A' }}
                                                    <br>
                                                    Zona: {{ $comanda->zona->nombre ?? 'N/A' }}
                                                </td>
                                                <td
                                                    class="px-3 py-4 text-sm text-center text-gray-500 whitespace-nowrap dark:text-gray-400 sm:px-6">
                                                    <span class="font-medium text-gray-800 dark:text-gray-200">S/
                                                        {{ number_format($comanda->total_general, 2) }}</span>
                                                </td>
                                                <td
                                                    class="hidden px-3 py-4 text-center whitespace-nowrap sm:table-cell sm:px-6">
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $comanda->estado === 'Abierta' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                                    {{ $comanda->estado === 'Procesando' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                                    {{ $comanda->estado === 'Completada' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                                    {{ $comanda->estado === 'Cancelada' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                                        {{ $comanda->estado }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="hidden px-3 py-4 text-center whitespace-nowrap sm:table-cell sm:px-6">
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $comanda->estado_pago === 'Pendiente' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                                        {{ $comanda->estado_pago }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="hidden px-3 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400 md:table-cell md:px-6">
                                                    {{ $comanda->created_at->format('d/m/Y H:i') }}
                                                </td>
                                                <td
                                                    class="px-3 py-4 text-sm font-medium text-center whitespace-nowrap sm:px-6">
                                                    <div
                                                        class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-center sm:space-x-2">
                                                        <!-- Botón de editar (solo disponible para comandas en estado "Abierta") -->
                                                        @if ($comanda->estado === 'Abierta')
                                                            <button wire:click="editarComanda({{ $comanda->id }})"
                                                                class="flex items-center justify-center w-full px-2 py-1 text-xs text-white bg-blue-500 rounded hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 sm:w-auto sm:px-3">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="w-3 h-3 mr-1 sm:w-4 sm:h-4" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                                <span class="hidden sm:inline">Editar</span>
                                                            </button>
                                                        @endif

                                                        <!-- Botón de generar comprobante (solo disponible cuando estado_pago es "Pendiente") -->
                                                        @if ($comanda->estado_pago === 'Pendiente')
                                                            <button
                                                                wire:click="generarComprobante({{ $comanda->id }})"
                                                                class="flex items-center justify-center w-full px-2 py-1 text-xs text-white bg-blue-600 rounded hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 sm:w-auto sm:px-3">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="w-3 h-3 mr-1 sm:w-4 sm:h-4" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                </svg>
                                                                <span class="hidden sm:inline">CPE</span>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8"
                                                    class="px-3 py-4 text-sm text-center text-gray-500 dark:text-gray-400 sm:px-6">
                                                    No se encontraron comandas para esta caja
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-4">
                        {{ $comandas->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de Edición de Comanda -->
    @if ($modalEditar)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-gray-900 bg-opacity-60 dark:bg-gray-900 dark:bg-opacity-80">
            <div class="relative w-full mx-auto bg-white rounded-lg shadow-xl dark:bg-gray-800 max-w-7xl">
                <!-- Cabecera del modal -->
                <div
                    class="px-4 py-4 border-b border-gray-200 rounded-t-lg bg-gradient-to-r from-blue-600 to-blue-800 dark:from-blue-700 dark:to-blue-900 dark:border-gray-700 sm:px-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-white sm:text-xl">
                            Editar Comanda #{{ $comandaId }}
                        </h3>
                        <button
                            class="p-1 text-white rounded-full hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/50"
                            wire:click="cerrarModalEditar">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Contenido del modal - Editar Comanda -->
                <div class="p-0 overflow-y-auto" style="max-height: 80vh;">
                    <livewire:comanda.editar-comanda :comanda-id="$comandaId" :key="'editar-comanda-' . $comandaId" />
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de Generación de Comprobante -->
    @if ($modalComprobante)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-gray-900 bg-opacity-60 dark:bg-gray-900 dark:bg-opacity-80">
            <div class="relative w-full mx-auto bg-white rounded-lg shadow-xl dark:bg-gray-800 max-w-7xl">
                <!-- Cabecera del modal -->
                <div
                    class="px-4 py-4 border-b border-gray-200 rounded-t-lg bg-gradient-to-r from-blue-600 to-blue-800 dark:from-blue-700 dark:to-blue-900 dark:border-gray-700 sm:px-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-white sm:text-xl">
                            Generar Comprobante - Comanda #{{ $comandaId }}
                        </h3>
                        <button
                            class="p-1 text-white rounded-full hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/50"
                            wire:click="cerrarModalComprobante">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Contenido del modal - Generar Comprobante -->
                <div class="p-0 overflow-y-auto" style="max-height: 80vh;">
                    <livewire:comanda.generar-comprobante :comanda-id="$comandaId" :key="'generar-comprobante-' . $comandaId" />
                </div>
            </div>
        </div>
    @endif

    <!-- Notificaciones -->
    <div x-data="{ show: false, message: '', type: 'success' }"
        x-on:notify.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => { show = false }, 3000)"
        x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        class="fixed z-50 px-4 py-2 rounded-lg shadow-lg bottom-4 right-4"
        :class="{ 'bg-blue-500 text-white dark:bg-blue-600': type === 'success', 'bg-red-500 text-white dark:bg-red-600': type === 'error' }"
        style="display: none;">
        <span x-text="message"></span>
    </div>
</div>
