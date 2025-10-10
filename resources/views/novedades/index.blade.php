@section('title', 'Gestión de Novedades')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">📰 Gestión de Novedades</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Gestión de Novedades', 'url' => '#']
    ]" />

    {{-- Botones superiores --}}
    <div class="p-5 max-w-7xl mx-auto flex gap-3">
        <a href="{{ route('novedades.create') }}"
            class="hci-button hci-lift hci-focus-ring inline-flex items-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-lg shadow transition-all duration-200"
            title="Crear nueva novedad">
            <img src="{{ asset('icons/agregar.svg') }}" alt="Agregar" class="w-5 h-5">
        </a>
    </div>

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
                            class="px-3 py-2 bg-[#84b6f4] hover:bg-[#005187] text-[#005187] rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 transform hover:scale-105"
                            title="Limpiar filtros"
                            aria-label="Limpiar filtros">
                        <img src="{{ asset('icons/filterw.svg') }}" alt="Limpiar" class="w-5 h-5">
                    </button>
                </div>
            </div>
        </div>

        {{-- 📊 Estadísticas --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-r from-[#4d82bc] to-[#005187] text-white p-4 rounded-lg shadow-lg">
                <div class="text-2xl font-bold">{{ $novedades->total() }}</div>
                <div class="text-sm opacity-90">Total Novedades</div>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-4 rounded-lg shadow-lg">
                <div class="text-2xl font-bold">{{ $novedades->where('visible_publico', true)->count() }}</div>
                <div class="text-sm opacity-90">Públicas</div>
            </div>
            <div class="bg-gradient-to-r from-red-500 to-red-600 text-white p-4 rounded-lg shadow-lg">
                <div class="text-2xl font-bold">{{ $novedades->where('es_urgente', true)->count() }}</div>
                <div class="text-sm opacity-90">Urgentes</div>
            </div>
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white p-4 rounded-lg shadow-lg">
                <div class="text-2xl font-bold">{{ $novedades->where('fecha_expiracion', '<', now())->count() }}</div>
                <div class="text-sm opacity-90">Expiradas</div>
            </div>
        </div>

        {{-- 📋 Tabla --}}
        @if($novedades->count() > 0)
            <div class="overflow-x-auto bg-[#fcffff] dark:bg-[#1e293b] shadow rounded-lg">
                <table class="min-w-full text-sm text-left text-[#005187] dark:text-[#c4dafa]">
                    <thead class="bg-[#c4dafa] text-[#005187]">
                        <tr>
                            <th class="px-4 py-2">Novedad</th>
                            <th class="px-4 py-2">Tipo</th>
                            <th class="px-4 py-2">Visibilidad</th>
                            <th class="px-4 py-2">Estado</th>
                            <th class="px-4 py-2">Creada</th>
                            <th class="px-4 py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($novedades as $novedad)
                            <tr class="border-t border-gray-200 dark:border-gray-600 
                                       hover:bg-[#e3f2fd] dark:hover:bg-blue-900/20 
                                       hover:border-l-4 hover:border-l-[#4d82bc]
                                       hover:-translate-y-0.5 hover:shadow-md
                                       transition-all duration-200 group cursor-pointer
                                       {{ $novedad->es_urgente ? 'von-restorff-critical' : '' }}
                                       {{ $novedad->fecha_expiracion && $novedad->fecha_expiracion instanceof \Carbon\Carbon && $novedad->fecha_expiracion->isPast() ? 'von-restorff-warning' : '' }}">
                                <td class="px-4 py-2 group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200">
                                    <div class="flex items-center">
                                        <div class="text-2xl mr-3">{{ $novedad->icono }}</div>
                                        <div>
                                            <div class="font-medium">
                                                {{ Str::limit($novedad->titulo, 50) }}
                                                @if($novedad->es_urgente)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-500 text-white ml-2">
                                                        URGENTE
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-xs opacity-75">
                                                {{ Str::limit($novedad->contenido, 60) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2 group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-[#4d82bc] text-white">
                                        {{ ucfirst($novedad->tipo_novedad) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200">
                                    @if($novedad->visible_publico)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-500 text-white">
                                            Público
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-500 text-white">
                                            Privado
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200">
                                    @if($novedad->fecha_expiracion && $novedad->fecha_expiracion instanceof \Carbon\Carbon && $novedad->fecha_expiracion->isPast())
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-500 text-white">
                                            Expirada
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-500 text-white">
                                            Activa
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200">
                                    {{ $novedad->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex space-x-1">
                                        {{-- Ver --}}
                                        <a href="{{ route('novedades.show', $novedad) }}" 
                                           class="inline-flex items-center justify-center w-12 px-4 py-2.5 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-1"
                                           title="Ver novedad">
                                            <img src="{{ asset('icons/verw.svg') }}" alt="Ver" class="w-6 h-6">
                                        </a>

                                        {{-- Editar --}}
                                        <a href="{{ route('novedades.edit', $novedad) }}" 
                                           class="inline-flex items-center justify-center w-12 px-4 py-2.5 bg-[#84b6f4] hover:bg-[#4d82bc] text-white rounded-lg text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-[#84b6f4] focus:ring-offset-1"
                                           title="Editar novedad">
                                            <img src="{{ asset('icons/editw.svg') }}" alt="Editar" class="w-6 h-6">
                                        </a>

                                        {{-- Duplicar --}}
                                        <form method="POST" action="{{ route('novedades.duplicate', $novedad) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center w-12 px-4 py-2.5 bg-[#ffa726] hover:bg-[#ff9800] text-white rounded-lg text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-1"
                                                    title="Duplicar novedad">
                                                <img src="{{ asset('icons/duplicate.svg') }}" alt="Duplicar" class="w-6 h-6">
                                            </button>
                                        </form>

                                        {{-- Eliminar --}}
                                        <form method="POST" action="{{ route('novedades.destroy', $novedad) }}" 
                                              class="form-eliminar inline"
                                              data-confirm="¿Estás seguro de que quieres eliminar esta novedad? Esta acción no se puede deshacer.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center w-12 px-4 py-2.5 bg-[#e57373] hover:bg-[#f28b82] text-white rounded-lg text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1"
                                                    title="Eliminar novedad">
                                                <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar" class="w-6 h-6">
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
            <div class="text-center py-12 bg-[#fcffff] dark:bg-[#1e293b] rounded-lg shadow">
                <div class="text-6xl mb-4">📰</div>
                <h3 class="text-lg font-medium text-[#005187] dark:text-[#84b6f4] mb-2">No hay novedades</h3>
                <p class="text-[#4d82bc] dark:text-[#84b6f4] mb-6">Crea tu primera novedad para comenzar</p>
                <a href="{{ route('novedades.create') }}" 
                   class="hci-button hci-lift hci-focus-ring inline-flex items-center bg-[#4d82bc] hover:bg-[#005187] text-white px-6 py-3 rounded-lg shadow-lg transition-all duration-200">
                    <img src="{{ asset('icons/agregar.svg') }}" alt="Crear" class="w-5 h-5 mr-2">
                    Crear Novedad
                </a>
            </div>
        @endif
    </div>
</x-app-layout>



