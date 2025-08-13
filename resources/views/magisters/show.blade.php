<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            Detalle del Magíster
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
            <p class="text-gray-800 dark:text-gray-200"><strong>Nombre:</strong> {{ $magister->nombre }}</p>
            <p class="text-gray-800 dark:text-gray-200"><strong>Color:</strong>
                <span class="inline-block w-4 h-4 rounded-full align-middle"
                      style="background-color: {{ $magister->color ?? '#6b7280' }}"></span>
                <span class="ml-2">{{ $magister->color ?? 'No definido' }}</span>
            </p>
        </div>
        <a href="{{ route('magisters.index') }}"
           class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Volver al listado
        </a>
    </div>
</x-app-layout>
