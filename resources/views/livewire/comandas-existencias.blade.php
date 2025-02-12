<div>
    @vite('resources/css/app.css')

    <div class="p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">

        <div>
            <!-- Menú de Áreas -->
            <div class="mb-8">
                <h2 class="mb-4 text-xl font-bold">Áreas</h2>
                <div class="flex flex-wrap gap-4">
                    @foreach ($areas as $area)
                        <button wire:click="selectArea({{ $area->id }})"
                            class="px-4 py-2 font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            {{ $area->nombre }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Lista de Comandas -->
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($comandas as $comanda)
                    @php
                        $existenciasArea = $comanda->comandaExistencias->filter(function ($comandaExistencia) {
                            return $comandaExistencia->existencia->area_existencia_id == $this->selectedArea;
                        });
                    @endphp

                    @if ($existenciasArea->isNotEmpty())
                        <div class="bg-white rounded-lg shadow-lg">
                            <div class="p-6">
                                <h3 class="mb-4 text-lg font-semibold">Comanda #{{ $comanda->id }}</h3>
                                <div class="mb-4">
                                    <p>Cliente: {{ $comanda->cliente->nombre }}</p>
                                    <p>Zona: {{ $comanda->zona->nombre }}</p>
                                    <p>Mesa: {{ $comanda->mesa->numero }}</p>
                                    <p class="font-semibold {{ $comanda->estado ? 'text-green-600' : 'text-red-600' }}">
                                        Estado: {{ $comanda->estado ? 'Activa' : 'Cerrada' }}
                                    </p>
                                </div>
                                <div>
                                    <h4 class="mb-2 font-semibold">Existencias:</h4>
                                    <ul>
                                        @foreach ($existenciasArea as $comandaExistencia)
                                            <li class="flex items-center justify-between">
                                                <span>{{ $comandaExistencia->existencia->nombre }}</span>
                                                <span class="font-semibold">x{{ $comandaExistencia->cantidad }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

</div>
