<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ✏️ Editar Clase Académica
        </h2>
    </x-slot>

    <div class="py-10 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-6">
            <form method="POST" action="{{ route('clases.update', $clase) }}">
                @csrf
                @method('PUT')

                {{-- Incluye campos del formulario --}}
                @include('clases.partials.form', ['clase' => $clase])

                {{-- Botones --}}
                <div class="mt-6 flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ route('clases.index') }}"
                       class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm px-4 py-2 rounded text-center">
                        ⬅️ Cancelar
                    </a>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded shadow">
                        💾 Actualizar Clase
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
