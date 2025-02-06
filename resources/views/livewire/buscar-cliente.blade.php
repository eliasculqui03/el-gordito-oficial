<div>
    <div class="p-4 border md:p-8">
        <div class="flex flex-col space-y-6 lg:space-y-0 lg:grid lg:grid-cols-3 lg:gap-6">
            <!-- Campo DNI -->
            <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                <div class="flex w-full">
                    <input wire:model="numero_documento" id="numero_documento" type="text"
                        placeholder="Ingrese N° de documento"
                        class="w-full px-4 py-2 border border-gray-300 sm:w-64 text-sm/6 rounded-l-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                    <button wire:click="buscar"
                        class="flex items-center px-4 py-2 text-white bg-blue-600 rounded-r-md hover:bg-blue-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:ring-offset-1 dark:bg-blue-500 dark:hover:bg-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">Buscar</span>
                    </button>
                </div>
                <div class="flex justify-center w-full sm:w-auto sm:justify-start">
                    <livewire:crear-cliente>

                </div>
            </div>

            <!-- Campo Nombre -->
            <div class="flex flex-col items-start justify-end col-span-2 gap-4 sm:flex-row sm:items-center">
                <label for="first-name" class="font-medium text-gray-900 whitespace-nowrap text-sm/6">Cliente: </label>
                <input type="text"
                    class="w-full px-4 py-2 text-sm bg-gray-100 border border-gray-300 rounded-md sm:w-96 ring-1 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                    value="{{ $nombre }}" disabled placeholder="Nombre del cliente" />
            </div>
        </div>

    </div>



    <div class="p-4 my-5 border md:p-8">
        <div class="flex flex-col space-y-6 lg:space-y-0 lg:grid lg:grid-cols-3 lg:gap-6">


        </div>

    </div>


</div>
