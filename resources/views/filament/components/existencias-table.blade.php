<div class="space-y-4">
    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Producto
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Cantidad Disponible
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Precio
                    </th>
                    <!-- Agrega aquí más columnas si las necesitas -->
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($existencias as $existencia)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $existencia['nombre'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $existencia['cantidad'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${{ number_format($existencia['precio'], 2) }}
                        </td>
                        <!-- Agrega aquí más columnas si las necesitas -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
