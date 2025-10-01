{{-- resources/views/clases/show.blade.php --}}
<<<<<<< Updated upstream

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
=======
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#c4dafa]">Detalle de Clase</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto p-6">
        <div class="rounded-xl shadow-md p-6 border-l-4 transition hover:shadow-lg hover:-translate-y-1 
    bg-[#fcffff] dark:bg-gray-800 text-gray-900 dark:text-gray-200"
            style="border-left:4px solid {{ $clase->course->magister->color ?? '#005187' }}">


            {{-- Nombre del curso --}}
            <h3 class="text-2xl font-bold mb-4 text-[#005187] dark:text-[#c4dafa]">
                {{ $clase->course->nombre }}
            </h3>

            {{-- Datos de la clase --}}
            <ul class="space-y-2 text-sm sm:text-base">
                <li><span class="font-semibold text-[#005187] dark:text-[#c4dafa]">Programa:</span>
                    {{ $clase->course->magister?->nombre ?? 'Sin Magíster' }}</li>
                <li><span class="font-semibold text-[#005187] dark:text-[#c4dafa]">Tipo:</span>
                    {{ $clase->tipo ? ucfirst($clase->tipo) : '—' }}</li>
                <li><span class="font-semibold text-[#005187] dark:text-[#c4dafa]">Sala:</span>
                    {{ $clase->room?->name ?? 'No asignada' }}</li>
                <li><span class="font-semibold text-[#005187] dark:text-[#c4dafa]">Día:</span> {{ $clase->dia ?? '—' }}
                </li>
                <li>
                    <span class="font-semibold text-[#005187] dark:text-[#c4dafa]">Horario:</span>
                    {{ $clase->hora_inicio ? \Carbon\Carbon::parse($clase->hora_inicio)->format('H:i') : '--:--' }}
                    –
                    {{ $clase->hora_fin ? \Carbon\Carbon::parse($clase->hora_fin)->format('H:i') : '--:--' }}
                </li>
                <li><span class="font-semibold text-[#005187] dark:text-[#c4dafa]">Año:</span>
                    {{ $clase->period?->anio ?? '—' }}</li>
                <li><span class="font-semibold text-[#005187] dark:text-[#c4dafa]">Trimestre:</span>
                    {{ $clase->period?->numero ?? '—' }}</li>
                <li><span class="font-semibold text-[#005187] dark:text-[#c4dafa]">Encargado:</span>
                    {{ $clase->encargado ?? '—' }}</li>
                <li><span class="font-semibold text-[#005187] dark:text-[#c4dafa]">Modalidad:</span>
                    {{ $clase->modality ? ucfirst($clase->modality) : '—' }}</li>

                @if($clase->url_zoom)
                    <li>
                        <span class="font-semibold text-[#005187] dark:text-[#c4dafa]">Enlace Zoom:</span>
                        <a href="{{ $clase->url_zoom }}" target="_blank"
                            class="text-[#005187] dark:text-[#84b6f4] hover:text-[#4d82bc] underline break-words">
>>>>>>> Stashed changes
                            {{ $clase->url_zoom }}
                        </a>
                    </li>
                @endif
            </ul>

<<<<<<< Updated upstream
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
                    <a href="{{ url('/Calendario-Academico') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded">
                        ← Volver al Calendario
=======
            {{-- 🔒 Botonera interna --}}
            @unless(!empty($public) && $public === true)
                <div class="flex items-center justify-between mt-6">

                    {{-- Volver (a la izquierda) --}}
                    <a href="{{ route('clases.index') }}"
                        class="inline-flex items-center px-5 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white font-medium rounded-lg shadow transition-all duration-200">
                        <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-5 h-5">
                    </a>

                    {{-- Editar + Eliminar (a la derecha) --}}
                    <div class="flex gap-3">
                        {{-- Editar --}}
                        <a href="{{ route('clases.edit', $clase) }}"
                            class="inline-flex items-center px-5 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white font-medium rounded-lg shadow transition-all duration-200">
                            <img src="{{ asset('icons/editw.svg') }}" alt="Editar" class="w-6 h-6">
                        </a>

                        {{-- Eliminar --}}
                        <form action="{{ route('clases.destroy', $clase) }}" method="POST"
                            onsubmit="return confirm('¿Eliminar esta clase?')" class="inline-flex">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 rounded-lg bg-[#4d82bc] hover:bg-[#005187] font-medium text-center">
                                <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar" class="w-5 h-5">
                            </button>
                        </form>
                    </div>
                </div>
            @else
                {{-- 🟢 Modo público --}}
                <div class="mt-6 flex justify-start">
                    <a href="{{ url('/Calendario-Academico') }}"
                        class="inline-flex items-center px-5 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white font-medium rounded-lg shadow transition-all duration-200">
                        <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-5 h-5">
>>>>>>> Stashed changes
                    </a>
                </div>
            @endunless
        </div>
    </div>
<<<<<<< Updated upstream
</x-app-layout>
=======
</x-app-layout>
>>>>>>> Stashed changes
