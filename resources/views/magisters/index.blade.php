<<<<<<< Updated upstream
{{-- Incio de Programas.blade.php --}}
@section('title', 'Programas')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Listado de Programas</h2>
=======
<<<<<<< Updated upstream
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Listado de Magísteres</h2>
=======
{{-- Inicio de Programas.blade.php --}}
@section('title', 'Programas')

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-[#005187] dark:text-[#84b6f4]">
            Listado de Programas
        </h2>
>>>>>>> Stashed changes
>>>>>>> Stashed changes
    </x-slot>

    {{-- Metas para toasts --}}
    @if(session('success'))
        <meta name="session-success" content="{{ session('success') }}">
    @endif

    @if(session('error'))
        <meta name="session-error" content="{{ session('error') }}">
    @endif

<<<<<<< Updated upstream
    <div class="py-6 max-w-5xl mx-auto px-4 space-y-4" x-data="{ q: '{{ request('q', '') }}' }">
        {{-- Header: crear + búsqueda --}}
        <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div class="flex gap-3">
                <a href="{{ route('courses.index') }}"
                   class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow">
                    ⬅️ Ver Cursos
                </a>
=======
<<<<<<< Updated upstream
            <div class="mb-4 p-4 border rounded dark:border-gray-600">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold dark:text-white">{{ $magister->nombre }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $magister->courses_count }} curso(s) asociado(s)
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('magisters.edit', $magister) }}"
                           class="text-sm text-blue-600 hover:underline">Editar</a>
>>>>>>> Stashed changes

                <a href="{{ route('magisters.create') }}"
                   class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
                    ➕ Nuevo Programa
                </a>
            </div>

            <form method="GET" class="w-full sm:w-auto">
                <input name="q" x-model="q" placeholder="Buscar por nombre…"
                       class="w-full sm:w-64 px-3 py-2 rounded border dark:bg-gray-800 dark:text-white" />
            </form>
        </div>

        {{-- Listado --}}
        <div class="space-y-4">
            @forelse ($magisters as $magister)
                @php
                    $count = $magister->courses_count ?? 0;
                    $hasCourses = $count > 0;
                    $msg = $hasCourses
                        ? 'Este programa tiene cursos asociados. ¿Deseas eliminar también esos cursos?'
                        : '¿Eliminar este programa?';
                @endphp

                <div class="p-4 border-l-4 rounded bg-white dark:bg-gray-800 dark:border-gray-600 shadow-sm"
                     style="border-left-color: {{ $magister->color ?? '#999' }};"
                     x-show="'{{ Str::lower($magister->nombre) }}'.includes(q.toLowerCase())" x-cloak>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                        {{-- Info principal --}}
                        <div class="space-y-1">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                                Magíster: {{ $magister->nombre }}
                            </h3>
                            <div class="text-sm text-gray-600 dark:text-gray-300 space-y-0.5">
                                @if ($magister->encargado)
                                    <p><strong>Encargado:</strong> {{ $magister->encargado }}</p>
                                @endif
                                @if ($magister->telefono)
                                    <p><strong>Teléfono:</strong> {{ $magister->telefono }}</p>
                                @endif
                                @if ($magister->correo)
                                    <p><strong>Correo:</strong> {{ $magister->correo }}</p>
                                @endif
                                <p><strong>Cursos asociados:</strong> {{ $count }}</p>
                            </div>
                        </div>

                        {{-- Color + acciones --}}
                        <div class="flex flex-col sm:items-end gap-2">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full border"
                                      style="background-color: {{ $magister->color ?? '#999' }}"></span>
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('magisters.edit', $magister) }}"
                                   class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded text-xs">
                                    ✏️
                                </a>

                                {{-- IMPORTANTE: usar class="form-eliminar" y data-confirm --}}
                                <form action="{{ route('magisters.destroy', $magister) }}" method="POST"
                                      class="form-eliminar"
                                      data-confirm="{{ $msg }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 hover:bg-red-200 rounded text-xs">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
<<<<<<< Updated upstream
            @empty
                <p class="text-center text-gray-500 dark:text-gray-400 py-8">
                    😕 No hay magísteres registrados.
=======
            </div>
        @endforeach
