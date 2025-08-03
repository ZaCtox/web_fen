<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Listado de Magísteres</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto px-4">
        <a href="{{ route('magisters.create') }}"
           class="mb-4 inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
            ➕ Nuevo Magíster
        </a>

        @foreach ($magisters as $magister)
            @php
                $hasCourses = $magister->courses_count > 0;
                $msg = $hasCourses
                    ? 'Este magíster tiene cursos asociados. ¿Deseas eliminar también esos cursos?'
                    : '¿Eliminar este magíster?';
            @endphp

            <div class="mb-4 p-4 border rounded bg-white dark:bg-gray-800 dark:border-gray-600 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ $magister->nombre }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $magister->courses_count }} curso(s) asociado(s)
                        </p>
                    </div>
                    <div class="flex gap-2 justify-end">
                        <a href="{{ route('magisters.edit', $magister) }}"
                           class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded text-xs">
                            ✏️ Editar
                        </a>

                        <form action="{{ route('magisters.destroy', $magister) }}" method="POST"
                              onsubmit="return confirm('{{ $msg }}')">
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
        @endforeach
    </div>
</x-app-layout>
