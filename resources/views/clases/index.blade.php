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
                 class="hci-button hci-lift hci-focus-ring inline-flex items-center bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-lg shadow transition-all duration-200">
                <img src="{{ asset('icons/agregar.svg') }}" alt="nueva" class="w-5 h-5">
            </a>

            <form method="GET" action="{{ route('clases.exportar') }}">
                <input type="hidden" name="magister" value="{{ request('magister') }}">
                <input type="hidden" name="room_id" value="{{ request('room_id') }}">
                <input type="hidden" name="dia" value="{{ request('dia') }}">
                <input type="hidden" name="anio" value="{{ request('anio') }}">
                <input type="hidden" name="trimestre" value="{{ request('trimestre') }}">
                <button type="submit"
                    class="inline-flex items-center justify-center w-20 px-3 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg text-xs font-medium transition"
                    title="Descargar Excel">
                    <img src="{{ asset('icons/download.svg') }}" alt="Descargar" class="w-6 h-6">
                </button>
            </form>
        </div>

        {{-- 🔍 Filtros --}}
        <div class="mb-6 grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
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

            {{-- Botón limpiar --}}
            <div class="flex items-end">
                <button @click="limpiarFiltros" type="button"
                    class="px-3 py-2 bg-[#84b6f4] hover:bg-[#005187] text-[#005187] rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
                    title="Limpiar filtros"
                    aria-label="Limpiar filtros">
                    <img src="{{ asset('icons/filterw.svg') }}" alt="Limpiar filtros" class="w-5 h-5">
                </button>
            </div>
        </div>

        {{-- 📌 Resultados paginados --}}
        @if ($clases->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach ($clases as $clase)
                    <div class="bg-[#fcffff] dark:bg-gray-800 shadow-md rounded-xl p-4 flex flex-col justify-between 
                                hci-card-hover hci-lift cursor-pointer relative
                                transition-all duration-300 hover:shadow-xl group"
                        style="border-left: 4px solid {{ $clase->course->magister->color ?? '#4d82bc' }}"
                        onclick="window.location='{{ route('clases.show', $clase) }}'">

                        <div class="space-y-3">
                            {{-- Nombre curso --}}
                            <h3 class="font-semibold text-lg text-[#005187] dark:text-[#84b6f4] group-hover:text-[#4d82bc] dark:group-hover:text-[#c4dafa] transition-colors duration-200">
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
                        <div class="flex justify-end gap-2 mt-4 relative z-10">
                            {{-- Botón Ver --}}
                            <x-action-button 
                                variant="view" 
                                type="link" 
                                :href="route('clases.show', $clase)" 
                                icon="verw.svg"
                                tooltip="Ver clase"
                                onclick="event.stopPropagation()" />

                            {{-- Botón Editar --}}
                            <x-action-button 
                                variant="edit" 
                                type="link" 
                                :href="route('clases.edit', $clase)" 
                                icon="editw.svg"
                                tooltip="Editar clase"
                                onclick="event.stopPropagation()" />

                            {{-- Botón Eliminar --}}
                            <x-action-button 
                                variant="delete" 
                                :formAction="route('clases.destroy', $clase)" 
                                formMethod="DELETE" 
                                class="form-eliminar"
                                tooltip="Eliminar clase"
                                onclick="event.stopPropagation()" />
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- 📌 Paginación --}}
            <div class="mt-6">
                {{ $clases->links() }}
            </div>
        @else
            <x-empty-state
                type="no-results"
                icon="🔍"
                title="No se encontraron clases"
                message="Intenta ajustar los filtros de programa, tipo, modalidad o período para ver más resultados."
                secondaryActionText="Limpiar Filtros"
                secondaryActionUrl="{{ route('clases.index') }}"
                secondaryActionIcon="🔄"
            />
        @endif
    </div>
</x-app-layout>
