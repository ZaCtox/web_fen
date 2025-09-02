{{-- Inicio de Periodo Académico.blade.php --}}
@section('title', 'Periodos Académicos')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Períodos Académicos</h2>
    </x-slot>

    <div class="p-6">
        <a href="{{ route('periods.create') }}"
           class="mb-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            + Nuevo período
        </a>

        {{-- Botón que confirma con SweetAlert y envía el form oculto --}}
        <form id="form-actualizar-proximo-anio" method="POST" action="{{ route('periods.actualizarProximoAnio') }}" class="hidden">
            @csrf
        </form>
        <button
            type="button"
            onclick="
                Swal.fire({
                    title:'¿Actualizar fechas al próximo año?',
                    text:'Se sumará 1 año a las fechas de inicio y término de TODOS los períodos. No se modifica el número de trimestre ni el año académico.',
                    icon:'warning',
                    showCancelButton:true,
                    confirmButtonColor:'#16a34a',
                    cancelButtonColor:'#6b7280',
                    confirmButtonText:'Sí, actualizar',
                    cancelButtonText:'Cancelar'
                }).then((r)=>{ if(r.isConfirmed) document.getElementById('form-actualizar-proximo-anio').submit(); });
            "
            class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded text-sm">
            🔄 Actualizar fechas al próximo año
        </button>

        @php
            $romanos = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI'];
            $agrupados = $periods->sortBy([['anio','asc'],['numero','asc']])->groupBy('anio');
        @endphp

        @foreach ($agrupados as $anio => $porTrimestre)
            <div class="mt-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">🗓️ Año {{ $anio }}</h3>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto text-sm text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left">Trimestre</th>
                                <th class="px-4 py-2 text-left">Inicio</th>
                                <th class="px-4 py-2 text-left">Término</th>
                                <th class="px-4 py-2 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($porTrimestre->sortBy('numero') as $period)
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <td class="px-4 py-2">Trimestre {{ $romanos[$period->numero] ?? $period->numero }}</td>
                                    <td class="px-4 py-2">{{ $period->fecha_inicio->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2">{{ $period->fecha_fin->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2">
                                        <div class="flex flex-col sm:flex-row sm:justify-start gap-2">
                                            <a href="{{ route('periods.edit', $period) }}"
                                               class="inline-flex items-center justify-center px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded text-xs w-full sm:w-auto">
                                                ✏️
                                            </a>
                                            {{-- Usar la clase form-eliminar para que alerts.js lance el Swal de confirmación --}}
                                            <form action="{{ route('periods.destroy', $period) }}" method="POST" class="form-eliminar inline w-full sm:w-auto">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center px-3 py-1 bg-red-100 text-red-700 hover:bg-red-200 rounded text-xs w-full sm:w-auto">
                                                    🗑️
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
    </div>
</x-app-layout>
