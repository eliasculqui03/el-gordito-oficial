<div>
    @vite('resources/css/app.css')

    <div class="py-4">
        <div class="flex items-center justify-between mb-4">
            <div class="relative w-full max-w-sm">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 dark:text-gray-400"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Buscar comprobante..."
                    class="w-full pl-10 text-gray-900 bg-white border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500">
            </div>
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-lg dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th scope="col"
                            class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Serie
                        </th>
                        <th scope="col"
                            class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Número
                        </th>
                        <th scope="col"
                            class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">
                            Cliente
                        </th>
                        <th scope="col"
                            class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">
                            Caja
                        </th>
                        <th scope="col"
                            class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">
                            Usuario
                        </th>
                        <th scope="col"
                            class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Fecha
                        </th>
                        <th scope="col"
                            class="px-4 py-3.5 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                    @forelse ($comprobantes as $comprobante)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-4 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                {{ $comprobante->serie }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                {{ $comprobante->numero }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden md:table-cell max-w-[200px] truncate"
                                title="{{ $comprobante->cliente->nombre }}">
                                {{ $comprobante->cliente->nombre }}
                            </td>
                            <td
                                class="hidden px-4 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400 md:table-cell">
                                {{ $comprobante->caja->nombre }}
                            </td>
                            <td
                                class="hidden px-4 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400 md:table-cell">
                                {{ $comprobante->user->name }}
                            </td>
                            <td
                                class="hidden px-4 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400 md:table-cell">
                                {{ $comprobante->created_at }}
                            </td>

                            <td class="px-4 py-4 text-sm font-medium text-right whitespace-nowrap">
                                <div class="flex justify-end gap-2">
                                    <button type="button"
                                        wire:click.prevent="{{ route('imprimir.comprobante', $comprobante->id) }}"
                                        class="inline-flex items-center justify-center p-2 text-gray-500 rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800"
                                        title="Imprimir">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                            <path
                                                d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2">
                                            </path>
                                            <rect x="6" y="14" width="12" height="8"></rect>
                                        </svg>
                                        <span class="sr-only">Imprimir</span>
                                    </button>
                                    <button type="button" wire:click="editar({{ $comprobante->id }})"
                                        class="inline-flex items-center justify-center p-2 text-gray-500 rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800"
                                        title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        <span class="sr-only">Editar</span>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-sm text-center text-gray-500 dark:text-gray-400">
                                No se encontraron comprobantes
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $comprobantes->links() }}
        </div>



    </div>

</div>
