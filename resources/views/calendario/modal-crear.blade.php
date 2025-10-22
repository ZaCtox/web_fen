<div id="modal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex justify-center items-center">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl p-6 w-full max-w-lg border-2 border-[#c4dafa] dark:border-gray-700 
                transform transition-all duration-300 hover:scale-[1.01]">
        <h3 id="modal-header" class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4] mb-4">
            Crear Evento
        </h3>
        <form id="event-form" class="space-y-4 no-loading">
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

            {{-- Botones con mejor estilo --}}
            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                <button type="button" id="cancel"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg shadow-md 
                           transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancelar
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#3ba55d] hover:bg-[#2d864a] text-white font-medium rounded-lg shadow-md 
                           transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#3ba55d] focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>



