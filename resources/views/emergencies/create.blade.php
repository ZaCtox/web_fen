{{-- Crear Emergencia.blade.php --}}
@section('title', 'Crear Emergencia')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Nueva Emergencia</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto px-4">
        <div class="bg-[#fcffff] dark:bg-gray-900 shadow-lg rounded-lg p-6 border border-[#c4dafa] dark:border-gray-700 transition">
            <form action="{{ route('emergencies.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Título --}}
                <div>
                    <label class="block text-sm font-semibold mb-2 text-[#005187] dark:text-[#84b6f4]">Título</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        class="w-full rounded-md border border-[#c4dafa] dark:border-gray-700 dark:bg-gray-800 dark:text-white px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#84b6f4] transition"
                        maxlength="100" required>
                    @error('title') 
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                {{-- Mensaje --}}
                <div>
                    <label class="block text-sm font-semibold mb-2 text-[#005187] dark:text-[#84b6f4]">Mensaje</label>
                    <textarea name="message" rows="5"
                        class="w-full rounded-md border border-[#c4dafa] dark:border-gray-700 dark:bg-gray-800 dark:text-white px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#84b6f4] transition"
                        required>{{ old('message') }}</textarea>
                    @error('message') 
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="flex items-center justify-between mt-6">
                    {{-- Cancelar a la izquierda --}}
                    <a href="{{ route('emergencies.index') }}"
                        class="inline-flex items-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-md shadow-md font-medium transition transform hover:scale-105">
                        <img src="{{ asset('icons/back.svg') }}" alt="back" class="w-5 h-5">
                    </a>

                    {{-- Guardar a la derecha --}}
                    <button type="submit"
                        class="inline-flex items-center justify-center bg-[#005187] hover:bg-[#4d82bc] text-white px-4 py-2 rounded-lg shadow-md text-sm font-medium transition transform hover:scale-105 gap-2">
                        <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-5 h-5">
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
