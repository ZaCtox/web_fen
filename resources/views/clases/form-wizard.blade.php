{{-- Formulario de Clases con Principios HCI - Wizard 4 pasos --}}
@section('title', isset($clase) ? 'Editar Clase' : 'Crear Clase')

@php
    $editing = isset($clase);
@endphp

<x-hci-breadcrumb 
    :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Clases', 'url' => route('clases.index')],
        ['label' => $editing ? 'Editar Clase' : 'Nueva Clase', 'url' => '#']
    ]"
/>

<div class="hci-container">
    <div class="hci-section">
        <h1 class="hci-heading-1 flex items-center">
            @if($editing)
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                Editar Clase
            @else
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                Nueva Clase
            @endif
        </h1>
        <p class="hci-text">{{ $editing ? 'Modifica la programación de la clase.' : 'Configura una nueva clase académica.' }}</p>
    </div>

    <div class="hci-wizard-layout">
        <x-classes-progress-sidebar />

        <div class="hci-form-content">
            <form id="form-clase" class="hci-form" method="POST" action="{{ $editing ? route('clases.update', $clase) : route('clases.store') }}" data-url-disponibilidad="{{ route('salas.disponibilidad') }}">
                @csrf
                @if($editing) @method('PUT') @endif

                {{-- Paso 1: Programa, Curso y Período --}}
                <x-hci-form-section 
                    :step="1"
                    title="Programa y Curso"
                    description="Selecciona el programa y asignatura para obtener el período"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z' clip-rule='evenodd'/></svg>"
                    section-id="programa"
                    :is-active="true"
                    :is-first="true"
                    :editing="$editing"
                >
                    @php $selectedMagister = isset($clase) ? optional($clase->course->magister)->nombre : old('magister'); @endphp
                    <x-hci-field name="magister" type="select" label="Programa" :required="true" id="magister" help="Primero selecciona el programa" data-agrupados='@json($agrupados ?? [])' :options="[]">
                        <option value="">-- Programa --</option>
                        @foreach(($agrupados ?? []) as $magNombre => $cursos)
                            <option value="{{ $magNombre }}" {{ ($selectedMagister == $magNombre) ? 'selected' : '' }}>{{ $magNombre }}</option>
                        @endforeach
                    </x-hci-field>

                    <x-hci-field name="course_id" type="select" label="Asignatura" :required="true" id="course_id" help="Luego elige la asignatura"> 
                        <option value="">-- Asignatura --</option>
                        @php
                            $selectedMagister = isset($clase) ? optional($clase->course->magister)->nombre : null;
                        @endphp
                        @if(isset($agrupados) && $selectedMagister && isset($agrupados[$selectedMagister]))
                            @foreach($agrupados[$selectedMagister] as $c)
                                <option value="{{ $c['id'] }}" data-period_id="{{ $c['period_id'] }}" data-periodo="{{ $c['periodo'] }}" data-period_numero="{{ $c['numero'] }}" data-period_anio="{{ $c['anio'] }}" {{ old('course_id', $clase->course_id ?? '') == $c['id'] ? 'selected' : '' }}>{{ $c['nombre'] }}</option>
                            @endforeach
                        @endif
                    </x-hci-field>

                    <input type="hidden" name="period_id" id="period_id" value="{{ old('period_id', $clase->period_id ?? '') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <x-hci-field name="anio" label="Año" id="anio" disabled="true" help="Se completa automáticamente" />
                        <x-hci-field name="trimestre" label="Trimestre" id="trimestre" disabled="true" help="Se completa automáticamente" />
                    </div>
                </x-hci-form-section>

                {{-- Paso 2: Sala y Modalidad --}}
                <x-hci-form-section 
                    :step="2"
                    title="Tipo, Modalidad y Sala"
                    description="Define el tipo de clase, la modalidad y la sala"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
                    section-id="sala"
                    :editing="$editing"
                >
                    <x-hci-field name="tipo" type="select" label="Tipo de Clase" :required="true">
                        @php $tipos = ['cátedra', 'taller', 'laboratorio', 'ayudantía']; @endphp
                        <option value="">-- Tipo --</option>
                        @foreach($tipos as $t)
                            <option value="{{ $t }}" {{ old('tipo', $clase->tipo ?? '') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </x-hci-field>

                    <x-hci-field name="modality" type="select" label="Modalidad" :required="true" id="modality">
                        <option value="presencial" {{ old('modality', $clase->modality ?? '') == 'presencial' ? 'selected' : '' }}>Presencial</option>
                        <option value="hibrida" {{ old('modality', $clase->modality ?? '') == 'hibrida' ? 'selected' : '' }}>Híbrida</option>
                        <option value="online" {{ old('modality', $clase->modality ?? '') == 'online' ? 'selected' : '' }}>Online</option>
                    </x-hci-field>

                    <x-hci-field name="room_id" type="select" label="Sala" id="room_id" help="Se deshabilita si es online">
                        <option value="">-- Sala --</option>
                        @foreach(($rooms ?? []) as $r)
                            <option value="{{ $r->id }}" {{ old('room_id', $clase->room_id ?? '') == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                        @endforeach
                    </x-hci-field>

                    <x-hci-field name="url_zoom" type="url" label="Enlace Zoom" placeholder="https://us02web.zoom.us/..." value="{{ old('url_zoom', $clase->url_zoom ?? '') }}" help="Obligatorio en Online; recomendado en Híbrida" />

                    <x-hci-field name="encargado" label="Encargado (Profesor)" placeholder="Ej: Prof. Juan Pérez" value="{{ old('encargado', $clase->encargado ?? '') }}" />
                </x-hci-form-section>

                {{-- Paso 3: Día y Horario --}}
                <x-hci-form-section 
                    :step="3"
                    title="Día y Horario"
                    description="Configura día y horas; revisa disponibilidad"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M8 7V3a1 1 0 112 0v4h4a1 1 0 110 2h-4v4a1 1 0 11-2 0V9H4a1 1 0 110-2h4z' clip-rule='evenodd'/></svg>"
                    section-id="horario"
                    :editing="$editing"
                >
                    <x-hci-field name="dia" type="select" label="Día" :required="true">
                        <option value="">-- Día --</option>
                        @foreach(['Viernes','Sábado'] as $d)
                            <option value="{{ $d }}" {{ old('dia', $clase->dia ?? '') == $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </x-hci-field>

                    <x-hci-field name="hora_inicio" type="time" label="Hora inicio" :required="true" />
                    <x-hci-field name="hora_fin" type="time" label="Hora fin" :required="true" />

                    <div class="md:col-span-2 lg:col-span-3">
                        <div class="flex items-center gap-3 mb-2 text-sm" id="help-huecos"></div>
                        <div class="flex items-center gap-4 mb-3">
                            <label class="text-sm text-gray-600">Bloques (60 min)</label>
                            <select id="block_count" class="hci-select w-28">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                            <button type="button" id="btn-horarios" class="hci-button hci-button-success" data-url-horarios="{{ route('salas.horarios') }}" @if($editing) data-exclude-id="{{ $clase->id }}" @endif>Ver horarios disponibles</button>
                        </div>
                        <div id="slots-wrap" class="hidden">
                            <div id="slots" class="flex flex-wrap gap-2"></div>
                        </div>

                        {{-- Disponibilidad en tiempo real (mismo comportamiento que create) --}}
                        <div x-data="{
                            checking:false, available:null, conflicts:[], timer:null,
                            init(){
                              const boot = () => {
                                const fn = {{ $editing ? 'window.disponibilidadSalaEdit' : 'window.disponibilidadSala' }};
                                if (typeof fn === 'function') {
                                  const base = {{ $editing ? 'fn('.($clase->id ?? 'null').')' : 'fn()' }};
                                  Object.assign(this, base);
                                  this.{{ $editing ? 'initEdit' : 'initCreate' }}();
                                } else {
                                  setTimeout(boot, 50);
                                }
                              };
                              boot();
                            }
                        }" x-cloak class="mt-4 border border-[#c4dafa]/60 rounded p-3 bg-[#fcffff]">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-sm font-semibold text-[#005187]">Disponibilidad</span>
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
                        </div>
                    </div>
                </x-hci-form-section>

                {{-- Paso 4: Resumen --}}
                <x-hci-form-section 
                    :step="4"
                    title="Resumen"
                    description="Verifica antes de guardar"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
                    section-id="resumen"
                    :is-last="true"
                    :editing="$editing"
                >
                    <div class="bg-[#c4dafa]/30 dark:bg-[#84b6f4]/10 rounded-lg p-6 border border-[#84b6f4]/30 w-full">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] block mb-2">Programa</span>
                                <p id="resumen-programa" class="font-medium">—</p>
                            </div>
                            <div class="md:col-span-1 lg:col-span-2 bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] block mb-2">Asignatura</span>
                                <p id="resumen-curso" class="font-medium">—</p>
                            </div>
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] block mb-2">Período</span>
                                <p id="resumen-periodo" class="font-medium">—</p>
                            </div>
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] block mb-2">Tipo</span>
                                <p id="resumen-tipo" class="font-medium">—</p>
                            </div>
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] block mb-2">Modalidad</span>
                                <p id="resumen-modalidad" class="font-medium">—</p>
                            </div>
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] block mb-2">Sala</span>
                                <p id="resumen-sala" class="font-medium">—</p>
                            </div>
                            <div class="md:col-span-2 lg:col-span-2 bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] block mb-2">Horario</span>
                                <p id="resumen-horario" class="font-medium">—</p>
                            </div>
                            <div class="md:col-span-2 lg:col-span-2 bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] block mb-2">Zoom</span>
                                <p id="resumen-zoom" class="font-medium break-all">—</p>
                            </div>
                        </div>
                    </div>
                </x-hci-form-section>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    @vite('resources/js/clases-form-wizard.js')
    @vite('resources/js/clases/form.js')
@endpush


