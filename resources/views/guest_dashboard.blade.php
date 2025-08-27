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
    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">BEN</h1>



    <div class="py-6 max-w-5xl mx-auto px-4 space-y-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded p-6 space-y-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Explora:</h3>
            <ul class="list-disc ml-6 text-gray-700 dark:text-gray-300 text-sm space-y-2">
                <li><a href="{{ route('public.calendario.index') }}" class="text-blue-600 hover:underline">📅 Ver
                        Calendario Académico</a></li>
                <li><a href="{{ route('public.rooms.index') }}" class="text-blue-600 hover:underline">🏫 Ver Salas
                        Registradas</a></li>
                <li><a href="{{ route('public.courses.index') }}" class="text-blue-600 hover:underline">📘 Ver Cursos
                        por Magíster</a></li>
                <li><a href="{{ route('public.staff.index') }}" class="text-blue-600 hover:underline">👥 Ver Staff
                        FEN</a></li>
            </ul>
        </div>
    </div>
</x-app-layout>