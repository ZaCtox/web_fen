{{-- Cursos de Postgrado FEN --}}
@section('title', 'Cursos')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Cursos por Programa
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4" x-data="{
        expandedMagisters: {}
    }">
        {{-- Selector de Cohorte --}}
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div class="flex-1">
                    <form method="GET" action="{{ route('public.courses.index') }}">
                        <label for="cohorte" class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4] mb-2">
                            Ciclo Académico:
                        </label>
                        <select name="cohorte" 
                                id="cohorte"
                                onchange="this.form.submit()"
                                class="w-full sm:w-64 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-700 text-[#005187] dark:text-[#84b6f4] px-4 py-2.5 focus:ring-[#4d82bc] focus:border-[#4d82bc] font-medium">
                            @foreach($cohortes as $cohorte)
                                <option value="{{ $cohorte }}" {{ $cohorteSeleccionada == $cohorte ? 'selected' : '' }}>
                                    {{ $cohorte }} {{ $cohorte == $cohortes->first() ? '(Actual)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            {{-- Indicador de cohorte --}}
            @if($cohorteSeleccionada != $cohortes->first())
                <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                    <p class="text-sm text-yellow-800 dark:text-yellow-300">
                        ⚠️ Estás visualizando cursos de la cohorte <strong>{{ $cohorteSeleccionada }}</strong> (período pasado).
                    </p>
                </div>
            @endif
        </div>

        <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg shadow p-6 border border-[#c4dafa]">

            @php
                $romanos = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI'];
            @endphp

            @forelse ($magisters as $magister)
                <div class="mb-6 border border-[#c4dafa] rounded-lg shadow-sm bg-[#fcffff] dark:bg-gray-800">

                    {{-- Header clickable con affordance --}}
                    <button @click="expandedMagisters[{{ $magister->id }}] = !expandedMagisters[{{ $magister->id }}]"
                            class="w-full flex justify-between items-center cursor-pointer
                                   bg-[#c4dafa]/30 hover:bg-[#84b6f4]/30 px-4 py-3 rounded-t-lg transition
                                   focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
                            :aria-expanded="expandedMagisters[{{ $magister->id }}]"
                            aria-label="Expandir/colapsar cursos del Magíster en {{ $magister->nombre }}">
                        <h3 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4]">
                            Magíster en {{ $magister->nombre }}
                        </h3>
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-[#4d82bc] flex items-center">
                                <svg class="ml-2 w-5 h-5 transition-transform duration-200" 
                                     :class="{ 'rotate-180': expandedMagisters[{{ $magister->id }}] }"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                     aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </div>
                    </button>

                    {{-- Contenido oculto inicialmente --}}
                    <div x-show="expandedMagisters[{{ $magister->id }}]" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="p-4 space-y-4">
                        @php
                            $agrupados = $magister->courses->groupBy([
                                fn($curso) => $curso->period->anio ?? 'Sin año',
                                fn($curso) => $curso->period->numero ?? 'Sin trimestre',
                            ]);
                        @endphp

                        @forelse ($agrupados as $anio => $porTrimestre)
                            <div class="border border-[#c4dafa] rounded bg-[#c4dafa]/10 p-3">
                                <h4 class="font-bold text-[#005187] dark:text-[#84b6f4] mb-2">Año {{ $anio }}</h4>

                                @foreach ($porTrimestre as $trimestre => $cursos)
                                    <div class="ml-4 mb-3">
                                        <h5 class="text-sm font-semibold text-[#4d82bc] dark:text-[#84b6f4] mb-1">
                                            Trimestre {{ $romanos[$trimestre] ?? $trimestre }}
                                        </h5>

                                        <div class="space-y-2">
                                            @foreach ($cursos as $course)
                                                <div class="px-4 py-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 
                                                         hover:bg-[#e3f2fd] dark:hover:bg-gray-700 
                                                         hover:border-l-4 hover:border-l-[#4d82bc]
                                                         hover:-translate-y-0.5 hover:shadow-md
                                                         transition-all duration-200 group cursor-pointer">
                                                    <span class="text-[#005187] dark:text-gray-100 font-medium group-hover:text-[#4d82bc] dark:group-hover:text-[#84b6f4] transition-colors duration-200">
                                                        {{ $course->nombre }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @empty
                            <p class="text-sm text-[#4d82bc] dark:text-gray-400 mt-2">
                                Este magíster aún no tiene cursos registrados.
                            </p>
                        @endforelse
                    </div>
                </div>
            @empty
                <p class="text-center text-[#4d82bc] dark:text-gray-400">
                    No hay programas de magíster registrados.
                </p>
            @endforelse
        </div>
    </div>


    @include('components.footer')
</x-app-layout>



