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
        <div class="mb-8">
            <a href="{{ route('rooms.index') }}"
               class="hci-button hci-lift hci-focus-ring inline-flex items-center bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-lg shadow transition-all duration-200"
               title="Volver a salas">
                <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-5 h-5">
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
            <div x-show="tab === 'ficha'" class="space-y-4">
                <div class="space-y-4 p-6 bg-[#fcffff] dark:bg-gray-800 rounded-lg shadow-lg border border-[#c4dafa]">
                    <p><strong class="text-[#005187]">Ubicación:</strong> {{ $room->location }}</p>
                    <p><strong class="text-[#005187]">Capacidad:</strong> {{ $room->capacity }}</p>
                    <p><strong class="text-[#005187]">Descripción:</strong> {{ $room->description ?? 'Sin descripción' }}</p>

                    {{-- Características --}}
                    <h3 class="text-lg font-semibold text-[#005187] mt-6">Características</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                        @foreach([
                            'calefaccion' => 'Calefacción',
                            'energia_electrica' => 'Energía eléctrica',
                            'existe_aseo' => 'Aseo disponible',
                            'plumones' => 'Plumones',
                            'borrador' => 'Borrador',
                            'pizarra_limpia' => 'Pizarra limpia',
                            'computador_funcional' => 'Computador funcional',
                            'cables_computador' => 'Cables para computador',
                            'control_remoto_camara' => 'Control remoto de cámara',
                            'televisor_funcional' => 'Televisor funcional'
                        ] as $campo => $label)
                            <div class="flex items-center space-x-2 p-2 bg-[#c4dafa]/40 rounded">
                                <span class="font-semibold">{{ $label }}:</span>
                                <span class="text-lg">
                                    @if($room->$campo)
                                        <img src="https://img.icons8.com/ios-filled/50/4d82bc/checkmark.png" 
                                             alt="Sí" class="w-5 h-5 inline-block">
                                    @else
                                        <span role="img" aria-label="No disponible">❌</span>
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Clases asignadas --}}
            <div x-show="tab === 'clases'" x-cloak class="mt-6">
                <div x-data="{
                    magister: '',
                    dia: '',
                    anio: '',
                    trimestre: '',
                    clases: @js($clases),
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
                        return this.clases.filter(c => 
                            (!this.magister || c.course?.magister?.nombre === this.magister) &&
                            (!this.dia || c.dia === this.dia) &&
                            (!this.anio || c.period?.anio == this.anio) &&
                            (!this.trimestre || c.period?.numero == this.trimestre)
                        );
                    },
                    limpiar() { this.magister = ''; this.dia = ''; this.anio = ''; this.trimestre = ''; }
                }">
                    <div class="flex flex-wrap gap-4 mb-4">
                        {{-- Programa --}}
                        <div>
                            <label class="block text-sm text-[#005187]">Programa:</label>
                            <select x-model="magister"
                                    class="w-full border rounded px-3 py-2 bg-[#fcffff] text-[#005187] border-[#84b6f4] focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                                <option value="">Todos</option>
                                @foreach ($magisters as $m)
                                    <option value="{{ $m->nombre }}">{{ $m->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Día --}}
                        <div class="w-full md:w-48">
                            <label class="block text-sm text-[#005187]">Día:</label>
                            <select x-model="dia"
                                    class="w-full border rounded px-3 py-2 bg-[#fcffff] text-[#005187] border-[#84b6f4] focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                                <option value="">Todos</option>
                                <option value="Viernes">Viernes</option>
                                <option value="Sábado">Sábado</option>
                            </select>
                        </div>

                        {{-- Año --}}
                        <div class="w-full md:w-32">
                            <label class="block text-sm text-[#005187]">Año:</label>
                            <select x-model="anio"
                                    @change="trimestre=''"
                                    class="w-full border rounded px-3 py-2 bg-[#fcffff] text-[#005187] border-[#84b6f4] focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                                <option value="">Todos</option>
                                <option value="1">Año 1</option>
                                <option value="2">Año 2</option>
                            </select>
                        </div>

                        {{-- Trimestre --}}
                        <div class="w-full md:w-32">
                            <label class="block text-sm text-[#005187]">Trimestre:</label>
                            <select x-model="trimestre"
                                    class="w-full border rounded px-3 py-2 bg-[#fcffff] text-[#005187] border-[#84b6f4] focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                                <template x-for="t in trimestresFiltrados" :key="t.id">
                                    <option :value="t.numero" x-text="'Trimestre ' + romanos[t.numero]"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Botón limpiar --}}
                        <div class="self-end">
                            <button @click="limpiar"
                                    class="bg-[#84b6f4] hover:bg-[#005187] text-[#005187] px-4 py-2 rounded-lg shadow transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
                                    title="Limpiar filtros"
                                    aria-label="Limpiar filtros">
                                <img src="{{ asset('icons/filterw.svg') }}" alt="Limpiar filtros" class="w-5 h-5">
                            </button>
                        </div>
                    </div>

                    {{-- Tarjetas de clases --}}
                    <template x-if="filtradas.length > 0">
                        <div class="space-y-4">
                            <template x-for="clase in filtradas" :key="clase.id">
                                <div class="shadow-sm rounded p-4 border-l-4 bg-[#fcffff] dark:bg-[#1e293b]"
                                    :style="`border-color: ${clase.course.magister?.color ?? '#999'}`">
                                    <h3 class="font-bold text-lg dark:text-white" 
                                        x-text="clase.course.nombre"></h3>
                                    <p class="text-sm text-gray-700 dark:text-gray-200">
                                        <strong>Programa:</strong> <span x-text="clase.course.magister.nombre"></span><br>
                                        <strong>Año:</strong> <span x-text="clase.period.anio"></span><br>
                                        <strong>Trimestre:</strong> <span x-text="clase.period.nombre_completo.split(' - ')[1]"></span><br>
                                        <strong>Día:</strong> <span x-text="clase.dia"></span><br>
                                        <strong>Hora:</strong> <span x-text="clase.hora_inicio + ' - ' + clase.hora_fin"></span><br>
                                        <strong>Modalidad:</strong> <span x-text="clase.modality"></span>
                                    </p>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="filtradas.length === 0">
                        <p class="text-center text-[#4d82bc] mt-6">
                            No hay clases que coincidan con los filtros seleccionados.
                        </p>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



