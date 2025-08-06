<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            ➕ Nueva Clase Académica
        </h2>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto">
        <form action="{{ route('clases.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Magíster --}}
            <div>
                <label for="magister" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Magíster</label>
                <select id="magister" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                    <option value="">-- Selecciona --</option>
                    @foreach($agrupados as $magister => $cursos)
                        <option value="{{ $magister }}">{{ $magister }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Asignatura --}}
            <div>
                <label for="course_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Asignatura</label>
                <select name="course_id" id="course_id" required
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                    <option value="">-- Asignatura --</option>
                </select>
            </div>

            {{-- Periodo automático --}}
            <input type="hidden" name="period_id" id="period_id">

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Periodo Académico</label>
                <select id="periodo_info" disabled
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 cursor-not-allowed">
                    <option>Selecciona un curso para ver el período</option>
                </select>
            </div>

            {{-- Modalidad --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Modalidad</label>
                <select name="modality" required
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                    <option value="presencial">Presencial</option>
                    <option value="online">Online</option>
                    <option value="hibrida">Híbrida</option>
                </select>
            </div>

            {{-- Sala --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Sala (opcional)</label>
                <select name="room_id"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                    <option value="">Sin sala (solo si es online)</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Día y horas --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Día</label>
                    <select name="dia" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <option value="Viernes">Viernes</option>
                        <option value="Sábado">Sábado</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Hora inicio</label>
                    <input type="time" name="hora_inicio" required
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Hora fin</label>
                    <input type="time" name="hora_fin" required
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                </div>
            </div>

            {{-- Zoom opcional --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Enlace Zoom (opcional)</label>
                <input type="url" name="url_zoom" placeholder="https://us02web.zoom.us/..."
                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
            </div>

            {{-- Botones --}}
            <div class="pt-4 flex flex-col sm:flex-row items-center gap-4">
                <x-button-fen class="w-full sm:w-auto">💾 Guardar Clase</x-button-fen>
                <a href="{{ route('clases.index') }}"
                   class="text-sm text-gray-700 dark:text-gray-300 hover:underline">
                    Cancelar
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

        magisterSelect.addEventListener('change', function () {
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
