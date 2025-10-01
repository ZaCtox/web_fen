{{-- Crear Programa.blade.php --}}
@section('title', 'Crear Programa')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-[#005187] dark:text-[#c4dafa]">Crear Programa</h2>
    </x-slot>

    <div class="p-6 max-w-xl mx-auto">
        @if (session('success'))
            <div class="mb-4 rounded border border-green-300 bg-green-50 dark:bg-green-900/30
                        text-green-800 dark:text-green-200 px-4 py-2">
                {{ session('success') }}
            </div>
        @endif

        {{-- Formulario unificado y estilizado --}}
        @include('magisters.form', ['magister' => null])
    </div>
</x-app-layout>
