{{-- Calendario Académico.blade.php --}}
@section('title', 'Calendario Académico')
<x-app-layout>
    <x-slot name="header">
        <meta name="inicio-trimestre" content="{{ $fechaInicio }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="store-url" content="{{ route('events.store') }}">
        <meta name="clases-show-base" content="{{ url('/clases') }}">
        <meta name="user-id" content="{{ auth()->id() }}">
        <meta name="es-visor" content="{{ tieneRol('visor') ? '1' : '0' }}">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Calendario Académico</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Calendario Académico', 'url' => '#']
    ]" />

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <x-filtros-calendario :aniosIngreso="$aniosIngreso" :anioIngresoSeleccionado="$anioIngresoSeleccionado" :periodos="$periodos" />
        <x-leyenda-magister />
        <div id="current-period-label" class="mt-4 text-center text-xl font-bold text-[#005187] dark:text-[#84b6f4]">
            <span id="current-period-text">Cargando...</span>
        </div>
        <div id="calendar" data-url="{{ route('events.index') }}" class="mt-6"></div>
    </div>

    {{-- Modales --}}
    @if(!tieneRol('visor'))
    @include('calendario.modal-crear')
    @endif
    @include('calendario.modal-ver')

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales-all.global.min.js"></script>
        @vite('resources/js/calendar-admin.js')
    @endpush


</x-app-layout>





