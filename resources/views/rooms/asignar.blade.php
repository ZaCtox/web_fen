<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            Asignar Usos Académicos a: {{ $room->name }}
        </h2>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto">
        <form action="{{ route('rooms.guardar-uso', $room) }}" method="POST">
            @csrf

            <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Usos Académicos</h3>
            <div id="usos-container" class="space-y-6">
                <div class="grid grid-cols-6 gap-2 items-start">
                    <select name="usos[0][dia]" required class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                        <option value="">Día</option>
                        <option value="Viernes">Viernes</option>
                        <option value="Sábado">Sábado</option>
                    </select>

                    <input type="time" name="usos[0][hora_inicio]" required class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                    <input type="time" name="usos[0][hora_fin]" required class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">

                    <select class="magister-select px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                        <option value="">Magíster</option>
                        @foreach($cursos->groupBy(fn($c) => $c->magister->nombre ?? 'Sin Magíster') as $magisterNombre => $asignaturas)
                            <option value="{{ $magisterNombre }}">{{ $magisterNombre }}</option>
                        @endforeach
                    </select>

                    <select name="usos[0][course_id]" required class="course-select px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                        <option value="">Asignatura</option>
                    </select>

                    <select name="usos[0][period_id]" required class="period-select px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                        <option value="">Periodo</option>
                        @foreach($periodos as $p)
                            <option value="{{ $p->id }}">{{ $p->nombre_completo }}</option>
                        @endforeach
                    </select>

                    <div class="period-info text-sm text-gray-600 dark:text-gray-300 col-span-6 sm:col-span-3"></div>
                </div>
            </div>

            <button type="button" id="add-uso" class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded">
                + Añadir uso
            </button>

            <hr class="my-6 border-gray-300 dark:border-gray-600">

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Guardar Asignaciones
            </button>
        </form>
    </div>

    @php
        $agrupados = $cursos->groupBy(fn($c) => $c->magister->nombre ?? 'Sin Magíster')
            ->map(function ($items) {
                return $items->map(function ($c) {
                    return [
                        'id' => $c->id,
                        'nombre' => $c->nombre,
                        'period_id' => $c->period_id,
                        'periodo' => $c->period ? 'Año: ' . $c->period->anio . ' | Trimestre: ' . $c->period->trimestre : 'Sin período'
                    ];
                });
            });
    @endphp

    <script>
        const cursosPorMagister = @json($agrupados);

        let usoIndex = 1;

        const periodosOptions = `@foreach($periodos as $p)<option value="{{ $p->id }}">{{ $p->nombre_completo }}</option>@endforeach`;
        const magisterOptions = `@foreach($cursos->groupBy(fn($c) => $c->magister->nombre ?? 'Sin Magíster') as $magisterNombre => $_)<option value="{{ $magisterNombre }}">{{ $magisterNombre }}</option>@endforeach`;

        function bindCourseFilter(magSelect, courseSelect, periodSelect, periodDiv) {
            magSelect.addEventListener('change', function () {
                const mag = this.value;
                courseSelect.innerHTML = '<option value="">Asignatura</option>';
                periodSelect.value = '';
                periodDiv.innerText = '';

                if (cursosPorMagister[mag]) {
                    cursosPorMagister[mag].forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c.id;
                        opt.textContent = c.nombre;
                        opt.dataset.period_id = c.period_id;
                        opt.dataset.periodo = c.periodo;
                        courseSelect.appendChild(opt);
                    });

                    courseSelect.addEventListener('change', function () {
                        const selected = this.options[this.selectedIndex];
                        const periodId = selected.dataset.period_id;
                        const periodo = selected.dataset.periodo;

                        if (periodId) {
                            periodSelect.value = periodId;
                        }

                        periodDiv.innerText = periodo ? `📘 ${periodo}` : '';
                    });
                }
            });
        }

        document.getElementById('add-uso').addEventListener('click', () => {
            const container = document.getElementById('usos-container');
            const div = document.createElement('div');
            div.classList.add('grid', 'grid-cols-6', 'gap-2', 'items-start', 'mt-2');
            div.innerHTML = `
                <select name="usos[${usoIndex}][dia]" class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white" required>
                    <option value="">Día</option>
                    <option value="Viernes">Viernes</option>
                    <option value="Sábado">Sábado</option>
                </select>
                <input type="time" name="usos[${usoIndex}][hora_inicio]" class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white" required>
                <input type="time" name="usos[${usoIndex}][hora_fin]" class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white" required>
                <select class="magister-select px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                    <option value="">Magíster</option>
                    ${magisterOptions}
                </select>
                <select name="usos[${usoIndex}][course_id]" class="course-select px-3 py-2 rounded border dark:bg-gray-700 dark:text-white" required>
                    <option value="">Asignatura</option>
                </select>
                <select name="usos[${usoIndex}][period_id]" class="period-select px-3 py-2 rounded border dark:bg-gray-700 dark:text-white" required>
                    <option value="">Periodo</option>
                    ${periodosOptions}
                </select>
                <div class="period-info text-sm text-gray-600 dark:text-gray-300 col-span-6 sm:col-span-3"></div>
            `;
            container.appendChild(div);
            bindCourseFilter(
                div.querySelector('.magister-select'),
                div.querySelector('.course-select'),
                div.querySelector('.period-select'),
                div.querySelector('.period-info')
            );
            usoIndex++;
        });

        document.querySelectorAll('.magister-select').forEach((mag, i) => {
            bindCourseFilter(
                mag,
                document.querySelectorAll('.course-select')[i],
                document.querySelectorAll('.period-select')[i],
                document.querySelectorAll('.period-info')[i]
            );
        });
    </script>
</x-app-layout>
