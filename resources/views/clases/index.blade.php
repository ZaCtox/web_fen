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
            anioIngreso: '{{ $anioIngresoSeleccionado }}',
            magister: '{{ request('magister') }}',
            sala: '{{ request('room_id') }}',
            anio: '{{ request('anio') }}',
            trimestre: '{{ request('trimestre') }}',

            periodos: @js($periodos),

            get periodosFiltrados() {
                if (!this.anio) return this.periodos;
                return this.periodos.filter(p => p.anio == this.anio);
            },

            actualizarURL() {
                const params = new URLSearchParams(window.location.search);
                this.anioIngreso ? params.set('anio_ingreso', this.anioIngreso) : params.delete('anio_ingreso');
                this.magister ? params.set('magister', this.magister) : params.delete('magister');
                this.sala ? params.set('room_id', this.sala) : params.delete('room_id');
                this.anio ? params.set('anio', this.anio) : params.delete('anio');
                this.trimestre ? params.set('trimestre', this.trimestre) : params.delete('trimestre');
                
                // Si cambió el año de ingreso, limpiar filtros de año y trimestre
                if (this.anioIngreso !== '{{ $anioIngresoSeleccionado }}') {
                    params.delete('anio');
                    params.delete('trimestre');
                }
                
                window.location.search = params.toString();
            },

            limpiarFiltros() {
                this.magister = '';
                this.sala = '';
                this.anio = '';
                this.trimestre = '';
                this.actualizarURL();
            },
        }">

        {{-- Botones superiores --}}
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
            {{-- Lado izquierdo: Botón Agregar --}}
            @if(false)
            <a href="{{ route('clases.create') }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-sm font-medium hci-button-ripple hci-glow"
               title="Crear nueva clase">
                <img src="{{ asset('icons/agregar.svg') }}" alt="" class="w-5 h-5">
                <span>Agregar Clase</span>
            </a>
            @endif

            {{-- Lado derecho: Botón Exportar --}}
            <form method="GET" action="{{ route('clases.exportar') }}">
                <input type="hidden" name="anio_ingreso" :value="anioIngreso">
                <input type="hidden" name="magister" :value="magister">
                <input type="hidden" name="room_id" :value="sala">
                <input type="hidden" name="anio" :value="anio">
                <input type="hidden" name="trimestre" :value="trimestre">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-sm font-medium hci-button-ripple hci-glow"
                    title="Exportar a Excel">
                    <img src="{{ asset('icons/download.svg') }}" alt="" class="w-5 h-5">
                </button>
            </form>
        </div>

        {{-- Filtro de programa y ciclo --}}
        <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-200 dark:border-blue-800 shadow-md p-4">
            <div class="flex flex-col lg:flex-row items-start lg:items-center gap-4">
                {{-- Programa --}}
                <div>
                    <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4] mb-2">Programa:</label>
                    <select x-model="magister" @change="actualizarURL"
                        class="w-full sm:w-80 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-700 text-[#005187] dark:text-[#84b6f4] px-4 py-2.5 focus:ring-[#4d82bc] focus:border-[#4d82bc] transition font-medium text-base">
                        <option value="">Todos</option>
                        @foreach ($magisters as $m)
                            <option value="{{ $m->nombre }}">{{ $m->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Año de Ingreso --}}
                <div>
                    <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4] mb-2">Año de Ingreso:</label>
                    <select x-model="anioIngreso" 
                            @change="actualizarURL()"
                            class="w-full sm:w-64 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-700 text-[#005187] dark:text-[#84b6f4] px-4 py-2.5 focus:ring-[#4d82bc] focus:border-[#4d82bc] font-medium">
                        @foreach($aniosIngreso as $anio)
                            <option value="{{ $anio }}">
                                {{ $anio }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Indicador de año de ingreso --}}
            @if($anioIngresoSeleccionado != $aniosIngreso->first())
                <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                        ⚠️ Mostrando clases de un Año de Ingreso Anterior
                    </p>
                </div>
            @endif
        </div>

        {{-- Filtros adicionales --}}
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">
                {{-- Sala --}}
                <div>
                    <label class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4] mb-2">Sala:</label>
                    <select x-model="sala" @change="actualizarURL"
                        class="w-full rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-700 text-[#005187] dark:text-[#84b6f4] px-3 py-2.5 focus:ring-2 focus:ring-[#4d82bc] focus:border-[#4d82bc] transition-colors hci-input-focus text-sm">
                        <option value="">Todas</option>
                        @foreach ($rooms as $r)
                            <option value="{{ $r->name }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Año --}}
                <div>
                    <label class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4] mb-2">Año:</label>
                    <select x-model="anio" @change="actualizarURL"
                        class="w-full rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-700 text-[#005187] dark:text-[#84b6f4] px-3 py-2.5 focus:ring-2 focus:ring-[#4d82bc] focus:border-[#4d82bc] transition-colors hci-input-focus text-sm">
                        <option value="">Todos</option>
                        @foreach ($anios as $a)
                            <option value="{{ $a }}">{{ $a }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Trimestre --}}
                <div>
                    <label class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4] mb-2">Trimestre:</label>
                    <select x-model="trimestre" @change="actualizarURL"
                        class="w-full rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-700 text-[#005187] dark:text-[#84b6f4] px-3 py-2.5 focus:ring-2 focus:ring-[#4d82bc] focus:border-[#4d82bc] transition-colors hci-input-focus text-sm">
                        <option value="">Todos</option>
                        <template x-for="p in periodosFiltrados" :key="p.id">
                            <option :value="p.numero" x-text="'Trimestre ' + p.numero"></option>
                        </template>
                    </select>
                </div>

                {{-- Botón limpiar --}}
                <div>
                    <button @click="limpiarFiltros" type="button"
                        class="p-3 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 hci-button-ripple hci-glow"
                        title="Limpiar filtros">
                        <img src="{{ asset('icons/filterw.svg') }}" alt="" class="w-5 h-5">
                    </button>
                </div>
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
                                    {{ $clase->sesiones->count() }} {{ $clase->sesiones->count() === 1 ? 'sesión' : 'sesiones' }}
                                </span>
                                @php
                                    $modalidades = $clase->sesiones->pluck('modalidad')->unique();
                                @endphp
                                @foreach($modalidades as $modalidad)
                                    <span class="px-2 py-0.5 rounded bg-[#4d82bc] text-white capitalize">
                                        {{ $modalidad }}
                                    </span>
                                @endforeach
                            </div>

                            {{-- Días de las sesiones --}}
                            @if($clase->sesiones->count() > 0)
                                @php
                                    $primeraSession = $clase->sesiones->first();
                                    $ultimaSesion = $clase->sesiones->last();
                                    $dias = $clase->sesiones->pluck('dia')->unique()->join(', ');
                                @endphp
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    <strong class="text-[#005187]">{{ $dias }}</strong>
                                    @if($primeraSession && $ultimaSesion)
                                        <br>
                                        <span class="text-xs">
                                            {{ $primeraSession->fecha->format('d/m/Y') }} - {{ $ultimaSesion->fecha->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </p>
                            @endif

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

                            @if(false)
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
                            @endif
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






