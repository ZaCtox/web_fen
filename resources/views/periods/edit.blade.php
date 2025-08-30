<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Editar period</h2>
    </x-slot>

<div class="p-6 max-w-2xl mx-auto">
    <form action="{{ route('periods.update', $period) }}" method="POST">
        @csrf
        @method('PUT')
        @include('periods.form', ['period' => $period])

        <div class="mt-6 flex justify-between items-center">
            <a href="{{ route('periods.index') }}"
               class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                ⬅️ Volver a Períodos
            </a>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                Actualizar
            </button>
        </div>
    </form>
</div>

</x-app-layout>