{{-- Detalle de Salas.blade.php --}}
@section('title', 'Detalles de la Sala')
<x-app-layout>
    <x-slot name="header">
<<<<<<< Updated upstream
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            Información de la Sala: {{ $room->name }}
        </h2>
    </x-slot>
    

    <div class="p-6 max-w-7xl mx-auto">
                <div class="mb-8">
            <a
                href="{{ route('rooms.index') }}"
                class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded"
            >
                ← Volver a listado de salas
            </a>
        </div>
        <div
            x-data="{
                tab: window.location.hash === '#clases' ? 'clases' : 'ficha',
                cambiarTab(valor) {
                    this.tab = valor;
                    history.replaceState(null, null, '#' + valor);
                }
            }"
        >
            {{-- Tabs --}}
            <div class="flex space-x-4 mb-4 border-b border-gray-300 dark:border-gray-600">
                <button
                    @click="cambiarTab('ficha')"
                    class="pb-2 font-semibold"
                    :class="tab === 'ficha' ? 'border-b-2 border-blue-500 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300'"
                >
                    🛠️ Ficha Técnica
                </button>
                <button
                    @click="cambiarTab('clases')"
                    class="pb-2 font-semibold"
                    :class="tab === 'clases' ? 'border-b-2 border-blue-500 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300'"
                >
                    📚 Clases Asignadas
                </button>
            </div>

            {{-- Ficha --}}
            <div x-show="tab === 'ficha'" class="space-y-4">
                <p><strong>Ubicación:</strong> {{ $room->location }}</p>
                <p><strong>Capacidad:</strong> {{ $room->capacity }}</p>
                <p><strong>Descripción:</strong> {{ $room->description ?? 'Sin descripción' }}</p>

<<<<<<< Updated upstream
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
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
                        <div class="flex items-center space-x-2">
                            <span class="font-semibold">{{ $label }}:</span>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" disabled {{ $room->$campo ? 'checked' : '' }}
                                    class="form-checkbox h-5 w-5 text-green-600 border-gray-300 rounded disabled:cursor-not-allowed">
                            </label>
                        </div>
                    @endforeach
=======
            <form method="GET" class="mb-4 grid sm:grid-cols-2 md:grid-cols-3 gap-3">
                <div>
                    <label for="period_id" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Filtrar por Periodo:</label>
                    <select name="period_id" id="period_id" onchange="this.form.submit()"
                        class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white w-full">
                        <option value="">Todos</option>
                        @foreach($periodos as $p)
                            <option value="{{ $p->id }}" {{ request('period_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
=======
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Información de la Sala: {{ $room->name }}
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('rooms.index') }}"
               class="inline-block bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-md shadow-md transition">
                <img src="{{ asset('icons/back.svg') }}" alt="check" class="w-5 h-5">
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
            <div class="flex space-x-4 mb-4 border-b border-[#84b6f4]">
                <button @click="cambiarTab('ficha')"
                        class="pb-2 font-semibold transition"
                        :class="tab === 'ficha' ? 'border-b-2 border-[#005187] text-[#005187]' : 'text-[#4d82bc]'">
                        Ficha Técnica
                </button>
                <button @click="cambiarTab('clases')"
                        class="pb-2 font-semibold transition"
                        :class="tab === 'clases' ? 'border-b-2 border-[#005187] text-[#005187]' : 'text-[#4d82bc]'">
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
                                        ❌
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
>>>>>>> Stashed changes
>>>>>>> Stashed changes
                </div>
            </div>

<<<<<<< Updated upstream
            {{-- Clases asignadas --}}
            <div x-show="tab === 'clases'" x-cloak class="mt-6">
                <div
                    x-data="{
                        magister: '',
                        dia: '',
                        trimestre: '',
                        clases: @js($clases),
                        get filtradas() {
                            return this.clases.filter(c =>
                                (!this.magister || c.course?.magister?.nombre === this.magister) &&
                                (!this.dia || c.dia === this.dia) &&
                                (!this.trimestre || `${c.period?.anio}-${c.period?.numero}` === this.trimestre)
                            );
                        },
                        limpiar() { this.magister = ''; this.dia = ''; this.trimestre = ''; }
                    }"
                >
                    <div class="flex flex-wrap gap-4 mb-4">
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300">Programa:</label>
                            <select x-model="magister" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:text-white">
