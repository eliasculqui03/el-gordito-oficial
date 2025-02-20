<div>
    @vite('resources/css/app.css')
    <div class="min-h-screen p-4 ">
        <!-- Barra superior -->
        <div class="p-4 mb-4 bg-white border shadow-sm dark:bg-gray-800 rounded-xl dark:border-gray-700 ">
            <div class="flex flex-col space-y-6 lg:space-y-0 lg:grid lg:grid-cols-3 lg:gap-6 ">
                <!-- Campo DNI -->
                <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                    <div class="flex w-full">
                        <input wire:model="numero_documento" id="numero_documento" type="text"
                            placeholder="Ingrese N° de documento"
                            class="w-full px-4 py-2 border border-gray-300 sm:w-64 text-sm/6 rounded-l-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        <button wire:click="buscar"
                            class="flex items-center px-4 py-2 text-white bg-blue-600 rounded-r-md hover:bg-blue-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:ring-offset-1 dark:bg-blue-500 dark:hover:bg-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Buscar</span>
                        </button>
                    </div>
                    <div class="flex justify-center w-full sm:w-auto sm:justify-start">
                        <livewire:crear-cliente>

                    </div>
                </div>

                <!-- Campo Nombre -->
                <div class="flex flex-col items-start justify-end col-span-2 gap-4 sm:flex-row sm:items-center">
                    <input type="hidden" wire:model="id_cliente">
                    <label for="first-name"
                        class="font-medium text-gray-900 whitespace-nowrap text-sm/6 dark:text-white">Cliente: </label>
                    <input type="text"
                        class="w-full px-4 py-2 text-sm bg-gray-100 border border-gray-300 rounded-md sm:w-96 ring-1 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                        value="{{ $nombre }}" disabled placeholder="Nombre del cliente" />
                </div>
            </div>
        </div>

        <!-- Contenedor principal de 2 columnas -->
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-12">
            <!-- Columna izquierda (Platos y Existencias) -->
            <div class="lg:col-span-8">

                <!-- Sección Platos -->
                <div class="p-4 mb-4 bg-white border shadow-sm dark:bg-gray-800 dark:border-gray-700 rounded-xl">
                    <h3 class="mb-4 text-lg font-semibold text-gray-700 dark:text-gray-300">Platos y bebidas</h3>

                    <!-- Menú de categorías (superior) -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        <button wire:click="$set('categoria_plato_id', '')"
                            class="px-4 py-2 text-sm/6 transition-colors rounded-lg
            {{ !$categoria_plato_id ? 'bg-primary-100 text-primary-700 dark:bg-primary-700 dark:text-primary-100' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            TODOS
                        </button>
                        @foreach ($categorias_platos as $categoria)
                            <button wire:click="$set('categoria_plato_id', '{{ $categoria->id }}')"
                                class="px-4 py-2 text-sm/6 transition-colors rounded-lg
                {{ $categoria_plato_id == $categoria->id ? 'bg-primary-100 text-primary-700 dark:bg-primary-700 dark:text-primary-100' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                {{ $categoria->nombre }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Lista de platos -->
                    <div class="overflow-y-auto" style="height: 300px">
                        <div class="grid gap-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                            @foreach ($platos as $plato)
                                <button wire:click="agregarPlato({{ $plato->id }})"
                                    class="p-3 text-left transition-colors rounded-lg text-sm/6 bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600">
                                    <p class="font-medium dark:text-gray-200">{{ $plato->nombre }}</p>
                                    <p class="text-gray-600 text-sm/6 dark:text-gray-400">
                                        S/ {{ number_format($plato->precio, 2) }}
                                    </p>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sección Existencias -->
                <div class="p-4 bg-white border shadow-sm dark:border-gray-700 dark:bg-gray-800 rounded-xl">
                    <h3 class="mb-4 text-lg font-semibold text-gray-700 dark:text-gray-300">Existencias</h3>
                    <div class="flex flex-col gap-4">
                        <!-- Menú de tipos y categorías (arriba) -->
                        <div class="flex gap-4">
                            <!-- Tipos de Existencia -->
                            <!-- Tipos de Existencia -->
                            <div class="w-1/2">
                                <h4 class="mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">Tipos</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($tipos_existencia as $tipo)
                                        <button wire:click="$set('tipo_existencia_id', '{{ $tipo->id }}')"
                                            class="px-4 py-2 text-sm/6 transition-colors rounded-lg
                {{ $tipo_existencia_id == $tipo->id ? 'bg-primary-100 text-primary-700 dark:bg-primary-700 dark:text-primary-100' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                            {{ $tipo->nombre }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Categorías de Existencia -->
                            <div class="w-1/2">
                                <h4 class="mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">Categorías</h4>
                                <div class="flex flex-wrap gap-2">
                                    <button wire:click="$set('categoria_existencia_id', '')"
                                        class="px-4 py-2 text-sm/6 transition-colors rounded-lg
                        {{ !$categoria_existencia_id ? 'bg-primary-100 text-primary-700 dark:bg-primary-700 dark:text-primary-100' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                        TODOS
                                    </button>
                                    @foreach ($categorias_existencias as $categoria)
                                        <button wire:click="$set('categoria_existencia_id', '{{ $categoria->id }}')"
                                            class="px-4 py-2 text-sm/6 transition-colors rounded-lg
                            {{ $categoria_existencia_id == $categoria->id ? 'bg-primary-100 text-primary-700 dark:bg-primary-700 dark:text-primary-100' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                            {{ $categoria->nombre }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Lista de existencias (abajo) -->
                        <div class="overflow-y-auto" style="height: 300px">
                            <div class="grid gap-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                                @foreach ($existencias as $existencia)
                                    <button wire:click="agregarExistencia({{ $existencia->id }})"
                                        class="p-3 text-left transition-colors rounded-lg bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600">
                                        <p class="font-medium text-sm/6 dark:text-gray-200">{{ $existencia->nombre }}
                                        </p>
                                        <p class="mb-1 text-xs text-gray-500 dark:text-gray-400"><?php echo $existencia->unidadMedida->nombre; ?></p>
                                        <p class="text-gray-600 text-sm/6 dark:text-gray-400">
                                            S/ {{ number_format($existencia->precio_venta, 2) }}
                                        </p>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>



            </div>

            <!-- Columna derecha (Comanda) -->
            <div class="lg:col-span-4">

                <div class="p-4 mb-4 bg-white border shadow-sm dark:border-gray-700 top-4 dark:bg-gray-800 rounded-xl">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <!-- Input de Mesa -->
                        <div class="flex items-center gap-2">
                            <label for="mesaSeleccionada" class="text-gray-700 dark:text-gray-200">Nro. Mesa:</label>
                            <input type="text" id="mesaSeleccionada" wire:model="mesaSeleccionada" readonly disabled
                                class="w-20 px-3 py-1 text-lg font-semibold text-gray-800 bg-gray-100 border border-gray-300 rounded-lg ring-1 dark:text-gray-200 dark:bg-gray-700 dark:border-gray-600">
                            <input type="hidden" wire:model="mesaSeleccionadaId">
                        </div>

                        <button wire:click="openModalMesa"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-600">
                            Cambiar Mesa
                        </button>
                        <!-- Modal de Selección de Mesa -->
                        @if ($isOpen)
                            <div
                                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 dark:bg-gray-950/75">
                                <div
                                    class="relative w-full max-w-4xl p-4 mx-2 bg-white shadow-lg rounded-xl dark:bg-gray-800 dark:ring-1 dark:ring-white/10 sm:mx-0">
                                    <!-- Cabecera del Modal -->
                                    <div class="flex items-center justify-between pb-4 border-b dark:border-gray-700">
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Seleccionar Mesa
                                        </h2>
                                        <button wire:click="closeModalMesa"
                                            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                            <x-heroicon-o-x-mark class="w-5 h-5" />
                                        </button>
                                    </div>

                                    <div class="mt-4">
                                        <!-- Zonas -->
                                        <div class="mb-6" wire:poll.2s>
                                            <h3 class="mb-3 text-sm font-medium text-gray-500 dark:text-gray-400">Zonas
                                            </h3>
                                            <div class="flex flex-wrap gap-2 ">
                                                @foreach ($zonas as $zona)
                                                    <button wire:click="seleccionarZona({{ $zona->id }})"
                                                        class="px-4 py-2 text-sm transition-colors rounded-lg
                                {{ $zonaSeleccionada == $zona->id ? 'bg-primary-100 text-primary-700 dark:bg-primary-700 dark:text-primary-100' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                                        {{ $zona->nombre }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Mesas -->
                                        <div class="mt-4" wire:poll.2s>
                                            @if ($zonaSeleccionada)
                                                <div
                                                    class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                                                    @foreach ($this->mesasZona as $mesa)
                                                        <div wire:key="mesa-{{ $mesa->id }}"
                                                            @if ($mesa->estado === 'Libre') wire:click="seleccionarMesa({{ $mesa }})"
                                        class="overflow-hidden transition-all duration-200 bg-white shadow-sm cursor-pointer dark:bg-gray-800 rounded-xl hover:shadow-md group {{ $mesaSeleccionadaId == $mesa->id ? 'ring-2 ring-primary-500 dark:ring-primary-400' : '' }}"
                                    @else
                                        class="overflow-hidden transition-all duration-200 bg-white shadow-sm opacity-75 cursor-not-allowed dark:bg-gray-800 rounded-xl group" @endif>
                                                            <div class="p-4">
                                                                <div class="flex flex-col items-center">
                                                                    <span
                                                                        class="mb-3 text-xl font-bold text-gray-800 dark:text-white">
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
                                                                                <x-heroicon-o-user
                                                                                    class="w-5 h-5 text-red-500 dark:text-red-400" />
                                                                            @else
                                                                                <x-heroicon-o-x-circle
                                                                                    class="w-5 h-5 text-gray-500 dark:text-gray-400" />
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
                                                                @if ($mesa->estado === 'Libre')
                                                                    Click para seleccionar mesa
                                                                @else
                                                                    Mesa no disponible
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div
                                                    class="flex items-center justify-center h-32 text-gray-500 dark:text-gray-400">
                                                    Selecciona una zona
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex justify-end mt-6">
                                        <button wire:click="closeModalMesa"
                                            class="px-4 py-2 text-sm font-medium text-white border border-transparent rounded-lg shadow-sm bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-primary-700 dark:hover:bg-primary-800 dark:focus:ring-primary-600 dark:focus:ring-offset-gray-800">
                                            Cerrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <div
                    class="sticky p-4 bg-white border shadow-sm dark:border-gray-700 top-4 dark:bg-gray-800 rounded-xl">
                    <h3 class="mb-4 text-lg font-semibold text-gray-700 dark:text-gray-300">Comanda</h3>

                    <!-- Items seleccionados -->
                    <div class="mb-4 overflow-y-auto" style="height: calc(100vh - 200px)">
                        @if (count($itemsPlatos) > 0)
                            <div class="mb-6">
                                <h4 class="mb-3 text-sm font-medium text-gray-500 dark:text-gray-400">PLATOS</h4>
                                <div class="space-y-3">
                                    @foreach ($itemsPlatos as $index => $item)
                                        <div
                                            class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                                            <div class="flex-1">
                                                <h5 class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $item['nombre'] }}</h5>
                                                <div class="flex items-center gap-2">
                                                    <div class="flex items-center">
                                                        <button wire:click="decrementarPlato({{ $index }})"
                                                            class="flex items-center justify-center w-8 h-8 text-gray-500 transition-colors border rounded-l-lg hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M20 12H4" />
                                                            </svg>
                                                        </button>
                                                        <span
                                                            class="flex items-center justify-center w-12 h-8 text-sm border-t border-b dark:border-gray-600 dark:text-gray-200">
                                                            {{ $item['cantidad'] }}
                                                        </span>
                                                        <button wire:click="incrementarPlato({{ $index }})"
                                                            class="flex items-center justify-center w-8 h-8 text-gray-500 transition-colors border rounded-r-lg hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M12 4v16m8-8H4" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                                        x S/ {{ number_format($item['precio'], 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                    S/ {{ number_format($item['subtotal'], 2) }}
                                                </span>
                                                <button wire:click="eliminarPlato({{ $index }})"
                                                    class="text-red-500 hover:text-red-700">
                                                    <x-heroicon-o-trash class="w-5 h-5" />
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if (count($itemsExistencias) > 0)
                            <div class="mb-6">
                                <h4 class="mb-3 text-sm font-medium text-gray-500 dark:text-gray-400">EXISTENCIAS</h4>
                                <div class="space-y-3">
                                    @foreach ($itemsExistencias as $index => $item)
                                        <div
                                            class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                                            <div class="flex-1">
                                                <h5 class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $item['nombre'] }}</h5>
                                                <div class="flex items-center gap-2">
                                                    <div class="flex items-center">
                                                        <button
                                                            wire:click="decrementarExistencia({{ $index }})"
                                                            class="flex items-center justify-center w-8 h-8 text-gray-500 transition-colors border rounded-l-lg hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M20 12H4" />
                                                            </svg>
                                                        </button>
                                                        <span
                                                            class="flex items-center justify-center w-12 h-8 text-sm border-t border-b dark:border-gray-600 dark:text-gray-200">
                                                            {{ $item['cantidad'] }}
                                                        </span>
                                                        <button
                                                            wire:click="incrementarExistencia({{ $index }})"
                                                            class="flex items-center justify-center w-8 h-8 text-gray-500 transition-colors border rounded-r-lg hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M12 4v16m8-8H4" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                                        x S/ {{ number_format($item['precio'], 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                    S/ {{ number_format($item['subtotal'], 2) }}
                                                </span>
                                                <button wire:click="eliminarExistencia({{ $index }})"
                                                    class="text-red-500 hover:text-red-700">
                                                    <x-heroicon-o-trash class="w-5 h-5" />
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Total y botones -->
                    <div class="pt-4 mt-auto border-t dark:border-gray-700">
                        <div class="mb-4 text-xl font-bold text-gray-700 dark:text-gray-300">
                            Total: S/ {{ number_format($total, 2) }}
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <button wire:click="limpiarComanda"
                                class="w-full px-4 py-2 text-gray-700 bg-gray-200 rounded-lg text-sm/6 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                Limpiar
                            </button>
                            <button wire:click="guardarComanda"
                                class="w-full px-4 py-2 text-white bg-blue-600 rounded-lg text-sm/6 hover:bg-blue-700">
                                Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <!-- Barra inferior -->
        <div class="p-4 mt-4 bg-white border shadow-sm dark:border-gray-700 dark:bg-gray-800 rounded-xl">
            <div class="text-gray-600 dark:text-gray-400">
                Info adicional...
            </div>
        </div>
    </div>



</div>
