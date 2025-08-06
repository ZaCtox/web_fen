@php
    $dias = ['Viernes', 'Sábado'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Curso --}}
    <div>
        <label for="course_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Curso</label>
        <select name="course_id" id="course_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded" required>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" @selected(old('course_id', $clase->course_id ?? '') == $course->id)>
                    {{ $course->nombre }} ({{ $course->magister->nombre }})
                </option>
            @endforeach
        </select>
    </div>

    {{-- Periodo --}}
    <div>
        <label for="period_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Trimestre</label>
        <select name="period_id" id="period_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded" required>
            @foreach ($periods as $period)
                <option value="{{ $period->id }}" @selected(old('period_id', $clase->period_id ?? '') == $period->id)>
                    {{ $period->nombre_completo }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Modalidad --}}
    <div>
        <label for="modality" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Modalidad</label>
        <select name="modality" id="modality" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded" required>
            @foreach (['presencial', 'online', 'hibrida'] as $modo)
                <option value="{{ $modo }}" @selected(old('modality', $clase->modality ?? '') == $modo)>
                    {{ ucfirst($modo) }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Sala --}}
    <div>
        <label for="room_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Sala (opcional)</label>
        <select name="room_id" id="room_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded">
            <option value="">Sin sala (Online)</option>
            @foreach ($rooms as $room)
                <option value="{{ $room->id }}" @selected(old('room_id', $clase->room_id ?? '') == $room->id)>
                    {{ $room->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Día --}}
    <div>
        <label for="dia" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Día</label>
        <select name="dia" id="dia" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded" required>
            @foreach ($dias as $dia)
                <option value="{{ $dia }}" @selected(old('dia', $clase->dia ?? '') == $dia)>
                    {{ $dia }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Hora inicio --}}
    <div>
        <label for="hora_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Hora Inicio</label>
        <input type="time" name="hora_inicio" id="hora_inicio" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded"
            value="{{ old('hora_inicio', $clase->hora_inicio ?? '') }}" required>
    </div>

    {{-- Hora fin --}}
    <div>
        <label for="hora_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Hora Fin</label>
        <input type="time" name="hora_fin" id="hora_fin" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded"
            value="{{ old('hora_fin', $clase->hora_fin ?? '') }}" required>
    </div>

    {{-- URL Zoom --}}
    <div class="md:col-span-2">
        <label for="url_zoom" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Enlace Zoom (opcional)</label>
        <input type="url" name="url_zoom" id="url_zoom" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded"
            value="{{ old('url_zoom', $clase->url_zoom ?? '') }}">
    </div>
</div>
