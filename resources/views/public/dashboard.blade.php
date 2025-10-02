{{-- Dashboard de Postgrado FEN --}}
@section('title', 'Inicio')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#4d82bc]">
            Bienvenido a Postgrado FEN!
        </h2>
    </x-slot>

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
    <div
        class="py-16 max-w-4xl mt-8 mx-auto px-6 space-y-6 text-center bg-[#fcffff] border border-[#c4dafa] rounded-lg shadow-lg">
        <h3 class="text-lg text-[#005187] dark:text-[#84b6f4] font-semibold">Accesos rápidos</h3>
        <div class="flex flex-row flex-wrap justify-center gap-6 mt-6">
            <a href="{{ route('public.calendario.index') }}"
                class="w-40 h-16 flex items-center justify-center bg-[#4d82bc] hover:bg-[#005187] text-white font-medium rounded-xl shadow-md transition-all duration-200">
                Calendario
            </a>
            <a href="{{ route('public.Equipo-FEN.index') }}"
                class="w-40 h-16 flex items-center justify-center bg-[#4d82bc] hover:bg-[#005187] text-white font-medium rounded-xl shadow-md transition-all duration-200">
                Nuestro Equipo
            </a>
            <a href="{{ route('public.rooms.index') }}"
                class="w-40 h-16 flex items-center justify-center bg-[#4d82bc] hover:bg-[#005187] text-white font-medium rounded-xl shadow-md transition-all duration-200">
                Salas
            </a>
            <a href="{{ route('public.courses.index') }}"
                class="w-40 h-16 flex items-center justify-center bg-[#4d82bc] hover:bg-[#005187] text-white font-medium rounded-xl shadow-md transition-all duration-200">
                Cursos
            </a>
            <a href="{{ route('public.informes.index') }}"
                class="w-40 h-16 flex items-center justify-center bg-[#4d82bc] hover:bg-[#005187] text-white font-medium rounded-xl shadow-md transition-all duration-200">
                Archivos
            </a>
        </div>
    </div>

    {{-- Footer --}}
    <footer">
        @include('components.footer')
        </footer>
</x-app-layout>