{{-- resources/views/clases/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">📘 Detalle de Clase</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto p-6">
        <div class="bg-white dark:bg-gray-800 rounded shadow p-6 border-l-4"
             style="border-left: 4px solid {{ $clase->course->magister->color ?? '#6b7280' }}">
            <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">{{ $clase->course->nombre }}</h3>

            <ul class="text-gray-700 dark:text-gray-300 space-y-2 text-sm sm:text-base">
                <li><strong>Programa:</strong> {{ $clase->course->magister?->nombre ?? 'Sin Magíster' }}</li>
                <li><strong>Tipo:</strong> {{ $clase->tipo ? ucfirst($clase->tipo) : '—' }}</li>
                <li><strong>Sala:</strong> {{ $clase->room?->name ?? 'No asignada' }}</li>
                <li><strong>Día:</strong> {{ $clase->dia ?? '—' }}</li>
                <li><strong>Horario:</strong> {{ $clase->hora_inicio }} - {{ $clase->hora_fin }}</li>
                <li><strong>Trimestre:</strong> {{ $clase->period?->numero ?? '—' }}</li>
                <li><strong>Año:</strong> {{ $clase->period?->anio ?? '—' }}</li>
                <li><strong>Encargado:</strong> {{ $clase->encargado ?? '—' }}</li>
                <li><strong>Modalidad:</strong> {{ $clase->modality ? ucfirst($clase->modality) : '—' }}</li>
                @if($clase->url_zoom)
                    <li>
                        <strong>Enlace Zoom:</strong>
                        <a href="{{ $clase->url_zoom }}" target="_blank" class="text-blue-600 hover:underline">
                            {{ $clase->url_zoom }}
                        </a>
                    </li>
                @endif
            </ul>

            {{-- 🔒 Botonera interna (editar/eliminar/volver al índice) --}}
            @unless(!empty($public) && $public === true)
                <div class="flex flex-wrap gap-3 mt-6">
                    <a href="{{ route('clases.edit', $clase) }}"
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded" title="Editar">✏️</a>

                    <form action="{{ route('clases.destroy', $clase) }}" method="POST"
                          onsubmit="return confirm('¿Eliminar esta clase?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded" title="Eliminar">🗑️</button>
                    </form>

                    <a href="{{ route('clases.index') }}"
                       class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-sm rounded text-gray-800 dark:text-white">
                        ← Volver
                    </a>
                </div>
            @else
                {{-- 🟢 Modo público: solo botón de volver al calendario --}}
                <div class="mt-6">
                    <a href="{{ url('/calendario-academico') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded">
                        ← Volver al Calendario
                    </a>
                </div>
            @endunless
        </div>
    </div>
</x-app-layout>
