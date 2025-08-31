{{-- Dashboard de Calendario Académico FEN--}}
@section('title', 'Calendario')
<x-app-layout>
    <x-slot name="header">
        <meta name="inicio-trimestre" content="{{ $fechaInicio }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="clases-show-base" content="{{ url('/public/clases') }}">
        <meta name="store-url" content="{{ route('events.store') }}">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Calendario Académico</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <x-filtros-calendario />
        <x-leyenda-magister />
        <div class="flex flex-col sm:flex-row justify-center sm:justify-start gap-2">
            <button id="btnAnterior"
                class="flex items-center justify-center gap-2 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow transition">
                ← Trimestre
            </button>
            <button id="btnSiguiente"
                class="flex items-center justify-center gap-2 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow transition">
                Trimestre →
            </button>
        </div>
        <div id="current-period-label" class="mt-4 text-center text-sm font-medium text-gray-700 dark:text-gray-200">
            <span id="current-period-text">Cargando...</span>
        </div>
        <div id="calendar" data-url="{{ route('guest.events.index') }}" class="mt-6"></div>
    </div>
    {{-- Modal de evento (público) --}}
    {{-- Agrega esta meta (base para el botón lupa). Ej: /clases --}}
    <meta name="clases-show-base" content="{{ url('/clases') }}">

    <div id="eventModal" class="fixed inset-0 z-50 hidden bg-black/50 flex justify-center items-center" role="dialog"
        aria-modal="true">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            {{-- Header --}}
            <div class="p-5 flex items-start justify-between gap-3">
                <h3 id="modal-title" class="text-xl font-semibold text-gray-900 dark:text-white">
                    Título
                </h3>

                {{-- Lupa: visible solo para eventos de tipo "clase" --}}
                <a id="view-class-link" href="#" target="_blank" rel="noopener noreferrer" class="hidden inline-flex items-center justify-center w-8 h-8 rounded-md bg-gray-100 hover:bg-gray-200
                dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-200"
                    title="Ver detalle de la clase">
                    {{-- SVG lupa --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z" />
                    </svg>
                </a>
            </div>

            {{-- Body --}}
            <div class="px-5 pb-5 text-sm">
                <div class="space-y-1">
                    <p class="text-gray-500 dark:text-gray-400">
                        <span class="font-medium">Programa:</span>
                        <span id="modal-program" class="text-gray-900 dark:text-gray-100">—</span>
                    </p>

                    <p class="text-gray-500 dark:text-gray-400">
                        <span class="font-medium">Modalidad:</span>
                        <span id="modal-modality" class="align-middle ml-1">—</span>
                    </p>

                    <p class="text-gray-500 dark:text-gray-400">
                        <span class="font-medium">Profesor:</span>
                        <span id="modal-teacher" class="text-gray-900 dark:text-gray-100">—</span>
                    </p>

                    <p class="text-gray-500 dark:text-gray-400">
                        <span class="font-medium">Inicio:</span>
                        <span id="modal-start" class="text-gray-900 dark:text-gray-100">—</span>
                    </p>

                    <p class="text-gray-500 dark:text-gray-400">
                        <span class="font-medium">Fin:</span>
                        <span id="modal-end" class="text-gray-900 dark:text-gray-100">—</span>
                    </p>

                    <p class="text-gray-500 dark:text-gray-400">
                        <span class="font-medium">Sala:</span>
                        <span id="modal-room" class="text-gray-900 dark:text-gray-100">—</span>
                    </p>
                </div>

                <div class="mt-6 flex justify-end">
                    <button onclick="closeModal()"
                        class="px-4 py-2 rounded-md bg-gray-600 hover:bg-gray-700 text-white">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>



    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales-all.global.min.js"></script>
        @vite('resources/js/calendar-public.js')
    @endpush
    
</x-app-layout>

{{-- Footer institucional --}}
@include('components.footer')