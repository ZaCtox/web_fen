<div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white dark:bg-gray-900 p-6 rounded shadow-lg w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Crear Evento</h3>
        <form id="event-form">
            <input type="hidden" id="start_time">
            <input type="hidden" id="end_time">

            <label class="block mb-2 text-sm">Título</label>
            <input type="text" id="title" required class="w-full mb-3 px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">

            <label class="block mb-2 text-sm">Sala (opcional)</label>
            <select id="room_id" class="w-full mb-4 px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">
                <option value="">-- Sin sala --</option>
                @foreach(\App\Models\Room::all() as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>

            <div class="flex justify-end gap-2">
                <button type="button" id="cancel" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Guardar</button>
            </div>
        </form>
    </div>
</div>
