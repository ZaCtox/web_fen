<x-app-layout>
    <x-slot name="header">
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
                </div>
            </div>

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
                            <label class="block text-sm text-gray-700 dark:text-gray-300">Magíster:</label>
                            <select x-model="magister" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:text-white">
                                <option value="">Todos</option>
                                @foreach ($magisters as $m)
                                    <option value="{{ $m->nombre }}">{{ $m->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300">Día:</label>
                            <select x-model="dia" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:text-white">
                                <option value="">Todos</option>
                                <option value="Viernes">Viernes</option>
                                <option value="Sábado">Sábado</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300">Trimestre:</label>
                            <select x-model="trimestre" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:text-white">
                                <option value="">Todos</option>
                                @foreach ($trimestres as $p)
                                    <option value="{{ $p->anio }}-{{ $p->numero }}">
                                        Año {{ $p->anio }} - Trimestre {{ ['I','II','III'][$p->numero - 1] ?? $p->numero }}
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
                            </button>
                        </div>
                    </div>

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
                                        <strong>Día:</strong> <span x-text="clase.dia"></span><br>
                                        <strong>Hora:</strong> <span x-text="clase.hora_inicio + ' - ' + clase.hora_fin"></span><br>
                                        <strong>Modalidad:</strong> <span x-text="clase.modality"></span>
                                    </p>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="filtradas.length === 0">
                        <p class="text-center text-gray-500 dark:text-gray-400 mt-6">
                            No hay clases que coincidan con los filtros seleccionados.
                        </p>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
