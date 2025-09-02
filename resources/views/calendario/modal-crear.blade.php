<div id="modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6 w-full max-w-md">
        <h3 id="modal-header" class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            Crear Evento
        </h3>
        <form id="event-form" class="space-y-4">
            @csrf
            <input type="hidden" id="event_id">

            <div>
                <label for="modal-title-input" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Título
                </label>
                <input id="modal-title-input" type="text" required
                    class="w-full px-3 py-2 rounded border dark:bg-gray-700 dark:text-white"
                    placeholder="Ej. Taller de Investigación">
            </div>

            <div>
                <label for="modal-description-input" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Descripción
                </label>
                <textarea id="modal-description-input" rows="2"
                    class="w-full px-3 py-2 rounded border dark:bg-gray-700 dark:text-white"
                    placeholder="Opcional..."></textarea>
            </div>

            <div>
                <label for="magister_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Magíster (opcional)
                </label>
                <select id="magister_id" class="w-full px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                    <option value="">-- Sin Magíster --</option>
                    @foreach(\App\Models\Magister::orderBy('nombre')->get() as $m)
                        <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="room_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Sala (opcional)
                </label>
                <select id="room_id" class="w-full px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                    <option value="">-- Sin sala --</option>
                    @foreach(\App\Models\Room::orderBy('name')->get() as $room)
                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 👇 Aquí añadimos inicio/fin -->
            <div>
                <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Inicio
                </label>
                <input id="start_time" type="datetime-local" required
                    class="w-full px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
            </div>

            <div>
                <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Fin
                </label>
                <input id="end_time" type="datetime-local" required
                    class="w-full px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" id="cancel" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>