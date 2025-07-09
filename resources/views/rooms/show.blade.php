<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            Asignaciones de Sala: {{ $room->name }}
        </h2>
    </x-slot>

    <div class="p-6">

        {{-- Formulario de filtro --}}
        <form method="GET" class="mb-6 flex flex-wrap gap-4">
            <div>
                <label class="text-sm text-gray-700 dark:text-gray-300">Año</label>
                <input type="number" name="year" value="{{ request('year') }}"
                    class="block w-full px-3 py-1 border rounded dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="text-sm text-gray-700 dark:text-gray-300">Trimestre</label>
                <select name="trimestre" class="block w-full px-3 py-1 border rounded dark:bg-gray-700 dark:text-white">
                    <option value="">Todos</option>
                    @for ($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" @selected(request('trimestre') == $i)>Trimestre {{ $i }}</option>
                    @endfor
                </select>

            </div>
            <div class="flex items-end gap-2">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded">Filtrar</button>
                <a href="{{ route('rooms.show', $room) }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-1 rounded">Limpiar</a>
            </div>
        </form>

        {{-- Resultados --}}
        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
            @forelse($usos as $uso)
                <div class="border-b py-2">
                    <div class="text-gray-800 dark:text-white font-semibold">
                        Año {{ $uso->year }} - Trimestre {{ $uso->trimestre }}
                    </div>
                    <div class="text-sm flex flex-wrap items-center gap-2">
                        <span class="inline-block px-2 py-1 rounded text-xs font-semibold bg-indigo-100 text-indigo-800">
                            {{ $uso->dia ?? 'Día no asignado' }} {{ $uso->horario ?? '' }}
                        </span>
                        <span class="text-gray-600 dark:text-gray-300">
                            {{ $uso->subject ?? 'Sin asignatura' }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 italic">No hay asignaciones registradas.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>