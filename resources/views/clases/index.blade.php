@section('title', 'Clases')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Clases Académicas</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Clases', 'url' => '#']
    ]" />

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

            limpiarFiltros() {
                this.magister = '';
                this.sala = '';
                this.dia = '';
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
            </form>
        </div>

        {{-- 🔍 Filtros --}}
        <div class="mb-4 grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
            {{-- Magíster --}}
            <div>
                <label class="text-sm font-semibold text-[#005187]">Programa:</label>
                <select x-model="magister" @change="actualizarURL"
                    class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] text-[#005187] px-2 py-2">
                    <option value="">Todos</option>
                    @foreach ($magisters as $m)
                        <option value="{{ $m->nombre }}">{{ $m->nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Sala --}}
            <div>
                <label class="text-sm font-semibold text-[#005187]">Sala:</label>
                <select x-model="sala" @change="actualizarURL"
                    class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] text-[#005187] px-2 py-2">
                    <option value="">Todas</option>
                    @foreach ($rooms as $r)
                        <option value="{{ $r->name }}">{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Día --}}
            <div>
                <label class="text-sm font-semibold text-[#005187]">Día:</label>
                <select x-model="dia" @change="actualizarURL"
                    class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] text-[#005187] px-2 py-2">
                    <option value="">Todos</option>
                    <option value="Viernes">Viernes</option>
                    <option value="Sábado">Sábado</option>
                </select>
            </div>

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
                    <div class="bg-[#fcffff] dark:bg-gray-800 shadow-md rounded-xl p-4 flex flex-col justify-between 
                                hci-card-hover hci-lift cursor-pointer
                                transition-all duration-300 hover:shadow-xl group"
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
                                <span><strong class="text-[#005187]">Sala:</strong> {{ $clase->room->name ?? 'No asignada' }}</span>
                                <span class="px-2 py-0.5 rounded text-[#005187]">
                                    Año {{ $clase->period->anio ?? '—' }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[#005187]">
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
                                   class="inline-flex items-center justify-center px-3 py-1 bg-[#e57373] hover:bg-[#f28b82] text-white rounded-lg text-xs font-medium transition w-full sm:w-auto">
                                    <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar" class="w-4 h-4">
                                </button>
                            </form>
                        </div>
                    </div>
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
    </div>
</x-app-layout>
