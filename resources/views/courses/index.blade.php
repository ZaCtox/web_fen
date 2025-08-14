<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Programas de Magíster</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4">
        {{-- Botón para ir a la vista de magísteres --}}
        <div class="mb-6">
            <a href="{{ route('magisters.index') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Ver Magísteres
            </a>
        </div>

        @foreach ($magisters as $magister)
            <div x-data="{ open: false }" class="mb-6 border border-gray-300 dark:border-gray-600 rounded p-4">
                <div
                    class="flex flex-col sm:flex-row sm:justify-between sm:items-center items-center text-center gap-2 mb-2 w-full">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white sm:text-left w-full sm:w-auto">
                        {{ $magister->nombre }}
                    </h3>
                    <div
                        class="flex flex-col sm:flex-row gap-2 sm:items-center sm:justify-end w-full sm:w-auto justify-center">
                        <a href="{{ route('courses.create', ['magister_id' => $magister->id]) }}"
                            class="text-sm bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">+ Añadir Curso</a>

                        @if($magister->courses->count() > 0)
                            <button @click="open = !open"
                                class="text-sm bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-white px-3 py-1 rounded hover:bg-gray-400 transition-colors duration-200">
                                <template x-if="!open">
                                    <span>📂 Ver Cursos</span>
                                </template>
                                <template x-if="open">
                                    <span>🔽 Ocultar Cursos</span>
                                </template>
                            </button>
                        @endif
                    </div>
                </div>
                @if($magister->courses->count() > 0)
                    <div x-show="open" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95" class="mt-3 space-y-4">
                        @php
                            $cursosAgrupados = $magister->courses->groupBy([
                                fn($curso) => $curso->period->anio ?? 'Sin año',
                                fn($curso) => $curso->period->numero ?? 'Sin trimestre',
                            ]);
                            $romanos = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI'];
                        @endphp

                        @foreach ($cursosAgrupados as $anio => $porTrimestre)
                            <div class="border rounded p-3 bg-gray-50 dark:bg-gray-800">
                                <h4 class="font-bold text-gray-800 dark:text-gray-200 mb-2">📘 Año {{ $anio }}</h4>

                                @foreach ($porTrimestre as $trimestre => $cursos)
                                    <div class="ml-4 mb-2">
                                        <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            Trimestre {{ $romanos[$trimestre] ?? $trimestre }}
                                        </h5>
                                        <table class="w-full table-auto text-sm mt-1">
                                            <thead class="bg-gray-100 dark:bg-gray-700">
                                                <tr>
                                                    <th class="px-4 py-2 text-left">Cursos</th>
                                                    <th class="px-4 py-2 text-right w-32">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($cursos as $course)
                                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                                        <td class="px-4 py-2">{{ $course->nombre }}</td>
                                                        <td class="px-4 py-2 text-right space-x-2">
                                                            <a href="{{ route('courses.edit', $course) }}"
                                                                class="text-blue-600 hover:underline">✏️ Editar</a>
                                                            <form action="{{ route('courses.destroy', $course) }}" method="POST"
                                                                class="inline">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:underline"
                                                                    onclick="return confirm('¿Eliminar curso?')">🗑️ Eliminar</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                        Este magíster aún no tiene cursos registrados.
                    </p>

                    <form action="{{ route('magisters.destroy', $magister) }}" method="POST" class="mt-4" x-data
                        @submit.prevent="if (confirm('¿Estás seguro de eliminar este magíster? Esta acción no se puede deshacer.')) $el.submit()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded">
                            🗑️ Eliminar Magíster
                        </button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</x-app-layout>