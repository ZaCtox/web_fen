@php
    $dias = ['Viernes', 'Sábado'];
@endphp

<div class="p-6 max-w-4xl mx-auto">
    <form action="{{ $action }}" method="POST" class="space-y-6">
        @csrf
        @isset($method)
            @method($method)
        @endisset

        {{-- Magíster --}}
        <div>
            <label for="magister" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Magíster</label>
            <select id="magister"
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                <option value="">-- Selecciona --</option>
                @foreach($agrupados as $magister => $cursos)
                    <option value="{{ $magister }}" @selected(old('magister') == $magister)>
                        {{ $magister }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Asignatura --}}
        <div>
            <label for="course_id"
                class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Asignatura</label>
            <select name="course_id" id="course_id" required
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                <option value="">-- Asignatura --</option>
                @if(old('course_id') || isset($clase))
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" data-period_id="{{ $course->period_id }}"
                            data-periodo="{{ $course->period->nombre_completo }}" @selected(old('course_id', $clase->course_id ?? '') == $course->id)>
                            {{ $course->nombre }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        {{-- Periodo automático --}}
        <input type="hidden" name="period_id" id="period_id" value="{{ old('period_id', $clase->period_id ?? '') }}">

        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Periodo Académico</label>
            <select id="periodo_info" disabled
                class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 cursor-not-allowed">
                <option>
                    {{ optional(optional($clase->course)->period)->nombre_completo ?? 'Selecciona un curso para ver el período' }}
                </option>
            </select>
        </div>

        {{-- Modalidad y Sala --}}
        <div x-data="{
            modalidad: '{{ old('modality', $clase->modality ?? '') }}',
            limpiarSala() {
                if (this.modalidad === 'online') this.$refs.roomSelect.value = '';
            }
        }" class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Modalidad --}}
            <div>
                <label for="modality"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Modalidad</label>
                <select name="modality" id="modality" x-model="modalidad" @change="limpiarSala()"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    required>
                    <option value="" disabled>Seleccione modalidad</option>
                    @foreach (['presencial', 'online', 'hibrida'] as $modo)
                        <option value="{{ $modo }}" @selected(old('modality', $clase->modality ?? '') == $modo)>
                            {{ ucfirst($modo) }}
                        </option>
                    @endforeach
                </select>
                <p x-show="modalidad === 'online'" class="text-sm text-blue-500 mt-1">
                    Esta clase no requiere una sala física.
                </p>
            </div>

            {{-- Sala --}}
            <div>
                <label for="room_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Sala
                    (opcional)</label>
                <select name="room_id" id="room_id" x-ref="roomSelect" :disabled="modalidad === 'online'"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                    <option value="">Sin sala (Online)</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}" @selected(old('room_id', $clase->room_id ?? '') == $room->id)>
                            {{ $room->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Día, Horario y Zoom --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Día --}}
            <div>
                <label for="dia" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Día</label>
                <select name="dia" id="dia"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    required>
                    @foreach ($dias as $dia)
                        <option value="{{ $dia }}" @selected(old('dia', $clase->dia ?? '') == $dia)>
                            {{ $dia }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Hora inicio --}}
            <div>
                <label for="hora_inicio" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Hora
                    inicio</label>
                <input type="time" name="hora_inicio" id="hora_inicio"
                    value="{{ old('hora_inicio', $clase->hora_inicio ?? '') }}"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    required>
            </div>

            {{-- Hora fin --}}
            <div>
                <label for="hora_fin" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Hora
                    fin</label>
                <input type="time" name="hora_fin" id="hora_fin" value="{{ old('hora_fin', $clase->hora_fin ?? '') }}"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    required>
            </div>
        </div>

        {{-- Zoom --}}
        <div>
            <label for="url_zoom" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Enlace Zoom
                (opcional)</label>
            <input type="url" name="url_zoom" id="url_zoom" value="{{ old('url_zoom', $clase->url_zoom ?? '') }}"
                placeholder="https://us02web.zoom.us/..."
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
        </div>

        {{-- Botones --}}
        <div class="pt-4 flex flex-col sm:flex-row items-center gap-4">
            <x-button-fen class="w-full sm:w-auto">{{ $submitText ?? '💾 Guardar Clase' }}</x-button-fen>
            <a href="{{ route('clases.index') }}"
                class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                ⬅️ Volver a Clases
            </a>
        </div>
    </form>
</div>

<script>
    const cursosPorMagister = @json($agrupados);
    const magisterSelect = document.getElementById('magister');
    const courseSelect = document.getElementById('course_id');
    const periodInput = document.getElementById('period_id');
    const periodoInfo = document.getElementById('periodo_info');

    magisterSelect?.addEventListener('change', function () {
        const mag = this.value;
        courseSelect.innerHTML = '<option value="">-- Asignatura --</option>';
        periodInput.value = '';
        periodoInfo.innerHTML = '<option>Selecciona un curso para ver el período</option>';

        if (cursosPorMagister[mag]) {
            cursosPorMagister[mag].forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = c.nombre;
                opt.dataset.period_id = c.period_id;
                opt.dataset.periodo = c.periodo;
                courseSelect.appendChild(opt);
            });

            courseSelect.onchange = function () {
                const selected = this.options[this.selectedIndex];
                const periodId = selected.dataset.period_id;
                const periodo = selected.dataset.periodo;

                periodInput.value = periodId ?? '';
                periodoInfo.innerHTML = `<option>${periodo ? '📘 ' + periodo : 'Sin periodo asignado'}</option>`;
            };
        }
    });
</script>