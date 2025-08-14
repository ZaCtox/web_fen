<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Registrar Nueva Incidencia
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <strong>Error:</strong> Por favor corrige los siguientes errores.
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('incidencias.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="titulo"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200">Título</label>
                        <input type="text" name="titulo" value="{{ old('titulo') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Descripción del problema
                        </label>
                        <textarea name="descripcion" rows="4" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="room_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Sala afectada
                        </label>
                        <select name="room_id" id="room_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-white">
                            <option value="">Selecciona una sala</option>
                            @foreach($salas as $sala)
                                <option value="{{ $sala->id }}" {{ old('room_id') == $sala->id ? 'selected' : '' }}>
                                    {{ $sala->name }} ({{ $sala->location }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="imagen" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Foto del problema (opcional)
                        </label>
                        <input type="file" name="imagen" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                                      file:rounded-md file:border-0 file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Registrado por
                        </label>
                        <input type="text" value="{{ Auth::user()->name }}" disabled
                            class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 rounded-md border-gray-300 shadow-sm text-gray-600 dark:text-gray-300">
                    </div>
                    <div>
                        <label for="nro_ticket" class="block text-sm font-medium text-gray-700 dark:text-gray-300">N°
                            Ticket UTALCA (opcional)</label>
                        <input type="text" name="nro_ticket" id="nro_ticket" value="{{ old('nro_ticket') }}"
                            class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                            placeholder="Ej: 2364552">
                    </div>

                    <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <a href="{{ route('incidencias.index') }}"
                            class="inline-flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-600 px-4 py-2 rounded shadow text-sm font-medium transition">
                            ← Cancelar y volver
                        </a>

                        <button type="submit"
                            class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-sm font-medium transition">
                            💾 Guardar Incidencia
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>