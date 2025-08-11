<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Listado de Magísteres</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto px-4 space-y-4" x-data="{ q: '{{ request('q','') }}' }">
        {{-- Header: crear + búsqueda (dinámica con Alpine y fallback GET) --}}
        <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <a href="{{ route('magisters.create') }}"
               class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
                ➕ Nuevo Magíster
            </a>

            {{-- Fallback server-side: si presionan Enter, envías 'q' para filtrar en backend también --}}
            <form method="GET" class="w-full sm:w-auto" @submit.window>
                <input
                    name="q"
                    x-model="q"
                    placeholder="Buscar por nombre…"
                    class="w-full sm:w-64 px-3 py-2 rounded border dark:bg-gray-800 dark:text-white"
                />
            </form>
        </div>

        {{-- Listado (ocultar/mostrar por Alpine) --}}
        <div class="space-y-4">
            @forelse ($magisters as $magister)
                @php
                  $count = $magister->courses_count ?? 0;
                  $hasCourses = $count > 0;
                  $msg = $hasCourses
                    ? 'Este magíster tiene cursos asociados. ¿Deseas eliminar también esos cursos?'
                    : '¿Eliminar este magíster?';
                @endphp

                <div
                  class="p-4 border rounded bg-white dark:bg-gray-800 dark:border-gray-600 shadow-sm"
                  x-show="'{{ Str::lower($magister->nombre) }}'.includes(q.toLowerCase())"
                  x-cloak
                >
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                                {{ $magister->nombre }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $count }} curso(s) asociado(s)
                            </p>
                        </div>

                        <div class="flex gap-2 justify-end">
                            <a href="{{ route('magisters.edit', $magister) }}"
                               class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded text-xs">
                                ✏️ Editar
                            </a>

                            <form action="{{ route('magisters.destroy', $magister) }}" method="POST"
                                  onsubmit="return confirm(@js($msg))">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 hover:bg-red-200 rounded text-xs">
                                    🗑️ Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 dark:text-gray-400 py-8">
                    😕 No hay magísteres registrados.
                </p>
            @endforelse
        </div>

        {{-- Paginación (si usas paginate) --}}
        @if(method_exists($magisters, 'links'))
            <div class="pt-2">{{ $magisters->links() }}</div>
        @endif
    </div>
</x-app-layout>
