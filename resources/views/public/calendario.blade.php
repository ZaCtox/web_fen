<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Calendario Académico</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 space-y-6">
        <x-filtros-calendario />
        <x-leyenda-magister />

        <div class="flex flex-col sm:flex-row justify-start gap-2">
            <button id="btnAnterior" class="bg-gray-300 hover:bg-gray-400 text-sm px-4 py-2 rounded shadow">
                ← Trimestre anterior
            </button>
            <button id="btnSiguiente" class="bg-gray-300 hover:bg-gray-400 text-sm px-4 py-2 rounded shadow">
                Trimestre siguiente →
            </button>
        </div>

        <div id="current-period-label" class="text-center text-sm font-medium text-gray-700 dark:text-gray-200">
            Trimestre actual: <span id="current-period-text">Cargando...</span>
        </div>

        <div id="calendar" data-url="{{ route('guest.events.index') }}"></div>
    </div>


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
        @vite('resources/js/calendar-public.js')
    @endpush
    

</x-app-layout>
@include('components.footer')
