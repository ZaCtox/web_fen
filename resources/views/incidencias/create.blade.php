{{-- Crear Incidencias.blade.php --}}
@section('title', 'Crear Incidencia')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#005187] dark:text-white leading-tight">
            Registrar Nueva Incidencia
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#fcffff] dark:bg-gray-800 shadow-md sm:rounded-lg p-6">

                {{-- Errores --}}
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                        <strong>Error:</strong> Por favor corrige los siguientes errores.
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('incidencias.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Título --}}
                    <div class="mb-4">
                        <label for="titulo" class="block text-sm font-medium text-[#005187] dark:text-gray-200">
                            Título
                        </label>
                        <input type="text" name="titulo" value="{{ old('titulo') }}" class="mt-1 block w-full rounded-md border-[#84b6f4] shadow-sm 
                               focus:border-[#4d82bc] focus:ring-[#4d82bc]
                               dark:bg-gray-700 dark:text-white" required>
                    </div>

                    {{-- Descripción --}}
                    <div class="mb-4">
                        <label for="descripcion" class="block text-sm font-medium text-[#005187] dark:text-gray-200">
                            Descripción del problema
                        </label>
                        <textarea name="descripcion" rows="4" required class="mt-1 block w-full rounded-md border-[#84b6f4] shadow-sm 
                               focus:border-[#4d82bc] focus:ring-[#4d82bc]
                               dark:bg-gray-700 dark:text-white">{{ old('descripcion') }}</textarea>
                    </div>

                    {{-- Sala --}}
                    <div class="mb-4">
                        <label for="room_id" class="block text-sm font-medium text-[#005187] dark:text-gray-200">
                            Sala afectada
                        </label>
                        <select name="room_id" id="room_id" required class="mt-1 block w-full rounded-md border-[#84b6f4] shadow-sm 
                               focus:border-[#4d82bc] focus:ring-[#4d82bc]
                               dark:bg-gray-700 dark:text-white">
                            <option value="">Selecciona una sala</option>
                            @foreach($salas as $sala)
                                <option value="{{ $sala->id }}" {{ old('room_id') == $sala->id ? 'selected' : '' }}>
                                    {{ $sala->name }} ({{ $sala->location }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Imagen --}}
                    <div class="mb-4">
                        <label for="imagen" class="block text-sm font-medium text-[#005187] dark:text-gray-200">
                            Foto del problema (opcional)
                        </label>
                        <input type="file" name="imagen" accept="image/*" class="mt-1 block w-full text-sm text-gray-600 dark:text-gray-300
                               file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 
                               file:text-sm file:font-semibold
                               file:bg-[#e6f0fa] file:text-[#005187] hover:file:bg-[#d0e4f7]">
                    </div>

                    {{-- Usuario --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-[#005187] dark:text-gray-200">
                            Registrado por
                        </label>
                        <input type="text" value="{{ Auth::user()->name }}" disabled class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 rounded-md border-[#84b6f4] 
                               shadow-sm text-gray-600 dark:text-gray-300">
                    </div>

                    {{-- Ticket Jira --}}
                    <div class="mb-4">
                        <label for="nro_ticket" class="block text-sm font-medium text-[#005187] dark:text-gray-200">
                            N° Ticket Jira (opcional)
                        </label>
                        <input type="text" name="nro_ticket" id="nro_ticket" value="{{ old('nro_ticket') }}" class="mt-1 block w-full rounded-md border-[#84b6f4] shadow-sm
                               focus:border-[#4d82bc] focus:ring-[#4d82bc]
                               dark:bg-gray-700 dark:text-white" placeholder="Ej: 2364552">
                    </div>

                    {{-- Botones --}}
                    <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                        {{-- Cancelar --}}
                        <a href="{{ route('incidencias.index') }}"
                            class="inline-flex items-center px-5 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow text-sm font-medium 
                                   transition transform hover:scale-105">
                            <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-5 h-5">
                        </a>

                        {{-- Guardar --}}
                        <button type="submit" class="inline-flex items-center justify-center bg-[#4d82bc] hover:bg-[#005187] 
                               text-white px-4 py-2 rounded-lg shadow text-sm font-medium 
                               transition transform hover:scale-105">
                            <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-5 h-5">
                            <span class="ml-2">Guardar Incidencia</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
