@section('title', 'Nueva Bitácora')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            ➕ Nueva Bitácora
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <form action="{{ route('bitacoras.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Título --}}
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300">Título</label>
                    <input type="text" name="titulo" class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-900 dark:text-gray-200" required>
                </div>

                {{-- Descripción --}}
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300">Descripción</label>
                    <textarea name="descripcion" rows="4" class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-900 dark:text-gray-200"></textarea>
                </div>

                {{-- Ubicación --}}
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300">Ubicación</label>
                    <select id="ubicacion" name="ubicacion" 
                            class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-900 dark:text-gray-200" required>
                        <option value="">Seleccione ubicación</option>
                        <option value="Sala">Sala</option>
                        <option value="Baño">Baño</option>
                        <option value="Pasillo">Pasillo</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                {{-- Si elige Sala -> mostrar rooms --}}
                <div id="salaField" class="mb-4 hidden">
                    <label class="block text-gray-700 dark:text-gray-300">Sala</label>
                    <select name="room_id" class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-900 dark:text-gray-200">
                        <option value="">Seleccione una sala</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Si elige Baño, Pasillo u Otro -> mostrar detalle --}}
                <div id="detalleUbicacionField" class="mb-4 hidden">
                    <label class="block text-gray-700 dark:text-gray-300">Detalle de ubicación</label>
                    <input type="text" name="detalle_ubicacion" 
                           class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-900 dark:text-gray-200"
                           placeholder="Ej: Baño primer piso, Pasillo edificio A">
                </div>

                {{-- Foto --}}
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300">Foto (Cloudinary)</label>
                    <input type="file" name="foto" accept="image/*" class="mt-1">
                </div>

                {{-- Botones --}}
                <div class="flex space-x-3 mt-6">
                    <a href="{{ route('bitacoras.index') }}"
                       class="px-4 py-2 bg-gray-500 text-white rounded-lg shadow hover:bg-gray-600 transition">
                        🔙 Cancelar
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                        💾 Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script para mostrar campos dinámicos --}}
    <script>
        document.getElementById('ubicacion').addEventListener('change', function () {
            const salaField = document.getElementById('salaField');
            const detalleField = document.getElementById('detalleUbicacionField');

            salaField.classList.add('hidden');
            detalleField.classList.add('hidden');

            if (this.value === 'Sala') {
                salaField.classList.remove('hidden');
            } else if (['Baño', 'Pasillo', 'Otro'].includes(this.value)) {
                detalleField.classList.remove('hidden');
            }
        });
    </script>
</x-app-layout>
