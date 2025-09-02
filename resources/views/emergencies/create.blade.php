{{-- Crear Emergencia.blade.php --}}
@section('title', 'Crear Emergencia')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Nueva Emergencia</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto px-4">
        <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
            <form action="{{ route('emergencies.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1">Título</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700" maxlength="100"
                        required>
                    @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Mensaje</label>
                    <textarea name="message" rows="5"
                        class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700"
                        required>{{ old('message') }}</textarea>
                    @error('message') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('emergencies.index') }}"
                        class="px-4 py-2 rounded bg-gray-200 text-gray-800 hover:bg-gray-300">Cancelar</a>
                    <button class="px-4 py-2 rounded bg-emerald-600 text-white hover:bg-emerald-700">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>