{{-- Inicio Emergencia.blade.php --}}
@section('title', 'Emergencias')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Emergencias</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Emergencias', 'url' => '#']
    ]" />

    @php
        $activeEmergency = app(\App\Http\Controllers\EmergencyController::class)->active();
    @endphp

    @if($activeEmergency)
        <meta name="active-emergency-title" content="{{ $activeEmergency->title }}">
        <meta name="active-emergency-message" content="{{ $activeEmergency->message }}">
    @endif

    <div class="py-6 max-w-7xl mx-auto px-4" x-data>
        {{-- Mostrar alerta de emergencia activa --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const titleMeta = document.querySelector('meta[name="active-emergency-title"]');
                const msgMeta = document.querySelector('meta[name="active-emergency-message"]');
                if (titleMeta && msgMeta) {
                    Swal.fire({
                        icon: 'warning',
                        title: titleMeta.content,
                        html: msgMeta.content.replace(/\n/g, '<br>'),
                        confirmButtonText: 'Cerrar'
                    });
                }
            });
        </script>

        {{-- Botón Nueva Emergencia --}}
        <div class="mb-4 flex">
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
                                <tr
                                    class="border-t border-[#c4dafa]/40 dark:border-gray-700 
                                           hover:bg-[#e3f2fd] dark:hover:bg-gray-700 
                                           hover:border-l-4 hover:border-l-[#4d82bc]
                                           hover:-translate-y-0.5 hover:shadow-md
                                           transition-all duration-200 group cursor-pointer
                                           {{ $emergency->active && !$isExpired ? 'von-restorff-critical' : '' }}"
                                    <td class="px-4 py-2 font-medium group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200">{{ $emergency->title }}</td>
                                    <td class="px-4 py-2">{{ Str::limit($emergency->message, 120) }}</td>
                                    <td class="px-4 py-2">
                                        @if($emergency->active && !$isExpired)
                                            <span class="von-restorff-badge von-restorff-badge-critical">
                                                🚨 Activa
                                            </span>
                                        @elseif($isExpired)
                                            <span class="von-restorff-badge" style="background-color: #6b7280; color: white;">
                                                ⏰ Expirada
                                            </span>
                                        @else
                                            <span class="von-restorff-badge" style="background-color: #9ca3af; color: white;">
                                                ⏸️ Inactiva
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ $emergency->expires_at ? $emergency->expires_at->format('d/m/Y H:i') : '—' }}
                                    </td>
                                    <td class="px-4 py-2">{{ $emergency->creator->name ?? '—' }}</td>
                                    <td class="px-4 py-2 flex justify-center gap-2">
                                        {{-- Editar --}}
                                        <a href="{{ route('emergencies.edit', $emergency) }}" class="inline-flex items-center justify-center 
                          w-10 px-3 py-2 bg-[#84b6f4] hover:bg-[#84b6f4]/80 
                          rounded-lg text-xs font-medium transition">
                                            <img src="{{ asset('icons/edit.svg') }}" alt="Editar" class="w-4 h-4">
                                        </a>

                                        {{-- Desactivar si está activa --}}
                                        @if($emergency->active && !$isExpired)
                                                <form action="{{ route('emergencies.deactivate', $emergency) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center justify-center 
                                               w-10 px-3 py-2 bg-[#84b6f4] hover:bg-[#84b6f4]/80 
                                               rounded-lg text-xs font-medium transition">
                                                        <img src="{{ asset('icons/pause.svg') }}" alt="Pausar" class="w-4 h-4">
                                                    </button>
                                                </form>
                                        @endif

                                        {{-- Eliminar --}}
                                        <form method="POST" action="{{ route('emergencies.destroy', $emergency) }}"
                                            class="form-eliminar">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center 
                                   w-10 px-3 py-2 bg-[#e57373] hover:bg-[#f28b82] 
                                   rounded-lg text-xs font-medium transition">
                                                <img src="{{ asset('icons/trash.svg') }}" alt="Eliminar" class="w-4 h-4">
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8">
                                <x-empty-state
                                    type="no-data"
                                    icon="🚨"
                                    title="No hay emergencias registradas"
                                    message="Las emergencias activas aparecerán aquí cuando se registren."
                                    actionText="Registrar Emergencia"
                                    actionUrl="{{ route('emergencies.create') }}"
                                    actionIcon="➕"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="mt-4">{{ $emergencies->links() }}</div>
    </div>
</x-app-layout>