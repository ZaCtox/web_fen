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

        {{-- Botón superior (WCAG 2.1 AA: 44x44px mínimo) --}}
        <div class="mb-6">
            <a href="{{ route('novedades.create') }}"
               class="inline-flex items-center justify-center w-11 h-11 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
               title="Nueva Novedad"
               aria-label="Crear nueva novedad">
                <img src="{{ asset('icons/agregar.svg') }}" alt="Nueva novedad" class="w-6 h-6">
            </a>
        </div>

        {{-- 🔍 Filtros --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                {{-- Buscar --}}
                <div>
                    <label for="search" class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4] mb-2">
                        Buscar:
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <img src="{{ asset('icons/filtro.svg') }}" alt="Buscar" class="h-4 w-4">
                        </div>
                        <input type="text" 
                               id="search"
                               x-model="search"
                               @keyup.enter="actualizarURL()"
                               placeholder="Título o contenido..."
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
                            @change="actualizarURL()"
                            class="w-full px-3 py-2.5 border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-700 text-[#005187] dark:text-white rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition">
                        <option value="">Todos los tipos</option>
                        <option value="academica">Académica</option>
                        <option value="evento">Evento</option>
                        <option value="admision">Admisión</option>
                        <option value="institucional">Institucional</option>
                        <option value="servicio">Servicio</option>
                        <option value="mantenimiento">Mantenimiento</option>
                    </select>
                </div>

                {{-- Estado --}}
                <div>
                    <label for="estado" class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4] mb-2">
                        Estado:
                    </label>
                    <select id="estado" 
                            x-model="estado"
                            @change="actualizarURL()"
                            class="w-full px-3 py-2.5 border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-700 text-[#005187] dark:text-white rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition">
                        <option value="">Todos los estados</option>
                        <option value="activas">Activas</option>
                        <option value="expiradas">Expiradas</option>
                        <option value="urgentes">Urgentes</option>
                    </select>
                </div>

                {{-- Botón Limpiar --}}
                <div class="flex items-end">
                    <button type="button"
                            @click="limpiarFiltros()" 
                            class="px-4 py-3 bg-[#84b6f4] hover:bg-[#005187] text-[#005187] rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 transform hover:scale-105"
                            title="Limpiar filtros"
                            aria-label="Limpiar filtros">
                        <img src="{{ asset('icons/filterw.svg') }}" alt="Limpiar" class="w-5 h-5">
                    </button>
                </div>
            </div>
        </div>

        {{-- 📊 Estadísticas simplificadas --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="p-3 bg-[#f8fafc] dark:bg-gray-700 rounded-lg">
                    <div class="text-xl font-bold text-[#005187] dark:text-[#84b6f4]">{{ $novedades->total() }}</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">Total</div>
                </div>
                <div class="p-3 bg-[#f8fafc] dark:bg-gray-700 rounded-lg">
                    <div class="text-xl font-bold text-green-600">{{ $novedades->where('visible_publico', true)->count() }}</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">Públicas</div>
                </div>
                <div class="p-3 bg-[#f8fafc] dark:bg-gray-700 rounded-lg">
                    <div class="text-xl font-bold text-red-600">{{ $novedades->where('es_urgente', true)->count() }}</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">Urgentes</div>
                </div>
                <div class="p-3 bg-[#f8fafc] dark:bg-gray-700 rounded-lg">
                    <div class="text-xl font-bold text-yellow-600">{{ $novedades->where('fecha_expiracion', '<', now())->count() }}</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">Expiradas</div>
                </div>
            </div>
        </div>

        {{-- 📋 Tabla simplificada --}}
        @if($novedades->count() > 0)
            <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
                <table class="min-w-full text-sm text-left text-gray-900 dark:text-gray-100">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3 font-medium">Novedad</th>
                            <th class="px-4 py-3 font-medium">Tipo</th>
                            <th class="px-4 py-3 font-medium">Estado</th>
                            <th class="px-4 py-3 font-medium">Creada</th>
                            <th class="px-4 py-3 font-medium">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($novedades as $novedad)
                            <tr class="border-t border-gray-200 dark:border-gray-600 
                                       hover:bg-[#e3f2fd] dark:hover:bg-gray-700 
                                       hover:border-l-4 hover:border-l-[#4d82bc]
                                       hover:-translate-y-0.5 hover:shadow-md
                                       transition-all duration-200 group cursor-pointer"
                                onclick="window.location='{{ route('novedades.show', $novedad) }}'">
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div class="text-xl mr-3">{{ $novedad->icono }}</div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-gray-100">
                                                {{ Str::limit($novedad->titulo, 40) }}
                                                @if($novedad->es_urgente)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 ml-2">
                                                        URGENTE
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ Str::limit($novedad->contenido, 50) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        {{ ucfirst($novedad->tipo_novedad) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($novedad->fecha_expiracion && $novedad->fecha_expiracion instanceof \Carbon\Carbon && $novedad->fecha_expiracion->isPast())
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            Expirada
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Activa
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    {{ $novedad->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex space-x-1" onclick="event.stopPropagation()">
                                        {{-- Editar --}}
                                        <a href="{{ route('novedades.edit', $novedad) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 bg-[#84B5F4] hover:bg-[#4d82bc] text-white rounded-lg transition focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-1"
                                           title="Editar novedad">
                                            <img src="{{ asset('icons/editw.svg') }}" alt="Editar" class="w-4 h-4">
                                        </a>

                                        {{-- Eliminar --}}
                                        <form method="POST" action="{{ route('novedades.destroy', $novedad) }}" 
                                              class="form-eliminar inline"
                                              data-confirm="¿Estás seguro de que quieres eliminar esta novedad? Esta acción no se puede deshacer.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-lg transition focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1"
                                                    title="Eliminar novedad">
                                                <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar" class="w-4 h-4">
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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



