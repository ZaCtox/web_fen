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
               class="inline-flex items-center gap-2 bg-[#005187] hover:bg-[#4d82bc] text-white font-medium px-4 py-2 rounded-lg shadow transition transform hover:scale-105">
                    <img src="{{ asset('icons/agregar.svg') }}" alt="Agregar" class="w-5 h-5">
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
                                    {{ $bitacora->room->nombre }}
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
                            <td class="px-4 py-2 flex gap-2">
                                <a href="{{ route('bitacoras.show', $bitacora) }}" class="px-2 py-1 bg-blue-500 text-white rounded">👁</a>
                                <a href="{{ route('bitacoras.edit', $bitacora) }}" class="px-2 py-1 bg-yellow-500 text-white rounded">✏</a>
                                <form action="{{ route('bitacoras.destroy', $bitacora) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded">🗑</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $bitacoras->links() }}
        </div>
    </div>
</x-app-layout>
