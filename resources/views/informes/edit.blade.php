{{-- Editar Informe --}}
@section('title', 'Editar Informe')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Editar Informe</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto px-4">
        <div class="bg-[#fcffff] dark:bg-gray-900 shadow-lg rounded-lg p-6 border border-[#c4dafa] dark:border-gray-700 transition">
            <form action="{{ route('informes.update', $informe->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nombre --}}
                <div>
                    <label class="block text-sm font-semibold mb-2 text-[#005187] dark:text-[#84b6f4]">Nombre del Informe</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $informe->nombre) }}"
                        class="w-full rounded-md border border-[#c4dafa] dark:border-gray-700 dark:bg-gray-800 dark:text-white px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#84b6f4] transition"
                        maxlength="150" required>
                    @error('nombre') 
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                {{-- Archivo --}}
                <div>
                    <label class="block text-sm font-semibold mb-2 text-[#005187] dark:text-[#84b6f4]">Archivo</label>
                    <input type="file" name="archivo" 
                        class="w-full text-sm text-gray-600 dark:text-gray-300 border border-[#c4dafa] dark:border-gray-700 rounded-md cursor-pointer bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-[#84b6f4] transition">
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Sube uno nuevo solo si deseas reemplazar el actual.</p>
                    @error('archivo') 
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                {{-- Magíster --}}
                <div>
                    <label class="block text-sm font-semibold mb-2 text-[#005187] dark:text-[#84b6f4]">Dirigido a</label>
                    <select name="magister_id"
                        class="w-full rounded-md border border-[#c4dafa] dark:border-gray-700 dark:bg-gray-800 dark:text-white px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#84b6f4] transition">
                        <option value="">Todos</option>
                        @foreach($magisters as $magister)
                            <option value="{{ $magister->id }}" {{ $informe->magister_id == $magister->id ? 'selected' : '' }}>{{ $magister->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Botones --}}
                <div class="flex items-center justify-between mt-6">
                    {{-- Cancelar --}}
                    <a href="{{ route('informes.index') }}"
                        class="inline-flex items-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-md shadow-md font-medium transition transform hover:scale-105">
                        <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-5 h-5">
                    </a>

                    {{-- Guardar --}}
                    <button type="submit"
                        class="inline-flex items-center justify-center bg-[#005187] hover:bg-[#4d82bc] text-white px-4 py-2 rounded-lg shadow-md text-sm font-medium transition transform hover:scale-105 gap-2">
                        <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-5 h-5">
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
