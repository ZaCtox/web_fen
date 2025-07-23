<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            {{ isset($magister) ? 'Editar Magíster' : 'Crear Magíster' }}
        </h2>
    </x-slot>

    <div class="p-6 max-w-xl mx-auto">
        <form action="{{ isset($magister) ? route('magisters.update', $magister) : route('magisters.store') }}" method="POST">
            @csrf
            @if(isset($magister)) @method('PUT') @endif

            <div class="mb-4">
                <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nombre del Magíster
                </label>
                <input type="text" name="nombre" id="nombre"
                       value="{{ old('nombre', $magister->nombre ?? '') }}"
                       required class="mt-1 block w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                {{ isset($magister) ? 'Actualizar' : 'Crear' }}
            </button>
        </form>
    </div>
</x-app-layout>
