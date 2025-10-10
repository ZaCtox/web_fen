{{-- Novedades Públicas FEN --}}
@section('title', 'Novedades y Actividades')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#4d82bc]">
            📰 Novedades y Actividades
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-6" x-data="{
        search: '{{ request('search') }}',
        tipo: '{{ request('tipo') }}',
        magister_id: '{{ request('magister_id') }}',
        novedades: @js($novedades->items()),
        tipos: @js($tipos),
        magisters: @js($magisters),
        get filtradas() {
            const searchLower = this.search.toLowerCase();
            return this.novedades.filter(novedad => {
                const matchSearch = !this.search || 
                    novedad.titulo.toLowerCase().includes(searchLower) ||
                    (novedad.contenido && novedad.contenido.toLowerCase().includes(searchLower));
                const matchTipo = !this.tipo || novedad.tipo_novedad === this.tipo;
                const matchMagister = !this.magister_id || 
                    (novedad.magister_id && novedad.magister_id.toString() === this.magister_id);
                return matchSearch && matchTipo && matchMagister;
            });
        },
        get hasFilters() {
            return this.search !== '' || this.tipo !== '' || this.magister_id !== '';
        },
        limpiarFiltros() {
            this.search = '';
            this.tipo = '';
            this.magister_id = '';
        }
    }">
        {{-- Filtros --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                {{-- Buscador --}}
                <div>
                    <label for="search" class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4] mb-2">
                        Buscar:
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <img src="{{ asset('icons/filtro.svg') }}" alt="Buscar" class="h-4 w-4 text-gray-400">
                        </div>
                        <input type="text" 
                               id="search" 
                               x-model="search"
                               placeholder="Buscar por título o contenido..."
                               class="w-full pl-10 pr-3 py-2.5 border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-700 text-[#005187] dark:text-white rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition">
                    </div>
                </div>

                {{-- Tipo --}}
                <div>
                    <label for="tipo" class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4] mb-2">
                        Tipo:
                    </label>
                    <select id="tipo" 
                            x-model="tipo"
                            class="w-full px-3 py-2.5 border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-700 text-[#005187] dark:text-white rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition">
                        <option value="">Todos los tipos</option>
                        <template x-for="(label, key) in tipos" :key="key">
                            <option :value="key" x-text="label"></option>
                        </template>
                    </select>
                </div>

                {{-- Programa --}}
                <div>
                    <label for="magister_id" class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4] mb-2">
                        Programa:
                    </label>
                    <select id="magister_id" 
                            x-model="magister_id"
                            class="w-full px-3 py-2.5 border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-700 text-[#005187] dark:text-white rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition">
                        <option value="">Todos los programas</option>
                        <template x-for="magister in magisters" :key="magister.id">
                            <option :value="magister.id" x-text="magister.nombre"></option>
                        </template>
                    </select>
                </div>

                {{-- Botón Limpiar --}}
                <div class="flex items-end">
                    <button type="button" 
                            @click="limpiarFiltros()"
                            class="px-4 py-2 bg-[#84b6f4] hover:bg-[#005187] text-[#005187] rounded-lg shadow text-sm transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
                            title="Limpiar filtros"
                            aria-label="Limpiar filtros">
                        <img src="{{ asset('icons/filterw.svg') }}" alt="Limpiar filtros" class="w-5 h-5">
                    </button>
                </div>
            </div>
        </div>

        {{-- Información de resultados --}}
        <div class="mb-4" x-show="filtradas.length > 0">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Mostrando <span x-text="filtradas.length"></span> novedades
                <span x-show="hasFilters">para los filtros seleccionados</span>
            </p>
        </div>

        {{-- Sin resultados --}}
        <template x-if="filtradas.length === 0 && hasFilters">
            <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <span class="text-6xl" role="img" aria-label="Búsqueda">🔍</span>
                <h3 class="text-xl text-[#005187] dark:text-[#4d82bc] font-semibold mt-4">
                    No hay novedades que coincidan con tu búsqueda
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">
                    Intenta ajustar los filtros o buscar con otros términos
                </p>
                <button type="button"
                        @click="limpiarFiltros()"
                        class="inline-flex items-center gap-2 mt-4 px-6 py-3 bg-[#4d82bc] hover:bg-[#005187] text-white font-semibold rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 transform hover:scale-105"
                        title="Ver todas las novedades">
                    <img src="{{ asset('icons/filterw.svg') }}" alt="Ver todas" class="w-5 h-5">
                    Ver Todas las Novedades
                </button>
            </div>
        </template>

        {{-- Sin novedades en la BD --}}
        <template x-if="filtradas.length === 0 && !hasFilters && novedades.length === 0">
            <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <span class="text-6xl" role="img" aria-label="Documentos">📰</span>
                <h3 class="text-xl text-[#005187] dark:text-[#4d82bc] font-semibold mt-4">
                    No hay novedades disponibles
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">
                    Las novedades se publicarán próximamente
                </p>
            </div>
        </template>

        {{-- Grid de Novedades --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" x-show="filtradas.length > 0">
            <template x-for="novedad in filtradas" :key="novedad.id">
                <div class="bg-white dark:bg-gray-700 rounded-xl shadow-lg overflow-hidden hover:scale-105 transform transition-all duration-300 border border-gray-200 dark:border-gray-600 flex flex-col">
                    {{-- Header con icono y tipo --}}
                    <div class="p-4 text-center bg-[#4d82bc]/10 dark:bg-[#4d82bc]/20">
                        <span class="text-4xl" role="img" x-text="novedad.icono || '📰'"></span>
                        <div class="mt-2">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-[#4d82bc] text-white"
                                  x-text="novedad.tipo_novedad.charAt(0).toUpperCase() + novedad.tipo_novedad.slice(1)">
                            </span>
                            <template x-if="novedad.es_urgente">
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-red-500 text-white ml-1">
                                    Urgente
                                </span>
                            </template>
                        </div>
                    </div>

                    {{-- Contenido --}}
                    <div class="p-4 flex-1 flex flex-col">
                        <h4 class="font-bold text-gray-800 dark:text-gray-100 mb-2 text-base line-clamp-2"
                            x-text="novedad.titulo">
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3 line-clamp-3 flex-1"
                           x-text="novedad.contenido.substring(0, 120) + (novedad.contenido.length > 120 ? '...' : '')">
                        </p>

                        {{-- Información adicional --}}
                        <div class="space-y-2 text-xs text-gray-500 dark:text-gray-400 mb-4">
                            <template x-if="novedad.magister">
                                <div class="flex items-center">
                                    <span class="mr-1" role="img" aria-label="Programa">🎓</span>
                                    <span x-text="novedad.magister.nombre.substring(0, 25) + (novedad.magister.nombre.length > 25 ? '...' : '')"></span>
                                </div>
                            </template>
                            <template x-if="novedad.fecha_expiracion">
                                <div class="flex items-center">
                                    <span class="mr-1" role="img" aria-label="Fecha">📅</span>
                                    <span x-text="'Hasta: ' + new Date(novedad.fecha_expiracion).toLocaleDateString('es-CL')"></span>
                                </div>
                            </template>
                            <div class="flex items-center text-gray-400">
                                <span class="mr-1" role="img" aria-label="Tiempo">🕐</span>
                                <span x-text="new Date(novedad.created_at).toLocaleDateString('es-CL')"></span>
                            </div>
                        </div>

                        {{-- Botón Ver Más - Siempre abajo --}}
                        <div class="mt-auto">
                            <a :href="`{{ url('Novedades-FEN') }}/${novedad.id}`"
                               class="block w-full text-center px-4 py-3 bg-[#4d82bc] hover:bg-[#005187] text-white font-semibold rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 transform hover:scale-105"
                               title="Ver más información">
                                Ver Más →
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Footer --}}
    <footer>
        @include('components.footer')
    </footer>
</x-app-layout>



