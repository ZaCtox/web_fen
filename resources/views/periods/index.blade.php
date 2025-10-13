{{-- Inicio de Periodo Académico.blade.php --}}
@section('title', 'Periodos Académicos')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-[#005187] dark:text-[#c4dafa]">
            Períodos Académicos
        </h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Períodos', 'url' => '#']
    ]" />

    <div class="p-6 max-w-6xl mx-auto">
        {{-- Botones superiores --}}
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
            {{-- Lado izquierdo: Botón Agregar --}}
            <div class="flex flex-col sm:flex-row gap-3">
                @if ($periods->count() < 6)
                    <a href="{{ route('periods.create') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-sm font-medium hci-button-ripple hci-glow">
                        <img src="{{ asset('icons/agregar.svg') }}" alt="" class="w-5 h-5">
                        <span>Agregar Período</span>
                    </a>
                @else
                    <button type="button" onclick="
                                        Swal.fire({
                                            title:'No puedes crear más trimestres',
                                            text:'Ya existen 6 trimestres registrados.',
                                            icon:'info',
                                            confirmButtonColor:'#4d82bc',
                                            confirmButtonText:'Entendido'
                                        });
                                    "
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gray-400 cursor-not-allowed text-white rounded-lg shadow transition duration-200"
                        aria-disabled="true"
                        title="Máximo de 6 trimestres alcanzado">
                        <img src='{{ asset('icons/agregar.svg') }}' alt='' class='w-5 h-5 opacity-75'>
                        <span>Agregar Período</span>
                    </button>
                @endif

                {{-- Botón Actualizar fechas al próximo año --}}
                <form id="form-actualizar-proximo-anio" method="POST" action="{{ route('periods.actualizarProximoAnio') }}"
                    class="hidden">
                    @csrf
                </form>
                <button type="button" onclick="
                    Swal.fire({
                        title:'¿Actualizar fechas al próximo año?',
                        text:'Se sumará 1 año a las fechas de inicio y término de TODOS los períodos. No se modifica el número de trimestre ni el año académico.',
                        icon:'warning',
                        showCancelButton:true,
                        confirmButtonColor:'#4d82bc',
                        cancelButtonColor:'#6b7280',
                        confirmButtonText:'Sí, actualizar',
                        cancelButtonText:'Cancelar'
                    }).then((r)=>{ if(r.isConfirmed) document.getElementById('form-actualizar-proximo-anio').submit(); });
                "
                    class="inline-flex items-center gap-2 px-4 py-3 bg-[#84b6f4] hover:bg-[#4d82bc] text-white font-medium rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#84b6f4] focus:ring-offset-2 text-sm hci-button-ripple hci-glow">
                    <img src="{{ asset('icons/actualizar.svg') }}" alt="" class="w-5 h-5">
                    <span>Actualizar al próximo año</span>
                </button>
            </div>

            {{-- Lado derecho: Filtro de cohorte --}}
            <div class="w-full sm:w-auto">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        <label for="cohorte-select" class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4] whitespace-nowrap">
                            Ciclo Académico:
                        </label>
                        <form method="GET" action="{{ route('periods.index') }}" id="cohorte-form">
                            <select name="cohorte" 
                                    id="cohorte-select"
                                    onchange="document.getElementById('cohorte-form').submit()"
                                    class="w-full sm:w-64 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-700 text-[#005187] dark:text-[#84b6f4] px-4 py-2.5 focus:ring-2 focus:ring-[#4d82bc] focus:border-[#4d82bc] font-medium hci-input-focus">
                                @foreach($cohortes as $cohorte)
                                    <option value="{{ $cohorte }}" {{ $cohorte == $cohorteSeleccionada ? 'selected' : '' }}>
                                        {{ $cohorte }} {{ $cohorte == $cohortes->first() ? '(Actual)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        @php
            $romanos = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI'];
            $agrupados = $periods->sortBy([['anio', 'asc'], ['numero', 'asc']])->groupBy('anio');
        @endphp

        @if($periods->isEmpty())
            <x-empty-state type="no-data" icon="📅" title="No hay períodos académicos registrados"
                message="Crea tu primer período académico para comenzar a organizar las clases y actividades del año."
                actionText="Crear Período" actionUrl="{{ route('periods.create') }}" actionIcon="➕" />
        @else
            @foreach ($agrupados as $anio => $porTrimestre)
                <div
                    class="mt-6 mb-10 bg-[#fcffff] dark:bg-gray-800 rounded-xl shadow-md border-l-4 border-[#005187] overflow-hidden">
                    <h3 class="text-lg font-bold text-[#005187] dark:text-[#c4dafa] px-6 py-3 bg-[#84b6f4]/20">
                        Año {{ $anio }}
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto text-sm text-gray-700 dark:text-gray-200">
                            <thead class="bg-[#c4dafa] dark:bg-gray-700 text-[#005187] dark:text-white">
                                <tr>
                                    <th class="px-4 py-2 text-left">Trimestre</th>
                                    <th class="px-4 py-2 text-left">Inicio</th>
                                    <th class="px-4 py-2 text-left">Término</th>
                                    <th class="px-8 py-2 text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($porTrimestre->sortBy('numero') as $period)
                                    <tr class="border-b border-gray-200 dark:border-gray-600 
                                                                   hover:bg-[#e3f2fd] dark:hover:bg-gray-700 
                                                                   hover:border-l-4 hover:border-l-[#4d82bc]
                                                                   hover:-translate-y-0.5 hover:shadow-md
                                                                   transition-all duration-200 group cursor-pointer"
                                                                   onclick="window.location='{{ route('periods.edit', $period) }}'">
                                        <td
                                            class="px-4 py-2 font-semibold text-[#005187] dark:text-[#c4dafa] group-hover:text-[#4d82bc] dark:group-hover:text-[#84b6f4] transition-colors duration-200">
                                            Trimestre {{ $romanos[$period->numero] ?? $period->numero }}
                                        </td>
                                        <td
                                            class="px-4 py-2 group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200">
                                            {{ $period->fecha_inicio->format('d/m/Y') }}</td>
                                        <td
                                            class="px-4 py-2 group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200">
                                            {{ $period->fecha_fin->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2" onclick="event.stopPropagation()">
                                            <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-2">
                                                {{-- Botón Editar --}}
                                                <a href="{{ route('periods.edit', $period) }}"
                                                    class="inline-flex items-center justify-center p-2.5 bg-[#84b6f4] hover:bg-[#4d82bc] text-white rounded-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#84b6f4] focus:ring-offset-1"
                                                    title="Editar período">
                                                    <img src="{{ asset('icons/editw.svg') }}" alt="" class="w-5 h-5">
                                                </a>

                                                {{-- Botón Eliminar --}}
                                                <form action="{{ route('periods.destroy', $period) }}" method="POST" class="form-eliminar inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="inline-flex items-center justify-center p-2.5 bg-[#e57373] hover:bg-[#d32f2f] text-white rounded-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1"
                                                            title="Eliminar período">
                                                        <img src="{{ asset('icons/trashw.svg') }}" alt="" class="w-5 h-5">
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</x-app-layout>





