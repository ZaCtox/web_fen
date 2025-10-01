<<<<<<< Updated upstream
{{-- Inicio de Clase.blade.php --}}
=======
>>>>>>> Stashed changes
@section('title', 'Clases')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Clases Académicas</h2>
    </x-slot>

<<<<<<< Updated upstream
    {{-- Si tu layout ya incluye meta para success/error, puedes agregar también warning allí.
    Si aún no, deja este meta aquí para que alerts.js muestre el aviso de advertencia. --}}
    @if (session('warning'))
        <meta name="session-warning" content="{{ session('warning') }}">
    @endif

    <div class="p-6 max-w-7xl mx-auto" x-data="{
            magister: '',
            sala: '',
            dia: '',
            clases: @js($clases),
            safe(val, fallback='') { return (val ?? fallback); },
            get filtradas() {
                return this.clases.filter(c => {
                    const mag = this.safe(c?.course?.magister?.nombre);
                    const room = this.safe(c?.room?.name);
                    const day = this.safe(c?.dia);
                    return (!this.magister || mag === this.magister)
                        && (!this.sala || room === this.sala)
                        && (!this.dia || day === this.dia);
                });
            },
=======
    <div class="p-6 max-w-7xl mx-auto" x-data="{
            magister: '{{ request('magister') }}',
            sala: '{{ request('room_id') }}',
            dia: '{{ request('dia') }}',
            anio: '{{ request('anio') }}',
            trimestre: '{{ request('trimestre') }}',

            periodos: @js($periodos),

            get periodosFiltrados() {
                if (!this.anio) return this.periodos;
                return this.periodos.filter(p => p.anio == this.anio);
            },

            actualizarURL() {
                const params = new URLSearchParams(window.location.search);
                this.magister ? params.set('magister', this.magister) : params.delete('magister');
                this.sala ? params.set('room_id', this.sala) : params.delete('room_id');
                this.dia ? params.set('dia', this.dia) : params.delete('dia');
                this.anio ? params.set('anio', this.anio) : params.delete('anio');
                this.trimestre ? params.set('trimestre', this.trimestre) : params.delete('trimestre');
                window.location.search = params.toString();
            },

>>>>>>> Stashed changes
            limpiarFiltros() {
                this.magister = '';
                this.sala = '';
                this.dia = '';
<<<<<<< Updated upstream
            },
         }">

        {{-- Acciones principales --}}
        <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
            <a href="{{ route('clases.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-sm">
                ➕ Nueva Clase
            </a>

            {{-- Exportar PDF con filtros actuales --}}
            @auth
                @if(tieneRol(['administrativo', 'docente']))
                    <form method="GET" action="{{ route('clases.exportar') }}">
                        <input type="hidden" name="magister" x-bind:value="magister">
                        <input type="hidden" name="sala" x-bind:value="sala">
                        <input type="hidden" name="dia" x-bind:value="dia">
                        <button class="bg-green-600 text-white px-4 py-2 rounded">📤 Exportar PDF</button>
                    </form>
                @endif
            @endauth
        </div>

        {{-- Filtros --}}
        <div class="flex flex-col sm:flex-row flex-wrap gap-4 mb-6">
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300">Programa:</label>
                <select x-model="magister" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:text-white">
