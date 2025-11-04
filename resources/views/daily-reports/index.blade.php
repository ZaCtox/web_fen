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
        <!-- Controles -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            @if(tieneRol(['asistente_postgrado','decano']))
            <a href="{{ route('daily-reports.create') }}"
                class="inline-flex items-center justify-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white px-6 py-3 rounded-lg shadow-md transition-all duration-200 font-semibold text-sm hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 hci-button-ripple hci-glow"
                aria-label="Agregar nuevo reporte diario">
                <img src="{{ asset('icons/agregar.svg') }}" alt="Agregar" class="w-5 h-5">
                Nuevo Reporte
            </a>
            @endif
            
            <div class="flex gap-3 items-center w-full sm:w-auto">
                <div class="relative flex-1 sm:flex-initial">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <img src="{{ asset('icons/filtro.svg') }}" alt="" class="h-5 w-5 opacity-60">
                    </div>
                    <input type="text" 
                           id="search-input"
                           role="search"
                           aria-label="Buscar reportes por título o creado por"
                           placeholder="Buscar reportes..."
                           class="w-full sm:w-[350px] pl-10 pr-4 py-3 rounded-lg border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition hci-input-focus">
                </div>
                
                <select id="sort-select" 
                        class="px-4 py-3 pr-10 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[160px] hci-focus-ring"
                        aria-label="Ordenar reportes">
                    <option value="fecha_desc">Fecha más reciente</option>
                    <option value="fecha_asc">Fecha más antigua</option>
                    <option value="titulo_asc">Título A-Z</option>
                    <option value="titulo_desc">Título Z-A</option>
                    <option value="entradas_desc">Más entradas</option>
                    <option value="entradas_asc">Menos entradas</option>
                </select>
                
                <button type="button" 
                        id="clear-filters"
                        class="p-3 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 hover:scale-105 hci-button-ripple hci-glow"
                        title="Limpiar búsqueda y ordenamiento"
                        aria-label="Limpiar búsqueda y ordenamiento">
                    <img src="{{ asset('icons/filterw.svg') }}" alt="Limpiar" class="w-5 h-5">
                </button>
            </div>
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
                        <thead class="bg-[#c4dafa]/50 dark:bg-gray-700 text-[#005187] dark:text-[#84b6f4]">
                            <tr>
                                <th class="px-4 py-3 font-medium">Título</th>
                                <th class="px-4 py-3 font-medium">Fecha</th>
                                <th class="px-4 py-3 font-medium">Entradas</th>
                                <th class="px-4 py-3 font-medium">Creado por</th>
                                <th class="px-4 py-3 font-medium">PDF</th>
                                <th class="px-4 py-3 text-right w-40">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                <tr class="border-b border-[#c4dafa]/60 dark:border-gray-600 
                                           hover:bg-[#e3f2fd] dark:hover:bg-gray-700 
                                           hover:border-l-4 hover:border-l-[#4d82bc]
                                           hover:-translate-y-0.5 hover:shadow-md
                                           transition-all duration-200 group cursor-pointer"
                                    onclick="window.location='{{ route('daily-reports.show', $report) }}'">
                                    <td class="px-4 py-3 font-medium group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-[#4d82bc]" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $report->title }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $report->fecha_formateada }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $report->numero_entradas }} {{ $report->numero_entradas === 1 ? 'entrada' : 'entradas' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $report->user->name }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($report->tiene_pdf)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Disponible
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                No disponible
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-2" onclick="event.stopPropagation()">
                                            @if(tieneRol(['asistente_postgrado','decano']))
                                            <a href="{{ route('daily-reports.edit', $report) }}" 
                                               class="inline-flex items-center justify-center p-2.5 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-1"
                                               title="Editar reporte">
                                                <img src="{{ asset('icons/editw.svg') }}" alt="Editar" class="w-5 h-5">
                                            </a>
                                            @endif
                                            @if($report->tiene_pdf)
                                            <a href="{{ route('daily-reports.download', $report) }}" 
                                               class="inline-flex items-center justify-center p-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1"
                                               title="Descargar PDF">
                                                <img src="{{ asset('icons/download.svg') }}" alt="Descargar" class="w-5 h-5">
                                            </a>
                                            @endif
                                            @if(tieneRol(['asistente_postgrado','decano']))
                                            <form action="{{ route('daily-reports.destroy', $report) }}" method="POST" class="form-eliminar inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center justify-center p-2.5 bg-[#e57373] hover:bg-[#d32f2f] text-white rounded-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1"
                                                        title="Eliminar reporte">
                                                    <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar" class="w-5 h-5">
                                                </button>
                                            </form>
                                            @endif
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
        // Funcionalidad de búsqueda y filtrado
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const sortSelect = document.getElementById('sort-select');
            const clearFiltersBtn = document.getElementById('clear-filters');
            const tableRows = document.querySelectorAll('tbody tr');

            function filterAndSort() {
                const searchTerm = searchInput.value.toLowerCase();
                const sortValue = sortSelect.value;
                
                let visibleRows = [];

                // Filtrar filas
                tableRows.forEach(row => {
                    const title = row.querySelector('td:first-child')?.textContent.toLowerCase() || '';
                    const fecha = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
                    const creadoPor = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
                    
                    const matches = !searchTerm || 
                                  title.includes(searchTerm) || 
                                  fecha.includes(searchTerm) || 
                                  creadoPor.includes(searchTerm);
                    
                    if (matches) {
                        visibleRows.push(row);
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Ordenar filas visibles
                visibleRows.sort((a, b) => {
                    let aValue, bValue;
                    
                    switch(sortValue) {
                        case 'fecha_desc':
                            aValue = a.querySelector('td:nth-child(2)')?.textContent || '';
                            bValue = b.querySelector('td:nth-child(2)')?.textContent || '';
                            return bValue.localeCompare(aValue);
                        
                        case 'fecha_asc':
                            aValue = a.querySelector('td:nth-child(2)')?.textContent || '';
                            bValue = b.querySelector('td:nth-child(2)')?.textContent || '';
                            return aValue.localeCompare(bValue);
                        
                        case 'titulo_asc':
                            aValue = a.querySelector('td:first-child')?.textContent || '';
                            bValue = b.querySelector('td:first-child')?.textContent || '';
                            return aValue.localeCompare(bValue);
                        
                        case 'titulo_desc':
                            aValue = a.querySelector('td:first-child')?.textContent || '';
                            bValue = b.querySelector('td:first-child')?.textContent || '';
                            return bValue.localeCompare(aValue);
                        
                        case 'entradas_desc':
                            aValue = parseInt(a.querySelector('td:nth-child(3)')?.textContent || '0');
                            bValue = parseInt(b.querySelector('td:nth-child(3)')?.textContent || '0');
                            return bValue - aValue;
                        
                        case 'entradas_asc':
                            aValue = parseInt(a.querySelector('td:nth-child(3)')?.textContent || '0');
                            bValue = parseInt(b.querySelector('td:nth-child(3)')?.textContent || '0');
                            return aValue - bValue;
                        
                        default:
                            return 0;
                    }
                });

                // Reordenar en el DOM
                const tbody = document.querySelector('tbody');
                visibleRows.forEach(row => tbody.appendChild(row));
            }

            // Event listeners
            searchInput.addEventListener('input', filterAndSort);
            sortSelect.addEventListener('change', filterAndSort);
            
            clearFiltersBtn.addEventListener('click', function() {
                searchInput.value = '';
                sortSelect.value = 'fecha_desc';
                filterAndSort();
            });
        });
    </script>
    @endpush
</x-app-layout>



