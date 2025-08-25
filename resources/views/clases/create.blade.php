<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            ➕ Nueva Clase Académica
        </h2>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto">
        <form id="form-clase" action="{{ route('clases.store') }}" method="POST" class="space-y-6"
            x-data="{ modalidad: '{{ old('modality', 'presencial') }}' }"
            data-url-disponibilidad="{{ route('salas.disponibilidad') }}">
            @csrf

            {{-- Magíster --}}
            <div>
                <label for="magister"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Magíster</label>
                <select id="magister" data-agrupados='@json($agrupados)'
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                    <option value="">-- Selecciona --</option>
                    @foreach($agrupados as $magister => $cursos)
                        <option value="{{ $magister }}">{{ $magister }}</option>
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
                </select>
            </div>

            {{-- Periodo (automático) --}}
            <input type="hidden" name="period_id" id="period_id">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-3">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Periodo
                        (automático)</label>
                    <select id="periodo_info" disabled
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 cursor-not-allowed">
                        <option>Selecciona un curso para ver el período</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Trimestre</label>
                    <input type="text" id="trimestre"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300"
                        disabled>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Año</label>
                    <input type="text" id="anio"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300"
                        disabled>
                </div>
            </div>

            {{-- Tipo --}}
            <div>
                <label for="tipo" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Tipo</label>
                <select name="tipo" id="tipo" required
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                    @php $tipos = ['clase', 'taller', 'laboratorio', 'ayudantia']; @endphp
                    <option value="">-- Selecciona tipo --</option>
                    @foreach($tipos as $t)
                        <option value="{{ $t }}" @selected(old('tipo') === $t)>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Modalidad --}}
            <div>
                <label for="modality"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Modalidad</label>
                <select name="modality" id="modality" x-model="modalidad"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    required>
                    <option value="presencial" @selected(old('modality') === 'presencial')>Presencial</option>
                    <option value="online" @selected(old('modality') === 'online')>Online</option>
                    <option value="hibrida" @selected(old('modality') === 'hibrida')>Híbrida</option>
                </select>
            </div>

            {{-- Sala --}}
            <div>
                <label for="room_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Sala
                    (opcional)</label>
                <select name="room_id" id="room_id"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    :disabled="modalidad === 'online'">
                    <option value="">-- Selecciona una sala --</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}" @selected(old('room_id') == $room->id)>{{ $room->name }}</option>
                    @endforeach
                </select>
                <p x-show="modalidad === 'online'" class="text-sm text-blue-500 mt-1">
                    Esta clase es online, por lo tanto no requiere sala.
                </p>
            </div>

            {{-- Enlace Zoom (solo online) --}}
            <div x-show="modalidad === 'online'" x-transition>
                <label for="url_zoom" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Enlace Zoom
                    (opcional)</label>
                <input type="url" name="url_zoom" id="url_zoom" placeholder="https://us02web.zoom.us/..."
                    value="{{ old('url_zoom') }}"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
            </div>

            {{-- Encargado --}}
            <div>
                <label for="encargado" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Encargado
                    (Profesor)</label>
                <input type="text" name="encargado" id="encargado" value="{{ old('encargado') }}"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    placeholder="Ej: Prof. Juan Pérez">
            </div>

            {{-- Día y horas --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Día</label>
                    <select name="dia" required
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <option value="Viernes" @selected(old('dia') === 'Viernes')>Viernes</option>
                        <option value="Sábado" @selected(old('dia') === 'Sábado')>Sábado</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Hora inicio</label>
                    <input type="time" name="hora_inicio" value="{{ old('hora_inicio') }}" required
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Hora fin</label>
                    <input type="time" name="hora_fin" value="{{ old('hora_fin') }}" required
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                </div>
            </div>

            {{-- Disponibilidad de sala (Alpine) --}}
            <div x-data="{
      checking:false, available:null, conflicts:[], timer:null,
      init(){
        const boot = () => {
          if (window.disponibilidadSala) {
            Object.assign(this, window.disponibilidadSala());
            this.initCreate();
          } else {
            setTimeout(boot, 30);
          }
        };
        boot();
      }
    }" x-cloak class="mt-2 border rounded p-3 dark:border-gray-700">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Disponibilidad</span>
                    <template x-if="checking"><span class="text-xs text-gray-500">Comprobando...</span></template>
                    <template x-if="!checking && available === true">
                        <span class="text-xs px-2 py-1 rounded bg-green-100 text-green-700">Disponible</span>
                    </template>
                    <template x-if="!checking && available === false">
                        <span class="text-xs px-2 py-1 rounded bg-red-100 text-red-700">Ocupada</span>
                    </template>
                </div>

                <template x-if="conflicts.length">
                    <div class="mt-3">
                        <p class="text-xs text-red-600 mb-2">Conflictos encontrados:</p>
                        <ul class="text-xs list-disc pl-5 space-y-1">
                            <template x-for="c in conflicts" :key="c.id">
                                <li>
                                    <strong x-text="c.programa"></strong> —
                                    <span x-text="c.curso"></span> —
                                    <span x-text="`${c.dia} ${c.hora_inicio}-${c.hora_fin}`"></span> —
                                    Sala <span x-text="c.sala"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </template>

                <input type="hidden" name="__no_conflicts" :value="available ? 1 : 0">
            </div>

            {{-- Consultar horarios disponibles --}}
            <div id="horarios-section" x-show="modalidad !== 'online'" x-cloak class="mt-2">
                <button type="button" id="btn-horarios"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-3 py-1 rounded"
                    data-url-horarios="{{ route('salas.horarios') }}" @isset($clase) data-exclude-id="{{ $clase->id }}"
                    @endisset>
                    🔎 Ver horarios disponibles
                </button>

                <div class="mt-2 flex flex-wrap items-center gap-3">
                    <label class="text-sm text-gray-600 dark:text-gray-300">Duración del bloque:</label>
                    <select id="block_hours"
                        class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <option value="1" selected>1 hora</option>
                        <option value="2">2 horas</option>
                        <option value="3">3 horas</option>
                        <option value="4">4 horas</option>
                    </select>

                    <label class="text-sm text-gray-600 dark:text-gray-300">Holgura:</label>
                    <input id="buffer_mins" type="number" min="0" max="60" value="10"
                        class="w-20 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white text-sm px-2 py-1">
                    <span class="text-sm text-gray-600 dark:text-gray-300">min</span>
                </div>

                <small id="help-huecos" class="block text-gray-500 mt-1">
                    Busca huecos libres de 1 hora con 10 minutos de holgura antes y después.
                </small>

                <div id="slots-wrap" class="mt-3 hidden">
                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">Selecciona un slot libre:</p>
                    <div id="slots" class="flex flex-wrap gap-2"></div>
                </div>
            </div>


            {{-- Botones --}}
            <div class="pt-4 flex flex-col sm:flex-row items-center gap-4">
                <x-button-fen class="w-full sm:w-auto">💾 Guardar Clase</x-button-fen>
                <a href="{{ route('clases.index') }}"
                    class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    ⬅️ Volver a Clases
                </a>
            </div>
        </form>
    </div>

    {{-- Carga el JS compartido --}}
    @vite('resources/js/clases/form.js')
</x-app-layout>