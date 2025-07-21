<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            Detalles de la Sala: {{ $room->name }}
        </h2>
    </x-slot>

    <div class="p-6 space-y-4">
        <div class="bg-white dark:bg-gray-800 shadow rounded p-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Información General</h3>
            <p><strong>Ubicación:</strong> {{ $room->location }}</p>
            <p><strong>Capacidad:</strong> {{ $room->capacity }}</p>
            <p><strong>Descripción:</strong> {{ $room->description }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded p-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Usos Académicos</h3>

            <form method="GET" class="mb-4">
                <label for="period_id" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Filtrar por Periodo:</label>
                <select name="period_id" id="period_id" onchange="this.form.submit()"
                    class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                    <option value="">Todos</option>
                    @foreach($periodos as $p)
                        <option value="{{ $p->id }}" {{ request('period_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nombre_completo }}
                        </option>
                    @endforeach
                </select>
            </form>

            @if($usos->isEmpty())
                <p class="text-gray-500 dark:text-gray-300">No hay usos registrados para esta sala.</p>
            @else
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm mt-4">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left">Periodo</th>
                            <th class="px-4 py-2 text-left">Día</th>
                            <th class="px-4 py-2 text-left">Horario</th>
                            <th class="px-4 py-2 text-left">Programa</th>
                            <th class="px-4 py-2 text-left">Asignatura</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($usos as $uso)
                            <tr>
                                <td class="px-4 py-2">{{ $uso->period->nombre_completo }}</td>
                                <td class="px-4 py-2">{{ $uso->dia }}</td>
                                <td class="px-4 py-2">{{ $uso->hora_inicio }} - {{ $uso->hora_fin }}</td>
                                <td class="px-4 py-2">{{ $uso->course->programa }}</td>
                                <td class="px-4 py-2">{{ $uso->course->nombre }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <a href="{{ route('rooms.index') }}"
           class="inline-block mt-4 bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            ← Volver al listado
        </a>
    </div>
</x-app-layout>