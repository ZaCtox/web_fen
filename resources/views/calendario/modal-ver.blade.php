<div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow w-full max-w-md">
        <h2 class="text-xl font-bold mb-2" id="modal-title">Título del evento</h2>
        <p class="mb-2 text-sm text-gray-700 dark:text-gray-300" id="modal-description">Descripción</p>
        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><strong>Inicio:</strong> <span id="modal-start"></span></p>
        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><strong>Fin:</strong> <span id="modal-end"></span></p>
        <p class="mb-4 text-sm text-gray-500 dark:text-gray-400"><strong>Sala:</strong> <span id="modal-room"></span></p>

        <div class="flex justify-end gap-2">
            <button id="edit-btn" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">Editar</button>
            <button id="delete-btn" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Eliminar</button>
            <button onclick="closeModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded">Cerrar</button>
        </div>
    </div>
</div>
