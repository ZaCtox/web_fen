<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            Asignar Clase Online
        </h2>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto">
        <form action="{{ route('online.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Selección de Magíster --}}
            <div>
                <label for="magister_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Magíster</label>
                <select name="magister_id" id="magister_id" required onchange="filtrarCursosPorMagister()"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-800 dark:text-white">
                    <option value="">-- Selecciona --</option>
                    @foreach ($cursos->pluck('magister')->unique('id') as $magister)
                        <option value="{{ $magister->id }}">{{ $magister->nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Cursos según Magíster --}}
            <div>
                <label for="course_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Asignatura</label>
                <select name="course_id" id="course_id" required onchange="actualizarPeriodo()"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-800 dark:text-white">
                    <option value="">-- Selecciona un magíster primero --</option>
                </select>
            </div>

            {{-- Periodo mostrado y oculto --}}
            <input type="hidden" name="period_id" id="period_id">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Periodo Académico</label>
                <input type="text" readonly id="periodo_texto"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 cursor-not-allowed">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="dia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Día</label>
                    <select name="dia" id="dia" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-800 dark:text-white">
                        <option value="">-- Día --</option>
                        <option value="Viernes" {{ old('dia') == 'Viernes' ? 'selected' : '' }}>Viernes</option>
                        <option value="Sábado" {{ old('dia') == 'Sábado' ? 'selected' : '' }}>Sábado</option>
                    </select>
                </div>

                <div>
                    <label for="hora_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hora inicio</label>
                    <input type="time" name="hora_inicio" id="hora_inicio" required value="{{ old('hora_inicio') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-800 dark:text-white">
                </div>

                <div>
                    <label for="hora_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hora fin</label>
                    <input type="time" name="hora_fin" id="hora_fin" required value="{{ old('hora_fin') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-800 dark:text-white">
                </div>
            </div>

            <div>
                <label for="url_zoom" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Enlace Zoom (opcional)</label>
                <input type="url" name="url_zoom" id="url_zoom" placeholder="https://us02web.zoom.us/..." value="{{ old('url_zoom') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-800 dark:text-white">
            </div>

            <div class="pt-4">
                <x-button-fen>💾 Guardar Clase Online</x-button-fen>
                <a href="{{ route('calendario') }}"
                   class="ml-4 text-sm text-gray-700 dark:text-gray-300 hover:underline">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        const cursos = @json($cursos);

        function filtrarCursosPorMagister() {
            const magisterId = document.getElementById('magister_id').value;
            const selectCurso = document.getElementById('course_id');

            selectCurso.innerHTML = '<option value="">-- Selecciona --</option>';

            cursos.forEach(curso => {
                if (curso.magister_id == magisterId) {
                    const option = document.createElement('option');
                    option.value = curso.id;
                    option.textContent = curso.nombre;
                    option.dataset.periodoNombre = curso.periodo?.nombre_completo ?? '';
                    option.dataset.periodoId = curso.periodo?.id ?? '';
                    selectCurso.appendChild(option);
                }
            });

            actualizarPeriodo();
        }

        function actualizarPeriodo() {
            const selectCurso = document.getElementById('course_id');
            const selectedOption = selectCurso.options[selectCurso.selectedIndex];
            const periodoTexto = selectedOption.dataset.periodoNombre || '';
            const periodoId = selectedOption.dataset.periodoId || '';

            document.getElementById('periodo_texto').value = periodoTexto;
            document.getElementById('period_id').value = periodoId;
        }
    </script>
</x-app-layout>
