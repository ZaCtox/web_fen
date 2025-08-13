<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            ✏️ Editar Clase Académica
        </h2>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto">
        @if ($errors->any())
            <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">
                <strong>Errores al guardar:</strong>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('clases.update', $clase) }}" class="space-y-6"
            x-data="{ modalidad: '{{ old('modality', $clase->modality) }}' }">
            @csrf
            @method('PUT')

            {{-- Magíster --}}
            <div>
                <label for="magister"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Magíster</label>
                <select id="magister"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                    <option value="">-- Selecciona --</option>
                    @foreach($agrupados as $magister => $cursos)
                        <option value="{{ $magister }}" @selected(optional($clase->course->magister)->nombre == $magister)>
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
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" data-period_id="{{ $course->period_id }}"
                            data-periodo="{{ optional($course->period)->nombre_completo }}"
                            @selected($clase->course_id == $course->id)>
                            {{ $course->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Periodo automático --}}
            <input type="hidden" name="period_id" id="period_id" value="{{ $clase->period_id }}">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Periodo Académico</label>
                <select id="periodo_info" disabled
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 cursor-not-allowed">
                    <option>{{ optional($clase->course->period)->nombre_completo ?? 'Selecciona un curso' }}</option>
                </select>
            </div>

            {{-- Modalidad --}}
            <div>
                <label for="modality"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Modalidad</label>
                <select name="modality" id="modality" x-model="modalidad"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    required>
                    <option value="presencial">Presencial</option>
                    <option value="online">Online</option>
                    <option value="hibrida">Híbrida</option>
                </select>
            </div>

            {{-- Sala --}}
            <div>
                <label for="room_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Sala
                    (opcional)</label>
                <select name="room_id" id="room_id"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                    <option value="">-- Selecciona una sala --</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}" @selected($clase->room_id == $room->id)>
                            {{ $room->name }}
                        </option>
                    @endforeach
                </select>
                <p x-show="modalidad === 'online'" class="text-sm text-blue-500 mt-1">
                    Esta clase es online, por lo tanto no requiere sala.
                </p>
            </div>

            {{-- Enlace Zoom --}}
            <div x-show="modalidad === 'online'" x-transition>
                <label for="url_zoom" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Enlace
                    Zoom</label>
                <input type="url" name="url_zoom" id="url_zoom" placeholder="https://us02web.zoom.us/..."
                    value="{{ old('url_zoom', $clase->url_zoom) }}"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
            </div>

            {{-- Día y horas --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Día</label>
                    <select name="dia" required
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <option value="Viernes" @selected($clase->dia === 'Viernes')>Viernes</option>
                        <option value="Sábado" @selected($clase->dia === 'Sábado')>Sábado</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Hora inicio</label>
                    <input type="time" name="hora_inicio" value="{{ \Carbon\Carbon::parse($clase->hora_inicio)->format('H:i') }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Hora fin</label>
                    <input type="time" name="hora_fin" value="{{ \Carbon\Carbon::parse($clase->hora_fin)->format('H:i') }}" ... 
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        required>
                </div>
            </div>

            {{-- Botones --}}
            <div class="pt-4 flex flex-col sm:flex-row items-center gap-4">
                <x-button-fen class="w-full sm:w-auto">💾 Actualizar Clase</x-button-fen>
                <a href="{{ route('clases.index') }}"
                    class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    ⬅️ Volver
                </a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('modality')?.addEventListener('change', function () {
            const roomSelect = document.getElementById('room_id');

            if (!roomSelect) return;

            if (this.value === 'online') {
                roomSelect.value = '';
                roomSelect.disabled = true;
            } else {
                roomSelect.disabled = false;
            }
        });
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

</x-app-layout>