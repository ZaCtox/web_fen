{{-- Detalle de Staff.blade.php --}}
@section('title', 'Detalle miembro')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Detalle del staff</h2>
    </x-slot>

    <div class="p-6 max-w-5xl mx-auto">
        <div class="rounded-2xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="flex flex-col md:flex-row">
                <div class="flex-1 p-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $staff->nombre }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $staff->cargo }}</p>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('staff.edit', $staff) }}"
                           class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">✏️</a>

                        <form method="POST" action="{{ route('staff.destroy', $staff) }}" class="form-eliminar">
                            @csrf @method('DELETE')
                            <button class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">🗑️</button>
                        </form>

                        <a href="{{ route('staff.index') }}"
                           class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                           Volver
                        </a>
                    </div>
                </div>

                <div class="md:w-80 bg-[#12c6df] text-white p-6">
                    <div class="tracking-wide opacity-90">Teléfono</div>
                    <div class="mb-4 break-words">{{ $staff->telefono ?: '—' }}</div>
                    <div class="tracking-wide opacity-90">Email</div>
                    <div class="break-words">{{ $staff->email }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
