<div>

    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="w-full max-w-md p-6 bg-white shadow-xl rounded-2xl">
            <div class="flex items-center justify-between pb-3 mb-4 border-b">
                <h3 class="text-xl font-semibold text-gray-800">Título del Modal</h3>
                <button class="text-gray-400 hover:text-gray-600" onclick="closeModal()">
                    &times;
                </button>
            </div>

            <div class="space-y-4">
                <p class="text-gray-600">
                    Este es el contenido del modal dentro de una tarjeta. Puedes poner aquí cualquier información
                    relevante.
                </p>
                <div class="flex justify-end space-x-2">
                    <button class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                        onclick="closeModal()">Cancelar</button>
                    <button class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">Aceptar</button>
                </div>
            </div>
        </div>
    </div>


</div>
