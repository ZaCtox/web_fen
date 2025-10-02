<div id="modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-[#fcffff] dark:bg-gray-900 rounded-xl shadow-lg p-6 w-full max-w-md border border-[#c4dafa] dark:border-gray-700 transition">
        <h3 id="modal-header" class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4] mb-4">
            Crear Evento
        </h3>
        <form id="event-form" class="space-y-4">
            @csrf
            <input type="hidden" id="event_id">

            {{-- Título --}}
            <div>
                <label for="modal-title-input" class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">
                    Título
                </label>
                <input id="modal-title-input" type="text" required
                    class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-4 py-2 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 dark:focus:ring-blue-600 transition"
                    placeholder="Ej. Coffe Break">
            </div>

            {{-- Descripción --}}
            <div>
                <label for="modal-description-input" class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">
                    Descripción
                </label>
                <textarea id="modal-description-input" rows="2"
                    class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-4 py-2 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 dark:focus:ring-blue-600 transition"
                    placeholder="Opcional..."></textarea>
            </div>

            {{-- Magíster --}}
            <div>
                <label for="magister_id" class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">
                    Programa (opcional)
                </label>
                <select id="magister_id"
                    class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-4 py-2 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 dark:focus:ring-blue-600 transition">
                    <option value="">-- Sin Programa --</option>
                    @foreach(\App\Models\Magister::orderBy('nombre')->get() as $m)
                        <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Sala --}}
            <div>
                <label for="room_id" class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">
                    Sala (opcional)
                </label>
                <select id="room_id"
                    class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-4 py-2 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 dark:focus:ring-blue-600 transition">
                    <option value="">-- Sin sala --</option>
                    @foreach(\App\Models\Room::orderBy('name')->get() as $room)
                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Inicio --}}
            <div>
                <label for="start_time" class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">
                    Inicio
                </label>
                <input id="start_time" type="datetime-local" required
                    class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-4 py-2 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 dark:focus:ring-blue-600 transition">
            </div>

            {{-- Fin --}}
            <div>
                <label for="end_time" class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">
                    Fin
                </label>
                <input id="end_time" type="datetime-local" required
                    class="w-full rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-4 py-2 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 dark:focus:ring-blue-600 transition">
            </div>

            {{-- Botones --}}
            <div class="mt-2 flex justify-between items-center">
                <button type="button" id="cancel"
                    class="inline-flex items-center justify-center px-4 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-md shadow-md transition transform hover:scale-105">
                    <img src="{{ asset('icons/back.svg') }}" alt="back" class="w-5 h-5">
                </button>
                <button type="submit"
                    class="inline-flex items-center justify-center px-4 py-2 bg-[#005187] hover:bg-[#4d82bc] text-white rounded-md shadow-md transition transform hover:scale-105">
                    <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-5 h-5">
                </button>
            </div>
        </form>
    </div>
</div>
