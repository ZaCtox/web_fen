<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4">
    <div class="flex flex-wrap items-center gap-4">
        <div>
            <label for="magister-filter" class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4] mb-2">
                Programa:
            </label>
            <select id="magister-filter" name="magister"
                class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-700 text-[#005187] dark:text-white px-3 py-2 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition">
                <option value="">Todos</option>
                @foreach(\App\Models\Magister::orderBy('nombre')->get() as $m)
                    <option value="{{ $m->id }}" {{ $m->id == 3 ? 'selected' : '' }}>
                        {{ $m->nombre }}
                    </option>
                @endforeach
            </select>

        </div>

        <div>
            <label for="room-filter" class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4] mb-2">
                Sala:
            </label>
            <select id="room-filter" name="room_id" class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-700 text-[#005187] dark:text-white px-3 py-2 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition">
                <option value="">Todas</option>
                @foreach(\App\Models\Room::orderBy('name')->get() as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-800 dark:text-white invisible">
                &nbsp; <!-- Para alinear el botón con los selects -->
            </label>
            <button id="clear-filters" type="button"
                class="mt-1 bg-[#84b6f4] hover:bg-[#005187] text-[#005187] px-4 py-2 rounded-lg shadow text-sm transition transform hover:scale-105"
                title="Limpiar filtros">
                <img src="{{ asset('icons/filterw.svg') }}" alt="Limpiar filtros" class="w-5 h-5">
            </button>
        </div>

    </div>