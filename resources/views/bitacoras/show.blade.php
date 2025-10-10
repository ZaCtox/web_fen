{{-- resources/views/bitacoras/show.blade.php --}}
@section('title', 'Detalle de Bitácora')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Detalle de Bitácora
        </h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Bitácoras', 'url' => route('bitacoras.index')],
        ['label' => 'Detalle de Bitácora', 'url' => '#']
    ]" />

    <div class="p-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            {{-- Título --}}
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                {{ $bitacora->titulo }}
            </h3>

            {{-- Descripción --}}
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                {{ $bitacora->descripcion }}
            </p>

            {{-- Sala / Baño / Área --}}
            <div class="mb-4">
                <span class="font-semibold text-gray-800 dark:text-gray-200">Ubicación:</span>
                <span class="text-gray-600 dark:text-gray-400">
                    {{ $bitacora->ubicacion ?? 'No especificada' }}
                </span>
            </div>

            {{-- Foto en Cloudinary --}}
            @if($bitacora->foto_url)
                <div class="mb-4">
                    <span class="font-semibold text-gray-800 dark:text-gray-200">Evidencia:</span>
                    <div class="mt-2">
                        <img src="{{ $bitacora->foto_url }}" alt="Foto evidencia" class="rounded-lg max-h-64 shadow">
                    </div>
                </div>
            @endif

            {{-- Fecha de creación --}}
            <div class="mb-4">
                <span class="font-semibold text-gray-800 dark:text-gray-200">Creado el:</span>
                <span class="text-gray-600 dark:text-gray-400">
                    {{ $bitacora->created_at->format('d/m/Y H:i') }}
                </span>
            </div>

            {{-- Botones --}}
            <div class="flex flex-wrap gap-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                <a href="{{ route('bitacoras.index') }}"
                   class="hci-button hci-lift hci-focus-ring inline-flex items-center gap-2 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg shadow transition-all duration-200">
                    <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-4 h-4">
                    <span>Volver</span>
                </a>

                <a href="{{ route('bitacoras.edit', $bitacora) }}"
                   class="hci-button hci-lift hci-focus-ring inline-flex items-center gap-2 px-4 py-2 bg-[#005187] hover:bg-[#4d82bc] text-white rounded-lg shadow transition-all duration-200">
                    <img src="{{ asset('icons/edit.svg') }}" alt="Editar" class="w-4 h-4">
                    <span>Editar</span>
                </a>

                @if($bitacora->pdf_path)
                <a href="{{ route('bitacoras.download', $bitacora) }}"
                   class="hci-button hci-lift hci-focus-ring inline-flex items-center gap-2 px-4 py-2 bg-[#005187] hover:bg-[#4d82bc] text-white rounded-lg shadow transition-all duration-200">
                    <img src="{{ asset('icons/download.svg') }}" alt="Descargar PDF" class="w-5 h-5">
                    <span>Descargar PDF</span>
                </a>
                @endif

                <button type="button" 
                        onclick="confirmarEliminacion('{{ route('bitacoras.destroy', $bitacora) }}', '{{ $bitacora->titulo ?? 'este reporte' }}')"
                        class="hci-button hci-lift hci-focus-ring inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow transition-all duration-200">
                    <img src="{{ asset('icons/trash.svg') }}" alt="Eliminar" class="w-4 h-4">
                    <span>Eliminar</span>
                </button>
            </div>
        </div>
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



