<div>
    <!-- resources/views/livewire/ventas-component.blade.php -->
    @vite('resources/css/app.css')
    <div class="min-h-screen p-4 bg-gray-100">
        <!-- Encabezado con título y búsqueda -->
        <div class="p-6 mb-5 bg-white rounded-lg shadow-md">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <h1 class="mb-4 text-2xl font-bold text-gray-800 md:mb-0">Generar Comprobante de Pago</h1>

                <!-- Búsqueda de cliente -->
                <div class="w-full md:w-1/3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 p-2.5"
                            placeholder="Buscar por DNI/RUC...">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-5 lg:flex-row">
            <!-- Panel izquierdo: Comandas encontradas -->
            <div class="w-full overflow-hidden bg-white rounded-lg shadow-md lg:w-1/3">
                <div class="px-6 py-4 bg-indigo-600">
                    <h2 class="text-lg font-semibold text-white">Comandas Pendientes</h2>
                </div>

                <div class="divide-y divide-gray-200 max-h-[calc(100vh-15rem)] overflow-y-auto">
                    <!-- Comanda Item (Seleccionada) -->
                    <div class="p-4 border-l-4 border-indigo-600 cursor-pointer bg-indigo-50">
                        <div class="flex justify-between">
                            <span class="font-medium text-indigo-700">Comanda #1234</span>
                            <span
                                class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Pendiente</span>
                        </div>
                        <div class="mt-2 text-sm text-gray-600">
                            <p>Mesa: 5 | Zona: Terraza</p>
                            <p class="mt-1">Cliente: Juan Pérez</p>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-gray-500">Actualizado: 10:30 AM</span>
                            <span class="font-bold text-gray-900">S/ 78.50</span>
                        </div>
                    </div>

                    <!-- Comanda Item -->
                    <div class="p-4 cursor-pointer hover:bg-gray-50">
                        <div class="flex justify-between">
                            <span class="font-medium">Comanda #1235</span>
                            <span
                                class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Pendiente</span>
                        </div>
                        <div class="mt-2 text-sm text-gray-600">
                            <p>Mesa: 3 | Zona: Interior</p>
                            <p class="mt-1">Cliente: María López</p>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-gray-500">Actualizado: 10:45 AM</span>
                            <span class="font-bold text-gray-900">S/ 125.00</span>
                        </div>
                    </div>

                    <div class="p-4 cursor-pointer hover:bg-gray-50">
                        <div class="flex justify-between">
                            <span class="font-medium">Comanda #1236</span>
                            <span
                                class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Pendiente</span>
                        </div>
                        <div class="mt-2 text-sm text-gray-600">
                            <p>Mesa: 8 | Zona: Terraza</p>
                            <p class="mt-1">Cliente: Carlos Rodríguez</p>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-gray-500">Actualizado: 11:15 AM</span>
                            <span class="font-bold text-gray-900">S/ 94.20</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel derecho: Detalles y formulario de venta -->
            <div class="w-full lg:w-2/3">
                <div class="mb-5 overflow-hidden bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 bg-gray-800">
                        <h2 class="text-lg font-semibold text-white">Detalle de Comanda #1234</h2>
                    </div>

                    <div class="p-6">
                        <!-- Info del cliente -->
                        <div class="flex flex-col gap-6 mb-6 md:flex-row">
                            <div class="flex-1 p-4 rounded-lg bg-gray-50">
                                <h3 class="mb-2 font-medium text-gray-700 text-md">Información del Cliente</h3>
                                <p><span class="font-medium">Nombre:</span> Juan Pérez</p>
                                <p><span class="font-medium">DNI:</span> 45678912</p>
                                <p><span class="font-medium">Teléfono:</span> 987654321</p>
                            </div>
                            <div class="flex-1 p-4 rounded-lg bg-gray-50">
                                <h3 class="mb-2 font-medium text-gray-700 text-md">Información de Mesa</h3>
                                <p><span class="font-medium">Mesa:</span> 5</p>
                                <p><span class="font-medium">Zona:</span> Terraza</p>
                                <p><span class="font-medium">Mesero:</span> Alex Torres</p>
                            </div>
                        </div>

                        <!-- Productos -->
                        <h3 class="mb-3 font-medium text-gray-700 text-md">Productos Ordenados</h3>
                        <div class="mb-6 overflow-x-auto border border-gray-200 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase">
                                            Producto</th>
                                        <th scope="col"
                                            class="px-4 py-3 text-xs font-medium text-center text-gray-500 uppercase">
                                            Cant.</th>
                                        <th scope="col"
                                            class="px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase">
                                            Precio</th>
                                        <th scope="col"
                                            class="px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase">
                                            Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">Lomo Saltado</td>
                                        <td class="px-4 py-3 text-sm text-center text-gray-500">2</td>
                                        <td class="px-4 py-3 text-sm text-right text-gray-500">S/ 28.00</td>
                                        <td class="px-4 py-3 text-sm text-right text-gray-500">S/ 56.00</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">Chicha Morada 1L</td>
                                        <td class="px-4 py-3 text-sm text-center text-gray-500">1</td>
                                        <td class="px-4 py-3 text-sm text-right text-gray-500">S/ 12.00</td>
                                        <td class="px-4 py-3 text-sm text-right text-gray-500">S/ 12.00</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">Ensalada Mixta</td>
                                        <td class="px-4 py-3 text-sm text-center text-gray-500">1</td>
                                        <td class="px-4 py-3 text-sm text-right text-gray-500">S/ 10.50</td>
                                        <td class="px-4 py-3 text-sm text-right text-gray-500">S/ 10.50</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Totales -->
                        <div class="p-4 rounded-lg bg-gray-50">
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-medium">S/ 78.50</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600">IGV (18%):</span>
                                <span class="font-medium">S/ 14.13</span>
                            </div>
                            <div class="flex justify-between py-2 text-lg font-bold">
                                <span>Total:</span>
                                <span>S/ 92.63</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulario de venta -->
                <div class="overflow-hidden bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 bg-green-600">
                        <h2 class="text-lg font-semibold text-white">Datos del Comprobante</h2>
                    </div>

                    <div class="p-6">
                        <form>
                            <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                                <!-- Tipo de comprobante -->
                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700">
                                        Tipo de comprobante *
                                    </label>
                                    <select
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
                                        <option value="">Seleccionar...</option>
                                        <option value="Boleta">Boleta</option>
                                        <option value="Factura">Factura</option>
                                        <option value="Ticket">Ticket</option>
                                    </select>
                                </div>

                                <!-- Método de pago -->
                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700">
                                        Método de pago *
                                    </label>
                                    <select
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
                                        <option value="">Seleccionar...</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Tarjeta de Débito">Tarjeta de Débito</option>
                                        <option value="Tarjeta de Crédito">Tarjeta de Crédito</option>
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Yape/Plin">Yape/Plin</option>
                                    </select>
                                </div>

                                <!-- RUC Empresa -->
                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700">
                                        RUC *
                                    </label>
                                    <input type="text"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                                        placeholder="Ej. 20123456789" value="20123456789">
                                </div>

                                <!-- Nombre de empresa -->
                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700">
                                        Nombre de empresa *
                                    </label>
                                    <input type="text"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                                        placeholder="Ej. Mi Restaurante S.A.C."
                                        value="RESTAURANTE EL BUEN SABOR S.A.C.">
                                </div>

                                <!-- Dirección -->
                                <div class="md:col-span-2">
                                    <label class="block mb-1 text-sm font-medium text-gray-700">
                                        Dirección *
                                    </label>
                                    <input type="text"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                                        placeholder="Ej. Av. Principal 123, Lima" value="Av. Arequipa 1250, Lima">
                                </div>

                                <!-- Observaciones -->
                                <div class="md:col-span-2">
                                    <label class="block mb-1 text-sm font-medium text-gray-700">
                                        Observaciones
                                    </label>
                                    <textarea
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                                        rows="2" placeholder="Observaciones adicionales"></textarea>
                                </div>
                            </div>

                            <!-- Botón de generar venta -->
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="inline-flex items-center px-5 py-3 font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Generar Comprobante
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
