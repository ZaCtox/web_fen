<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Clases Académicas</h2>
    </x-slot>

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
            limpiarFiltros() {
                this.magister = '';
                this.sala = '';
                this.dia = '';
            },
        }">
        {{-- Encabezado superior --}}
        <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
            <a href="{{ route('clases.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-sm">
                ➕ Nueva Clase
            </a>

            {{-- Exportar PDF (usa tus filtros actuales) --}}
            <form method="GET" action="{{ route('clases.exportar') }}" class="flex gap-2 flex-wrap">
                <input type="hidden" name="magister" :value="magister">
                <input type="hidden" name="sala" :value="sala">
                <input type="hidden" name="dia" :value="dia">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                    📤 Exportar PDF
                </button>
            </form>
        </div>

        {{-- Filtros dinámicos --}}
        <div class="flex flex-col sm:flex-row flex-wrap gap-4 mb-6">
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
                <label class="block text-sm text-gray-700 dark:text-gray-300">Sala:</label>
                <select x-model="sala" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:text-white">
                    <option value="">Todas</option>
                    @foreach ($rooms as $r)
                        <option value="{{ $r->name }}">{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300">Día:</label>
                <select x-model="dia" class="w-full border rounded px-5 py-2 dark:bg-gray-800 dark:text-white">
                    <option value="">Todos</option>
                    <option value="Viernes">Viernes</option>
                    <option value="Sábado">Sábado</option>
                </select>
            </div>

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
                    <div class="bg-white dark:bg-gray-800 shadow-md rounded p-4"
                        :style="`border-left: 4px solid ${safe(clase?.course?.magister?.color ?? '#6b7280')}`">
                        <h3 class="font-semibold text-lg text-gray-800 dark:text-white"
                            x-text="safe(clase?.course?.nombre, '—')"></h3>

                        <div class="text-sm text-gray-600 dark:text-gray-300 mt-1 space-y-1">
                            <p><strong>Magíster:</strong>
                                <span x-text="safe(clase?.course?.magister?.nombre, 'Desconocido')"></span>
                            </p>
                            <p><strong>Periodo:</strong>
                                <span x-text="safe(clase?.period?.nombre_completo, '---')"></span>
                            </p>
                            <p><strong>Día:</strong>
                                <span x-text="safe(clase?.dia, '—')"></span>
                            </p>
                            <p><strong>Hora:</strong>
                                <span
                                    x-text="`${safe(clase?.hora_inicio,'--:--')} - ${safe(clase?.hora_fin,'--:--')}`"></span>
                            </p>
                            <p><strong>Modalidad:</strong>
                                <span x-text="safe(clase?.modality, '—')"></span>
                            </p>
                            <template x-if="clase?.room">
                                <p><strong>Sala:</strong> <span x-text="safe(clase?.room?.name, '—')"></span></p>
                            </template>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 mt-4">
                            <a :href="`/clases/${clase.id}`"
                                class="text-xs bg-gray-100 text-gray-800 hover:bg-gray-200 px-3 py-1 rounded text-center">
                                Ver
                            </a>

                            <a :href="`/clases/${clase.id}/edit`"
                                class="text-xs bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1 rounded text-center">
                                ✏️ Editar
                            </a>

                            <form :action="`/clases/${clase.id}`" method="POST" x-data
                                @submit.prevent="if (confirm('¿Eliminar esta clase?')) $el.submit()">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-xs bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1 rounded w-full text-center">
                                    🗑️ Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </template>
            </div>
        </template>
        {{-- Sin resultados --}}
        <template x-if="filtradas.length === 0">
            <p class="text-center text-gray-500 dark:text-gray-400 mt-12">
                😢 No hay clases que coincidan con los filtros seleccionados.
            </p>
        </template>
    </div>
</x-app-layout>