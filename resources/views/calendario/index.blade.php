<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Calendario Académico</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @include('calendario.partials.filtros')
        @include('calendario.partials.leyenda')
        <div id="calendar" class="mt-6"></div>
    </div>

    {{-- Modales --}}
    @include('calendario.modal-crear')
    @include('calendario.modal-ver')

    @push('scripts')
    @include('calendario.scripts.calendar-js')
@endpush

</x-app-layout>


