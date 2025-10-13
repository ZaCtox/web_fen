@section('title', 'Gestión de Novedades')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Gestión de Novedades</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Gestión de Novedades', 'url' => '#']
    ]" />

    <div class="p-6 max-w-7xl mx-auto" x-data="{
        search: '{{ request('search') }}',
        tipo: '{{ request('tipo') }}',
        estado: '{{ request('estado') }}',
        actualizarURL() {
            const params = new URLSearchParams();
            if (this.search) params.set('search', this.search);
            if (this.tipo) params.set('tipo', this.tipo);
            if (this.estado) params.set('estado', this.estado);
            window.location.href = '{{ route('novedades.index') }}' + (params.toString() ? '?' + params.toString() : '');
        },
        limpiarFiltros() {
            this.search = '';
            this.tipo = '';
            this.estado = '';
            window.location.href = '{{ route('novedades.index') }}';
        }
    }">

        <!-- Controles -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <a href="{{ route('novedades.create') }}"
                class="inline-flex items-center justify-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white px-6 py-3 rounded-lg shadow-md transition-all duration-200 font-semibold text-sm hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 hci-button-ripple hci-glow"
                aria-label="Crear nueva novedad">
                <img src="{{ asset('icons/agregar.svg') }}" alt="" class="w-5 h-5">
                Agregar Novedad
            </a>
            
            <div class="flex gap-3 items-center w-full sm:w-auto">
                <div class="relative flex-1 sm:flex-initial">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <img src="{{ asset('icons/filtro.svg') }}" alt="" class="h-5 w-5 opacity-60">
                    </div>
                    <input x-model="search" 
                           @keyup.enter="actualizarURL()"
                           type="text" 
                           role="search"
                           aria-label="Buscar novedades por título o contenido"
                           placeholder="Buscar por título o contenido"
                           class="w-full sm:w-[350px] pl-10 pr-4 py-3 rounded-lg border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition hci-input-focus">
                </div>
                
                <select x-model="tipo" 
                        @change="actualizarURL()"
                        class="px-4 py-3 pr-10 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[160px] hci-focus-ring"
                        aria-label="Filtrar por tipo de novedad">
                    <option value="">Todos los tipos</option>
                    <option value="academica">Académica</option>
                    <option value="evento">Evento</option>
                    <option value="admision">Admisión</option>
                    <option value="institucional">Institucional</option>
                    <option value="servicio">Servicio</option>
                    <option value="mantenimiento">Mantenimiento</option>
                </select>

                <select x-model="estado" 
                        @change="actualizarURL()"
                        class="px-4 py-3 pr-10 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[160px] hci-focus-ring"
                        aria-label="Filtrar por estado">
                    <option value="">Todos los estados</option>
                    <option value="activas">Activas</option>
                    <option value="expiradas">Expiradas</option>
                    <option value="urgentes">Urgentes</option>
                </select>
                
                <button type="button" 
                        @click="limpiarFiltros()"
                        class="p-3 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 hover:scale-105 hci-button-ripple hci-glow"
                        title="Limpiar búsqueda y filtros"
                        aria-label="Limpiar búsqueda y filtros">
                    <img src="{{ asset('icons/filterw.svg') }}" alt="" class="w-5 h-5">
                </button>
            </div>
        </div>

        {{-- 📊 Estadísticas corregidas --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="p-3 bg-[#f8fafc] dark:bg-gray-700 rounded-lg">
                    <div class="text-xl font-bold text-[#005187] dark:text-[#84b6f4]">{{ $estadisticas['total'] }}</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">Total</div>
                </div>
                <div class="p-3 bg-[#f8fafc] dark:bg-gray-700 rounded-lg">
                    <div class="text-xl font-bold text-green-600">{{ $estadisticas['publicas'] }}</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">Públicas</div>
                </div>
                <div class="p-3 bg-[#f8fafc] dark:bg-gray-700 rounded-lg">
                    <div class="text-xl font-bold text-red-600">{{ $estadisticas['urgentes'] }}</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">Urgentes</div>
                </div>
                <div class="p-3 bg-[#f8fafc] dark:bg-gray-700 rounded-lg">
                    <div class="text-xl font-bold text-yellow-600">{{ $estadisticas['expiradas'] }}</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">Expiradas</div>
                </div>
            </div>
        </div>

        {{-- 📋 Grid de Cards --}}
        @if($novedades->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                @foreach($novedades as $index => $novedad)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:scale-105 transform transition-all duration-300 border border-gray-200 dark:border-gray-700 flex flex-col hci-fade-in cursor-pointer"
                         style="animation-delay: {{ $index * 0.05 }}s"
                         onclick="window.location='{{ route('novedades.show', $novedad) }}'">
                        {{-- Header con icono y tipo --}}
                        <div class="p-4 text-center bg-[#4d82bc]/10 dark:bg-[#4d82bc]/20">
                            <span class="text-4xl" role="img">{{ $novedad->icono ?? '📰' }}</span>
                            <div class="mt-2 flex flex-wrap gap-1 justify-center">
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-[#4d82bc] text-white">
                                    {{ ucfirst($novedad->tipo_novedad) }}
                                </span>
                                @if($novedad->es_urgente)
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-red-500 text-white">
                                        🔴 Urgente
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Contenido --}}
                        <div class="p-4 flex-1 flex flex-col">
                            <h4 class="font-bold text-gray-800 dark:text-gray-100 mb-2 text-base line-clamp-2 min-h-[48px]">
                                {{ $novedad->titulo }}
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3 line-clamp-3 flex-1">
                                {{ Str::limit($novedad->contenido, 120) }}
                            </p>

                            {{-- Información adicional --}}
                            <div class="space-y-2 text-xs text-gray-500 dark:text-gray-400 mb-4">
                                @if($novedad->magister)
                                    <div class="flex items-center">
                                        <span class="mr-1">🎓</span>
                                        <span>{{ Str::limit($novedad->magister->nombre, 25) }}</span>
                                    </div>
                                @endif
                                
                                {{-- Estado --}}
                                <div class="flex items-center justify-center gap-2">
                                    @if($novedad->fecha_expiracion && $novedad->fecha_expiracion instanceof \Carbon\Carbon && $novedad->fecha_expiracion->isPast())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                            ⏱️
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                            ✅
                                        </span>
                                    @endif

                                    @if($novedad->visible_publico)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                            🌐
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center text-gray-400">
                                    <span class="mr-1">🕐</span>
                                    <span>{{ $novedad->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            {{-- Botones de Administración --}}
                            <div class="flex gap-2 pt-3 border-t border-gray-200 dark:border-gray-700 mt-auto" onclick="event.stopPropagation()">
                                {{-- Editar --}}
                                <a href="{{ route('novedades.edit', $novedad) }}"
                                   class="flex-1 inline-flex items-center justify-center gap-1 px-2 py-2 bg-[#84b6f4] hover:bg-[#4d82bc] text-white rounded-lg text-xs font-medium transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-1"
                                   title="Editar novedad"
                                   aria-label="Editar novedad {{ $novedad->titulo }}">
                                    <img src="{{ asset('icons/editw.svg') }}" alt="" class="w-3.5 h-3.5 flex-shrink-0">
                                </a>

                                {{-- Eliminar --}}
                                <form method="POST" 
                                      action="{{ route('novedades.destroy', $novedad) }}" 
                                      class="form-eliminar flex-1"
                                      data-confirm="¿Estás seguro de que quieres eliminar esta novedad? Esta acción no se puede deshacer.">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit"
                                                class="w-full inline-flex items-center justify-center gap-1 px-2 py-2 bg-[#e57373] hover:bg-[#d32f2f] text-white rounded-lg text-xs font-medium transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1"
                                                title="Eliminar novedad"
                                                aria-label="Eliminar novedad {{ $novedad->titulo }}">
                                            <img src="{{ asset('icons/trashw.svg') }}" alt="" class="w-3.5 h-3.5 flex-shrink-0">
                                        </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Paginación --}}
            <div class="mt-6 flex justify-center">
                {{ $novedades->links() }}
            </div>
        @else
            {{-- Estado vacío --}}
            <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="text-6xl mb-4">📰</div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No hay novedades</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Crea tu primera novedad para comenzar</p>
                <a href="{{ route('novedades.create') }}" 
                   class="inline-flex items-center bg-[#4d82bc] hover:bg-[#005187] text-white px-6 py-3 rounded-lg shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2">
                    <img src="{{ asset('icons/agregar.svg') }}" alt="Crear" class="w-5 h-5 mr-2">
                    Crear Novedad
                </a>
            </div>
        @endif
    </div>
</x-app-layout>



