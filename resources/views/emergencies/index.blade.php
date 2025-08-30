<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Emergencias</h2>
    </x-slot>

    @php
        $activeEmergency = app(\App\Http\Controllers\EmergencyController::class)->active();
    @endphp

    @if($activeEmergency)
        <meta name="active-emergency-title" content="{{ $activeEmergency->title }}">
        <meta name="active-emergency-message" content="{{ $activeEmergency->message }}">
    @endif

    <div class="py-6 max-w-7xl mx-auto px-4" x-data>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const titleMeta = document.querySelector('meta[name="active-emergency-title"]');
                const msgMeta = document.querySelector('meta[name="active-emergency-message"]');
                if(titleMeta && msgMeta) {
                    Swal.fire({
                        icon: 'warning',
                        title: titleMeta.content,
                        html: msgMeta.content.replace(/\n/g, '<br>'),
                        confirmButtonText: 'Cerrar'
                    });
                }
            });
        </script>

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
                        <tr class="border-t border-gray-200 dark:border-gray-700">
                            <td class="px-4 py-2 font-medium">{{ $emergency->title }}</td>
                            <td class="px-4 py-2">{{ Str::limit($emergency->message, 120) }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-xs {{ $statusColor }}">{{ $statusText }}</span>
                            </td>
                            <td class="px-4 py-2">{{ $emergency->expires_at ? $emergency->expires_at->format('d/m/Y H:i') : '—' }}</td>
                            <td class="px-4 py-2">{{ $emergency->creator->name ?? '—' }}</td>
                            <td class="px-4 py-2 flex gap-2">
                                <a href="{{ route('emergencies.edit', $emergency) }}"
                                   class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-center">
                                   ✏️
                                </a>

                                @if($emergency->active && !$isExpired)
                                    <form action="{{ route('emergencies.deactivate', $emergency) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="px-4 py-2 rounded-lg bg-yellow-600 text-white hover:bg-yellow-700 text-center">
                                            ⏸️
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('emergencies.destroy', $emergency) }}" class="form-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 text-center">
                                        🗑️
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

        <div class="mt-4">{{ $emergencies->links() }}</div>
    </div>
</x-app-layout>
