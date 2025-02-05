<div>
    @vite('resources/css/app.css')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="p-4">
            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Nueva Comanda</h2>
            </div>

            <div class="grid grid-cols-12 gap-6">
                <!-- Panel Izquierdo - Selección y Productos -->
                <div class="col-span-8 space-y-6">
                    <!-- Sección de Selección -->
                    <div class="p-6 bg-white shadow-sm dark:bg-gray-800 rounded-xl">
                        <div class="grid grid-cols-3 gap-4">
                            <!-- Selector de Caja -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Caja
                                </label>
                                <select wire:model.live="cajaId"
                                    class="w-full text-gray-900 border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">Seleccionar Caja</option>
                                    @foreach ($cajas as $caja)
                                        <option value="{{ $caja->id }}">{{ $caja->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Selector de Zona -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Zona
                                </label>
                                <select wire:model.live="zonaId" @if (empty($zonas)) disabled @endif
                                    class="w-full text-gray-900 border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">Seleccionar Zona</option>
                                    @foreach ($zonas as $zona)
                                        <option value="{{ $zona->id }}">{{ $zona->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Selector de Mesa -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Mesa
                                </label>
                                <select wire:model="mesaId" @if (empty($mesas)) disabled @endif
                                    class="w-full text-gray-900 border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">Seleccionar Mesa</option>
                                    @foreach ($mesas as $mesa)
                                        <option value="{{ $mesa->id }}">Mesa {{ $mesa->numero }} -
                                            {{ ucfirst($mesa->estado) }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>

                    <!-- Sección de Platos -->
                    <div class="p-6 bg-white shadow-sm dark:bg-gray-800 rounded-xl">
                        <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Platos</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Seleccionar Plato
                                </label>
                                <select wire:model="platoSeleccionado"
                                    class="w-full text-gray-900 border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">Seleccionar Plato</option>
                                    @foreach ($platos as $plato)
                                        <option value="{{ $plato->id }}">{{ $plato->nombre }} - S/
                                            {{ number_format($plato->precio, 2) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Cantidad
                                </label>
                                <div class="flex gap-2">
                                    <input type="number" wire:model="cantidadPlato" min="1"
                                        class="w-20 text-gray-900 border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                    <button wire:click="agregarPlato"
                                        class="px-4 py-2 text-white rounded-lg bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                        Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Existencias -->
                    <div class="p-6 bg-white shadow-sm dark:bg-gray-800 rounded-xl">
                        <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Existencias</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Seleccionar Existencia
                                </label>
                                <select wire:model="existenciaSeleccionada"
                                    class="w-full text-gray-900 border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">Seleccionar Existencia</option>
                                    @foreach ($existencias as $existencia)
                                        <option value="{{ $existencia->id }}">{{ $existencia->nombre }} - S/
                                            {{ number_format($existencia->precio_venta, 2) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Cantidad
                                </label>
                                <div class="flex gap-2">
                                    <input type="number" wire:model="cantidadExistencia" min="1"
                                        class="w-20 text-gray-900 border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                                    <button wire:click="agregarExistencia"
                                        class="px-4 py-2 text-white rounded-lg bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                        Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel Derecho - Resumen de Comanda -->
                <div class="col-span-4">
                    <div class="sticky p-6 bg-white shadow-sm dark:bg-gray-800 rounded-xl top-4">
                        <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">Resumen de Comanda</h3>

                        @if (!$mesaId)
                            <div class="py-8 text-center text-gray-500 dark:text-gray-400">
                                Selecciona una mesa para continuar
                            </div>
                        @else
                            <!-- Lista de Platos -->
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
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $item['cantidad'] }} x S/
                                                        {{ number_format($item['precio'], 2) }}
                                                    </p>
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

                            <!-- Lista de Existencias -->
                            @if (count($itemsExistencias) > 0)
                                <div class="mb-6">
                                    <h4 class="mb-3 text-sm font-medium text-gray-500 dark:text-gray-400">EXISTENCIAS
                                    </h4>
                                    <div class="space-y-3">
                                        @foreach ($itemsExistencias as $index => $item)
                                            <div
                                                class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                                                <div class="flex-1">
                                                    <h5 class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $item['nombre'] }}</h5>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $item['cantidad'] }} x S/
                                                        {{ number_format($item['precio'], 2) }}
                                                    </p>
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

                            @if (empty($itemsPlatos) && empty($itemsExistencias))
                                <div class="py-8 text-center text-gray-500 dark:text-gray-400">
                                    Agrega items a la comanda
                                </div>
                            @else
                                <!-- Total -->
                                <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div
                                        class="flex items-center justify-between text-lg font-bold text-gray-900 dark:text-white">
                                        <span>Total</span>
                                        <span>S/ {{ number_format($total, 2) }}</span>
                                    </div>
                                </div>

                                <!-- Botón Guardar -->
                                <button wire:click="guardarComanda"
                                    class="w-full px-6 py-3 mt-6 font-medium text-white rounded-lg bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                    Guardar Comanda
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('livewire:initialized', () => {
                @this.on('commandaGuardada', () => {
                    // Aquí puedes agregar notificaciones o redirecciones
                });
            });
        </script>
    @endpush

    <!-- Agregar al inicio del componente -->
    @if (session()->has('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <!-- Agregar mensajes de error en los inputs -->
    @error('platoSeleccionado')
        <span class="text-sm text-red-600">{{ $message }}</span>
    @enderror

    @error('cantidadPlato')
        <span class="text-sm text-red-600">{{ $message }}</span>
    @enderror


</div>
