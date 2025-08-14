<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">📘 Detalle de Clase</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto p-6">
        <div class="bg-white dark:bg-gray-800 rounded shadow p-6 border-l-4"
             style="border-left: 4px solid {{ $clase->course->magister->color ?? '#6b7280' }}">
            <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">{{ $clase->course->nombre }}</h3>

            <ul class="text-gray-700 dark:text-gray-300 space-y-2 text-sm sm:text-base">
                <li><strong>Magíster:</strong> {{ $clase->course->magister?->nombre ?? 'Sin Magíster' }}</li>
                <li><strong>Periodo:</strong> {{ $clase->period->nombre_completo }}</li>
                <li><strong>Día:</strong> {{ $clase->dia }}</li>
                <li><strong>Horario:</strong> {{ $clase->hora_inicio }} - {{ $clase->hora_fin }}</li>
                <li><strong>Modalidad:</strong> {{ ucfirst($clase->modality) }}</li>
                <li><strong>Sala:</strong> {{ $clase->room?->name ?? 'No asignada' }}</li>
                @if($clase->url_zoom)
                    <li><strong>Enlace Zoom:</strong>
                        <a href="{{ $clase->url_zoom }}" target="_blank" class="text-blue-500 underline">
                            {{ $clase->url_zoom }}
                        </a>
                    </li>
                @endif
            </ul>

            <div class="flex gap-3 mt-6">
                <a href="{{ route('clases.edit', $clase) }}"
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded">✏️ Editar</a>

                <form action="{{ route('clases.destroy', $clase) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar esta clase?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded">🗑️ Eliminar
                    </button>
                </form>

                <a href="{{ route('clases.index') }}"
                   class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-sm rounded text-gray-800 dark:text-white">← Volver</a>
            </div>
        </div>
    </div>
</x-app-layout>
