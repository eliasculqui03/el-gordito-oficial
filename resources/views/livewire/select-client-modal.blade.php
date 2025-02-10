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



























<div>
    @vite('resources/css/app.css')
    <div class="min-h-screen p-4 bg-gray-50 dark:bg-gray-900">
        <!-- Barra superior -->
        <div class="p-4 mb-4 bg-white shadow-sm dark:bg-gray-800 rounded-xl">
            <h2 class="text-xl font-bold text-gray-700 dark:text-gray-300">Nueva Comanda</h2>

        </div>

        <!-- Contenedor principal de 2 columnas -->
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-12">
            <!-- Columna izquierda (Platos y Existencias) -->
            <div class="lg:col-span-8">


                <!-- Sección Platos -->
                <div class="p-4 mb-4 bg-white shadow-sm dark:bg-gray-800 rounded-xl">


                    <div class="flex gap-4">
                        <!-- Menú de categorías (izquierda) -->
                        <div class="w-48 border-r shrink-0 dark:border-gray-700">
                            <button wire:click="$set('categoria_plato_id', '')"
                                class="w-full px-4 py-2 mb-1 text-left transition-colors rounded-lg
                {{ !$categoria_plato_id ? 'bg-primary-100 text-primary-700 dark:bg-primary-700 dark:text-primary-100' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                Todos los platos
                            </button>

                            @foreach ($categorias_platos as $categoria)
                                <button wire:click="$set('categoria_plato_id', '{{ $categoria->id }}')"
                                    class="w-full px-4 py-2 mb-1 text-left transition-colors rounded-lg
                    {{ $categoria_plato_id == $categoria->id ? 'bg-primary-100 text-primary-700 dark:bg-primary-700 dark:text-primary-100' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    {{ $categoria->nombre }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Lista de platos (derecha) -->
                        <div class="flex-1 overflow-y-auto" style="height: 400px">
                            <div class="grid gap-2 sm:grid-cols-2">
                                @foreach ($platos as $plato)
                                    <button wire:click="agregarPlato({{ $plato->id }})"
                                        class="w-full p-3 text-left transition-colors rounded-lg bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600">
                                        <p class="font-medium dark:text-gray-200">{{ $plato->nombre }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">S/
                                            {{ number_format($plato->precio, 2) }}</p>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Sección Existencias -->
                <div class="p-4 bg-white shadow-sm dark:bg-gray-800 rounded-xl">
                    <h3 class="mb-4 text-lg font-semibold text-gray-700 dark:text-gray-300">Existencias</h3>

                    <div class="flex gap-4">
                        <!-- Menú de tipos y categorías (izquierda) -->
                        <div class="w-48 border-r shrink-0 dark:border-gray-700">
                            <!-- Tipos de Existencia -->
                            <div class="mb-4">
                                <h4 class="mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">Tipos</h4>
                                <button wire:click="$set('tipo_existencia_id', '')"
                                    class="w-full px-4 py-2 mb-1 text-left transition-colors rounded-lg
                    {{ !$tipo_existencia_id ? 'bg-primary-100 text-primary-700 dark:bg-primary-700 dark:text-primary-100' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    Todos los tipos
                                </button>
                                @foreach ($tipos_existencia as $tipo)
                                    <button wire:click="$set('tipo_existencia_id', '{{ $tipo->id }}')"
                                        class="w-full px-4 py-2 mb-1 text-left transition-colors rounded-lg
                        {{ $tipo_existencia_id == $tipo->id ? 'bg-primary-100 text-primary-700 dark:bg-primary-700 dark:text-primary-100' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        {{ $tipo->nombre }}
                                    </button>
                                @endforeach
                            </div>

                            <!-- Categorías de Existencia -->
                            <div>
                                <h4 class="mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">Categorías</h4>
                                <button wire:click="$set('categoria_existencia_id', '')"
                                    class="w-full px-4 py-2 mb-1 text-left transition-colors rounded-lg
                    {{ !$categoria_existencia_id ? 'bg-primary-100 text-primary-700 dark:bg-primary-700 dark:text-primary-100' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    Todas las categorías
                                </button>
                                @foreach ($categorias_existencias as $categoria)
                                    <button wire:click="$set('categoria_existencia_id', '{{ $categoria->id }}')"
                                        class="w-full px-4 py-2 mb-1 text-left transition-colors rounded-lg
                        {{ $categoria_existencia_id == $categoria->id ? 'bg-primary-100 text-primary-700 dark:bg-primary-700 dark:text-primary-100' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        {{ $categoria->nombre }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Lista de existencias (derecha) -->
                        <div class="flex-1 overflow-y-auto" style="height: 400px">
                            <div class="grid gap-2 sm:grid-cols-2">
                                @foreach ($existencias as $existencia)
                                    <button wire:click="agregarExistencia({{ $existencia->id }})"
                                        class="w-full p-3 text-left transition-colors rounded-lg bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600">
                                        <p class="font-medium dark:text-gray-200">{{ $existencia->nombre }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">S/
                                            {{ number_format($existencia->precio, 2) }}</p>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha (Comanda) -->
                <div class="lg:col-span-4">
                    <div class="sticky p-4 bg-white shadow-sm top-4 dark:bg-gray-800 rounded-xl">
                        <h3 class="mb-4 text-lg font-semibold text-gray-700 dark:text-gray-300">Comanda</h3>

                        <!-- Items seleccionados -->
                        <div class="mb-4 overflow-y-auto" style="height: calc(100vh - 300px)">
                            <table class="w-full">
                                <thead class="text-sm text-gray-600 border-b dark:text-gray-400">
                                    <tr>
                                        <th class="pb-2 text-left">Item</th>
                                        <th class="pb-2 text-center">Cant.</th>
                                        <th class="pb-2 text-right">Precio</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($items as $index => $item)
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="py-2">{{ $item['nombre'] }}</td>
                                        <td class="py-2">
                                            <input type="number" wire:model="items.{{ $index }}.cantidad"
                                                class="w-16 text-center border-gray-300 rounded-lg dark:bg-gray-700">
                                        </td>
                                        <td class="py-2 text-right">S/ {{ $item['subtotal'] }}</td>
                                        <td class="py-2">
                                            <button wire:click="eliminarItem({{ $index }})"
                                                class="text-red-500 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach --}}
                                </tbody>
                            </table>
                        </div>

                        <!-- Total y botones -->
                        <div class="pt-4 mt-auto border-t dark:border-gray-700">
                            <div class="mb-4 text-xl font-bold">
                                {{-- Total: S/ {{ $total }} --}}
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <button wire:click="limpiarComanda"
                                    class="w-full px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                                    Limpiar
                                </button>
                                <button wire:click="guardarComanda"
                                    class="w-full px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barra inferior -->
            <div class="p-4 mt-4 bg-white shadow-sm dark:bg-gray-800 rounded-xl">
                <!-- Info adicional o totales -->
                <div class="text-gray-600 dark:text-gray-400">
                    Info adicional...
                </div>
            </div>
        </div>
    </div>