=======
                this.anio = '';
                this.trimestre = '';
                this.actualizarURL();
            },
        }">

        {{-- 🔹 Acciones principales --}}
        <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
            <a href="{{ route('clases.create') }}"
                class="inline-flex items-center bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-lg shadow transition transform hover:scale-105">
                <img src="{{ asset('icons/agregar.svg') }}" alt="nueva" class="w-5 h-5">
            </a>

            <form method="GET" action="{{ route('clases.exportar') }}">
                <input type="hidden" name="magister" value="{{ request('magister') }}">
                <input type="hidden" name="room_id" value="{{ request('room_id') }}">
                <input type="hidden" name="dia" value="{{ request('dia') }}">
                <input type="hidden" name="anio" value="{{ request('anio') }}">
                <input type="hidden" name="trimestre" value="{{ request('trimestre') }}">
                <button type="submit"
                    class="bg-[#005187] hover:bg-[#4d82bc] text-white px-4 py-2 rounded-lg shadow text-sm transition transform hover:scale-105 flex items-center gap-2">
                    <img src="{{ asset('icons/download.svg') }}" alt="download" class="w-5 h-5">
                </button>
        </div>

        {{-- 🔍 Filtros --}}
        <div class="mb-4 grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
            {{-- Magíster --}}
            <div>
                <label class="text-sm font-semibold text-[#005187]">Programa:</label>
                <select x-model="magister" @change="actualizarURL"
                    class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] text-[#005187] px-2 py-2">
>>>>>>> Stashed changes
                    <option value="">Todos</option>
                    @foreach ($magisters as $m)
                        <option value="{{ $m->nombre }}">{{ $m->nombre }}</option>
                    @endforeach
                </select>
            </div>

<<<<<<< Updated upstream
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300">Sala:</label>
                <select x-model="sala" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:text-white">
=======
            {{-- Sala --}}
            <div>
                <label class="text-sm font-semibold text-[#005187]">Sala:</label>
                <select x-model="sala" @change="actualizarURL"
                    class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] text-[#005187] px-2 py-2">
>>>>>>> Stashed changes
                    <option value="">Todas</option>
                    @foreach ($rooms as $r)
                        <option value="{{ $r->name }}">{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>

<<<<<<< Updated upstream
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300">Día:</label>
                <select x-model="dia" class="w-full border rounded px-5 py-2 dark:bg-gray-800 dark:text-white">
=======
            {{-- Día --}}
            <div>
                <label class="text-sm font-semibold text-[#005187]">Día:</label>
                <select x-model="dia" @change="actualizarURL"
                    class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] text-[#005187] px-2 py-2">
>>>>>>> Stashed changes
                    <option value="">Todos</option>
                    <option value="Viernes">Viernes</option>
                    <option value="Sábado">Sábado</option>
                </select>
            </div>

<<<<<<< Updated upstream
            <div class="flex flex-col justify-end">
                <button @click="limpiarFiltros" type="button"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded w-full sm:w-auto">
                    Limpiar filtros
                </button>
            </div>
        </div>

        {{-- Resultados --}}
        <template x-if="filtradas.length > 0">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                <template x-for="clase in filtradas" :key="clase.id">
                    <div class="bg-white dark:bg-gray-800 shadow-md rounded p-4 space-y-3"
                        :style="`border-left: 4px solid ${safe(clase?.course?.magister?.color ?? '#6b7280')}`">

                        {{-- Título del curso --}}
                        <h3 class="font-semibold text-lg text-gray-800 dark:text-white"
                            x-text="safe(clase?.course?.nombre, '—')"></h3>

                        {{-- Badges: Programa, Tipo, Modalidad --}}
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span
                                class="px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200"
                                x-text="safe(clase?.course?.magister?.nombre, 'Programa')"></span>
                            <span
                                class="px-2 py-0.5 rounded bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-100"
                                x-text="safe(clase?.tipo, 'Tipo')"></span>
                            <span
                                class="px-2 py-0.5 rounded bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-100"
                                x-text="safe(clase?.modality, 'Modalidad')"></span>
                        </div>

                        {{-- Línea principal: Día • Horario --}}
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            <strong x-text="safe(clase?.dia, '—')"></strong>
                            <span> • </span>
                            <span
                                x-text="`${safe(clase?.hora_inicio,'--:--')} - ${safe(clase?.hora_fin,'--:--')}`"></span>
                        </p>

                        {{-- Secundario: Sala + Trimestre/Año como chips compactos --}}
                        <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                            <span><strong>Sala:</strong> <span
                                    x-text="safe(clase?.room?.name, 'No asignada')"></span></span>
                            <span
                                class="px-2 py-0.5 rounded bg-amber-100 dark:bg-amber-900 text-amber-800 dark:text-amber-100"
                                x-text="`T${safe(clase?.period?.numero, '—')}`"></span>
                            <span
                                class="px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-100"
                                x-text="safe(clase?.period?.anio, '—')"></span>
                        </div>

                        {{-- Acciones --}}
                        <div class="flex flex-col sm:flex-row gap-2 pt-2">
                            <a :href="`/clases/${clase.id}`"
                                class="text-xs bg-gray-100 text-gray-800 hover:bg-gray-200 px-3 py-1 rounded text-center">🔍</a>

                            <a :href="`/clases/${clase.id}/edit`"
                                class="text-xs bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1 rounded text-center">✏️</a>

                            <form :action="`/clases/${clase.id}`" method="POST" class="form-eliminar">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="__confirm_msg"
                                    :value="`¿Eliminar la clase '${safe(clase?.course?.nombre,'')}'?`" />
                                <button type="submit"
                                    class="text-xs bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1 rounded w-full text-center">
                                    🗑️
=======
            {{-- Año --}}
            <div>
                <label class="text-sm font-semibold text-[#005187]">Año:</label>
                <select x-model="anio" @change="actualizarURL"
                    class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] text-[#005187] px-2 py-2">
                    <option value="">Todos</option>
                    @foreach ($anios as $a)
                        <option value="{{ $a }}">{{ $a }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Trimestre --}}
            <div>
                <label class="text-sm font-semibold text-[#005187]">Trimestre:</label>
                <select x-model="trimestre" @change="actualizarURL"
                    class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] text-[#005187] px-2 py-2">
                    <option value="">Todos</option>
                    <template x-for="p in periodosFiltrados" :key="p.id">
                        <option :value="p.numero" x-text="'Trimestre ' + p.numero"></option>
                    </template>
                </select>
            </div>
        </div>

        {{-- Botón limpiar --}}
        <div class="mb-6">
            <button @click="limpiarFiltros" type="button"
                class="bg-[#c4dafa] hover:bg-[#84b6f4] text-[#005187] px-4 py-2 rounded-lg shadow text-sm transition transform hover:scale-105">
                <img src="{{ asset('icons/filtro.svg') }}" alt="Filtro" class="w-5 h-5">
            </button>
        </div>

        {{-- 📌 Resultados paginados --}}
        @if ($clases->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach ($clases as $clase)
                    <div class="bg-[#fcffff] dark:bg-gray-800 shadow-md rounded-xl p-4 flex flex-col justify-between transition hover:shadow-lg hover:-translate-y-1"
                        style="border-left: 4px solid {{ $clase->course->magister->color ?? '#4d82bc' }}">

                        <div class="space-y-3">
                            {{-- Nombre curso --}}
                            <h3 class="font-semibold text-lg text-[#005187] dark:text-[#84b6f4]">
                                {{ $clase->course->nombre ?? '—' }}
                            </h3>

                            {{-- Tags principales --}}
                            <div class="flex flex-wrap gap-2 text-xs">
                                <span class="px-2 py-0.5 rounded bg-[#c4dafa] text-[#005187]">
                                    {{ $clase->course->magister->nombre ?? 'Programa' }}
                                </span>
                                <span class="px-2 py-0.5 rounded bg-[#84b6f4] text-[#005187] capitalize">
                                    {{ $clase->tipo ?? 'Tipo' }}
                                </span>
                                <span class="px-2 py-0.5 rounded bg-[#4d82bc] text-white capitalize">
                                    {{ $clase->modality ?? 'Modalidad' }}
                                </span>
                            </div>

                            {{-- Día y hora --}}
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <strong class="text-[#005187]">{{ $clase->dia ?? '—' }}</strong>
                                •
                                {{ $clase->hora_inicio ? substr($clase->hora_inicio, 0, 5) : '--:--' }} -
                                {{ $clase->hora_fin ? substr($clase->hora_fin, 0, 5) : '--:--' }}
                            </p>

                            {{-- Sala y periodo --}}
                            <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                <span><strong>Sala:</strong> {{ $clase->room->name ?? 'No asignada' }}</span>
                                <span class="px-2 py-0.5 rounded bg-[#c4dafa] text-[#005187]">
                                    Año {{ $clase->period->anio ?? '—' }}
                                </span>
                                <span class="px-2 py-0.5 rounded bg-[#84b6f4] text-[#005187]">
                                    Trimestre {{ $clase->period->numero ?? '—' }}
                                </span>
                            </div>
                        </div>

                        {{-- Botones acción (siempre abajo) --}}
                        <div class="flex justify-end gap-2 mt-4">
                            <a href="{{ route('clases.show', $clase) }}"
                                class="inline-flex items-center justify-center px-3 py-1 bg-[#84b6f4] hover:bg-[#4d82bc] hover:text-white rounded-lg transition">
                                <img src="{{ asset('icons/ver.svg') }}" alt="Ver" class="w-5 h-5">
                            </a>

                            <a href="{{ route('clases.edit', $clase) }}"
                                class="inline-flex items-center justify-center px-3 py-1 bg-[#84b6f4] hover:bg-[#4d82bc] hover:text-white rounded-lg transition">
                                <img src="{{ asset('icons/edit.svg') }}" alt="Editar" class="w-5 h-5">
                            </a>

                            <form action="{{ route('clases.destroy', $clase) }}" method="POST"
                                onsubmit="return confirm('¿Seguro que deseas eliminar esta clase?')" class="inline-flex">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center justify-center px-3 py-1 bg-[#84b6f4] hover:bg-[#4d82bc] hover:text-white rounded-lg transition">
                                    <img src="{{ asset('icons/trash.svg') }}" alt="Eliminar" class="w-4 h-4">
>>>>>>> Stashed changes
                                </button>
                            </form>
                        </div>
                    </div>
<<<<<<< Updated upstream
                </template>
            </div>
        </template>

        {{-- Sin resultados --}}
        <template x-if="filtradas.length === 0">
            <p class="text-center text-gray-500 dark:text-gray-400 mt-12">
                😢 No hay clases que coincidan con los filtros seleccionados.
            </p>
        </template>
=======
                @endforeach
            </div>


            {{-- 📌 Paginación --}}
            <div class="mt-6">
                {{ $clases->links() }}
            </div>
        @else
            <p class="text-center text-gray-500 dark:text-gray-400 mt-12">
                😢 No hay clases que coincidan con los filtros seleccionados.
            </p>
        @endif
>>>>>>> Stashed changes
    </div>
</x-app-layout>