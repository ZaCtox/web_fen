@section('title', $mallaCurricular->nombre)
<x-app-layout>
    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Mallas Curriculares', 'url' => route('mallas-curriculares.index')],
        ['label' => $mallaCurricular->nombre, 'url' => '#']
    ]" />

    <div class="p-6 max-w-7xl mx-auto">
        {{-- Encabezado --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h2 class="text-2xl font-bold text-[#005187] dark:text-[#84b6f4]">
                            {{ $mallaCurricular->nombre }}
                        </h2>
                        {!! $mallaCurricular->estado_badge !!}
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-mono font-semibold">{{ $mallaCurricular->codigo }}</span>
                    </p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="w-3 h-3 rounded-full" 
                              style="background-color: {{ $mallaCurricular->magister ? $mallaCurricular->magister->color : '#6b7280' }}"></span>
                        <span class="text-sm text-gray-700 dark:text-gray-300">
                            {{ $mallaCurricular->magister ? $mallaCurricular->magister->nombre : 'Sin programa' }}
                        </span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('mallas-curriculares.edit', $mallaCurricular) }}"
                       class="inline-flex items-center gap-2 bg-[#84b6f4] hover:bg-[#4d82bc] text-white px-4 py-2 rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 font-medium"
                       title="Editar malla">
                        <img src="{{ asset('icons/editw.svg') }}" alt="Editar" class="w-5 h-5">
                    </a>

                    <a href="{{ route('mallas-curriculares.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white font-medium rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
                       title="Volver al listado">
                        <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-5 h-5">
                    </a>
                </div>
            </div>
        </div>

        {{-- Información general --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            {{-- Vigencia --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-3xl">📅</span>
                    <h3 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4]">
                        Vigencia
                    </h3>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $mallaCurricular->periodo_vigencia }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $mallaCurricular->es_vigente ? '✅ Vigente actualmente' : '⏳ No vigente' }}
                </p>
            </div>

            {{-- Cursos --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-3xl">📚</span>
                    <h3 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4]">
                        Cursos
                    </h3>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $mallaCurricular->courses_count ?? $mallaCurricular->courses->count() }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    @php
                        $count = $mallaCurricular->courses_count ?? $mallaCurricular->courses->count();
                    @endphp
                    Curso{{ $count != 1 ? 's' : '' }} asociado{{ $count != 1 ? 's' : '' }}
                </p>
            </div>

            {{-- Estado --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-3xl">⚡</span>
                    <h3 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4]">
                        Estado
                    </h3>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $mallaCurricular->activa ? 'Activa' : 'Inactiva' }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $mallaCurricular->activa ? 'Disponible para nuevos cursos' : 'No disponible' }}
                </p>
            </div>
        </div>

        {{-- Descripción --}}
        @if($mallaCurricular->descripcion)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4] mb-3 flex items-center gap-2">
                    <span class="text-2xl">📋</span>
                    Descripción
                </h3>
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">
                    {{ $mallaCurricular->descripcion }}
                </p>
            </div>
        @endif

        {{-- Cursos de la malla --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4] flex items-center gap-2">
                    <span class="text-2xl">📖</span>
                    Cursos de esta Malla Curricular
                </h3>
                @if($mallaCurricular->activa)
                    <a href="{{ route('courses.create') }}?malla_curricular_id={{ $mallaCurricular->id }}"
                       class="inline-flex items-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-sm font-medium"
                       title="Agregar curso a esta malla">
                        <img src="{{ asset('icons/add.svg') }}" alt="Agregar" class="w-4 h-4">
                        Agregar Curso
                    </a>
                @endif
            </div>

            @if($mallaCurricular->courses->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-[#c4dafa] dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#005187] dark:text-[#84b6f4] uppercase tracking-wider">
                                    Curso
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#005187] dark:text-[#84b6f4] uppercase tracking-wider">
                                    Periodo
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-[#005187] dark:text-[#84b6f4] uppercase tracking-wider">
                                    Clases
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-[#005187] dark:text-[#84b6f4] uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($mallaCurricular->courses->sortBy('period.numero') as $course)
                                <tr class="hover:bg-[#e3f2fd] dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $course->nombre }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-900 dark:text-white">
                                            {{ $course->period->nombre_completo }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                            {{ $course->clases->count() }} clase{{ $course->clases->count() != 1 ? 's' : '' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('courses.edit', $course) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 bg-[#84b6f4] hover:bg-[#4d82bc] text-white rounded-lg transition-all duration-200"
                                           title="Editar curso">
                                            <img src="{{ asset('icons/editar.svg') }}" alt="Editar" class="w-4 h-4">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <span class="text-6xl">📚</span>
                    <p class="mt-4 text-gray-600 dark:text-gray-400">
                        No hay cursos asociados a esta malla curricular
                    </p>
                    @if($mallaCurricular->activa)
                        <a href="{{ route('courses.create') }}?malla_curricular_id={{ $mallaCurricular->id }}"
                           class="inline-flex items-center gap-2 mt-4 bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-sm font-medium">
                            <img src="{{ asset('icons/add.svg') }}" alt="Agregar" class="w-4 h-4">
                            Agregar Primer Curso
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>




