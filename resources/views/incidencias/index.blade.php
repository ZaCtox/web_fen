{{-- Inicio de Incidencias.blade.php --}}
@section('title', 'Incidencias')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Bitácora de Incidencias
        </h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Incidencias', 'url' => '#']
    ]" />

    <div class="p-6 max-w-7xl mx-auto" x-data="{
            busqueda: '{{ request('busqueda') }}',
            estado: '{{ request('estado') }}',
            sala: '{{ request('room_id') }}',
            anio: '{{ request('anio') }}',
            trimestre: '{{ request('trimestre') }}',
            historico: {{ request()->filled('historico') ? 'true' : 'false' }},
            periodos: @js($periodos),
            get periodosFiltrados() {
                if (!this.anio) return this.periodos;
                return this.periodos.filter(p => {
                    const year = new Date(p.fecha_inicio).getFullYear();
                    return year == this.anio;
                });
            },
            actualizarURL() {
                // Si se activa histórico, limpiar trimestre
                if (this.historico) {
                    this.trimestre = '';
                }
                
                // Actualizar opciones del select de año
                this.toggleAnioOptions();
                
                const params = new URLSearchParams(window.location.search);
                this.busqueda ? params.set('busqueda', this.busqueda) : params.delete('busqueda');
                this.estado ? params.set('estado', this.estado) : params.delete('estado');
                this.sala ? params.set('room_id', this.sala) : params.delete('room_id');
                this.anio ? params.set('anio', this.anio) : params.delete('anio');
                this.trimestre ? params.set('trimestre', this.trimestre) : params.delete('trimestre');
                this.historico ? params.set('historico', '1') : params.delete('historico');
                window.location.search = params.toString();
            },
            limpiarFiltros() {
                this.busqueda = '';
                this.estado = '';
                this.sala = '';
                this.anio = '';
                this.trimestre = '';
                this.historico = false;
                window.location.href = '{{ route('incidencias.index') }}';
            },
            toggleAnioOptions() {
                const anioSelect = document.getElementById('anio-select');
                const trimestreDiv = document.getElementById('trimestre-div');
                
                if (!anioSelect) return;
                
                const aniosNormales = anioSelect.querySelectorAll('.anio-normal');
                const aniosHistoricos = anioSelect.querySelectorAll('.anio-historico');
                
                if (this.historico) {
                    // Mostrar años históricos, ocultar normales y trimestre
                    aniosNormales.forEach(option => option.style.display = 'none');
                    aniosHistoricos.forEach(option => option.style.display = 'block');
                    if (trimestreDiv) trimestreDiv.style.display = 'none';
                } else {
                    // Mostrar años normales, ocultar históricos y mostrar trimestre
                    aniosNormales.forEach(option => option.style.display = 'block');
                    aniosHistoricos.forEach(option => option.style.display = 'none');
                    if (trimestreDiv) trimestreDiv.style.display = 'block';
                }
            }
        }" x-init="toggleAnioOptions()">

        <!-- Botones Superiores -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            {{-- Botones de acción (Izquierda) --}}
            <div class="flex gap-3">
                <a href="{{ route('incidencias.create') }}"
                    class="inline-flex items-center justify-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white px-6 py-3 rounded-lg shadow-md transition-all duration-200 font-semibold text-sm hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 hci-button-ripple hci-glow"
                    aria-label="Agregar nueva incidencia">
                    <img src="{{ asset('icons/agregar.svg') }}" alt="" class="w-5 h-5">
                    Agregar Incidencia
                </a>

                <a href="{{ route('incidencias.estadisticas') }}"
                    class="inline-flex items-center justify-center gap-2 bg-[#84b6f4] hover:bg-[#4d82bc] text-white px-6 py-3 rounded-lg shadow-md transition-all duration-200 font-semibold text-sm hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#84b6f4] focus:ring-offset-2 hci-button-ripple hci-glow"
                    aria-label="Ver estadísticas">
                    <img src="{{ asset('icons/estadistica.svg') }}" alt="" class="w-5 h-5">
                    Estadísticas
                </a>
            </div>
            
            {{-- Botón Descargar PDF (Derecha) --}}
            <div>
                <form action="{{ route('incidencias.exportar.pdf') }}" method="GET" class="inline">
                    <input type="hidden" name="busqueda" :value="busqueda">
                    <input type="hidden" name="estado" :value="estado">
                    <input type="hidden" name="room_id" :value="sala">
                    <input type="hidden" name="anio" :value="anio">
                    <input type="hidden" name="trimestre" :value="trimestre">
                    <input type="hidden" name="historico" x-bind:value="historico ? 1 : ''">

                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg shadow-md transition-all duration-200 font-semibold text-sm hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 hci-button-ripple hci-glow"
                        title="Descargar listado en PDF">
                        <img src="{{ asset('icons/download.svg') }}" alt="" class="w-5 h-5">
                    </button>
                </form>
            </div>
        </div>

        {{-- Filtros Abajo --}}
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-end">
                {{-- Búsqueda --}}
                <div class="w-full lg:flex-1 lg:max-w-md">
                    <label for="busqueda" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Buscar
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <img src="{{ asset('icons/filtro.svg') }}" alt="" class="h-5 w-5 opacity-60">
                        </div>
                        <input x-model="busqueda" 
                               @keyup.enter="actualizarURL()"
                               type="text" 
                               id="busqueda"
                               role="search"
                               aria-label="Buscar incidencias por título o descripción"
                               placeholder="Buscar por título o descripción..."
                               class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition">
                    </div>
                </div>

                {{-- Resto de filtros --}}
                <div class="flex flex-wrap gap-3 items-end w-full lg:w-auto">
                    {{-- Estado --}}
                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Estado
                        </label>
                        <select x-model="estado" 
                                @change="actualizarURL()"
                                id="estado"
                                class="px-4 py-2.5 pr-10 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[140px]"
                                aria-label="Filtrar por estado">
                            <option value="">Todos</option>
                            <option value="pendiente">Pendientes</option>
                            <option value="en_revision">En Revisión</option>
                            <option value="resuelta">Resueltas</option>
                            <option value="no_resuelta">No Resueltas</option>
                        </select>
                    </div>

                    {{-- Sala --}}
                    <div>
                        <label for="sala" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Sala
                        </label>
                        <select x-model="sala" 
                                @change="actualizarURL()"
                                id="sala"
                                class="px-4 py-2.5 pr-10 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[140px]"
                                aria-label="Filtrar por sala">
                            <option value="">Todas</option>
                            @foreach ($salas as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Año --}}
                    <div>
                        <label for="anio-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Año
                        </label>
                        <select x-model="anio" 
                                @change="actualizarURL()" 
                                id="anio-select"
                                class="px-4 py-2.5 pr-10 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[120px]"
                                aria-label="Filtrar por año">
                            <option value="">Todos</option>
                            @foreach ($anios as $a)
                                <option value="{{ $a }}" class="anio-normal">{{ $a }}</option>
                            @endforeach
                            @foreach ($aniosHistoricos as $a)
                                <option value="{{ $a }}" class="anio-historico" style="display: none;">{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Trimestre --}}
                    <div id="trimestre-div">
                        <label for="trimestre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Trimestre
                        </label>
                        <select x-model="trimestre" 
                                @change="actualizarURL()"
                                id="trimestre"
                                class="px-4 py-2.5 pr-10 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[130px]"
                                aria-label="Filtrar por trimestre">
                            <option value="">Todos</option>
                            <template x-for="p in periodosFiltrados" :key="p.id">
                                <option :value="p.numero" x-text="'Trimestre ' + p.numero" :selected="trimestre == p.numero">
                                </option>
                            </template>
                        </select>
                    </div>

                    {{-- Histórico --}}
                    <div class="flex items-end">
                        <label class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <input type="checkbox" 
                                   x-model="historico" 
                                   @change="actualizarURL()" 
                                   id="historico"
                                   class="rounded border-gray-300 dark:border-gray-600 text-[#4d82bc] focus:ring-[#4d82bc]">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Histórico</span>
                        </label>
                    </div>
                    
                    {{-- Limpiar --}}
                    <button type="button" 
                            @click="limpiarFiltros()"
                            class="p-3 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 hover:scale-105 hci-button-ripple hci-glow"
                            title="Limpiar búsqueda y filtros"
                            aria-label="Limpiar búsqueda y filtros">
                        <img src="{{ asset('icons/filterw.svg') }}" alt="" class="w-5 h-5">
                    </button>
                </div>
            </div>
        </div>

        <template x-if="historico">
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 px-4 py-3 rounded mb-4 text-sm">
                Mostrando solo incidencias fuera de los períodos académicos actuales.
            </div>
        </template>

        {{-- 📋 Tabla --}}
        <div class="overflow-x-auto bg-[#fcffff] dark:bg-[#1e293b] shadow rounded-lg">
            <table class="min-w-full text-sm text-left text-[#005187] dark:text-[#c4dafa]">
                <thead class="bg-[#c4dafa] text-[#005187]">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Título</th>
                        <th class="px-4 py-2">Sala</th>
                        <th class="px-4 py-2">Estado</th>
                        <th class="px-4 py-2">Fecha</th>
                        <th class="px-4 py-2">Ticket Jira</th>
                        <th class="px-4 py-2">Resuelta</th>
                        <th class="px-4 py-2">Ver</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($incidencias as $incidencia)
                        <tr class="border-t border-gray-200 dark:border-gray-600 
                                   hover:bg-[#e3f2fd] dark:hover:bg-blue-900/20 
                                   hover:border-l-4 hover:border-l-[#4d82bc]
                                   hover:-translate-y-0.5 hover:shadow-md
                                   transition-all duration-200 group cursor-pointer
                                   {{ $incidencia->estado === 'no_resuelta' ? 'von-restorff-critical' : '' }}
                                   {{ $incidencia->estado === 'pendiente' ? 'von-restorff-warning' : '' }}"
                            onclick="window.location='{{ route('incidencias.show', $incidencia) }}'">
                            <td class="px-4 py-2 group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200">{{ $incidencia->id }}</td>
                            <td class="px-4 py-2 group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200 font-medium">{{ $incidencia->titulo }}</td>
                            <td class="px-4 py-2 group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200">{{ $incidencia->room->name ?? 'Sin sala' }}</td>
                            <td class="px-4 py-2">
                                @if ($incidencia->estado === 'resuelta')
                                    <img src="{{ asset('icons/check.svg') }}" alt="Resuelta" 
                                         class="w-6 h-6 inline group-hover:scale-110 transition-transform duration-200">
                                @elseif ($incidencia->estado === 'pendiente')
                                    <img src="{{ asset('icons/clock.svg') }}" alt="Pendiente" 
                                         class="w-5 h-5 inline group-hover:scale-110 transition-transform duration-200">
                                @elseif ($incidencia->estado === 'en_revision')
                                    <img src="{{ asset('icons/revision.svg') }}" alt="Revision" 
                                         class="w-5 h-5 inline group-hover:scale-110 transition-transform duration-200">
                                @elseif ($incidencia->estado === 'no_resuelta')
                                    <img src="{{ asset('icons/no_resuelta.svg') }}" alt="No Resuelta" 
                                         class="w-6 h-6 inline group-hover:scale-110 transition-transform duration-200">
                                @else
                                    <span class="group-hover:scale-110 transition-transform duration-200 inline-block">ℹ️</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200">{{ $incidencia->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2 group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200">{{ $incidencia->nro_ticket ?? '—' }}</td>
                            <td class="px-4 py-2 group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200">
                                {{ $incidencia->resuelta_en ? $incidencia->resuelta_en->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-4 py-2 space-x-2" onclick="event.stopPropagation()">
                                {{-- Botón Ver --}}
                                <x-action-button 
                                    variant="view" 
                                    type="link" 
                                    :href="route('incidencias.show', $incidencia)" 
                                    icon="verw.svg"
                                    tooltip="Ver incidencia" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8">
                                <x-empty-state
                                    type="no-results"
                                    icon="🔍"
                                    title="No se encontraron incidencias"
                                    message="Intenta ajustar los filtros o elimina alguno para ver más resultados."
                                    secondaryActionText="Ver Todas"
                                    secondaryActionUrl="{{ route('incidencias.index') }}"
                                    secondaryActionIcon="🔄"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $incidencias->links() }}
        </div>
    </div>
</x-app-layout>


