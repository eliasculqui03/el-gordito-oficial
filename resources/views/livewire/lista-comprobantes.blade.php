<div>
    @vite('resources/css/app.css')

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
                    <td class="px-4 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                        {{ $comprobante->created_at }}
                    </td>

                    <td class="px-4 py-4 text-sm font-medium text-right whitespace-nowrap">
                        <div class="flex justify-end gap-2">

                            <button type="button" wire:click="abrirModalImprimir({{ $comprobante->id }})"
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
                            <!-- Otros botones si los hay -->
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

    <div class="mt-4">
        {{ $comprobantes->links() }}
    </div>
</div>
