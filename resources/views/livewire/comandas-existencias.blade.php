<div>
    @vite('resources/css/app.css')
    <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
        <!-- Panel izquierdo (Comandas) - Ocupa todo el ancho en móvil, 9/12 en desktop -->
        <div class="order-2 col-span-1 md:col-span-12 lg:col-span-9 lg:order-1">
            <div class="min-h-screen pt-2" wire:poll.{{ $refreshInterval }}ms>
                <!-- Área Tabs -->
                <div class="sticky top-0 z-10 p-2 mb-4 rounded-lg bg-gray-50/80 dark:bg-gray-900/80 backdrop-blur-sm">
                    @if (count($areas) > 1)
                        <nav class="flex flex-wrap justify-center gap-2 py-2" aria-label="Áreas">
                            @foreach ($areas as $area)
                                <button wire:click="selectArea({{ $area->id }})"
                                    class="{{ $selectedArea == $area->id
                                        ? 'bg-white shadow text-primary-600 dark:bg-gray-800 dark:text-primary-400'
                                        : 'text-gray-500 hover:text-gray-700 hover:bg-white/50 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800/50' }}
                    px-3 py-2 sm:px-4 text-xs sm:text-sm font-medium rounded-lg transition-all duration-150 flex-grow sm:flex-grow-0">
                                    {{ $area->nombre }}
                                </button>
                            @endforeach
                        </nav>
                    @endif
                </div>


                <!-- Grid de Comandas - Adaptativo a diferentes tamaños de pantalla -->
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3">
                    @forelse($comandas as $comanda)
                        @php
                            $tiempoTranscurrido = now()->diffInMinutes($comanda->created_at);
                            $colorClase = match (true) {
                                $tiempoTranscurrido >= 6 => 'border-l-4 border-l-rose-500 dark:border-l-rose-400',
                                $tiempoTranscurrido >= 4 => 'border-l-4 border-l-amber-500 dark:border-l-amber-400',
                                $tiempoTranscurrido >= 2 => 'border-l-4 border-l-emerald-500 dark:border-l-emerald-400',
                                default => 'border-l-4 border-l-gray-300 dark:border-l-gray-600',
                            };
                        @endphp

                        <div
                            class="overflow-hidden transition-all duration-150 bg-white border shadow-sm hover:shadow dark:bg-gray-800 dark:border-gray-700 rounded-xl {{ $colorClase }}">
                            <!-- Header -->
                            <div class="flex items-center justify-between p-3 border-b dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    <span class="text-base font-bold text-gray-900 dark:text-white">
                                        N° {{ str_pad($comanda->id, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <span
                                        class="px-2 py-0.5 text-xs font-medium rounded-full
                                    {{ $tiempoTranscurrido >= 6
                                        ? 'bg-rose-100 text-rose-700 dark:bg-rose-900 dark:text-rose-300'
                                        : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $tiempoTranscurrido }} min
                                    </span>
                                </div>
                                <div
                                    class="px-2 py-1 text-xs font-medium rounded-lg {{ $comanda->estado === 'Abierta'
                                        ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/50 dark:text-primary-400'
                                        : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400' }}">
                                    {{ $comanda->estado }}
                                </div>
                            </div>

                            <!-- Info Cliente y Ubicación -->
                            <div class="p-3 bg-gray-50/50 dark:bg-gray-800/50">
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-2 text-sm">
                                        <span class="p-1 rounded-md bg-gray-100/80 dark:bg-gray-700/80">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </span>
                                        <span class="font-medium text-gray-700 truncate dark:text-gray-300">
                                            {{ $comanda->cliente->nombre }}

                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1 text-sm">
                                        <span class="p-1 rounded-md bg-gray-100/80 dark:bg-gray-700/80">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                        </span>
                                        <span class="text-gray-600 truncate dark:text-gray-400">
                                            {{ $comanda->zona->nombre }} • Mesa {{ $comanda->mesa->numero }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Lista de Existencias -->
                            <div class="px-3 py-2 space-y-1">
                                @foreach ($comanda->comandaExistencias->where('existencia.area_existencia_id', $selectedArea) as $comandaExistencia)
                                    <div
                                        class="flex flex-wrap items-center justify-between p-2 transition-colors rounded-lg {{ $comandaExistencia->helado ? 'border-l-4 border-blue-500 dark:border-blue-400' : '' }} bg-gray-50/50 dark:bg-gray-700/50">
                                        <div class="w-full mb-1 sm:w-auto sm:mb-0">
                                            <div class="flex flex-col">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $comandaExistencia->existencia->nombre }}
                                                    </span>
                                                    @if ($comandaExistencia->helado)
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                            </svg>
                                                            Helado
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $comandaExistencia->existencia->unidadMedida->descripcion }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center w-full gap-2 sm:w-auto">
                                            <span
                                                class="px-2 py-1 text-sm font-medium text-gray-900 bg-gray-100 rounded-lg dark:bg-gray-600 dark:text-white">
                                                x{{ $comandaExistencia->cantidad }}
                                            </span>
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-lg
                {{ $comandaExistencia->estado === 'Listo'
                    ? 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400'
                    : ($comandaExistencia->estado === 'Procesando'
                        ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400'
                        : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300') }}">
                                                {{ $comandaExistencia->estado }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Footer -->
                            @if ($comanda->comandaExistencias->where('existencia.area_existencia_id', $selectedArea)->every(fn($item) => $item->estado === 'Pendiente'))
                                <div class="flex justify-end p-3 border-t dark:border-gray-700">
                                    <button wire:click="procesarComanda({{ $comanda->id }})"
                                        class="px-4 py-2 text-sm font-medium text-white transition-colors rounded-lg bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-primary-500 dark:hover:bg-primary-600">
                                        Procesar
                                    </button>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full">
                            <div
                                class="flex flex-col items-center gap-2 p-8 text-center bg-white rounded-xl dark:bg-gray-800">
                                <div class="p-3 bg-gray-100 rounded-full dark:bg-gray-700">
                                    <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <h3 class="text-base font-medium text-gray-900 dark:text-white">No hay comandas
                                    pendientes</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">No se encontraron comandas
                                    pendientes para
                                    esta área.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <!-- Panel derecho - Adaptado para móviles y desktop -->
        <div class="order-1 col-span-1 md:col-span-12 lg:col-span-3 lg:order-2">
            <div class="sticky p-4 mb-4 overflow-y-auto bg-white border shadow-sm top-2 dark:bg-gray-800 dark:border-gray-700 rounded-xl lg:mb-0"
                style="max-height: calc(100vh - 2rem)">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Lista de existencias
                    </h2>
                    <span
                        class="px-2.5 py-1 text-xs font-medium bg-primary-100 text-primary-700 rounded-lg dark:bg-primary-900/50 dark:text-primary-400">
                        {{ count($existenciasAProcesar ?? []) }} existencias
                    </span>
                </div>
                <div class="space-y-2">
                    @forelse ($existenciasAProcesar ?? [] as $existencia)
                        <div
                            class="flex flex-col justify-between p-3 transition-colors rounded-lg sm:flex-row sm:items-center bg-gray-50 hover:bg-gray-100 dark:bg-gray-700/50 dark:hover:bg-gray-700 {{ $existencia['helado'] ? 'border-l-4 border-blue-500 dark:border-blue-400' : '' }}">
                            <div class="flex-1 min-w-0 mb-2 sm:mb-0">
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $existencia['nombre'] }}
                                        </p>
                                        @if ($existencia['helado'])
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                                Helado
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $existencia['unidad'] ?? '' }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="flex items-center justify-between w-full gap-2 sm:w-auto sm:ml-4 sm:justify-end">
                                <span
                                    class="px-2.5 py-1 text-sm font-medium bg-primary-100 text-primary-700 rounded-lg dark:bg-primary-900/50 dark:text-primary-400">
                                    x{{ $existencia['total'] }}
                                </span>
                                <div class="flex space-x-2">
                                    <button wire:click="confirmarCancelacion('{{ $existencia['grupoKey'] }}')"
                                        class="p-2 text-sm font-medium text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:bg-red-500 dark:hover:bg-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    <button wire:click="marcarExistenciaLista('{{ $existencia['grupoKey'] }}')"
                                        class="px-3 py-1 text-sm font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:bg-green-500 dark:hover:bg-green-600">
                                        Listo
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                No hay existencias
                            </span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>


    @if ($mostrarConfirmacion)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Fondo oscuro -->
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-800 dark:bg-opacity-75"
                    aria-hidden="true"></div>

                <!-- Centrado del modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Contenido del modal -->
                <div
                    class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl dark:bg-gray-800 sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 bg-white dark:bg-gray-800 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <!-- Ícono de alerta -->
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white"
                                    id="modal-title">
                                    Cancelar preparación
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        ¿Estás seguro de que deseas cancelar la preparación de esta existencia?
                                        Esta acción devolverá la existencia al estado pendiente.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="procederCancelacion"
                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 sm:ml-3 sm:w-auto sm:text-sm">
                            Sí, cancelar preparación
                        </button>
                        <button type="button" wire:click="cerrarConfirmacion"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:text-gray-300 dark:bg-gray-600 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
