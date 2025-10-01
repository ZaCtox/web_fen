{{-- resources/views/clases/edit.blade.php --}}
@section('title', 'Editar Clase')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#c4dafa]">Editar Clase Académica</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto p-6">
        {{-- Caja principal --}}
        <div class="rounded-xl shadow-md p-6 border-l-4 bg-[#fcffff] dark:bg-gray-800 
                    text-gray-900 dark:text-gray-200 transition hover:shadow-lg hover:-translate-y-1"
            style="border-left:4px solid {{ $clase->course->magister->color ?? '#005187' }}">

            {{-- Errores --}}
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded mb-4">
                    <strong class="font-semibold">⚠️ Errores al guardar:</strong>
                    <ul class="list-disc pl-5 mt-2 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="form-clase" method="POST" action="{{ route('clases.update', $clase) }}" class="space-y-6"
                x-data="{ modalidad: '{{ old('modality', $clase->modality) }}' }"
                data-url-disponibilidad="{{ route('salas.disponibilidad') }}">
                @csrf
                @method('PUT')

                {{-- Programa --}}
                <div>
                    <label for="magister" class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">
                        Programa
                    </label>
                    <select id="magister" data-agrupados='@json($agrupados)'
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <option value="">-- Selecciona --</option>
                        @foreach($agrupados as $magister => $cursos)
                            <option value="{{ $magister }}"
                                @selected(optional($clase->course->magister)->nombre == $magister)>
                                {{ $magister }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Asignatura --}}
                <div>
                    <label for="course_id" class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">
                        Asignatura
                    </label>
                    <select name="course_id" id="course_id" required
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <option value="">-- Asignatura --</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" data-period_id="{{ $course->period_id }}"
                                data-periodo="{{ optional($course->period)->nombre_completo }}"
                                data-period_numero="{{ optional($course->period)->numero }}"
                                data-period_anio="{{ optional($course->period)->anio }}"
                                @selected($clase->course_id == $course->id)>
                                {{ $course->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Período automático (solo hidden) --}}
                <input type="hidden" name="period_id" id="period_id" value="{{ $clase->period_id }}">

                {{-- Mostrar solo Año y Trimestre en dos columnas --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">Año</label>
                        <input type="text" id="anio" value="{{ optional($clase->period)->anio }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 
                   text-gray-500 dark:text-gray-300" disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">Trimestre</label>
                        <input type="text" id="trimestre" value="{{ optional($clase->period)->numero }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 
                   text-gray-500 dark:text-gray-300" disabled>
                    </div>
                </div>

                {{-- Tipo --}}
                <div>
                    <label for="tipo"
                        class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">Tipo</label>
                    @php $tipos = ['catedra', 'taller', 'laboratorio', 'ayudantia']; @endphp
                    <select name="tipo" id="tipo" required
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <option value="">-- Selecciona tipo --</option>
                        @foreach($tipos as $t)
                            <option value="{{ $t }}" @selected(old('tipo', $clase->tipo) === $t)>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Modalidad --}}
                <div>
                    <label for="modality" class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">
                        Modalidad
                    </label>
                    <select name="modality" id="modality" x-model="modalidad" required
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <option value="presencial" @selected(old('modality', $clase->modality) === 'presencial')>
                            Presencial</option>
                        <option value="online" @selected(old('modality', $clase->modality) === 'online')>Online</option>
                        <option value="híbrida" @selected(old('modality', $clase->modality) === 'híbrida')>Híbrida
                        </option>
                    </select>
                </div>

                {{-- Sala --}}
                <div>
                    <label for="room_id" class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">Sala
                        (opcional)</label>
                    <select name="room_id" id="room_id" :disabled="modalidad === 'online'"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <option value="">-- Selecciona una sala --</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}" @selected(old('room_id', $clase->room_id) == $room->id)>
                                {{ $room->name }}
                            </option>
                        @endforeach
                    </select>
                    <p x-show="modalidad === 'online'" class="text-sm text-[#4d82bc] mt-1">
                        Esta clase es online, por lo tanto no requiere sala.
                    </p>
                </div>

                {{-- Zoom --}}
                <div x-show="modalidad === 'online'" x-transition>
                    <label for="url_zoom" class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">Enlace
                        Zoom</label>
                    <input type="url" name="url_zoom" id="url_zoom" placeholder="https://us02web.zoom.us/..."
                        value="{{ old('url_zoom', $clase->url_zoom) }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                </div>

                {{-- Encargado --}}
                <div>
                    <label for="encargado" class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">
                        Encargado (Profesor)
                    </label>
                    <input type="text" name="encargado" id="encargado" value="{{ old('encargado', $clase->encargado) }}"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        placeholder="Ej: Prof. Juan Pérez">
                </div>

                {{-- Día y Horas --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">Día</label>
                        <select name="dia" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                            <option value="Viernes" @selected(old('dia', $clase->dia) === 'Viernes')>Viernes</option>
                            <option value="Sábado" @selected(old('dia', $clase->dia) === 'Sábado')>Sábado</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">Hora
                            inicio</label>
                        <input type="time" name="hora_inicio"
                            value="{{ old('hora_inicio', \Carbon\Carbon::parse($clase->hora_inicio)->format('H:i')) }}"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">Hora fin</label>
                        <input type="time" name="hora_fin"
                            value="{{ old('hora_fin', \Carbon\Carbon::parse($clase->hora_fin)->format('H:i')) }}"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                            required>
                    </div>
                </div>

                {{-- Disponibilidad de sala --}}
                <div x-data="{
                        checking:false, available:null, conflicts:[], timer:null,
                        init(){
                          const boot = () => {
                            if (window.disponibilidadSalaEdit) {
                              Object.assign(this, window.disponibilidadSalaEdit({{ $clase->id }}));
                              this.initEdit();
                            } else {
                              setTimeout(boot, 30);
                            }
                          };
                          boot();
                        }
                      }" x-cloak class="mt-2 border rounded p-3 dark:border-gray-700">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">Disponibilidad</span>
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

                {{-- Botones --}}
                <div class="mt-6 flex justify-between items-center">
                    <a href="{{ route('clases.index') }}" class="inline-block bg-[#4d82bc] hover:bg-[#005187] 
               text-white px-4 py-2 rounded-md shadow-md transition">
                        <img src="{{ asset('icons/back.svg') }}" alt="back" class="w-5 h-5">
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center bg-[#005187] hover:bg-[#4d82bc] 
                                   text-white px-4 py-2 rounded-lg shadow text-sm font-medium 
                                   transition transform hover:scale-105">
                        <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-5 h-5">
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- JS --}}
    @vite('resources/js/clases/form.js')
</x-app-layout>