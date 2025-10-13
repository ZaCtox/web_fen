{{-- Detalle de Salas.blade.php --}}
@section('title', 'Detalles de la Sala')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Información de la Sala: {{ $room->name }}
        </h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Salas', 'url' => route('rooms.index')],
        ['label' => $room->name, 'url' => '#']
    ]" />

    <div class="p-6 max-w-7xl mx-auto">
        {{-- Sticky Header con Botón Volver --}}
        <div class="sticky top-0 z-10 bg-white dark:bg-gray-900 py-4 mb-6 -mx-6 px-6 border-b border-gray-200 dark:border-gray-700 shadow-sm">
            <a href="{{ route('rooms.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-sm font-medium"
               aria-label="Volver a salas">
                <img src="{{ asset('icons/back.svg') }}" alt="" class="w-5 h-5">
            </a>
        </div>

        <div x-data="{
            tab: window.location.hash === '#clases' ? 'clases' : 'ficha',
            cambiarTab(valor) {
                this.tab = valor;
                history.replaceState(null, null, '#' + valor);
            }
        }">
            {{-- Tabs --}}
            <div class="flex space-x-4 mb-4 border-b border-[#84b6f4]" role="tablist">
                <button @click="cambiarTab('ficha')"
                        role="tab"
                        :aria-selected="tab === 'ficha'"
                        class="pb-2 font-semibold transition focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 rounded-t"
                        :class="tab === 'ficha' ? 'border-b-2 border-[#005187] text-[#005187]' : 'text-[#4d82bc]'"
                        title="Ver ficha técnica">
                        Ficha Técnica
                </button>
                <button @click="cambiarTab('clases')"
                        role="tab"
                        :aria-selected="tab === 'clases'"
                        class="pb-2 font-semibold transition focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 rounded-t"
                        :class="tab === 'clases' ? 'border-b-2 border-[#005187] text-[#005187]' : 'text-[#4d82bc]'"
                        title="Ver clases asignadas">
                        Clases Asignadas
                </button>
            </div>

            {{-- Ficha --}}
            <div x-show="tab === 'ficha'" class="space-y-6">
                {{-- Información General --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4] mb-4">Información General</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-[#4d82bc]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-xl">📍</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Ubicación</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $room->location }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-[#4d82bc]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-xl">👥</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Capacidad</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $room->capacity }} personas</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-[#4d82bc]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-xl">📝</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Descripción</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $room->description ?? 'Sin descripción' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Características --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4] mb-4">Características y Equipamiento</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach([
                            'calefaccion' => ['label' => 'Calefacción', 'icon' => '🔥'],
                            'energia_electrica' => ['label' => 'Energía eléctrica', 'icon' => '⚡'],
                            'existe_aseo' => ['label' => 'Aseo disponible', 'icon' => '🧹'],
                            'plumones' => ['label' => 'Plumones', 'icon' => '🖊️'],
                            'borrador' => ['label' => 'Borrador', 'icon' => '🧽'],
                            'pizarra_limpia' => ['label' => 'Pizarra limpia', 'icon' => '📋'],
                            'computador_funcional' => ['label' => 'Computador funcional', 'icon' => '💻'],
                            'cables_computador' => ['label' => 'Cables para computador', 'icon' => '🔌'],
                            'control_remoto_camara' => ['label' => 'Control remoto de cámara', 'icon' => '📹'],
                            'televisor_funcional' => ['label' => 'Televisor funcional', 'icon' => '📺']
                        ] as $campo => $data)
                            <div class="flex items-center gap-3 p-3 rounded-lg {{ $room->$campo ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : 'bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600' }}">
                                <span class="text-2xl">{{ $data['icon'] }}</span>
                                <div class="flex-1">
                                    <p class="text-sm font-medium {{ $room->$campo ? 'text-green-900 dark:text-green-100' : 'text-gray-600 dark:text-gray-400' }}">
                                        {{ $data['label'] }}
                                    </p>
                                </div>
                                <span class="text-lg">
                                    @if($room->$campo)
                                        <span class="text-green-600">✓</span>
                                    @else
                                        <span class="text-gray-400">✗</span>
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Sesiones asignadas --}}
            <div x-show="tab === 'clases'" x-cloak class="mt-6">
                <div x-data="{
                    magister: '',
                    dia: '',
                    anio: '',
                    trimestre: '',
                    sesiones: @js($sesiones),
                    trimestres: @js($trimestres),
                    romanos: {1:'I',2:'II',3:'III',4:'IV',5:'V',6:'VI'},
                    get trimestresFiltrados() {
                        if(!this.anio) return this.trimestres;
                        return this.trimestres.filter(t => {
                            if(this.anio == 1) return t.numero <=3;
                            if(this.anio == 2) return t.numero >=4 && t.numero <=6;
                            return true;
                        });
                    },
                    get filtradas() {
                        return this.sesiones.filter(s => 
                            (!this.magister || s.clase?.course?.magister?.nombre === this.magister) &&
                            (!this.dia || s.dia === this.dia) &&
                            (!this.anio || s.clase?.period?.anio == this.anio) &&
                            (!this.trimestre || s.clase?.period?.numero == this.trimestre)
                        );
                    },
                    limpiar() { this.magister = ''; this.dia = ''; this.anio = ''; this.trimestre = ''; }
                }">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
                        <div class="flex flex-wrap gap-3 items-end">
                            {{-- Programa --}}
                            <div>
                                <label class="block text-sm font-semibold text-[#005187] dark:text-gray-300 mb-2">Programa</label>
                                <select x-model="magister"
                                        class="px-4 py-2.5 pr-10 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 text-[#005187] dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[200px] hci-focus-ring">
                                    <option value="">Todos</option>
                                    @foreach ($magisters as $m)
                                        <option value="{{ $m->nombre }}">{{ $m->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Día --}}
                            <div>
                                <label class="block text-sm font-semibold text-[#005187] dark:text-gray-300 mb-2">Día</label>
                                <select x-model="dia"
                                        class="px-4 py-2.5 pr-10 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 text-[#005187] dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[140px] hci-focus-ring">
                                    <option value="">Todos</option>
                                    @foreach ($dias as $d)
                                        <option value="{{ $d }}">{{ $d }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Año --}}
                            <div>
                                <label class="block text-sm font-semibold text-[#005187] dark:text-gray-300 mb-2">Año</label>
                                <select x-model="anio"
                                        @change="trimestre=''"
                                        class="px-4 py-2.5 pr-10 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 text-[#005187] dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[120px] hci-focus-ring">
                                    <option value="">Todos</option>
                                    <option value="1">Año 1</option>
                                    <option value="2">Año 2</option>
                                </select>
                            </div>

                            {{-- Trimestre --}}
                            <div>
                                <label class="block text-sm font-semibold text-[#005187] dark:text-gray-300 mb-2">Trimestre</label>
                                <select x-model="trimestre"
                                        class="px-4 py-2.5 pr-10 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 text-[#005187] dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[150px] hci-focus-ring">
                                    <option value="">Todos</option>
                                    <template x-for="t in trimestresFiltrados" :key="t.id">
                                        <option :value="t.numero" x-text="'Trimestre ' + romanos[t.numero]"></option>
                                    </template>
                                </select>
                            </div>

                            {{-- Botón limpiar --}}
                            <button @click="limpiar"
                                    class="p-3 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 hover:scale-105 hci-button-ripple hci-glow"
                                    title="Limpiar filtros"
                                    aria-label="Limpiar filtros">
                                <img src="{{ asset('icons/filterw.svg') }}" alt="" class="w-5 h-5">
                            </button>
                        </div>
                    </div>

                    {{-- Tarjetas de sesiones --}}
                    <template x-if="filtradas.length > 0">
                        <div class="space-y-4">
                            <template x-for="sesion in filtradas" :key="sesion.id">
                                <a :href="`/clases/${sesion.clase?.id}`"
                                   class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-lg hover:scale-[1.02] transition-all duration-200 cursor-pointer group"
                                   :style="`border-left: 4px solid ${sesion.clase?.course?.magister?.color ?? '#4d82bc'}`"
                                   :title="`Ver detalles de ${sesion.clase?.course?.nombre || 'la clase'}`">
                                    <div class="flex items-start justify-between mb-3">
                                        <h3 class="font-bold text-lg text-gray-900 dark:text-white group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200" 
                                            x-text="sesion.clase?.course?.nombre || 'Sin nombre'"></h3>
                                        <span class="px-2 py-1 text-xs rounded-lg bg-[#4d82bc]/10 text-[#005187] dark:text-[#84b6f4] font-semibold"
                                              x-text="'Sesión ' + (sesion.numero_sesion || '-')"></span>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700 dark:text-gray-300">
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg">📚</span>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Programa</p>
                                                <p class="font-medium" x-text="sesion.clase?.course?.magister?.nombre || 'Sin programa'"></p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg">📅</span>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Fecha</p>
                                                <p class="font-medium" x-text="new Date(sesion.fecha).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' })"></p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg">📆</span>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Día</p>
                                                <p class="font-medium" x-text="sesion.dia || '-'"></p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg">⏰</span>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Horario</p>
                                                <p class="font-medium" x-text="(sesion.hora_inicio ? sesion.hora_inicio.substring(0, 5) : '-') + ' - ' + (sesion.hora_fin ? sesion.hora_fin.substring(0, 5) : '-')"></p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg">🎓</span>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Modalidad</p>
                                                <p class="font-medium" x-text="sesion.modalidad || '-'"></p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg">📊</span>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Año</p>
                                                <p class="font-medium" x-text="sesion.clase?.period?.anio || '-'"></p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg">🔢</span>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Trimestre</p>
                                                <p class="font-medium" x-text="sesion.clase?.period ? romanos[sesion.clase.period.numero] : '-'"></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </template>

                    <template x-if="filtradas.length === 0">
                        <div class="text-center py-12">
                            <span class="text-6xl mb-4 block">📅</span>
                            <p class="text-lg text-gray-600 dark:text-gray-400">
                                No hay sesiones que coincidan con los filtros seleccionados.
                            </p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