=======
    <div class="py-6 max-w-5xl mx-auto px-4 space-y-6" x-data="{ q: '{{ request('q', '') }}' }">

        {{-- Header: acciones + búsqueda --}}
        <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div class="flex gap-3">
                <a href="{{ route('courses.index') }}"
                    class="inline-block bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-md shadow-md transition">
                    <img src="{{ asset('icons/back.svg') }}" alt="check" class="w-5 h-5">
                </a>

                <a href="{{ route('magisters.create') }}"
                    class="inline-flex items-center bg-[#4d82bc] hover:bg-[#005187] ml-4 text-white px-4 py-2 rounded-lg shadow transition transform hover:scale-105">
                    <img src="{{ asset('icons/agregar.svg') }}" alt="nueva" class="w-5 h-5">
                </a>
            </div>

            <form method="GET" class="w-full sm:w-auto">
                <input name="q" x-model="q" placeholder="Buscar por nombre…"
                    class="w-full sm:w-64 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
            </form>
        </div>

        {{-- Listado --}}
        <div class="space-y-4">
            @forelse ($magisters as $magister)
                @php
                    $count = $magister->courses_count ?? 0;
                    $hasCourses = $count > 0;
                    $msg = $hasCourses
                        ? 'Este programa tiene cursos asociados. ¿Deseas eliminar también esos cursos?'
                        : '¿Eliminar este programa?';
                @endphp

                <div class="p-5 rounded-xl shadow-md bg-white dark:bg-gray-900 border-l-4 transition hover:shadow-lg"
                    style="border-left-color: {{ $magister->color ?? '#999' }};"
                    x-show="'{{ Str::lower($magister->nombre) }}'.includes(q.toLowerCase())" x-cloak>

                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">

                        {{-- Info principal --}}
                        <div class="space-y-2">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                                <span class="text-[#005187] dark:text-[#84b6f4]">Magíster:</span> {{ $magister->nombre }}
                            </h3>
                            <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                                @if ($magister->encargado)
                                    <p><strong class="text-[#005187] dark:text-[#84b6f4]">Encargado:</strong>
                                        {{ $magister->encargado }}</p>
                                @endif
                                @if ($magister->asistente)
                                    <p><strong class="text-[#005187] dark:text-[#84b6f4]">Asistente:</strong> {{ $magister->asistente }}</p>
                                @endif
                                @if ($magister->telefono)
                                    <p><strong class="text-[#005187] dark:text-[#84b6f4]">Teléfono:</strong>
                                        {{ $magister->telefono }}</p>
                                @endif
                                @if ($magister->anexo)
                                    <p><strong class="text-[#005187] dark:text-[#84b6f4]">Anexo:</strong>
                                        {{ $magister->anexo }}</p>
                                @endif
                                @if ($magister->correo)
                                    <p><strong class="text-[#005187] dark:text-[#84b6f4]">Correo:</strong>
                                        {{ $magister->correo }}</p>
                                @endif
                                <p><strong class="text-[#005187] dark:text-[#84b6f4]">Cursos asociados:</strong>
                                    {{ $count }}</p>
                            </div>
                        </div>


                        {{-- Color + acciones --}}
                        <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-2">
                            <div class="flex gap-2">
                                <a href="{{ route('magisters.edit', $magister) }}"
                                    class="inline-flex items-center justify-center px-1 py-1 hover:bg-[#84b6f4]/30 rounded-lg text-xs font-medium transition w-full sm:w-auto">
                                    <img src="{{ asset('icons/edit.svg') }}" alt="Editar" class="w-6 h-6">
                                </a>

                                {{-- IMPORTANTE: usar class="form-eliminar" y data-confirm --}}
                                <form action="{{ route('magisters.destroy', $magister) }}" method="POST"
                                    class="form-eliminar" data-confirm="{{ $msg }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center px-1 py-1 hover:bg-[#84b6f4]/30 rounded-lg text-xs font-medium transition w-full sm:w-auto">
                                        <img src="{{ asset('icons/trash.svg') }}" alt="Borrar" class="w-5 h-5">
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 dark:text-gray-400 py-10 text-lg">
                    😕 No hay programas registrados.
>>>>>>> Stashed changes
                </p>
            @endforelse
        </div>

        {{-- Paginación --}}
        @if(method_exists($magisters, 'links'))
<<<<<<< Updated upstream
            <div class="pt-2">{{ $magisters->links() }}</div>
        @endif
=======
            <div class="pt-4">{{ $magisters->links() }}</div>
        @endif
>>>>>>> Stashed changes
>>>>>>> Stashed changes
    </div>
</x-app-layout>