=======
<<<<<<< Updated upstream
                <div>
                    <label for="magister_id" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Filtrar por Magíster:</label>
                    <select name="magister_id" id="magister_id" onchange="this.form.submit()"
                        class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white w-full">
                        <option value="">Todos</option>
                        @foreach(\App\Models\Magister::orderBy('nombre')->get() as $m)
                            <option value="{{ $m->id }}" {{ request('magister_id') == $m->id ? 'selected' : '' }}>
                                {{ $m->nombre }}
                            </option>
                        @endforeach
                    </select>
=======
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
                            // Año 1: trimestres 1,2,3 ; Año 2: 4,5,6
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
>>>>>>> Stashed changes
                                <option value="">Todos</option>
                                @foreach ($magisters as $m)
                                    <option value="{{ $m->nombre }}">{{ $m->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

<<<<<<< Updated upstream
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300">Día:</label>
                            <select x-model="dia" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:text-white">
=======
                        {{-- Día --}}
                        <div class="w-full md:w-48">
                            <label class="block text-sm text-[#005187]">Día:</label>
                            <select x-model="dia"
                                    class="w-full border rounded px-3 py-2 bg-[#fcffff] text-[#005187] border-[#84b6f4] focus:ring-[#4d82bc] focus:border-[#4d82bc]">
>>>>>>> Stashed changes
                                <option value="">Todos</option>
                                <option value="Viernes">Viernes</option>
                                <option value="Sábado">Sábado</option>
                            </select>
                        </div>

<<<<<<< Updated upstream
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300">Trimestre:</label>
                            <select x-model="trimestre" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:text-white">
                                <option value="">Todos</option>
                                @foreach ($trimestres as $p)
                                    <option value="{{ $p->anio }}-{{ $p->numero }}">
                                        Año {{ $p->anio }} - Trimestre {{ ['I','II','III','IV','V','VI'][$p->numero - 1] ?? $p->numero }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="self-end">
                            <button
                                @click="limpiar"
                                class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:text-white px-4 py-2 rounded"
                            >
                                Limpiar filtros
=======
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
                                <option value="">Todos</option>
                                <template x-for="t in trimestresFiltrados" :key="t.id">
                                    <option :value="t.numero" x-text="'Trimestre ' + romanos[t.numero]"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Botón limpiar --}}
                        <div class="self-end">
                            <button @click="limpiar"
                                    class="bg-[#84b6f4] hover:bg-[#4d82bc] text-white px-4 py-2 rounded-md shadow transition transform hover:scale-105">
                                🧹
>>>>>>> Stashed changes
                            </button>
                        </div>
                    </div>

<<<<<<< Updated upstream
                    <template x-if="filtradas.length > 0">
                        <div class="space-y-4">
                            <template x-for="clase in filtradas" :key="clase.id">
                                <div
                                    class="bg-white dark:bg-gray-800 shadow-sm rounded p-4 border-l-4"
                                    :class="{
                                        'border-blue-500': clase.course.magister?.nombre === 'Economía',
                                        'border-red-500': clase.course.magister?.nombre === 'Dirección y Planificación Tributaria',
                                        'border-green-500': clase.course.magister?.nombre === 'Gestión de Sistemas de Salud',
                                        'border-orange-500': clase.course.magister?.nombre === 'Gestión y Políticas Públicas',
                                    }"
                                >
                                    <h3 class="font-bold text-lg text-gray-800 dark:text-white" x-text="clase.course.nombre"></h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        <strong>Magíster:</strong> <span x-text="clase.course.magister.nombre"></span><br>
                                        <strong>Periodo:</strong> <span x-text="`Año ${clase.period.anio} - Trimestre ${clase.period.numero}`"></span><br>
=======
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
>>>>>>> Stashed changes
                                        <strong>Día:</strong> <span x-text="clase.dia"></span><br>
                                        <strong>Hora:</strong> <span x-text="clase.hora_inicio + ' - ' + clase.hora_fin"></span><br>
                                        <strong>Modalidad:</strong> <span x-text="clase.modality"></span>
                                    </p>
                                </div>
                            </template>
                        </div>
                    </template>
<<<<<<< Updated upstream

                    <template x-if="filtradas.length === 0">
                        <p class="text-center text-gray-500 dark:text-gray-400 mt-6">
                            No hay clases que coincidan con los filtros seleccionados.
                        </p>
                    </template>
=======
                    <template x-if="filtradas.length === 0">
                        <p class="text-center text-[#4d82bc] mt-6">
                            No hay clases que coincidan con los filtros seleccionados.
                        </p>
                    </template>
>>>>>>> Stashed changes
>>>>>>> Stashed changes
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
