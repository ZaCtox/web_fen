{{-- resources/views/daily-reports/index.blade.php --}}
@section('title', 'Reportes Diarios')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Reportes Diarios</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Reportes Diarios', 'url' => '#']
    ]" />

    <div class="p-6">
        <div class="mb-4 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Lista de Reportes Diarios</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Gestiona los reportes diarios de observaciones</p>
            </div>
            <a href="{{ route('daily-reports.create') }}"
               class="hci-button hci-lift hci-focus-ring inline-flex items-center gap-2 bg-[#005187] hover:bg-[#4d82bc] text-white font-medium px-4 py-2 rounded-lg shadow transition-all duration-200">
                <img src="{{ asset('icons/agregar.svg') }}" alt="Agregar" class="w-5 h-5">
                <span>Nuevo Reporte</span>
            </a>
        </div>

        @if($reports->isEmpty())
            <x-empty-state 
                type="no-data"
                title="No hay reportes diarios registrados"
                message="Comienza creando tu primer reporte diario para registrar todas las observaciones del día."
                :action="['url' => route('daily-reports.create'), 'text' => 'Crear Primer Reporte']"
            />
        @else
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-3 font-medium">Título</th>
                                <th class="px-4 py-3 font-medium">Fecha</th>
                                <th class="px-4 py-3 font-medium">Entradas</th>
                                <th class="px-4 py-3 font-medium">Creado por</th>
                                <th class="px-4 py-3 font-medium">PDF</th>
                                <th class="px-4 py-3 font-medium">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                <tr class="border-b border-gray-200 dark:border-gray-600 
                                           hover:bg-[#e3f2fd] dark:hover:bg-gray-700 
                                           hover:border-l-4 hover:border-l-[#4d82bc]
                                           hover:-translate-y-0.5 hover:shadow-md
                                           transition-all duration-200 group cursor-pointer">
                                    <td class="px-4 py-3 font-medium group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200">
                                        {{ $report->title }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                        {{ $report->fecha_formateada }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ $report->numero_entradas }} {{ $report->numero_entradas === 1 ? 'entrada' : 'entradas' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                        {{ $report->user->name }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($report->tiene_pdf)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Disponible
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                No disponible
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            <a href="{{ route('daily-reports.show', $report) }}" 
                                               class="hci-button hci-lift hci-focus-ring p-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-all duration-200"
                                               title="Ver detalles">
                                                <img src="{{ asset('icons/ver.svg') }}" alt="Ver" class="w-4 h-4">
                                            </a>
                                            <a href="{{ route('daily-reports.edit', $report) }}" 
                                               class="hci-button hci-lift hci-focus-ring p-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-all duration-200"
                                               title="Editar">
                                                <img src="{{ asset('icons/edit.svg') }}" alt="Editar" class="w-4 h-4">
                                            </a>
                                            @if($report->tiene_pdf)
                                            <x-action-button 
                                                variant="download" 
                                                type="link" 
                                                :href="route('daily-reports.download', $report)" 
                                                tooltip="Descargar PDF" />
                                            @endif
                                            <button type="button" 
                                                    onclick="confirmarEliminacion('{{ route('daily-reports.destroy', $report) }}', '{{ $report->title }}')"
                                                    class="hci-button hci-lift hci-focus-ring p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all duration-200"
                                                    title="Eliminar">
                                                <img src="{{ asset('icons/trash.svg') }}" alt="Eliminar" class="w-4 h-4">
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $reports->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function confirmarEliminacion(url, titulo) {
            Swal.fire({
                title: '¿Eliminar reporte?',
                text: `¿Estás seguro de que quieres eliminar "${titulo}"? Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'hci-button hci-lift hci-focus-ring px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all duration-200',
                    cancelButton: 'hci-button hci-lift hci-focus-ring px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-all duration-200'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
