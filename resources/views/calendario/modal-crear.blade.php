<div id="modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Crear Evento</h3>
        <form id="event-form" class="space-y-4">
            @csrf
            <input type="hidden" id="start_time">
            <input type="hidden" id="end_time">

            <div>
                <label for="modal-title-input" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título</label>
                <input id="modal-title-input" type="text" required
                    class="w-full px-3 py-2 rounded border dark:bg-gray-700 dark:text-white" placeholder="Ej. Taller de Investigación">
            </div>

            <div>
                <label for="modal-description-input" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                <textarea id="modal-description-input" rows="2"
                    class="w-full px-3 py-2 rounded border dark:bg-gray-700 dark:text-white" placeholder="Opcional..."></textarea>
            </div>

            <div>
                <label for="modal-magister" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Magíster</label>
                <select id="modal-magister" required
                    class="w-full px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                    <option value="">Seleccione</option>
                    <option value="Economía">Economía</option>
                    <option value="Gestión de Sistemas de Salud">Gestión de Sistemas de Salud</option>
                    <option value="Gestión y Políticas Públicas">Gestión y Políticas Públicas</option>
                    <option value="Dirección y Planificación Tributaria">Dirección y Planificación Tributaria</option>
                </select>
            </div>

            <div>
                <label for="room_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sala (opcional)</label>
                <select id="room_id"
                    class="w-full px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                    <option value="">-- Sin sala --</option>
                    @foreach(\App\Models\Room::all() as $room)
                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" id="cancel"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
