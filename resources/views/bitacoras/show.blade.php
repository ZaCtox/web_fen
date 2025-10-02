{{-- resources/views/bitacoras/show.blade.php --}}
@section('title', 'Detalle de Bitácora')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Detalle de Bitácora
        </h2>
    </x-slot>

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
            <div class="flex space-x-3 mt-6">
                <a href="{{ route('bitacoras.index') }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded-lg shadow hover:bg-gray-600 transition">
                    🔙 Volver
                </a>

                <a href="{{ route('bitacoras.edit', $bitacora) }}"
                   class="px-4 py-2 bg-[#005187] text-white rounded-lg shadow hover:bg-[#003c63] transition">
                    ✏️ Editar
                </a>

                <form action="{{ route('bitacoras.destroy', $bitacora) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar este registro?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-700 transition">
                        🗑️ Eliminar
                    </button>
                </form>

                <a href="{{ route('bitacoras.exportarPDF', $bitacora) }}"
                   class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                    📄 Exportar PDF
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
