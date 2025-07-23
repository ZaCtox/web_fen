<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Programas de Magíster</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4">
        {{-- Botón para ir a la vista de magísteres --}}
        <div class="mb-6">
            <a href="{{ route('magisters.index') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                🔍 Ver Magísteres
            </a>
        </div>

        @foreach ($magisters as $magister)
            <div x-data="{ open: false }" class="mb-6 border border-gray-300 dark:border-gray-600 rounded p-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $magister->nombre }}</h3>
                    <div class="flex gap-2">
                        <a href="{{ route('courses.create', ['magister_id' => $magister->id]) }}"
                            class="text-sm bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">+ Añadir Curso</a>

                        @if($magister->courses->count() > 0)
                            <button @click="open = !open"
                                class="text-sm bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-white px-3 py-1 rounded hover:bg-gray-400">
                                <span x-show="!open">📂 Ver Cursos</span>
                                <span x-show="open">🔽 Ocultar</span>
                            </button>
                        @endif
                    </div>
                </div>

                @if($magister->courses->count() > 0)
                    <table x-show="open" class="w-full table-auto text-sm text-gray-700 dark:text-gray-200 mt-3">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left">Nombre del Curso</th>
                                <th class="px-4 py-2 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($magister->courses as $course)
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <td class="px-4 py-2">{{ $course->nombre }}</td>
                                    <td class="px-4 py-2 space-x-2">
                                        <a href="{{ route('courses.edit', $course) }}"
                                            class="text-blue-600 hover:underline">Editar</a>
                                        <form action="{{ route('courses.destroy', $course) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline"
                                                onclick="return confirm('¿Eliminar curso?')">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Este magíster aún no tiene cursos registrados.</p>
                @endif
            </div>
        @endforeach
    </div>
</x-app-layout>