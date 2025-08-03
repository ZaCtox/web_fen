<div id="eventModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2" id="modal-title">Título</h2>

        <p class="text-sm text-gray-700 dark:text-gray-300 mb-1" id="modal-description">Descripción</p>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1"><strong>Inicio:</strong> <span id="modal-start"></span></p>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1"><strong>Fin:</strong> <span id="modal-end"></span></p>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4"><strong>Sala:</strong> <span id="modal-room"></span></p>

        <div class="flex justify-end gap-2">
            <button id="delete-btn"
                class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded">
                Eliminar
            </button>
            <button onclick="closeModal()"
                class="px-3 py-1 bg-gray-500 hover:bg-gray-600 text-white rounded">
                Cerrar
            </button>
        </div>
    </div>
</div>
