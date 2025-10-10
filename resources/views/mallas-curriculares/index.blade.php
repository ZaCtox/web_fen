@section('title', 'Mallas Curriculares')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-[#005187] dark:text-[#84b6f4]">
            Mallas Curriculares
        </h2>
    </x-slot>
    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Mallas Curriculares', 'url' => '#']
    ]" />

    <div class="p-6 max-w-7xl mx-auto" x-data="{
            magisterId: '{{ request('magister_id') }}',
            activa: '{{ request('activa') }}',
            año: '{{ request('año') }}',

            actualizarURL() {
                const params = new URLSearchParams();
                if (this.magisterId) params.set('magister_id', this.magisterId);
                if (this.activa !== '') params.set('activa', this.activa);
                if (this.año) params.set('año', this.año);
                
                window.location.search = params.toString();
            },

            limpiarFiltros() {
                this.magisterId = '';
                this.activa = '';
                this.año = '';
                window.location.href = '{{ route('mallas-curriculares.index') }}';
            }
        }">

        {{-- Encabezado --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-[#005187] dark:text-[#84b6f4]">
                    Gestión de Mallas Curriculares
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Administra las versiones de mallas curriculares por programa
                </p>
            </div>
            <a href="{{ route('mallas-curriculares.create') }}"
                class="inline-flex items-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white px-6 py-3 rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 font-medium"
                title="Crear nueva malla curricular">
                <img src="{{ asset('icons/agregar.svg') }}" alt="Agregar" class="w-5 h-5">
            </a>
        </div>

        {{-- Filtros Dinámicos --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                {{-- Filtro Programa --}}
                <div>
                    <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4] mb-2">
                        Programa:
                    </label>
                    <select x-model="magisterId" @change="actualizarURL()"
                        class="w-full rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-700 text-[#005187] dark:text-[#84b6f4] px-3 py-2.5 focus:ring-[#4d82bc] focus:border-[#4d82bc] transition-colors">
                        <option value="">Todos los programas</option>
                        @foreach($magisters as $magister)
                            <option value="{{ $magister->id }}">
                                {{ $magister->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtro Estado --}}
                <div>
                    <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4] mb-2">
                        Estado:
                    </label>
                    <select x-model="activa" @change="actualizarURL()"
                        class="w-full rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-700 text-[#005187] dark:text-[#84b6f4] px-3 py-2.5 focus:ring-[#4d82bc] focus:border-[#4d82bc] transition-colors">
                        <option value="">Todas</option>
                        <option value="1">Activas</option>
                        <option value="0">Inactivas</option>
                    </select>
                </div>

                {{-- Filtro Año --}}
                <div>
                    <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4] mb-2">
                        Año:
                    </label>
                    <input type="number" x-model="año" @input.debounce.500ms="actualizarURL()" placeholder="Ej: 2025"
                        min="2020" max="2100"
                        class="w-full rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-700 text-[#005187] dark:text-[#84b6f4] px-3 py-2.5 focus:ring-[#4d82bc] focus:border-[#4d82bc] transition-colors">
                </div>

                {{-- Botón Limpiar Filtros --}}
                <div>
                    <button @click="limpiarFiltros()"
                        class="mt-1 bg-[#84b6f4] hover:bg-[#005187] text-[#005187] px-4 py-2 rounded-lg shadow text-sm transition transform hover:scale-105"
                        title="Limpiar filtros">
                        <img src="{{ asset('icons/filterw.svg') }}" alt="Limpiar" class="w-5 h-5">
                    </button>
                </div>
            </div>
        </div>

        {{-- Tabla de mallas --}}
        @if($mallas->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-[#c4dafa] dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-[#005187] dark:text-[#84b6f4] uppercase tracking-wider">
                                Código
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-[#005187] dark:text-[#84b6f4] uppercase tracking-wider">
                                Nombre
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-[#005187] dark:text-[#84b6f4] uppercase tracking-wider">
                                Programa
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-[#005187] dark:text-[#84b6f4] uppercase tracking-wider">
                                Vigencia
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-[#005187] dark:text-[#84b6f4] uppercase tracking-wider">
                                Cursos
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-[#005187] dark:text-[#84b6f4] uppercase tracking-wider">
                                Estado
                            </th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-[#005187] dark:text-[#84b6f4] uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($mallas as $malla)
                            <tr class="hover:bg-[#e3f2fd] dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-mono font-semibold text-[#005187] dark:text-[#84b6f4]">
                                        {{ $malla->codigo }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $malla->nombre }}
                                    </div>
                                    @if($malla->descripcion)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ Str::limit($malla->descripcion, 60) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full"
                                            style="background-color: {{ $malla->magister->color ?? '#6b7280' }}"></span>
                                        <span class="text-sm text-gray-900 dark:text-white">
                                            {{ $malla->magister->nombre }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ $malla->periodo_vigencia }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                        {{ $malla->courses_count }} curso{{ $malla->courses_count != 1 ? 's' : '' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {!! $malla->estado_badge !!}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Ver --}}
                                        <a href="{{ route('mallas-curriculares.show', $malla) }}"
                                            class="inline-flex items-center justify-center w-10 h-10 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
                                            title="Ver detalle">
                                            <img src="{{ asset('icons/verw.svg') }}" alt="Ver" class="w-5 h-5">
                                        </a>

                                        {{-- Editar --}}
                                        <a href="{{ route('mallas-curriculares.edit', $malla) }}"
                                            class="inline-flex items-center justify-center w-10 h-10 bg-[#84b6f4] hover:bg-[#4d82bc] text-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
                                            title="Editar">
                                            <img src="{{ asset('icons/editw.svg') }}" alt="Editar" class="w-5 h-5">
                                        </a>

                                        {{-- Activar/Desactivar --}}
                                        <form action="{{ route('mallas-curriculares.toggle-estado', $malla) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-10 h-10 {{ $malla->activa ? 'bg-orange-500 hover:bg-orange-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2"
                                                title="{{ $malla->activa ? 'Desactivar' : 'Activar' }}">
                                                <img src="{{ asset($malla->activa ? 'icons/desactivarw.svg' : 'icons/activarw.svg') }}"
                                                    alt="{{ $malla->activa ? 'Desactivar' : 'Activar' }}" class="w-5 h-5">
                                            </button>
                                        </form>

                                        {{-- Eliminar --}}
                                        <form action="{{ route('mallas-curriculares.destroy', $malla) }}" method="POST"
                                            class="inline form-eliminar"
                                            data-confirm="¿Eliminar la malla curricular '{{ $malla->nombre }}'?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-10 h-10 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                                title="Eliminar">
                                                <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar" class="w-5 h-5">
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
            <div class="mt-6">
                {{ $mallas->links() }}
            </div>
        @else
            {{-- Estado vacío --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                <span class="text-6xl">📚</span>
                <h3 class="mt-4 text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
                    No hay mallas curriculares
                </h3>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    {{ request()->has('magister_id') || request()->has('activa') || request()->has('año')
            ? 'No se encontraron mallas con los filtros seleccionados'
            : 'Comienza creando tu primera malla curricular' }}
                </p>
                @if(!request()->has('magister_id') && !request()->has('activa') && !request()->has('año'))
                    <a href="{{ route('mallas-curriculares.create') }}"
                        class="inline-flex items-center gap-2 mt-6 bg-[#4d82bc] hover:bg-[#005187] text-white px-6 py-3 rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 font-medium">
                        <img src="{{ asset('icons/add.svg') }}" alt="Agregar" class="w-5 h-5">
                        Crear Malla Curricular
                    </a>
                @endif
            </div>
        @endif
    </div>

    @push('scripts')
        @vite('resources/js/alerts.js')
    @endpush
</x-app-layout>


