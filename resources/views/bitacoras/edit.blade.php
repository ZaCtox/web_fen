@section('title', 'Editar Bitácora')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            ✏️ Editar Bitácora
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <form action="{{ route('bitacoras.update', $bitacora) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Ubicación --}}
                <select id="ubicacion" name="ubicacion" class="...">
                    <option value="Sala" {{ $bitacora->ubicacion == 'Sala' ? 'selected' : '' }}>Sala</option>
                    <option value="Baño" {{ $bitacora->ubicacion == 'Baño' ? 'selected' : '' }}>Baño</option>
                    <option value="Pasillo" {{ $bitacora->ubicacion == 'Pasillo' ? 'selected' : '' }}>Pasillo</option>
                    <option value="Otro" {{ $bitacora->ubicacion == 'Otro' ? 'selected' : '' }}>Otro</option>
                </select>

                {{-- Rooms --}}
                <div id="salaField" class="mb-4 {{ $bitacora->ubicacion == 'Sala' ? '' : 'hidden' }}">
                    <label class="block text-gray-700 dark:text-gray-300">Sala</label>
                    <select name="room_id" class="...">
                        <option value="">Seleccione una sala</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ $bitacora->room_id == $room->id ? 'selected' : '' }}>
                                {{ $room->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Detalle --}}
                <div id="detalleUbicacionField"
                    class="mb-4 {{ in_array($bitacora->ubicacion, ['Baño', 'Pasillo', 'Otro']) ? '' : 'hidden' }}">
                    <label class="block text-gray-700 dark:text-gray-300">Detalle de ubicación</label>
                    <input type="text" name="detalle_ubicacion" value="{{ $bitacora->detalle_ubicacion }}"
                        class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-900 dark:text-gray-200">
                </div>
            </form>

        </div>
    </div>
</x-app-layout>