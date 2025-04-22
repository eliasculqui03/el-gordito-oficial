<div>

    @vite('resources/css/app.css')

    <div class="min-h-screen transition-colors duration-200 bg-gray-50 dark:bg-gray-900">


        <div class="container px-4 py-6 mx-auto">
            @if (!$mostrarGestionVentas)
                <div class="mb-5">
                    <div class="flex flex-wrap items-center justify-between mb-5">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Seleccione una caja
                        </h3>
                        <span
                            class="inline-flex items-center px-3 py-1 mt-2 text-sm font-medium text-purple-800 bg-purple-100 rounded-full dark:bg-purple-900/30 dark:text-purple-300 sm:mt-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ auth()->user()->name }}
                        </span>
                    </div>

                    @if ($cajas->count() > 0)
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            @foreach ($cajas as $caja)
                                <div wire:click="seleccionarCaja({{ $caja->id }})"
                                    class="overflow-hidden transition-all duration-200 transform bg-white border border-gray-200 rounded-lg shadow-sm cursor-pointer dark:bg-gray-800 dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-700/40 hover:-translate-y-1">
                                    <div class="p-5">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                                    {{ $caja->nombre }}</h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $caja->sucursal->nombre ?? 'Sin sucursal' }}</p>
                                            </div>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $caja->estado === 'Abierta' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' }}">
                                                {{ $caja->estado }}
                                            </span>
                                        </div>

                                        <div class="pt-4 mt-4 border-t border-gray-100 dark:border-gray-700">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-500 dark:text-gray-400">Saldo inicial:</span>
                                                <span class="font-medium text-gray-800 dark:text-gray-200">S/.
                                                    {{ number_format($caja->saldo_actual, 2) }}</span>
                                            </div>
                                        </div>

                                        <div class="mt-4 text-center">
                                            <button type="button"
                                                class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white transition-colors duration-200 bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-700 dark:hover:bg-indigo-800 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                </svg>
                                                Abrir Caja
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div
                            class="p-4 border-l-4 border-yellow-400 rounded-md bg-yellow-50 dark:bg-yellow-900/20 dark:border-yellow-700">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-yellow-400 dark:text-yellow-300"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                        No hay cajas disponibles con estado "Cerrada" en este momento.
                                    </p>
                                </div>
                            </div>
                        </div>


                    @endif
                </div>
            @endif

            @if ($mostrarGestionVentas)
                <div class="mt-4">
                    <livewire:gestion-ventas :caja-id="$cajaSeleccionada" />
                </div>
            @endif
        </div>
    </div>

</div>
