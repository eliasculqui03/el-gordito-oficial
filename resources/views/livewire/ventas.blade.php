<div>
    @vite('resources/css/app.css')
    <div class="min-h-screen transition-colors duration-200 bg-gray-100 dark:bg-gray-900">
        <!--
        =================================================
        SECCIÓN 1: ENCABEZADO / INFORMACIÓN EMPRESARIAL
        =================================================
        -->
        <div class="p-4 mb-4 transition-colors duration-200 bg-white shadow-md dark:bg-gray-800 dark:shadow-gray-700/30">
            <!-- INSERTAR AQUÍ EL CÓDIGO DEL ENCABEZADO -->
            <div class="flex flex-col justify-between md:flex-row md:items-center">
                <div class="flex items-center space-x-3">
                    <div
                        class="p-3 text-white rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 1h6v4H7V5zm8 8v2h1v1H4v-1h1v-2a1 1 0 011-1h8a1 1 0 011 1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">CAYOTOPA TANTALEAN E.I.R.L</h1>
                        <h2 class="font-medium text-indigo-600 dark:text-indigo-400">RESTAURANT EL GORDITO</h2>
                    </div>
                </div>
                <div class="mt-3 md:mt-0">
                    <div
                        class="p-3 text-center border-2 border-indigo-500 rounded-lg dark:border-indigo-400 bg-indigo-50 dark:bg-indigo-900/20">
                        <p class="font-medium text-gray-700 dark:text-gray-300">PEDIDO</p>
                        <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400">N° 000004</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-2 mt-3 md:grid-cols-2">
                <div>
                    <div class="flex items-start space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-indigo-500 dark:text-indigo-400 mt-0.5" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">NRO. SN CAS. EL PROGRESO LAMBAYEQUE - CHICLAYO -
                            PATAPO</span>
                    </div>
                    <div class="flex items-center mt-1 space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500 dark:text-indigo-400"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">RUC: 20608161296</span>
                    </div>
                    <div class="flex items-center mt-1 space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500 dark:text-indigo-400"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">cayotopatantalen@gmail.com</span>
                    </div>
                    <div class="flex items-center mt-1 space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500 dark:text-indigo-400"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">Usuario: ADMINISTRADOR</span>
                    </div>
                    <div class="flex items-center mt-1 space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500 dark:text-indigo-400"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3 3a1 1 0 000 2h10a1 1 0 100-2H3zm0 4a1 1 0 000 2h6a1 1 0 100-2H3zm0 4a1 1 0 100 2h8a1 1 0 100-2H3z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">Caja: CAJA 1</span>
                    </div>
                </div>
                <div class="flex items-center justify-end">
                    <div class="text-right">
                        <div class="flex items-center justify-end">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 mr-2 text-indigo-500 dark:text-indigo-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-gray-700 dark:text-gray-300">Fecha: 21/03/2025</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="px-4 pb-4">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <!-- Panel izquierdo (ocupa 2 columnas en pantallas grandes) -->
                <div class="space-y-4 lg:col-span-2">
                    <!--
                    =================================================
                    SECCIÓN 2: GESTIÓN DE CLIENTES
                    =================================================
                    -->
                    <div
                        class="p-4 transition-colors duration-200 bg-white rounded-lg shadow dark:bg-gray-800 dark:shadow-gray-700/30">
                        <!-- INSERTAR AQUÍ EL CÓDIGO DE GESTIÓN DE CLIENTES -->
                        <div class="flex items-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-6 h-6 mr-2 text-indigo-500 dark:text-indigo-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">DATOS DEL CLIENTE</h2>
                        </div>

                        <div class="flex flex-col mb-4 space-y-2 md:flex-row md:space-y-0 md:space-x-2">
                            <div class="flex-1">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" placeholder="Buscar cliente por RUC/DNI o nombre"
                                        class="w-full py-2 pl-10 pr-3 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:text-gray-200">
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button
                                    class="px-4 py-2 text-white transition-colors duration-200 bg-green-600 rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400">
                                    <span class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                                        </svg>
                                        Nuevo
                                    </span>
                                </button>
                                <button
                                    class="px-4 py-2 text-white transition-colors duration-200 bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                    <span class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Consultar
                                    </span>
                                </button>
                            </div>
                        </div>

                        <!-- Cliente seleccionado -->
                        <div
                            class="p-3 mt-2 border border-indigo-200 rounded-md bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-800">
                            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5 text-indigo-500 dark:text-indigo-400" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <div>
                                            <span class="text-sm text-indigo-600 dark:text-indigo-400">NOMBRE</span>
                                            <p class="font-medium text-gray-800 dark:text-gray-200">ELIAS CULQUI
                                                SANCHEZ
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center mt-2 space-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5 text-indigo-500 dark:text-indigo-400" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                        </svg>
                                        <div>
                                            <span class="text-sm text-indigo-600 dark:text-indigo-400">CORREO</span>
                                            <p class="font-medium text-gray-700 dark:text-gray-300">No registrado</p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex items-center space-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5 text-indigo-500 dark:text-indigo-400" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 1h6v4H7V5zm8 8v2h1v1H4v-1h1v-2a1 1 0 011-1h8a1 1 0 011 1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <div>
                                            <span class="text-sm text-indigo-600 dark:text-indigo-400">N°
                                                DOCUMENTO</span>
                                            <p class="font-medium text-gray-800 dark:text-gray-200">75655963</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center mt-2 space-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5 text-indigo-500 dark:text-indigo-400" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <div>
                                            <span class="text-sm text-indigo-600 dark:text-indigo-400">MESA -
                                                ZONA</span>
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-800 dark:text-gray-200">Mesa 37</p>
                                                <span class="mx-2 text-gray-500 dark:text-gray-400">•</span>
                                                <p class="font-medium text-indigo-600 dark:text-indigo-400">Módulo 1
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="flex items-center justify-between pt-3 mt-3 border-t border-indigo-200 dark:border-indigo-800">
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    <span>Última compra: </span>
                                    <span class="font-medium text-gray-800 dark:text-gray-200">17/03/2025</span>
                                    <span class="mx-2">|</span>
                                    <span>Total: </span>
                                    <span class="font-medium text-gray-800 dark:text-gray-200">S/. 156.70</span>
                                </div>
                                <div class="flex space-x-2">
                                    <button
                                        class="p-1 text-indigo-500 transition-colors duration-200 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </button>
                                    <button
                                        class="p-1 text-red-500 transition-colors duration-200 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Historial rápido de compras (opcional) -->
                        <div class="mt-3 overflow-hidden border border-gray-200 rounded-md dark:border-gray-600">
                            <div
                                class="flex items-center justify-between px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                                <span>Historial de Compras</span>
                                <button
                                    class="text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M3 3a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <div class="p-2 overflow-y-auto bg-white max-h-32 dark:bg-gray-800">
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <li
                                        class="px-2 py-2 transition-colors duration-150 rounded hover:bg-indigo-50 dark:hover:bg-indigo-900/20">
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">17/03/2025</span>
                                            <span class="text-sm font-medium text-gray-800 dark:text-gray-200">S/.
                                                156.70</span>
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-500">Comprobante:
                                            F001-000003</div>
                                    </li>
                                    <li
                                        class="px-2 py-2 transition-colors duration-150 rounded hover:bg-indigo-50 dark:hover:bg-indigo-900/20">
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">10/03/2025</span>
                                            <span class="text-sm font-medium text-gray-800 dark:text-gray-200">S/.
                                                78.20</span>
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-500">Comprobante:
                                            F001-000002</div>
                                    </li>
                                    <li
                                        class="px-2 py-2 transition-colors duration-150 rounded hover:bg-indigo-50 dark:hover:bg-indigo-900/20">
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">03/03/2025</span>
                                            <span class="text-sm font-medium text-gray-800 dark:text-gray-200">S/.
                                                205.30</span>
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-500">Comprobante:
                                            F001-000001</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!--
                    =================================================
                    SECCIÓN 3: SELECCIÓN DE MESA / ÁREA
                    =================================================
                    -->
                    <div
                        class="p-4 transition-colors duration-200 bg-white rounded-lg shadow dark:bg-gray-800 dark:shadow-gray-700/30">
                        <!-- INSERTAR AQUÍ EL CÓDIGO DE SELECCIÓN DE MESA -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 mr-2 text-indigo-500 dark:text-indigo-400" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2zM3 16a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2z" />
                                </svg>
                                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Selección de Mesa
                                </h2>
                            </div>
                            <div class="flex items-center space-x-2">
                                <select
                                    class="px-2 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:text-gray-200">
                                    <option value="all">Todas las zonas</option>
                                    <option value="modulo1" selected>Módulo 1</option>
                                    <option value="modulo2">Módulo 2</option>
                                    <option value="terraza">Terraza</option>
                                </select>
                                <button
                                    class="p-1 text-indigo-500 transition-colors duration-200 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7.805v2.202a1 1 0 01-2 0V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Vista de cuadrícula de mesas -->
                        <div class="mb-3">
                            <div class="grid grid-cols-4 gap-2 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10">
                                <!-- Mesas disponibles, ocupadas y seleccionadas -->
                                <div
                                    class="p-2 text-center text-gray-800 transition-colors duration-200 bg-gray-100 rounded-md cursor-pointer dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-200">
                                    <span>1</span>
                                </div>
                                <div
                                    class="p-2 text-center text-white transition-colors duration-200 bg-indigo-500 rounded-md shadow-md cursor-pointer dark:bg-indigo-600">
                                    <span>2</span>
                                </div>
                                <div
                                    class="p-2 text-center text-red-800 transition-colors duration-200 bg-red-100 rounded-md cursor-pointer dark:bg-red-900 dark:text-red-200">
                                    <span>3</span>
                                </div>
                                <div
                                    class="p-2 text-center text-gray-800 transition-colors duration-200 bg-gray-100 rounded-md cursor-pointer dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-200">
                                    <span>4</span>
                                </div>
                                <div
                                    class="p-2 text-center text-red-800 transition-colors duration-200 bg-red-100 rounded-md cursor-pointer dark:bg-red-900 dark:text-red-200">
                                    <span>5</span>
                                </div>
                                <div
                                    class="p-2 text-center text-gray-800 transition-colors duration-200 bg-gray-100 rounded-md cursor-pointer dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-200">
                                    <span>6</span>
                                </div>
                                <div
                                    class="p-2 text-center text-gray-800 transition-colors duration-200 bg-gray-100 rounded-md cursor-pointer dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-200">
                                    <span>7</span>
                                </div>
                                <div
                                    class="p-2 text-center text-gray-800 transition-colors duration-200 bg-gray-100 rounded-md cursor-pointer dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-200">
                                    <span>8</span>
                                </div>
                                <div
                                    class="p-2 text-center text-red-800 transition-colors duration-200 bg-red-100 rounded-md cursor-pointer dark:bg-red-900 dark:text-red-200">
                                    <span>9</span>
                                </div>
                                <div
                                    class="p-2 text-center text-gray-800 transition-colors duration-200 bg-gray-100 rounded-md cursor-pointer dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-200">
                                    <span>10</span>
                                </div>
                                <div
                                    class="p-2 text-center text-gray-800 transition-colors duration-200 bg-gray-100 rounded-md cursor-pointer dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-200">
                                    <span>11</span>
                                </div>
                                <div
                                    class="p-2 text-center text-gray-800 transition-colors duration-200 bg-gray-100 rounded-md cursor-pointer dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-200">
                                    <span>12</span>
                                </div>
                                <div
                                    class="p-2 text-center text-gray-800 transition-colors duration-200 bg-gray-100 rounded-md cursor-pointer dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-200">
                                    <span>13</span>
                                </div>
                                <div
                                    class="p-2 text-center text-red-800 transition-colors duration-200 bg-red-100 rounded-md cursor-pointer dark:bg-red-900 dark:text-red-200">
                                    <span>14</span>
                                </div>
                                <div
                                    class="p-2 text-center text-gray-800 transition-colors duration-200 bg-gray-100 rounded-md cursor-pointer dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-200">
                                    <span>15</span>
                                </div>
                                <div
                                    class="p-2 text-center text-gray-800 transition-colors duration-200 bg-gray-100 rounded-md cursor-pointer dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-200">
                                    <span>16</span>
                                </div>
                            </div>
                        </div>

                        <!-- Leyenda -->
                        <div class="flex flex-wrap items-center justify-start space-x-4 text-sm">
                            <div class="flex items-center space-x-1">
                                <div class="w-4 h-4 bg-gray-100 rounded-sm dark:bg-gray-700"></div>
                                <span class="text-gray-600 dark:text-gray-400">Disponible</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <div class="w-4 h-4 bg-red-100 rounded-sm dark:bg-red-900"></div>
                                <span class="text-gray-600 dark:text-gray-400">Ocupada</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <div class="w-4 h-4 bg-indigo-500 rounded-sm dark:bg-indigo-600"></div>
                                <span class="text-gray-600 dark:text-gray-400">Seleccionada</span>
                            </div>
                        </div>

                        <!-- Vista alternativa de mapa (puedes comentar/descomentar según lo necesites) -->
                        <div
                            class="hidden mt-4 overflow-hidden border border-gray-200 rounded-lg dark:border-gray-700">
                            <div class="relative p-4 bg-white dark:bg-gray-800">
                                <!-- Título de la vista de mapa -->
                                <h3 class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Vista de Mapa
                                </h3>

                                <!-- Cuadrícula ejemplo de un mapa de restaurante -->
                                <div class="relative h-64 p-2 bg-gray-100 rounded-md dark:bg-gray-700">
                                    <!-- Zona 1 -->
                                    <div
                                        class="absolute w-1/3 p-1 border border-gray-400 border-dashed rounded top-2 left-2 h-1/2 dark:border-gray-500">
                                        <div class="mb-1 text-xs font-medium text-gray-600 dark:text-gray-400">Módulo 1
                                        </div>
                                        <div class="grid grid-cols-2 gap-1">
                                            <div
                                                class="p-1 text-xs text-center text-gray-800 bg-gray-200 rounded-sm dark:bg-gray-600 dark:text-gray-200">
                                                1</div>
                                            <div
                                                class="p-1 text-xs text-center text-red-800 bg-red-100 rounded-sm dark:bg-red-900 dark:text-red-200">
                                                2</div>
                                            <div
                                                class="p-1 text-xs text-center text-gray-800 bg-gray-200 rounded-sm dark:bg-gray-600 dark:text-gray-200">
                                                3</div>
                                            <div
                                                class="p-1 text-xs text-center text-gray-800 bg-gray-200 rounded-sm dark:bg-gray-600 dark:text-gray-200">
                                                4</div>
                                        </div>
                                    </div>

                                    <!-- Zona 2 -->
                                    <div
                                        class="absolute w-1/3 p-1 border border-gray-400 border-dashed rounded top-2 right-2 h-1/2 dark:border-gray-500">
                                        <div class="mb-1 text-xs font-medium text-gray-600 dark:text-gray-400">Módulo 2
                                        </div>
                                        <div class="grid grid-cols-2 gap-1">
                                            <div
                                                class="p-1 text-xs text-center text-white bg-indigo-500 rounded-sm dark:bg-indigo-600">
                                                5</div>
                                            <div
                                                class="p-1 text-xs text-center text-gray-800 bg-gray-200 rounded-sm dark:bg-gray-600 dark:text-gray-200">
                                                6</div>
                                            <div
                                                class="p-1 text-xs text-center text-gray-800 bg-gray-200 rounded-sm dark:bg-gray-600 dark:text-gray-200">
                                                7</div>
                                            <div
                                                class="p-1 text-xs text-center text-red-800 bg-red-100 rounded-sm dark:bg-red-900 dark:text-red-200">
                                                8</div>
                                        </div>
                                    </div>

                                    <!-- Zona 3 -->
                                    <div
                                        class="absolute p-1 border border-gray-400 border-dashed rounded bottom-2 left-2 right-2 h-1/3 dark:border-gray-500">
                                        <div class="mb-1 text-xs font-medium text-gray-600 dark:text-gray-400">Terraza
                                        </div>
                                        <div class="grid grid-cols-4 gap-1">
                                            <div
                                                class="p-1 text-xs text-center text-gray-800 bg-gray-200 rounded-sm dark:bg-gray-600 dark:text-gray-200">
                                                9</div>
                                            <div
                                                class="p-1 text-xs text-center text-gray-800 bg-gray-200 rounded-sm dark:bg-gray-600 dark:text-gray-200">
                                                10</div>
                                            <div
                                                class="p-1 text-xs text-center text-red-800 bg-red-100 rounded-sm dark:bg-red-900 dark:text-red-200">
                                                11</div>
                                            <div
                                                class="p-1 text-xs text-center text-gray-800 bg-gray-200 rounded-sm dark:bg-gray-600 dark:text-gray-200">
                                                12</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--
                    =================================================
                    SECCIÓN 4: PRODUCTOS / MENÚ
                    =================================================
                    -->
                    <div
                        class="transition-colors duration-200 bg-white rounded-lg shadow dark:bg-gray-800 dark:shadow-gray-700/30">
                        <!-- Navegación entre pestañas con Tailwind (sin JavaScript) -->
                        <div class="border-b border-gray-200 dark:border-gray-700">
                            <div class="flex -mb-px">
                                <!-- Usando radio inputs ocultos para controlar la visibilidad de los tabs -->
                                <div class="relative">
                                    <input type="radio" name="tabs" id="tab-existencias" class="sr-only peer"
                                        checked>
                                    <label for="tab-existencias"
                                        class="block px-4 py-3 text-sm font-medium leading-5 text-gray-500 transition-colors duration-200 cursor-pointer rounded-t-md peer-checked:text-indigo-600 peer-checked:border-b-2 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:text-indigo-400 dark:peer-checked:border-indigo-400 dark:peer-checked:bg-indigo-900/30 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                        Existencias
                                    </label>
                                </div>
                                <div class="relative">
                                    <input type="radio" name="tabs" id="tab-platos" class="sr-only peer">
                                    <label for="tab-platos"
                                        class="block px-4 py-3 text-sm font-medium leading-5 text-gray-500 transition-colors duration-200 cursor-pointer rounded-t-md peer-checked:text-indigo-600 peer-checked:border-b-2 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:text-indigo-400 dark:peer-checked:border-indigo-400 dark:peer-checked:bg-indigo-900/30 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                        Platos
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!--
                        =================================================
                        SECCIÓN 4.1: EXISTENCIAS (INVENTARIO)
                        =================================================
                        -->
                        <div class="p-4 block [#tab-platos:checked~&]:hidden" id="existencias-content">
                            <!-- INSERTAR AQUÍ EL CÓDIGO DE EXISTENCIAS -->
                            <!-- Filtros de categorías para existencias -->
                            <div class="mb-4">
                                <h3 class="mb-2 font-medium text-gray-700 text-md dark:text-gray-300">Categorías</h3>
                                <div class="flex flex-wrap gap-2">
                                    <button
                                        class="px-3 py-1 text-sm text-white transition-colors duration-200 bg-indigo-500 rounded-full shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                        Todas
                                    </button>
                                    <button
                                        class="px-3 py-1 text-sm text-gray-700 transition-colors duration-200 bg-gray-200 rounded-full hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500">
                                        Bebidas
                                    </button>
                                    <button
                                        class="px-3 py-1 text-sm text-gray-700 transition-colors duration-200 bg-gray-200 rounded-full hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500">
                                        Abarrotes
                                    </button>
                                    <button
                                        class="px-3 py-1 text-sm text-gray-700 transition-colors duration-200 bg-gray-200 rounded-full hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500">
                                        Carnes
                                    </button>
                                    <button
                                        class="px-3 py-1 text-sm text-gray-700 transition-colors duration-200 bg-gray-200 rounded-full hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500">
                                        Lácteos
                                    </button>
                                </div>
                            </div>

                            <!-- Barra de búsqueda para productos -->
                            <div class="relative mb-4">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" placeholder="Buscar producto por nombre o código"
                                        class="w-full py-2 pl-10 pr-3 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:bg-gray-700 dark:text-gray-200">
                                </div>
                            </div>

                            <!-- Filtros adicionales -->
                            <div class="mb-4">
                                <div class="flex flex-wrap gap-2">
                                    <select
                                        class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:text-gray-200">
                                        <option value="">Todos los tipos</option>
                                        <option value="perecible">Perecible</option>
                                        <option value="no-perecible">No Perecible</option>
                                        <option value="congelado">Congelado</option>
                                    </select>

                                    <select
                                        class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:text-gray-200">
                                        <option value="">Ordenar por</option>
                                        <option value="nombre-asc">Nombre: A-Z</option>
                                        <option value="nombre-desc">Nombre: Z-A</option>
                                        <option value="precio-asc">Precio: Menor a Mayor</option>
                                        <option value="precio-desc">Precio: Mayor a Menor</option>
                                        <option value="stock-asc">Stock: Menor a Mayor</option>
                                        <option value="stock-desc">Stock: Mayor a Menor</option>
                                    </select>

                                    <button
                                        class="flex items-center px-3 py-2 text-sm text-white transition-colors duration-200 bg-indigo-500 rounded-md shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Filtros
                                    </button>
                                </div>
                            </div>

                            <!-- Lista de existencias -->
                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
                                <!-- Producto 1 -->
                                <div
                                    class="p-3 transition-all duration-200 bg-white border border-gray-200 rounded-md cursor-pointer dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-700/20 dark:bg-gray-800 hover:border-indigo-300 dark:hover:border-indigo-700">
                                    <div
                                        class="mb-2 overflow-hidden bg-gray-200 rounded-md aspect-w-1 aspect-h-1 dark:bg-gray-700">
                                        <img src="https://via.placeholder.com/150" alt="Agua Mineral 500ml"
                                            class="object-cover w-full h-full">
                                    </div>
                                    <p class="font-medium text-gray-800 truncate dark:text-gray-200">Agua Mineral 500ml
                                    </p>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">AG001</span>
                                        <span class="font-semibold text-indigo-600 dark:text-indigo-400">S/.
                                            2.50</span>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-xs text-gray-500 dark:text-gray-500">Stock: 48</span>
                                        <div class="flex space-x-1">
                                            <button
                                                class="p-1 text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <button
                                                class="p-1 text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Producto 2 -->
                                <div
                                    class="p-3 transition-all duration-200 bg-white border border-gray-200 rounded-md cursor-pointer dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-700/20 dark:bg-gray-800 hover:border-indigo-300 dark:hover:border-indigo-700">
                                    <div
                                        class="mb-2 overflow-hidden bg-gray-200 rounded-md aspect-w-1 aspect-h-1 dark:bg-gray-700">
                                        <img src="https://via.placeholder.com/150" alt="Gaseosa Cola 1L"
                                            class="object-cover w-full h-full">
                                    </div>
                                    <p class="font-medium text-gray-800 truncate dark:text-gray-200">Gaseosa Cola 1L
                                    </p>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">GS002</span>
                                        <span class="font-semibold text-indigo-600 dark:text-indigo-400">S/.
                                            5.00</span>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-xs text-gray-500 dark:text-gray-500">Stock: 36</span>
                                        <div class="flex space-x-1">
                                            <button
                                                class="p-1 text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <button
                                                class="p-1 text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Producto 3 -->
                                <div
                                    class="p-3 transition-all duration-200 bg-white border border-gray-200 rounded-md cursor-pointer dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-700/20 dark:bg-gray-800 hover:border-indigo-300 dark:hover:border-indigo-700">
                                    <div
                                        class="mb-2 overflow-hidden bg-gray-200 rounded-md aspect-w-1 aspect-h-1 dark:bg-gray-700">
                                        <img src="https://via.placeholder.com/150" alt="Arroz Extra 1kg"
                                            class="object-cover w-full h-full">
                                    </div>
                                    <p class="font-medium text-gray-800 truncate dark:text-gray-200">Arroz Extra 1kg
                                    </p>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">AR003</span>
                                        <span class="font-semibold text-indigo-600 dark:text-indigo-400">S/.
                                            4.50</span>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-xs text-gray-500 dark:text-gray-500">Stock: 25</span>
                                        <div class="flex space-x-1">
                                            <button
                                                class="p-1 text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <button
                                                class="p-1 text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Producto 4 -->
                                <div
                                    class="p-3 transition-all duration-200 bg-white border border-gray-200 rounded-md cursor-pointer dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-700/20 dark:bg-gray-800 hover:border-indigo-300 dark:hover:border-indigo-700">
                                    <div
                                        class="mb-2 overflow-hidden bg-gray-200 rounded-md aspect-w-1 aspect-h-1 dark:bg-gray-700">
                                        <img src="https://via.placeholder.com/150" alt="Aceite Vegetal 1L"
                                            class="object-cover w-full h-full">
                                    </div>
                                    <p class="font-medium text-gray-800 truncate dark:text-gray-200">Aceite Vegetal 1L
                                    </p>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">AC004</span>
                                        <span class="font-semibold text-indigo-600 dark:text-indigo-400">S/.
                                            9.90</span>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-xs text-gray-500 dark:text-gray-500">Stock: 18</span>
                                        <div class="flex space-x-1">
                                            <button
                                                class="p-1 text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <button
                                                class="p-1 text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Producto 5 (stock bajo) -->
                                <div
                                    class="p-3 transition-all duration-200 border border-orange-200 rounded-md cursor-pointer dark:border-orange-800 hover:shadow-md dark:hover:shadow-gray-700/20 bg-orange-50 dark:bg-orange-900/20">
                                    <div
                                        class="mb-2 overflow-hidden bg-gray-200 rounded-md aspect-w-1 aspect-h-1 dark:bg-gray-700">
                                        <img src="https://via.placeholder.com/150" alt="Azúcar Rubia 1kg"
                                            class="object-cover w-full h-full">
                                    </div>
                                    <p class="font-medium text-gray-800 truncate dark:text-gray-200">Azúcar Rubia 1kg
                                    </p>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">AZ005</span>
                                        <span class="font-semibold text-indigo-600 dark:text-indigo-400">S/.
                                            3.80</span>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-xs font-medium text-orange-500 dark:text-orange-400">Stock
                                            bajo: 3</span>
                                        <div class="flex space-x-1">
                                            <button
                                                class="p-1 text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <button
                                                class="p-1 text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Producto 6 (sin stock) -->
                                <div
                                    class="p-3 transition-all duration-200 border border-red-200 rounded-md cursor-pointer dark:border-red-800 hover:shadow-md dark:hover:shadow-gray-700/20 bg-red-50 dark:bg-red-900/20">
                                    <div
                                        class="mb-2 overflow-hidden bg-gray-200 rounded-md aspect-w-1 aspect-h-1 dark:bg-gray-700 opacity-60">
                                        <img src="https://via.placeholder.com/150" alt="Leche Evaporada"
                                            class="object-cover w-full h-full">
                                        <div
                                            class="absolute inset-0 flex items-center justify-center bg-red-500/20 dark:bg-red-700/40">
                                            <span
                                                class="px-2 py-1 text-xs font-medium text-white bg-red-500 rounded">Sin
                                                stock</span>
                                        </div>
                                    </div>
                                    <p class="font-medium text-gray-800 truncate dark:text-gray-200">Leche Evaporada
                                    </p>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">LE006</span>
                                        <span class="font-semibold text-indigo-600 dark:text-indigo-400">S/.
                                            4.20</span>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-xs font-medium text-red-500 dark:text-red-400">Sin
                                            stock</span>
                                        <div class="flex space-x-1">
                                            <button
                                                class="p-1 text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <button disabled
                                                class="p-1 text-gray-400 cursor-not-allowed dark:text-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Paginación -->
                            <div class="flex items-center justify-between mt-6">
                                <button
                                    class="flex items-center px-3 py-1 text-gray-700 transition-colors duration-200 bg-white border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Anterior
                                </button>

                                <div class="flex items-center space-x-1 text-sm">
                                    <span
                                        class="px-3 py-1 text-white bg-indigo-500 rounded-md shadow-sm dark:bg-indigo-600">1</span>
                                    <span
                                        class="px-3 py-1 text-gray-700 bg-white rounded-md cursor-pointer dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600">2</span>
                                    <span
                                        class="px-3 py-1 text-gray-700 bg-white rounded-md cursor-pointer dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600">3</span>
                                </div>

                                <button
                                    class="flex items-center px-3 py-1 text-gray-700 transition-colors duration-200 bg-white border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                    Siguiente
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!--
                        =================================================
                        SECCIÓN 4.2: PLATOS (COCINA)
                        =================================================
                        -->
                        <div class="p-4 hidden [#tab-platos:checked~&]:block" id="platos-content">
                            <!-- INSERTAR AQUÍ EL CÓDIGO DE PLATOS -->
                            <div class="mb-4">
                                <h3 class="mb-2 font-medium text-gray-700 text-md dark:text-gray-300">Categorías de
                                    Platos</h3>
                                <div class="flex flex-wrap gap-2">
                                    <button
                                        class="px-3 py-1 text-sm text-white transition-colors duration-200 bg-indigo-500 rounded-full shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                        Todos
                                    </button>
                                    <button
                                        class="px-3 py-1 text-sm text-gray-700 transition-colors duration-200 bg-gray-200 rounded-full hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500">
                                        Entradas
                                    </button>
                                    <button
                                        class="px-3 py-1 text-sm text-gray-700 transition-colors duration-200 bg-gray-200 rounded-full hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500">
                                        Platos Principales
                                    </button>
                                    <button
                                        class="px-3 py-1 text-sm text-gray-700 transition-colors duration-200 bg-gray-200 rounded-full hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500">
                                        Postres
                                    </button>
                                    <button
                                        class="px-3 py-1 text-sm text-gray-700 transition-colors duration-200 bg-gray-200 rounded-full hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500">
                                        Bebidas
                                    </button>
                                </div>
                            </div>

                            <!-- Barra de búsqueda para platos -->
                            <div class="relative mb-4">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" placeholder="Buscar plato por nombre"
                                        class="w-full py-2 pl-10 pr-3 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:bg-gray-700 dark:text-gray-200">
                                </div>
                            </div>

                            <!-- Lista de platos -->
                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
                                <!-- Plato 1 -->
                                <div
                                    class="p-3 transition-all duration-200 bg-white border border-gray-200 rounded-md cursor-pointer dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-700/20 dark:bg-gray-800 hover:border-indigo-300 dark:hover:border-indigo-700">
                                    <div
                                        class="mb-2 overflow-hidden bg-gray-200 rounded-md aspect-w-1 aspect-h-1 dark:bg-gray-700">
                                        <img src="https://via.placeholder.com/150" alt="Pollo a la Brasa"
                                            class="object-cover w-full h-full">
                                    </div>
                                    <p class="font-medium text-gray-800 truncate dark:text-gray-200">Pollo a la Brasa
                                        (1/4)</p>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">PL001</span>
                                        <span class="font-semibold text-indigo-600 dark:text-indigo-400">S/.
                                            18.90</span>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-xs text-green-500 dark:text-green-400">Disponible</span>
                                        <button
                                            class="p-1 text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Plato 2 -->
                                <div
                                    class="p-3 transition-all duration-200 bg-white border border-gray-200 rounded-md cursor-pointer dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-700/20 dark:bg-gray-800 hover:border-indigo-300 dark:hover:border-indigo-700">
                                    <div
                                        class="mb-2 overflow-hidden bg-gray-200 rounded-md aspect-w-1 aspect-h-1 dark:bg-gray-700">
                                        <img src="https://via.placeholder.com/150" alt="Lomo Saltado"
                                            class="object-cover w-full h-full">
                                    </div>
                                    <p class="font-medium text-gray-800 truncate dark:text-gray-200">Lomo Saltado</p>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">PL002</span>
                                        <span class="font-semibold text-indigo-600 dark:text-indigo-400">S/.
                                            22.50</span>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-xs text-green-500 dark:text-green-400">Disponible</span>
                                        <button
                                            class="p-1 text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Plato 3 -->
                                <div
                                    class="p-3 transition-all duration-200 bg-white border border-gray-200 rounded-md cursor-pointer dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-700/20 dark:bg-gray-800 hover:border-indigo-300 dark:hover:border-indigo-700">
                                    <div
                                        class="mb-2 overflow-hidden bg-gray-200 rounded-md aspect-w-1 aspect-h-1 dark:bg-gray-700">
                                        <img src="https://via.placeholder.com/150" alt="Ceviche Mixto"
                                            class="object-cover w-full h-full">
                                    </div>
                                    <p class="font-medium text-gray-800 truncate dark:text-gray-200">Ceviche Mixto</p>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">PL003</span>
                                        <span class="font-semibold text-indigo-600 dark:text-indigo-400">S/.
                                            25.00</span>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-xs text-green-500 dark:text-green-400">Disponible</span>
                                        <button
                                            class="p-1 text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Plato 4 -->
                                <div
                                    class="p-3 transition-all duration-200 bg-white border border-gray-200 rounded-md cursor-pointer dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-700/20 dark:bg-gray-800 hover:border-indigo-300 dark:hover:border-indigo-700">
                                    <div
                                        class="mb-2 overflow-hidden bg-gray-200 rounded-md aspect-w-1 aspect-h-1 dark:bg-gray-700">
                                        <img src="https://via.placeholder.com/150" alt="Arroz con Mariscos"
                                            class="object-cover w-full h-full">
                                    </div>
                                    <p class="font-medium text-gray-800 truncate dark:text-gray-200">Arroz con Mariscos
                                    </p>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">PL004</span>
                                        <span class="font-semibold text-indigo-600 dark:text-indigo-400">S/.
                                            24.50</span>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-xs text-green-500 dark:text-green-400">Disponible</span>
                                        <button
                                            class="p-1 text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div> />
                                    </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Paginación -->
                        <div class="flex items-center justify-between mt-6">
                            <button
                                class="flex items-center px-3 py-1 text-gray-700 transition-colors duration-200 bg-white border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Anterior
                            </button>

                            <div class="flex items-center space-x-1 text-sm">
                                <span
                                    class="px-3 py-1 text-white bg-indigo-500 rounded-md shadow-sm dark:bg-indigo-600">1</span>
                                <span
                                    class="px-3 py-1 text-gray-700 bg-white rounded-md cursor-pointer dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600">2</span>
                            </div>

                            <button
                                class="flex items-center px-3 py-1 text-gray-700 transition-colors duration-200 bg-white border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                Siguiente
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel derecho -->
            <div class="space-y-4">
                <!--
                    =================================================
                    SECCIÓN 5: ORDEN ACTUAL
                    =================================================
                    -->
                <div
                    class="p-4 transition-colors duration-200 bg-white rounded-lg shadow dark:bg-gray-800 dark:shadow-gray-700/30">
                    <!-- INSERTAR AQUÍ EL CÓDIGO DE ORDEN ACTUAL -->
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Orden Actual</h2>
                        <div class="flex space-x-2">
                            <button
                                class="px-2 py-1 text-sm text-indigo-700 transition-colors duration-200 bg-indigo-100 rounded hover:bg-indigo-200 dark:bg-indigo-900/30 dark:hover:bg-indigo-800/40 dark:text-indigo-400">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z" />
                                    </svg>
                                    Guardar
                                </span>
                            </button>
                            <button
                                class="px-2 py-1 text-sm text-red-600 transition-colors duration-200 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Limpiar
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Información de cliente y mesa -->
                    <div
                        class="px-3 py-2 mb-3 text-sm border border-indigo-100 rounded-md bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-800">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-gray-600 dark:text-gray-400">Cliente:</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">ELIAS CULQUI SANCHEZ</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Mesa:</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">Mesa 37 - Módulo 1</span>
                        </div>
                    </div>

                    <!-- Lista de items en la orden -->
                    <div class="mb-3 overflow-y-auto border border-gray-200 rounded-md max-h-60 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                                        Producto
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-2 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-400">
                                        Precio
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400">
                                        Cant.
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-2 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-400">
                                        Subtotal
                                    </th>
                                    <th scope="col" class="relative px-3 py-2">
                                        <span class="sr-only">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                <!-- Item 1 -->
                                <tr
                                    class="transition-colors duration-150 hover:bg-indigo-50 dark:hover:bg-indigo-900/20">
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-200">Agua
                                            Mineral 500ml</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">AG001</div>
                                    </td>
                                    <td
                                        class="px-3 py-2 text-sm text-right text-gray-500 whitespace-nowrap dark:text-gray-400">
                                        S/. 2.50
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-1">
                                            <button
                                                class="p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">2</span>
                                            <button
                                                class="p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td
                                        class="px-3 py-2 text-sm font-medium text-right text-gray-700 whitespace-nowrap dark:text-gray-300">
                                        S/. 5.00
                                    </td>
                                    <td class="px-3 py-2 text-sm font-medium text-right whitespace-nowrap">
                                        <button
                                            class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Item 2 -->
                                <tr
                                    class="transition-colors duration-150 hover:bg-indigo-50 dark:hover:bg-indigo-900/20">
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-200">Arroz
                                            Extra 1kg</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">AR003</div>
                                    </td>
                                    <td
                                        class="px-3 py-2 text-sm text-right text-gray-500 whitespace-nowrap dark:text-gray-400">
                                        S/. 4.50
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-1">
                                            <button
                                                class="p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">1</span>
                                            <button
                                                class="p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td
                                        class="px-3 py-2 text-sm font-medium text-right text-gray-700 whitespace-nowrap dark:text-gray-300">
                                        S/. 4.50
                                    </td>
                                    <td class="px-3 py-2 text-sm font-medium text-right whitespace-nowrap">
                                        <button
                                            class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Item 3 -->
                                <tr
                                    class="transition-colors duration-150 hover:bg-indigo-50 dark:hover:bg-indigo-900/20">
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-200">Gaseosa
                                            Cola 1L</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">GS002</div>
                                    </td>
                                    <td
                                        class="px-3 py-2 text-sm text-right text-gray-500 whitespace-nowrap dark:text-gray-400">
                                        S/. 5.00
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-1">
                                            <button
                                                class="p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">3</span>
                                            <button
                                                class="p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td
                                        class="px-3 py-2 text-sm font-medium text-right text-gray-700 whitespace-nowrap dark:text-gray-300">
                                        S/. 15.00
                                    </td>
                                    <td class="px-3 py-2 text-sm font-medium text-right whitespace-nowrap">
                                        <button
                                            class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Item 4 -->
                                <tr
                                    class="transition-colors duration-150 hover:bg-indigo-50 dark:hover:bg-indigo-900/20">
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-200">Pollo a
                                            la Brasa (1/4)</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">PL001</div>
                                    </td>
                                    <td
                                        class="px-3 py-2 text-sm text-right text-gray-500 whitespace-nowrap dark:text-gray-400">
                                        S/. 18.90
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-1">
                                            <button
                                                class="p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">2</span>
                                            <button
                                                class="p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td
                                        class="px-3 py-2 text-sm font-medium text-right text-gray-700 whitespace-nowrap dark:text-gray-300">
                                        S/. 37.80
                                    </td>
                                    <td class="px-3 py-2 text-sm font-medium text-right whitespace-nowrap">
                                        <button
                                            class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Opciones y notas -->
                    <div class="mb-3">
                        <div class="flex flex-col space-y-2">
                            <div class="flex items-center">
                                <input id="aplicar-descuento" type="checkbox"
                                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded dark:text-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:border-gray-600">
                                <label for="aplicar-descuento"
                                    class="block ml-2 text-sm text-gray-700 dark:text-gray-300">
                                    Aplicar descuento
                                </label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <select
                                    class="py-1 pl-2 pr-8 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:text-gray-200">
                                    <option value="porcentaje">Porcentaje %</option>
                                    <option value="monto">Monto fijo</option>
                                </select>
                                <input type="number" min="0" placeholder="0.00"
                                    class="w-20 px-2 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:text-gray-200">
                            </div>
                        </div>

                        <div class="mt-2">
                            <label for="notas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Notas
                            </label>
                            <textarea id="notas" rows="2"
                                class="block w-full px-3 py-2 mt-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:text-gray-200"
                                placeholder="Observaciones o instrucciones especiales..."></textarea>
                        </div>
                    </div>

                    <!-- Resumen de totales -->
                    <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between py-1">
                            <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                            <span class="font-medium text-gray-700 dark:text-gray-300">S/. 62.30</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-600 dark:text-gray-400">Descuento:</span>
                            <span class="font-medium text-gray-700 dark:text-gray-300">S/. 0.00</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-600 dark:text-gray-400">IGV (18%):</span>
                            <span class="font-medium text-gray-700 dark:text-gray-300">S/. 11.21</span>
                        </div>
                        <div class="flex justify-between py-1 text-lg font-bold">
                            <span class="text-gray-800 dark:text-gray-200">Total:</span>
                            <span class="text-indigo-600 dark:text-indigo-400">S/. 73.51</span>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex mt-4 space-x-2">
                        <button
                            class="flex-1 px-4 py-2 text-white transition-colors duration-200 bg-indigo-600 rounded-md shadow-md hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                            <span class="flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Procesar Pago
                            </span>
                        </button>
                        <button
                            class="px-4 py-2 text-gray-700 transition-colors duration-200 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                        clip-rule="evenodd" />
                                </svg>
                                En Espera
                            </span>
                        </button>
                    </div>
                </div>

                <!--
                    =================================================
                    SECCIÓN 6: FACTURACIÓN
                    =================================================
                    -->
                <div
                    class="p-4 transition-colors duration-200 bg-white rounded-lg shadow dark:bg-gray-800 dark:shadow-gray-700/30">
                    <!-- INSERTAR AQUÍ EL CÓDIGO DE FACTURACIÓN -->
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Datos de Facturación
                        </h2>
                        <div class="flex items-center">
                            <span
                                class="px-2 py-1 text-xs text-indigo-800 bg-indigo-100 rounded-full dark:bg-indigo-900/30 dark:text-indigo-300">
                                Emisión electrónica
                            </span>
                        </div>
                    </div>

                    <!-- Tipo de comprobante -->
                    <div class="mb-3">
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Tipo
                            Comprobante</label>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="relative">
                                <input type="radio" name="tipo-comprobante" id="factura" value="FACTURA"
                                    class="sr-only peer" checked>
                                <label for="factura"
                                    class="flex items-center justify-center px-3 py-2 text-sm text-gray-700 transition-colors duration-200 border border-gray-300 rounded-md cursor-pointer dark:border-gray-600 peer-checked:border-indigo-500 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    Factura
                                </label>
                            </div>
                            <div class="relative">
                                <input type="radio" name="tipo-comprobante" id="boleta" value="BOLETA"
                                    class="sr-only peer">
                                <label for="boleta"
                                    class="flex items-center justify-center px-3 py-2 text-sm text-gray-700 transition-colors duration-200 border border-gray-300 rounded-md cursor-pointer dark:border-gray-600 peer-checked:border-indigo-500 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    Boleta
                                </label>
                            </div>
                            <div class="relative">
                                <input type="radio" name="tipo-comprobante" id="nota" value="NOTA"
                                    class="sr-only peer">
                                <label for="nota"
                                    class="flex items-center justify-center px-3 py-2 text-sm text-gray-700 transition-colors duration-200 border border-gray-300 rounded-md cursor-pointer dark:border-gray-600 peer-checked:border-indigo-500 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    Nota de Venta
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Serie y Número -->
                    <div class="flex mb-3 space-x-3">
                        <div class="w-1/3">
                            <label
                                class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Serie</label>
                            <input type="text" value="F001"
                                class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:text-gray-200">
                        </div>
                        <div class="w-2/3">
                            <label
                                class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Número</label>
                            <div class="relative">
                                <input type="text" value="000004"
                                    class="w-full px-3 py-2 text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm dark:bg-gray-600 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:text-gray-200"
                                    readonly>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 pointer-events-none dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Moneda y Fecha -->
                    <div class="flex mb-3 space-x-3">
                        <div class="w-1/2">
                            <label
                                class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Moneda</label>
                            <select
                                class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:text-gray-200">
                                <option value="PEN" selected>Soles (PEN)</option>
                                <option value="USD">Dólares (USD)</option>
                                <option value="EUR">Euros (EUR)</option>
                            </select>
                        </div>
                        <div class="w-1/2">
                            <label
                                class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Fecha</label>
                            <input type="date" value="2025-03-21"
                                class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:text-gray-200">
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="mb-3">
                        <label
                            class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Observaciones</label>
                        <textarea
                            class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:text-gray-200"
                            rows="2" placeholder="Información adicional para el comprobante...">Pago al contado</textarea>
                    </div>

                    <!-- Métodos de pago -->
                    <div class="mb-3">
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Método de
                            Pago</label>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="relative">
                                <input type="radio" name="metodo-pago" id="efectivo" value="efectivo"
                                    class="sr-only peer" checked>
                                <label for="efectivo"
                                    class="flex items-center justify-center px-3 py-2 text-sm text-gray-700 transition-colors duration-200 border border-gray-300 rounded-md dark:border-gray-600 peer-checked:border-indigo-500 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Efectivo
                                </label>
                            </div>
                            <div class="relative">
                                <input type="radio" name="metodo-pago" id="tarjeta" value="tarjeta"
                                    class="sr-only peer">
                                <label for="tarjeta"
                                    class="flex items-center justify-center px-3 py-2 text-sm text-gray-700 transition-colors duration-200 border border-gray-300 rounded-md dark:border-gray-600 peer-checked:border-indigo-500 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                        <path fill-rule="evenodd"
                                            d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Tarjeta
                                </label>
                            </div>
                            <div class="relative">
                                <input type="radio" name="metodo-pago" id="transferencia" value="transferencia"
                                    class="sr-only peer">
                                <label for="transferencia"
                                    class="flex items-center justify-center px-3 py-2 text-sm text-gray-700 transition-colors duration-200 border border-gray-300 rounded-md dark:border-gray-600 peer-checked:border-indigo-500 peer-checked:text-indigo-600 dark:peer-checked:text-indigo-400 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z" />
                                    </svg>
                                    Transferencia
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Opciones adicionales de pago (dependiendo del método) -->
                    <div id="opciones-efectivo"
                        class="p-3 mb-3 border border-indigo-100 rounded-md bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-800">
                        <div class="flex items-end space-x-3">
                            <div class="flex-1">
                                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Monto
                                    recibido</label>
                                <input type="number" min="0" step="0.10" placeholder="0.00"
                                    value="100.00"
                                    class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:text-gray-200">
                            </div>
                            <div class="flex-1">
                                <label
                                    class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Vuelto</label>
                                <div
                                    class="w-full px-3 py-2 font-medium text-indigo-700 border border-indigo-200 rounded-md bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-700 dark:text-indigo-300">
                                    S/. 26.49
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Desglose final de pago -->
                    <div class="pt-3 mb-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div
                                class="p-2 border border-indigo-100 rounded bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-800">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                                <span class="float-right font-medium text-gray-800 dark:text-gray-200">S/.
                                    62.30</span>
                            </div>
                            <div
                                class="p-2 border border-indigo-100 rounded bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-800">
                                <span class="text-gray-600 dark:text-gray-400">IGV (18%):</span>
                                <span class="float-right font-medium text-gray-800 dark:text-gray-200">S/.
                                    11.21</span>
                            </div>
                            <div
                                class="p-2 border border-indigo-100 rounded bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-800">
                                <span class="text-gray-600 dark:text-gray-400">Descuento:</span>
                                <span class="float-right font-medium text-gray-800 dark:text-gray-200">S/.
                                    0.00</span>
                            </div>
                            <div
                                class="p-2 border border-indigo-100 rounded bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-800">
                                <span class="text-gray-600 dark:text-gray-400">Total a pagar:</span>
                                <span class="float-right font-medium text-indigo-600 dark:text-indigo-400">S/.
                                    73.51</span>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex space-x-2">
                        <button
                            class="flex-1 px-4 py-2 text-white transition-colors duration-200 bg-indigo-600 rounded-md shadow-md hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                            <span class="flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z"
                                        clip-rule="evenodd" />
                                </svg>
                                Generar Comprobante
                            </span>
                        </button>
                        <button
                            class="px-4 py-2 text-indigo-700 transition-colors duration-200 bg-indigo-100 rounded-md hover:bg-indigo-200 dark:bg-indigo-900/30 dark:hover:bg-indigo-800/40 dark:text-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                        clip-rule="evenodd" />
                                </svg>
                                Vista Previa
                            </span>
                        </button>
                        <button
                            class="px-4 py-2 text-gray-700 transition-colors duration-200 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 dark:focus:ring-gray-500">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                    <path fill-rule="evenodd"
                                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                        clip-rule="evenodd" />
                                </svg>
                                Borrador
                            </span>
                        </button>
                    </div>
                </div>

                <!--
                    =================================================
                    SECCIÓN 7: ACCIONES RÁPIDAS
                    =================================================
                    -->
                <div
                    class="p-4 transition-colors duration-200 bg-white rounded-lg shadow dark:bg-gray-800 dark:shadow-gray-700/30">
                    <h2 class="mb-3 text-lg font-semibold text-gray-700 dark:text-gray-200">Acciones
                        Rápidas</h2>

                    <div class="grid grid-cols-2 gap-2">
                        <!-- Botones de operación principales -->
                        <button
                            class="flex flex-col items-center justify-center p-3 text-green-700 transition-colors duration-200 rounded-lg shadow-sm bg-green-50 hover:bg-green-100 dark:bg-green-900/20 dark:hover:bg-green-800/30 dark:text-green-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="text-sm font-medium">Cobrar</span>
                        </button>

                        <button
                            class="flex flex-col items-center justify-center p-3 text-blue-700 transition-colors duration-200 rounded-lg shadow-sm bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-800/30 dark:text-blue-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-sm font-medium">Nueva Orden</span>
                        </button>

                        <button
                            class="flex flex-col items-center justify-center p-3 text-indigo-700 transition-colors duration-200 rounded-lg shadow-sm bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-800/30 dark:text-indigo-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span class="text-sm font-medium">División</span>
                        </button>

                        <button
                            class="flex flex-col items-center justify-center p-3 text-purple-700 transition-colors duration-200 rounded-lg shadow-sm bg-purple-50 hover:bg-purple-100 dark:bg-purple-900/20 dark:hover:bg-purple-800/30 dark:text-purple-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                            <span class="text-sm font-medium">Transferir</span>
                        </button>

                        <button
                            class="flex flex-col items-center justify-center p-3 text-orange-700 transition-colors duration-200 rounded-lg shadow-sm bg-orange-50 hover:bg-orange-100 dark:bg-orange-900/20 dark:hover:bg-orange-800/30 dark:text-orange-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-1" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="2" />
                                <path d="M12 19c-4.4 0-8-3.6-8-8s3.6-8 8-8 8 3.6 8 8-3.6 8-8 8z" />
                                <path d="M12 6v2M12 16v2" />
                                <path d="M6 12h2M16 12h2" />
                            </svg>
                            <span class="text-sm font-medium">Ver Pedido</span>
                        </button>

                        <button
                            class="flex flex-col items-center justify-center p-3 text-red-700 transition-colors duration-200 rounded-lg shadow-sm bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-800/30 dark:text-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span class="text-sm font-medium">Cancelar</span>
                        </button>
                    </div>

                    <!-- Acciones administrativas -->
                    <div class="grid grid-cols-3 gap-2 mt-4">
                        <button
                            class="flex items-center justify-center p-2 text-gray-700 transition-colors duration-200 bg-gray-100 rounded-md shadow-sm hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-xs">Historial</span>
                        </button>

                        <button
                            class="flex items-center justify-center p-2 text-gray-700 transition-colors duration-200 bg-gray-100 rounded-md shadow-sm hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-xs">Configuración</span>
                        </button>

                        <button
                            class="flex items-center justify-center p-2 text-gray-700 transition-colors duration-200 bg-gray-100 rounded-md shadow-sm hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span class="text-xs">Imprimir</span>
                        </button>
                    </div>

                    <!-- Monitor de caja -->
                    <div
                        class="p-3 mt-4 border border-indigo-100 rounded-md bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-800">
                        <h3
                            class="mb-2 text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400">
                            Estado de Caja</h3>
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Apertura:</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Ventas del día:</div>
                                <div class="text-sm font-medium text-gray-800 dark:text-gray-200">Saldo
                                    actual:</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-800 dark:text-gray-200">S/.
                                    500.00</div>
                                <div class="text-sm font-medium text-green-600 dark:text-green-400">+ S/.
                                    1,250.80</div>
                                <div class="text-sm font-medium text-indigo-600 dark:text-indigo-400">S/.
                                    1,750.80</div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón tema oscuro/claro -->
                    <div class="flex justify-end mt-4">
                        <button
                            class="p-2 text-gray-800 transition-colors duration-200 bg-gray-200 rounded-full dark:bg-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600"
                            onclick="document.documentElement.classList.toggle('dark')">
                            <!-- Icono sol (tema claro) -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="block w-5 h-5 dark:hidden"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <!-- Icono luna (tema oscuro) -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="hidden w-5 h-5 dark:block"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
