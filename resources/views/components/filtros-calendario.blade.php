<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4">
    <div class="flex flex-wrap items-center gap-4">
        <div>
            <label for="magister-filter" class="block text-sm font-medium text-gray-800 dark:text-white">
                Filtrar por Programa:
            </label>
            <select id="magister-filter" name="magister"
                class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                <option value="">Todos</option>
                @foreach(\App\Models\Magister::orderBy('nombre')->get() as $m)
                    <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="room-filter" class="block text-sm font-medium text-gray-800 dark:text-white">
                Filtrar por Sala:
            </label>
            <select id="room-filter" name="room_id" class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
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
            <button id="clear-filters" type="button" class="px-3 py-2  rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-150 ease-in-out">
                Limpiar filtros
            </button>
        </div>

    </div>