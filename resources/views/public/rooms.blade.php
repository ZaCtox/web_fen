{{-- Salas de Postgrado FEN --}}
@section('title', 'Salas')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Información de salas
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4">
        <div x-data="{ search: '' }" class="bg-[#fcffff] dark:bg-gray-800 rounded-lg shadow-lg p-6 border border-[#c4dafa]">
            
            {{-- Buscador --}}
            <input type="text" 
                x-model="search" 
                placeholder="Buscar sala por nombre..."
                class="w-full mb-4 px-3 py-2 border border-[#4d82bc] rounded focus:outline-none focus:ring-2 focus:ring-[#005187] dark:bg-gray-700 dark:text-white">

            {{-- Tabla --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                    <thead class="bg-[#c4dafa] dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-center font-semibold text-[#005187]">Nombre</th>
                            <th class="px-4 py-2 text-center font-semibold text-[#005187]">Ubicación</th>
                            <th class="px-4 py-2 text-center font-semibold text-[#005187]">Capacidad</th>
                            <th class="px-4 py-2 text-center font-semibold text-[#005187]">Descripción</th>
                            <th class="px-4 py-2 text-center font-semibold text-[#005187]">Ver ficha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rooms as $room)
                            <tr class="border-b border-gray-200 dark:border-gray-600 hover:bg-[#f0f6ff] dark:hover:bg-gray-700 transition">
                                <td class="px-4 py-2 text-center">{{ $room->name }}</td>
                                <td class="px-4 py-2 text-center">{{ $room->location }}</td>
                                <td class="px-4 py-2 text-center">{{ $room->capacity }}</td>
                                <td class="px-4 py-2 text-center">{{ $room->description }}</td>
                                <td class="px-4 py-2 text-center">
                                    <a href="{{ route('public.rooms.show', $room->id) }}"
                                        class="inline-flex items-center justify-center px-3 py-2 rounded-full hover:bg-[#005187] text-white shadow transition-all duration-200"
                                        title="Ver ficha de la sala">
                                        📄
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer>
        @include('components.footer')
    </footer>
</x-app-layout>
