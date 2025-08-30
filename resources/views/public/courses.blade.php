<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Cursos por Magíster</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4">
        <div x-data="{ search: '' }" class="bg-white dark:bg-gray-800 rounded shadow p-6">
            <input type="text" x-model="search" placeholder="Buscar programa por nombre..."
                class="w-full mb-6 px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">

            @php $romanos = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI']; @endphp

            @foreach ($magisters as $magister)
                <div class="mb-6"
                    x-show="search === '' || '{{ strtolower($magister->nombre) }}'.includes(search.toLowerCase())">
                    <h4 class="text-base font-bold text-blue-700 dark:text-blue-300">{{ $magister->nombre }}</h4>

                    @php
                        $agrupados = $magister->courses->groupBy([
                            fn($curso) => $curso->period->anio ?? 'Sin año',
                            fn($curso) => $curso->period->numero ?? 'Sin trimestre',
                        ]);
                    @endphp

                    @foreach ($agrupados as $anio => $porTrimestre)
                        <div class="ml-4 mt-2">
                            <h5 class="text-sm font-semibold text-gray-800 dark:text-gray-300">📘 Año {{ $anio }}</h5>

                            @foreach ($porTrimestre as $trimestre => $cursos)
                                <div class="ml-4 mt-1">
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                        Trimestre {{ $romanos[$trimestre] ?? $trimestre }}
                                    </p>
                                    <ul class="list-disc ml-5 text-sm text-gray-700 dark:text-gray-200">
                                        @foreach ($cursos as $curso)
                                            <li>{{ $curso->nombre }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
    @include('components.footer')
</x-app-layout>
