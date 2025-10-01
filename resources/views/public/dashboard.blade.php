{{-- Dashboard de Postgrado FEN --}}
@section('title', 'Inicio')
<x-app-layout>
    <x-slot name="header">
<<<<<<< Updated upstream
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Bienvenido a Postgrado FEN!</h2>
    </x-slot>
=======
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#4d82bc]">
            Bienvenido a Postgrado FEN!
        </h2>
    </x-slot>

>>>>>>> Stashed changes
    @php
        $emergency = app(\App\Http\Controllers\EmergencyController::class)->active();
    @endphp

    @if($emergency)
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'warning',
                    title: '{{ $emergency->title }}',
                    html: '{!! nl2br(e($emergency->message)) !!}',
                    confirmButtonText: 'Cerrar'
                });
            });
        </script>
    @endif

    {{-- Accesos Rápidos --}}
<<<<<<< Updated upstream
    <div class="py-10 max-w-4xl mx-auto px-4 space-y-6 text-center">
        <h3 class="text-lg text-gray-800 dark:text-gray-200 font-semibold">Accesos rápidos</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-4">
            <a href="{{ route('public.calendario.index') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded shadow">
                📅 Calendario
            </a>
            <a href="{{ route('public.rooms.index') }}"
                class="bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded shadow">
                🏫 Salas
            </a>
            <a href="{{ route('public.courses.index') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded shadow">
=======
    <div
        class="py-16 max-w-4xl mt-8 mx-auto px-6 space-y-6 text-center bg-[#fcffff] border border-[#c4dafa] rounded-lg shadow-lg">
        <h3 class="text-lg text-[#005187] dark:text-[#84b6f4] font-semibold">Accesos rápidos</h3>
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mt-6">
            <a href="{{ route('public.calendario.index') }}"
                class="bg-[#4d82bc] hover:bg-[#005187] text-white font-medium py-4 px-4 rounded-xl shadow-md transition-all duration-200">
                📅 Calendario
            </a>
            <a href="{{ route('public.Equipo-FEN.index') }}"
                class="bg-[#4d82bc] hover:bg-[#005187] text-white font-medium py-4 px-4 rounded-xl shadow-md transition-all duration-200">
                📘 Nuestro Equipo
            </a>
            <a href="{{ route('public.rooms.index') }}"
                class="bg-[#4d82bc] hover:bg-[#005187] text-white font-medium py-4 px-4 rounded-xl shadow-md transition-all duration-200">
                🏫 Salas
            </a>
            <a href="{{ route('public.courses.index') }}"
                class="bg-[#4d82bc] hover:bg-[#005187] text-white font-medium py-4 px-4 rounded-xl shadow-md transition-all duration-200">
>>>>>>> Stashed changes
                📘 Cursos
            </a>
        </div>
    </div>
<<<<<<< Updated upstream
    {{-- Footer --}}
    @include('components.footer')
=======

    {{-- Footer --}}
    <footer">
        @include('components.footer')
    </footer>
>>>>>>> Stashed changes
</x-app-layout>