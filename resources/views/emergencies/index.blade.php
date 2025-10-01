{{-- Inicio Emergencia.blade.php --}}
@section('title', 'Emergencias')
<x-app-layout>
    <x-slot name="header">
<<<<<<< Updated upstream
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Emergencias</h2>
=======
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Emergencias</h2>
>>>>>>> Stashed changes
    </x-slot>

    @php
        $activeEmergency = app(\App\Http\Controllers\EmergencyController::class)->active();
    @endphp

    @if($activeEmergency)
        <meta name="active-emergency-title" content="{{ $activeEmergency->title }}">
        <meta name="active-emergency-message" content="{{ $activeEmergency->message }}">
    @endif

    <div class="py-6 max-w-7xl mx-auto px-4" x-data>
<<<<<<< Updated upstream
=======
        {{-- Mostrar alerta de emergencia activa --}}
>>>>>>> Stashed changes
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const titleMeta = document.querySelector('meta[name="active-emergency-title"]');
                const msgMeta = document.querySelector('meta[name="active-emergency-message"]');
<<<<<<< Updated upstream
                if(titleMeta && msgMeta) {
=======
                if (titleMeta && msgMeta) {
>>>>>>> Stashed changes
                    Swal.fire({
                        icon: 'warning',
                        title: titleMeta.content,
                        html: msgMeta.content.replace(/\n/g, '<br>'),
                        confirmButtonText: 'Cerrar'
                    });
                }
            });
        </script>

<<<<<<< Updated upstream
        <div class="mb-4">
            <a href="{{ route('emergencies.create') }}"
                class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 font-medium">
                ⚠️ Nueva Emergencia
            </a>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded">
            <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2">Título</th>
                        <th class="px-4 py-2">Mensaje</th>
                        <th class="px-4 py-2">Estado</th>
                        <th class="px-4 py-2">Expira</th>
                        <th class="px-4 py-2">Creada por</th>
                        <th class="px-4 py-2">Acciones</th>
=======
        {{-- Botón Nueva Emergencia --}}
        <div class="mb-4 flex ">
            <a href="{{ route('emergencies.create') }}"
                class="inline-block bg-[#005187] hover:bg-[#4d82bc] text-white font-medium px-4 py-2 rounded-lg shadow transition duration-200">
                <img src="{{ asset('icons/agregar.svg') }}" alt="nuevo usuario" class="w-5 h-5">
            </a>
        </div>

        {{-- Tabla de emergencias --}}
        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
            <table class="min-w-full table-auto text-sm text-[#005187] dark:text-[#fcffff]">
                <thead class="bg-[#c4dafa]/50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Título</th>
                        <th class="px-4 py-2 text-left">Mensaje</th>
                        <th class="px-4 py-2 text-left">Estado</th>
                        <th class="px-4 py-2 text-left">Expira</th>
                        <th class="px-4 py-2 text-left">Creada por</th>
                        <th class="px-4 py-2 text-center">Acciones</th>
>>>>>>> Stashed changes
                    </tr>
                </thead>
                <tbody>
                    @forelse($emergencies as $emergency)
                        @php
                            $isExpired = $emergency->expires_at && $emergency->expires_at->isPast();
                            $statusColor = $emergency->active && !$isExpired
                                ? 'bg-green-100 text-green-800'
                                : ($isExpired ? 'bg-gray-300 text-gray-800' : 'bg-gray-200 text-gray-800');
                            $statusText = $emergency->active && !$isExpired
                                ? 'Activa'
                                : ($isExpired ? 'Expirada' : 'Inactiva');
                        @endphp
<<<<<<< Updated upstream
                        <tr class="border-t border-gray-200 dark:border-gray-700">
=======
                        <tr
                            class="border-t border-[#c4dafa]/40 dark:border-gray-700 hover:bg-[#c4dafa]/20 dark:hover:bg-gray-700 transition">
>>>>>>> Stashed changes
                            <td class="px-4 py-2 font-medium">{{ $emergency->title }}</td>
                            <td class="px-4 py-2">{{ Str::limit($emergency->message, 120) }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-xs {{ $statusColor }}">{{ $statusText }}</span>
                            </td>
<<<<<<< Updated upstream
                            <td class="px-4 py-2">{{ $emergency->expires_at ? $emergency->expires_at->format('d/m/Y H:i') : '—' }}</td>
                            <td class="px-4 py-2">{{ $emergency->creator->name ?? '—' }}</td>
                            <td class="px-4 py-2 flex gap-2">
                                <a href="{{ route('emergencies.edit', $emergency) }}"
                                   class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-center">
                                   ✏️
                                </a>

=======
                            <td class="px-4 py-2">
                                {{ $emergency->expires_at ? $emergency->expires_at->format('d/m/Y H:i') : '—' }}</td>
                            <td class="px-4 py-2">{{ $emergency->creator->name ?? '—' }}</td>
                            <td class="px-4 py-2 flex justify-center gap-2">
                                {{-- Editar --}}
                                <a href="{{ route('emergencies.edit', $emergency) }}"
                                    class="inline-flex items-center justify-center px-3 py-1 hover:bg-[#84b6f4]/30 rounded-lg text-xs font-medium transition w-full sm:w-auto">
                                            <img src="{{ asset('icons/edit.svg') }}" alt="Editar" class="w-5 h-5">
                                </a>

                                {{-- Desactivar si está activa --}}
>>>>>>> Stashed changes
                                @if($emergency->active && !$isExpired)
                                    <form action="{{ route('emergencies.deactivate', $emergency) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
<<<<<<< Updated upstream
                                            class="px-4 py-2 rounded-lg bg-yellow-600 text-white hover:bg-yellow-700 text-center">
                                            ⏸️
=======
                                            class="inline-flex items-center justify-center px-3 py-1 hover:bg-[#84b6f4]/30 rounded-lg text-xs font-medium transition w-full sm:w-auto">
                                            <img src="{{ asset('icons/pause.svg') }}" alt="Editar" class="w-5 h-5">
>>>>>>> Stashed changes
                                        </button>
                                    </form>
                                @endif

<<<<<<< Updated upstream
                                <form method="POST" action="{{ route('emergencies.destroy', $emergency) }}" class="form-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 text-center">
                                        🗑️
=======
                                {{-- Eliminar --}}
                                <form method="POST" action="{{ route('emergencies.destroy', $emergency) }}"
                                    class="form-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center px-3 py-1 hover:bg-[#84b6f4]/30 rounded-lg text-xs font-medium transition w-full sm:w-auto">
                                        <img src="{{ asset('icons/trash.svg') }}" alt="Eliminar" class="w-4 h-4">
>>>>>>> Stashed changes
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                No hay emergencias registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

<<<<<<< Updated upstream
        <div class="mt-4">{{ $emergencies->links() }}</div>
    </div>
</x-app-layout>
=======
        {{-- Paginación --}}
        <div class="mt-4">{{ $emergencies->links() }}</div>
    </div>
</x-app-layout>
>>>>>>> Stashed changes
