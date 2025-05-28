<div class="p-2 bg-white dark:bg-gray-800 sm:p-4">
    <form wire:submit.prevent="guardarComanda">
        <!-- Información básica de la comanda -->
        <div class="mb-4">
            <h4 class="mb-3 text-sm font-semibold text-gray-900 dark:text-gray-100 sm:text-base">
                Información Básica
            </h4>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Cliente con búsqueda -->
                <div class="relative sm:col-span-2 lg:col-span-1">
                    <label for="cliente_search" class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                        Cliente
                    </label>
                    <div class="relative">
                        <input type="text" wire:model.live="searchCliente" placeholder="Buscar cliente..."
                            class="block w-full mt-1 text-xs border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:placeholder-gray-400 sm:text-sm">
                        @if ($searchCliente)
                            <button type="button" wire:click="limpiarCliente"
                                class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>

                    @if ($showClientesList && $this->clientesFiltrados->count() > 0)
                        <div
                            class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg dark:bg-gray-700 dark:border-gray-600">
                            @foreach ($this->clientesFiltrados as $cliente)
                                <div wire:click="seleccionarCliente({{ $cliente->id }}, '{{ $cliente->nombre }}')"
                                    class="px-3 py-2 text-xs cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 sm:text-sm">
                                    <div class="font-medium">{{ $cliente->nombre }}</div>
                                    <div class="text-xs text-gray-500">{{ $cliente->numero_documento }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Zona -->
                <div>
                    <label for="zona_id" class="block text-xs font-medium text-gray-700 dark:text-gray-300 sm:text-sm">
                        Zona <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="zona_id" wire:change="updatedZonaId" id="zona_id" required
                        class="block w-full mt-1 text-xs border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 sm:text-sm">
                        <option value="">Seleccionar zona</option>
                        @foreach ($zonas as $zona)
                            <option value="{{ $zona->id }}">{{ $zona->nombre }}</option>
                        @endforeach
                    </select>
                    @error('zona_id')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Mesa -->
                <div>
                    <label for="mesa_id" class="block text-xs font-medium text-gray-700 dark:text-gray-300 sm:text-sm">
                        Mesa <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="mesa_id" id="mesa_id" required
                        class="block w-full mt-1 text-xs border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 sm:text-sm">
                        <option value="">Seleccionar mesa</option>
                        @foreach ($mesas as $mesa)
                            <option value="{{ $mesa->id }}">
                                Mesa {{ $mesa->numero }}
                                @if ($mesa->estado)
                                    - {{ ucfirst($mesa->estado) }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('mesa_id')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label for="estado" class="block text-xs font-medium text-gray-700 dark:text-gray-300 sm:text-sm">
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="estado" id="estado" required
                        class="block w-full mt-1 text-xs border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 sm:text-sm">
                        <option value="Abierta">Abierta</option>
                        <option value="Procesando">Procesando</option>
                        <option value="Completada">Completada</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                    @error('estado')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Productos/Existencias -->
        <div class="mb-4">
            <div
                class="flex flex-col items-start justify-between mb-3 space-y-2 sm:flex-row sm:items-center sm:space-y-0">
                <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 sm:text-base">
                    Productos/Bebidas
                </h4>
                <button type="button" wire:click="$set('showAgregarProducto', true)"
                    class="w-full px-3 py-2 text-xs text-white bg-blue-500 rounded hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 sm:w-auto sm:text-sm">
                    + Agregar Producto
                </button>
            </div>

            <!-- Formulario para agregar producto -->
            @if ($showAgregarProducto)
                <div
                    class="p-3 mb-3 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                    <div class="space-y-3 sm:grid sm:grid-cols-2 sm:gap-3 sm:space-y-0 lg:grid-cols-4">
                        <!-- Producto con búsqueda -->
                        <div class="relative sm:col-span-2 lg:col-span-1">
                            <label
                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 sm:text-sm">Producto</label>
                            <div class="relative">
                                <input type="text" wire:model.live="searchProducto" placeholder="Buscar producto..."
                                    class="block w-full mt-1 text-xs border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-200 dark:placeholder-gray-400 sm:text-sm">
                                @if ($searchProducto)
                                    <button type="button" wire:click="limpiarProducto"
                                        class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>

                            @if ($showProductosList && $this->productosFiltrados->count() > 0)
                                <div
                                    class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg dark:bg-gray-700 dark:border-gray-600">
                                    @foreach ($this->productosFiltrados as $producto)
                                        <div wire:click="seleccionarProducto({{ $producto->id }}, '{{ $producto->nombre }}')"
                                            class="px-3 py-2 text-xs cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 sm:text-sm">
                                            <div class="font-medium">{{ $producto->nombre }}</div>
                                            <div class="text-xs text-gray-500">
                                                S/ {{ number_format($producto->precio_venta, 2) }} - Stock:
                                                {{ $producto->inventario->stock ?? 0 }}
                                                @if ($producto->inventario && $producto->inventario->almacen)
                                                    - {{ $producto->inventario->almacen->nombre }}
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @error('nuevoProductoId')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 sm:text-sm">Cantidad</label>
                            <input type="number" wire:model="nuevoProductoCantidad" min="1"
                                class="block w-full mt-1 text-xs border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-200 sm:text-sm">
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="nuevoProductoHelado" id="nuevoProductoHelado"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="nuevoProductoHelado"
                                class="ml-2 text-xs font-medium text-gray-700 dark:text-gray-300 sm:text-sm">
                                Helado
                            </label>
                        </div>
                        <div class="flex gap-2 sm:col-span-2 lg:col-span-1">
                            <button type="button" wire:click="agregarProducto"
                                class="flex-1 px-3 py-2 text-xs text-white bg-green-500 rounded hover:bg-green-600 sm:text-sm">
                                Agregar
                            </button>
                            <button type="button" wire:click="resetNuevoProducto"
                                class="flex-1 px-3 py-2 text-xs text-gray-600 bg-gray-200 rounded hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500 sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Lista de productos - Responsiva -->
            <div class="overflow-x-auto">
                <!-- Vista desktop -->
                <table class="hidden min-w-full divide-y divide-gray-200 dark:divide-gray-700 md:table">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400 sm:text-sm">
                                Producto
                            </th>
                            <th
                                class="px-3 py-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:text-sm">
                                Cantidad
                            </th>
                            <th
                                class="px-3 py-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:text-sm">
                                Precio Unit.
                            </th>
                            <th
                                class="px-3 py-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:text-sm">
                                Subtotal
                            </th>
                            <th
                                class="px-3 py-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:text-sm">
                                Helado
                            </th>
                            <th
                                class="px-3 py-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:text-sm">
                                Estado
                            </th>
                            <th
                                class="px-3 py-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:text-sm">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @forelse($existencias as $index => $existencia)
                            <tr>
                                <td class="px-3 py-2 text-xs text-gray-900 dark:text-gray-200 sm:text-sm">
                                    {{ $existencia['nombre'] }}
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <button type="button"
                                            wire:click="decrementarCantidadExistencia({{ $index }})"
                                            class="flex items-center justify-center w-6 h-6 text-xs text-white bg-red-500 rounded hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 {{ $existencia['cantidad'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $existencia['cantidad'] <= 1 ? 'disabled' : '' }}>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <span
                                            class="flex items-center justify-center w-12 h-6 text-xs font-medium text-gray-900 bg-gray-100 border border-gray-300 rounded dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600">
                                            {{ $existencia['cantidad'] }}
                                        </span>
                                        <button type="button"
                                            wire:click="incrementarCantidadExistencia({{ $index }})"
                                            class="flex items-center justify-center w-6 h-6 text-xs text-white bg-green-500 rounded hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <input type="number"
                                        wire:model="existencias.{{ $index }}.precio_unitario"
                                        wire:change="actualizarSubtotalExistencia({{ $index }})"
                                        step="0.01" min="0"
                                        class="w-20 text-xs text-center border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                </td>
                                <td class="px-3 py-2 text-xs text-center text-gray-900 dark:text-gray-200 sm:text-sm">
                                    S/ {{ number_format($existencia['subtotal'], 2) }}
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <input type="checkbox" wire:model="existencias.{{ $index }}.helado"
                                        class="w-3 h-3 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-gray-700 dark:border-gray-600">
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <select wire:model="existencias.{{ $index }}.estado"
                                        class="text-xs border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Procesando">Procesando</option>
                                        <option value="Listo">Listo</option>
                                        <option value="Entregando">Entregando</option>
                                        <option value="Completado">Completado</option>
                                        <option value="Cancelado">Cancelado</option>
                                    </select>
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <button type="button" wire:click="eliminarExistencia({{ $index }})"
                                        class="p-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                        onclick="return confirm('¿Estás seguro de eliminar este producto?')"
                                        title="Eliminar producto">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="px-3 py-3 text-xs text-center text-gray-500 dark:text-gray-400 sm:text-sm">
                                    No hay productos en esta comanda
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Vista móvil - Cards -->
                <div class="space-y-3 md:hidden">
                    @forelse($existencias as $index => $existencia)
                        <div
                            class="p-3 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                            <div class="flex items-start justify-between mb-2">
                                <h5 class="text-sm font-medium text-gray-900 dark:text-gray-200">
                                    {{ $existencia['nombre'] }}</h5>
                                <button type="button" wire:click="eliminarExistencia({{ $index }})"
                                    class="p-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                    onclick="return confirm('¿Estás seguro de eliminar este producto?')"
                                    title="Eliminar producto">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-xs">
                                <div>
                                    <label
                                        class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Cantidad</label>
                                    <div class="flex items-center space-x-1">
                                        <button type="button"
                                            wire:click="decrementarCantidadExistencia({{ $index }})"
                                            class="flex items-center justify-center w-6 h-6 text-xs text-white bg-red-500 rounded hover:bg-red-600 {{ $existencia['cantidad'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $existencia['cantidad'] <= 1 ? 'disabled' : '' }}>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <span
                                            class="flex items-center justify-center w-8 h-6 text-xs font-medium text-gray-900 bg-gray-100 border border-gray-300 rounded dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500">
                                            {{ $existencia['cantidad'] }}
                                        </span>
                                        <button type="button"
                                            wire:click="incrementarCantidadExistencia({{ $index }})"
                                            class="flex items-center justify-center w-6 h-6 text-xs text-white bg-green-500 rounded hover:bg-green-600">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Precio
                                        Unit.</label>
                                    <input type="number"
                                        wire:model="existencias.{{ $index }}.precio_unitario"
                                        wire:change="actualizarSubtotalExistencia({{ $index }})"
                                        step="0.01" min="0"
                                        class="w-full text-xs text-center border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-200">
                                </div>

                                <div>
                                    <label
                                        class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Subtotal</label>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-200">
                                        S/ {{ number_format($existencia['subtotal'], 2) }}
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Estado</label>
                                    <select wire:model="existencias.{{ $index }}.estado"
                                        class="w-full text-xs border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-200">
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Procesando">Procesando</option>
                                        <option value="Listo">Listo</option>
                                        <option value="Entregando">Entregando</option>
                                        <option value="Completado">Completado</option>
                                        <option value="Cancelado">Cancelado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex items-center mt-2">
                                <input type="checkbox" wire:model="existencias.{{ $index }}.helado"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-gray-600 dark:border-gray-500">
                                <label class="ml-2 text-xs font-medium text-gray-700 dark:text-gray-300">Helado</label>
                            </div>
                        </div>
                    @empty
                        <div
                            class="p-4 text-center text-gray-500 rounded-lg dark:text-gray-400 bg-gray-50 dark:bg-gray-700">
                            <p class="text-sm">No hay productos en esta comanda</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Platos -->
        <div class="mb-5">
            <div
                class="flex flex-col items-start justify-between mb-3 space-y-2 sm:flex-row sm:items-center sm:space-y-0">
                <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 sm:text-base">
                    Platos
                </h4>
                <button type="button" wire:click="$set('showAgregarPlato', true)"
                    class="w-full px-3 py-2 text-xs text-white bg-blue-500 rounded hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 sm:w-auto sm:text-sm">
                    + Agregar Plato
                </button>
            </div>

            <!-- Formulario para agregar plato -->
            @if ($showAgregarPlato)
                <div
                    class="p-3 mb-3 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-5">
                        <!-- Plato con búsqueda -->
                        <div class="relative sm:col-span-2 md:col-span-1">
                            <label
                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 sm:text-sm">Plato</label>
                            <div class="relative">
                                <input type="text" wire:model.live="searchPlato" placeholder="Buscar plato..."
                                    class="block w-full mt-1 text-xs border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-200 dark:placeholder-gray-400 sm:text-sm">
                                @if ($searchPlato)
                                    <button type="button" wire:click="limpiarPlato"
                                        class="absolute inset-y-0 right-0 flex items-center pr-2">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>

                            @if ($showPlatosList && $this->platosFiltrados->count() > 0)
                                <div
                                    class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg dark:bg-gray-700 dark:border-gray-600">
                                    @foreach ($this->platosFiltrados as $plato)
                                        <div wire:click="seleccionarPlato({{ $plato->id }}, '{{ $plato->nombre }}')"
                                            class="px-3 py-2 text-xs cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 sm:text-sm">
                                            <div class="font-medium">{{ $plato->nombre }}</div>
                                            <div class="text-xs text-gray-500">
                                                Mesa: S/ {{ number_format($plato->precio, 2) }}
                                                @if ($plato->precio_llevar > 0 && $plato->precio_llevar != $plato->precio)
                                                    | Llevar: S/ {{ number_format($plato->precio_llevar, 2) }}
                                                @endif
                                                - Stock: {{ $plato->disponibilidadPlato->cantidad ?? 0 }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @error('nuevoPlatoId')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 sm:text-sm">Cantidad</label>
                            <input type="number" wire:model="nuevoPlatoCantidad" min="1"
                                class="block w-full mt-1 text-xs border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-200 sm:text-sm">
                        </div>
                        <div class="mt-5">
                            <label
                                class="block mb-2 text-xs font-medium text-gray-700 dark:text-gray-300 sm:text-sm">Tipo
                                de
                                Servicio</label>
                            <button type="button" wire:click="$toggle('nuevoPlatoLlevar')"
                                class="w-full px-3 py-2 text-xs font-medium rounded-md border-2 transition-all duration-200 sm:text-sm {{ $nuevoPlatoLlevar ? 'bg-amber-100 border-amber-300 text-amber-800 dark:bg-amber-900/30 dark:border-amber-600 dark:text-amber-300' : 'bg-blue-100 border-blue-300 text-blue-800 dark:bg-blue-900/30 dark:border-blue-600 dark:text-blue-300' }}">
                                {{ $nuevoPlatoLlevar ? 'PARA LLEVAR' : 'PARA MESA' }}
                            </button>
                        </div>
                        <div class="mt-5">
                            <label
                                class="block mb-2 text-xs font-medium text-gray-700 dark:text-gray-300 sm:text-sm">Precio</label>
                            <div
                                class="px-3 py-2 text-xs font-bold text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-md dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 sm:text-sm">
                                S/ {{ number_format($nuevoPlatoPrecio, 2) }}
                            </div>
                        </div>
                        <div class="flex gap-2 mt-5 sm:col-span-2 md:col-span-1">
                            <button type="button" wire:click="agregarPlato"
                                class="flex-1 px-3 py-2 text-xs text-white bg-green-500 rounded hover:bg-green-600 sm:text-sm">
                                Agregar
                            </button>
                            <button type="button" wire:click="resetNuevoPlato"
                                class="flex-1 px-3 py-2 text-xs text-gray-600 bg-gray-200 rounded hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500 sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Lista de platos - Responsiva -->
            <div class="overflow-x-auto">
                <!-- Vista desktop -->
                <table class="hidden min-w-full divide-y divide-gray-200 dark:divide-gray-700 md:table">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400 sm:text-sm">
                                Plato
                            </th>
                            <th
                                class="px-3 py-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:text-sm">
                                Cantidad
                            </th>
                            <th
                                class="px-3 py-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:text-sm">
                                Precio Unit.
                            </th>
                            <th
                                class="px-3 py-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:text-sm">
                                Subtotal
                            </th>
                            <th
                                class="px-3 py-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:text-sm">
                                Tipo
                            </th>
                            <th
                                class="px-3 py-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:text-sm">
                                Estado
                            </th>
                            <th
                                class="px-3 py-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400 sm:text-sm">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @forelse($platos as $index => $plato)
                            <tr>
                                <td class="px-3 py-2 text-xs text-gray-900 dark:text-gray-200 sm:text-sm">
                                    {{ $plato['nombre'] }}
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <button type="button"
                                            wire:click="decrementarCantidadPlato({{ $index }})"
                                            class="flex items-center justify-center w-6 h-6 text-xs text-white bg-red-500 rounded hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 {{ $plato['cantidad'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $plato['cantidad'] <= 1 ? 'disabled' : '' }}>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <span
                                            class="flex items-center justify-center w-12 h-6 text-xs font-medium text-gray-900 bg-gray-100 border border-gray-300 rounded dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600">
                                            {{ $plato['cantidad'] }}
                                        </span>
                                        <button type="button"
                                            wire:click="incrementarCantidadPlato({{ $index }})"
                                            class="flex items-center justify-center w-6 h-6 text-xs text-white bg-green-500 rounded hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <input type="number" wire:model="platos.{{ $index }}.precio_unitario"
                                        wire:change="actualizarSubtotalPlato({{ $index }})" step="0.01"
                                        min="0"
                                        class="w-20 text-xs text-center border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                </td>
                                <td class="px-3 py-2 text-xs text-center text-gray-900 dark:text-gray-200 sm:text-sm">
                                    S/ {{ number_format($plato['subtotal'], 2) }}
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <button type="button" wire:click="toggleLlevarPlato({{ $index }})"
                                        class="px-3 py-1 text-xs font-semibold rounded-full border-2 transition-all duration-200 {{ $plato['llevar'] ? 'bg-amber-100 border-amber-300 text-amber-800 hover:bg-amber-200 dark:bg-amber-900/30 dark:border-amber-600 dark:text-amber-300 dark:hover:bg-amber-800/40' : 'bg-blue-100 border-blue-300 text-blue-800 hover:bg-blue-200 dark:bg-blue-900/30 dark:border-blue-600 dark:text-blue-300 dark:hover:bg-blue-800/40' }}"
                                        title="Click para cambiar tipo de servicio">
                                        {{ $plato['llevar'] ? 'LLEVAR' : 'MESA' }}
                                    </button>
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <select wire:model="platos.{{ $index }}.estado"
                                        class="text-xs border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Procesando">Procesando</option>
                                        <option value="Listo">Listo</option>
                                        <option value="Entregando">Entregando</option>
                                        <option value="Completado">Completado</option>
                                        <option value="Cancelado">Cancelado</option>
                                    </select>
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <button type="button" wire:click="eliminarPlato({{ $index }})"
                                        class="p-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                        onclick="return confirm('¿Estás seguro de eliminar este plato?')"
                                        title="Eliminar plato">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="px-3 py-3 text-xs text-center text-gray-500 dark:text-gray-400 sm:text-sm">
                                    No hay platos en esta comanda
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Vista móvil - Cards -->
                <div class="space-y-3 md:hidden">
                    @forelse($platos as $index => $plato)
                        <div
                            class="p-3 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                            <div class="flex items-start justify-between mb-2">
                                <h5 class="text-sm font-medium text-gray-900 dark:text-gray-200">
                                    {{ $plato['nombre'] }}</h5>
                                <button type="button" wire:click="eliminarPlato({{ $index }})"
                                    class="p-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                    onclick="return confirm('¿Estás seguro de eliminar este plato?')"
                                    title="Eliminar plato">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>

                            <div class="grid grid-cols-2 gap-3 mb-3 text-xs">
                                <div>
                                    <label
                                        class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Cantidad</label>
                                    <div class="flex items-center space-x-1">
                                        <button type="button"
                                            wire:click="decrementarCantidadPlato({{ $index }})"
                                            class="flex items-center justify-center w-6 h-6 text-xs text-white bg-red-500 rounded hover:bg-red-600 {{ $plato['cantidad'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $plato['cantidad'] <= 1 ? 'disabled' : '' }}>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <span
                                            class="flex items-center justify-center w-8 h-6 text-xs font-medium text-gray-900 bg-gray-100 border border-gray-300 rounded dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500">
                                            {{ $plato['cantidad'] }}
                                        </span>
                                        <button type="button"
                                            wire:click="incrementarCantidadPlato({{ $index }})"
                                            class="flex items-center justify-center w-6 h-6 text-xs text-white bg-green-500 rounded hover:bg-green-600">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Precio
                                        Unit.</label>
                                    <input type="number" wire:model="platos.{{ $index }}.precio_unitario"
                                        wire:change="actualizarSubtotalPlato({{ $index }})" step="0.01"
                                        min="0"
                                        class="w-full text-xs text-center border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-200">
                                </div>

                                <div>
                                    <label
                                        class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Subtotal</label>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-200">
                                        S/ {{ number_format($plato['subtotal'], 2) }}
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Estado</label>
                                    <select wire:model="platos.{{ $index }}.estado"
                                        class="w-full text-xs border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-200">
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Procesando">Procesando</option>
                                        <option value="Listo">Listo</option>
                                        <option value="Entregando">Entregando</option>
                                        <option value="Completado">Completado</option>
                                        <option value="Cancelado">Cancelado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-center">
                                <button type="button" wire:click="toggleLlevarPlato({{ $index }})"
                                    class="px-4 py-2 text-xs font-semibold rounded-full border-2 transition-all duration-200 {{ $plato['llevar'] ? 'bg-amber-100 border-amber-300 text-amber-800 hover:bg-amber-200 dark:bg-amber-900/30 dark:border-amber-600 dark:text-amber-300' : 'bg-blue-100 border-blue-300 text-blue-800 hover:bg-blue-200 dark:bg-blue-900/30 dark:border-blue-600 dark:text-blue-300' }}"
                                    title="Click para cambiar tipo de servicio">
                                    {{ $plato['llevar'] ? 'PARA LLEVAR' : 'PARA MESA' }}
                                </button>
                            </div>
                        </div>
                    @empty
                        <div
                            class="p-4 text-center text-gray-500 rounded-lg dark:text-gray-400 bg-gray-50 dark:bg-gray-700">
                            <p class="text-sm">No hay platos en esta comanda</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Totales -->
        <div
            class="p-3 mb-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 sm:p-4">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                <div class="text-center">
                    <span class="block text-xs font-medium text-gray-700 dark:text-gray-300 sm:text-sm">Subtotal</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-base">
                        S/ {{ number_format($subtotal, 2) }}
                    </span>
                </div>
                <div class="text-center">
                    <span class="block text-xs font-medium text-gray-700 dark:text-gray-300 sm:text-sm">IGV
                        (18%)</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-base">
                        S/ {{ number_format($igv, 2) }}
                    </span>
                </div>
                <div class="text-center">
                    <span class="block text-xs font-medium text-gray-700 dark:text-gray-300 sm:text-sm">Total
                        General</span>
                    <span class="text-base font-bold text-blue-600 dark:text-blue-400 sm:text-lg">
                        S/ {{ number_format($total_general, 2) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
            <button type="button" wire:target="cancelarEdicion" wire:click="$dispatch('cerrarModalEditar')"
                class="w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500 sm:w-auto">
                Cancelar
            </button>
            <button type="submit" wire:loading.attr="disabled"
                class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 disabled:opacity-50 disabled:cursor-not-allowed sm:w-auto">
                <span wire:loading.remove wire:target="guardarComanda">Guardar Cambios</span>
                <span wire:loading wire:target="guardarComanda" class="flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Guardando...
                </span>
            </button>
        </div>
    </form>

    {{-- <!-- Loading estado (mantengo el overlay también) -->
    <div wire:loading wire:target="guardarComanda"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="p-3 bg-white rounded-lg shadow-lg dark:bg-gray-800 sm:p-4">
            <div class="flex items-center space-x-2">
                <div
                    class="w-4 h-4 border-2 border-blue-600 rounded-full animate-spin border-t-transparent sm:w-5 sm:h-5">
                </div>
                <span class="text-xs text-gray-700 dark:text-gray-300 sm:text-sm">Guardando cambios...</span>
            </div>
        </div>
    </div> --}}
</div>
