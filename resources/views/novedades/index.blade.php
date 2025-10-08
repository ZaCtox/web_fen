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
            class="hci-button hci-lift hci-focus-ring inline-flex items-center bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-lg shadow transition-all duration-200">
            <img src="{{ asset('icons/agregar.svg') }}" alt="nueva" class="w-5 h-5">
        </a>
    </div>

    <div class="p-6 max-w-7xl mx-auto">

        {{-- 🔍 Filtros --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Título o contenido..."
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-[#4d82bc] focus:border-[#4d82bc] dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                    <select name="tipo" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-[#4d82bc] focus:border-[#4d82bc] dark:bg-gray-700 dark:text-white">
                        <option value="">Todos los tipos</option>
                        <option value="academica" {{ request('tipo') == 'academica' ? 'selected' : '' }}>Académica</option>
                        <option value="evento" {{ request('tipo') == 'evento' ? 'selected' : '' }}>Evento</option>
                        <option value="admision" {{ request('tipo') == 'admision' ? 'selected' : '' }}>Admisión</option>
                        <option value="institucional" {{ request('tipo') == 'institucional' ? 'selected' : '' }}>Institucional</option>
                        <option value="servicio" {{ request('tipo') == 'servicio' ? 'selected' : '' }}>Servicio</option>
                        <option value="mantenimiento" {{ request('tipo') == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                    <select name="estado" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-[#4d82bc] focus:border-[#4d82bc] dark:bg-gray-700 dark:text-white">
                        <option value="">Todos los estados</option>
                        <option value="activas" {{ request('estado') == 'activas' ? 'selected' : '' }}>Activas</option>
                        <option value="expiradas" {{ request('estado') == 'expiradas' ? 'selected' : '' }}>Expiradas</option>
                        <option value="urgentes" {{ request('estado') == 'urgentes' ? 'selected' : '' }}>Urgentes</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="hci-button hci-focus-ring bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-md transition-all duration-200">
                        Filtrar
                    </button>
                    @if(request()->hasAny(['search', 'tipo', 'estado']))
                        <a href="{{ route('novedades.index') }}" class="hci-button hci-focus-ring bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-all duration-200">
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>
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
                                        <a href="{{ route('novedades.show', $novedad) }}" 
                                           class="hci-button hci-focus-ring bg-[#84b6f4] hover:bg-[#4d82bc] text-white px-2 py-1 rounded text-xs transition-all duration-200"
                                           title="Ver">
                                            <img src="{{ asset('icons/ver.svg') }}" alt="Ver" class="w-4 h-4">
                                        </a>
                                        <a href="{{ route('novedades.edit', $novedad) }}" 
                                           class="hci-button hci-focus-ring bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs transition-all duration-200"
                                           title="Editar">
                                            <img src="{{ asset('icons/editw.svg') }}" alt="Editar" class="w-4 h-4">
                                        </a>
                                        <form method="POST" action="{{ route('novedades.duplicate', $novedad) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="hci-button hci-focus-ring bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-all duration-200"
                                                    title="Duplicar">
                                                <img src="{{ asset('icons/duplicate.svg') }}" alt="Duplicar" class="w-4 h-4">
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('novedades.destroy', $novedad) }}" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta novedad?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="hci-button hci-focus-ring bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs transition-all duration-200"
                                                    title="Eliminar">
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
