<x-app-layout>
    <x-slot name="header">
        <h1>hola</h1>
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Bienvenido a Postgrado FEN</h2>
    </x-slot>
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

    <div class="py-10 max-w-4xl mx-auto px-4 space-y-6 text-center">
        <h3 class="text-lg text-gray-800 dark:text-gray-200 font-semibold">Accesos rápidos</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-4">
            <a href="{{ route('public.calendario.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded shadow">
                📅 Calendario
            </a>
            <a href="{{ route('public.rooms.index') }}" class="bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded shadow">
                🏫 Salas
            </a>
            <a href="{{ route('public.courses.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded shadow">
                📘 Cursos
            </a>
        </div>
    </div>

    @include('components.footer')
</x-app-layout>
