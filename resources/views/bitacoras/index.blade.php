{{-- resources/views/bitacoras/index.blade.php --}}
@section('title', 'Bitácoras')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Bitácoras</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Bitácoras', 'url' => '#']
    ]" />

    <div class="p-6">
        <div class="mb-4 flex justify-end">
            <a href="{{ route('bitacoras.create') }}"
               class="hci-button hci-lift hci-focus-ring inline-flex items-center gap-2 bg-[#005187] hover:bg-[#4d82bc] text-white font-medium px-4 py-2 rounded-lg shadow transition-all duration-200">
                <img src="{{ asset('icons/agregar.svg') }}" alt="Agregar" class="w-5 h-5">
                <span>Nuevo Reporte</span>
            </a>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-2">Lugar</th>
                        <th class="px-4 py-2">Descripción</th>
                        <th class="px-4 py-2">Foto</th>
                        <th class="px-4 py-2">PDF</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bitacoras as $bitacora)
                        <tr class="border-b dark:border-gray-700 
                                   hover:bg-[#e3f2fd] dark:hover:bg-gray-700 
                                   hover:border-l-4 hover:border-l-[#4d82bc]
                                   hover:-translate-y-0.5 hover:shadow-md
                                   transition-all duration-200 group cursor-pointer">
                            <td class="px-4 py-2 font-medium group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200">
                                @if($bitacora->room)
                                    {{ $bitacora->room->name }}
                                @else
                                    {{ $bitacora->detalle_ubicacion ?? $bitacora->lugar }}
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ Str::limit($bitacora->descripcion, 50) }}</td>
                            <td class="px-4 py-2">
                                @if($bitacora->foto_url)
                                    <a href="{{ $bitacora->foto_url }}" target="_blank" class="text-blue-500 underline">Ver</a>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if($bitacora->pdf_path)
                                    <a href="{{ route('bitacoras.download', $bitacora) }}" class="text-green-600 underline">Descargar</a>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    {{-- Botón Ver --}}
                                    <x-action-button 
                                        variant="view" 
                                        type="link" 
                                        :href="route('bitacoras.show', $bitacora)" 
                                        icon="ver.svg"
                                        tooltip="Ver detalles" />

                                    {{-- Botón Editar --}}
                                    <x-action-button 
                                        variant="warning" 
                                        type="link" 
                                        :href="route('bitacoras.edit', $bitacora)" 
                                        tooltip="Editar" />

                                    {{-- Botón Eliminar --}}
                                    <button type="button" 
                                            onclick="confirmarEliminacion('{{ route('bitacoras.destroy', $bitacora) }}', '{{ $bitacora->titulo ?? 'este reporte' }}')"
                                            class="inline-flex items-center justify-center w-10 px-3 py-2 bg-[#e57373] hover:bg-[#f28b82] text-white rounded-lg text-xs font-medium transition"
                                            title="Eliminar">
                                        <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar" class="w-3 h-3">
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($bitacoras->isEmpty())
            <x-empty-state 
                type="no-data"
                title="No hay reportes registrados"
                message="Comienza creando tu primer reporte de bitácora para registrar observaciones y evidencias."
                :action="['url' => route('bitacoras.create'), 'text' => 'Crear Primer Reporte']"
            />
        @else
            <div class="mt-4">
                {{ $bitacoras->links() }}
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
