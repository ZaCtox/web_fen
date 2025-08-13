<x-app-layout>
    <x-slot name="header">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="store-url" content="{{ route('events.store') }}">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Calendario Académico</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <x-filtros-calendario />
        <x-leyenda-magister />
        <div id="calendar" data-url="{{ route('events.index') }}" class="mt-6"></div>
    </div>

    {{-- Modales --}}
    @include('calendario.modal-crear')
    @include('calendario.modal-ver')

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
        @vite('resources/js/calendar-admin.js')
    @endpush


</x-app-layout>