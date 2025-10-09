{{-- Show de Incidencias.blade.php --}}
@section('title', 'Detalle de Incidencia')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#005187] leading-tight">
            Detalle de Incidencia
        </h2>
    </x-slot>

    {{-- Breadcrumb (Ley de Jakob) --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Incidencias', 'url' => route('incidencias.index')],
        ['label' => 'Detalle de Incidencia', 'url' => '#']
    ]" />

    <div class="hci-container">
        <div class="hci-section">
            <h1 class="hci-heading-1 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                        clip-rule="evenodd" />
                </svg>
                Detalle de Incidencia
            </h1>
            <p class="hci-text">
                Revisa los detalles de la incidencia y actualiza su estado según corresponda.
            </p>
        </div>

        <div class="max-w-6xl mx-auto">
            <div class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-xl p-8 space-y-8">

                {{-- Título Principal (Ley de Miller: Información más importante) --}}
                <div class="hci-card">
                    <div class="hci-card-header">
                        <h2 class="hci-heading-2 text-[#005187]">
                            {{ $incidencia->titulo }}
                        </h2>
                        <p class="hci-text-sm text-[#4d82bc]">
                            Registrada el {{ $incidencia->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>

                {{-- Información Principal - Layout de 2 columnas --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Información General - Con más padding --}}
                    <div class="hci-card">
                        <div class="hci-card-header">
                            <h3 class="hci-heading-3 flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Información General
                            </h3>
                        </div>
                        <div class="hci-card-body space-y-3 px-6 py-4">
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-gray-600">ID:</span>
                                <span class="text-sm text-[#005187] font-bold">#{{ $incidencia->id }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-gray-600">Ticket:</span>
                                <span
                                    class="text-sm text-[#005187] font-bold">{{ $incidencia->nro_ticket ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-start py-2">
                                <span class="text-sm font-medium text-gray-600">Sala:</span>
                                <div class="text-right">
                                    <span class="text-sm text-[#005187] font-bold">
                                        {{ $incidencia->room->name ?? 'Sin sala' }}
                                    </span>
                                    @if($incidencia->room->location)
                                        <p class="text-xs text-[#4d82bc]">({{ $incidencia->room->location }})</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-gray-600">Estado:</span>
                                <div class="flex items-center">
                                    <span class="px-1">
                                        <x-estado-icon :estado="$incidencia->estado" />
                                    </span>
                                    <span class="text-sm text-[#005187] font-bold ml-2">
                                        {{ ucfirst(str_replace('_', ' ', $incidencia->estado)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Responsables - Con más padding --}}
                    <div class="hci-card">
                        <div class="hci-card-header">
                            <h3 class="hci-heading-3 flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                </svg>
                                Responsables
                            </h3>
                        </div>
                        <div class="hci-card-body space-y-3 px-6 py-4">
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-gray-600">Registrado por:</span>
                                <span
                                    class="text-sm text-[#005187] font-bold">{{ $incidencia->user->name ?? 'N/D' }}</span>
                            </div>
                            @if($incidencia->resuelta_en)
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm font-medium text-gray-600">Resuelta el:</span>
                                    <span
                                        class="text-sm text-[#005187] font-bold">{{ $incidencia->resuelta_en->format('d/m/Y H:i') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-gray-600">Resuelta por:</span>
                                <span
                                    class="text-sm text-[#005187] font-bold">{{ $incidencia->resolvedBy->name ?? 'Pendiente' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Descripción (Ley de Miller: Información detallada) --}}
                <div class="hci-card">
                    <div class="hci-card-header">
                        <h3 class="hci-heading-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                    clip-rule="evenodd" />
                            </svg>
                            Descripción del Problema
                        </h3>
                    </div>
                    <div class="hci-card-body px-6 py-4">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed text-left">
                            {{ $incidencia->descripcion }}</p>
                    </div>
                </div>

                {{-- Imagen (Ley de Miller: Evidencia visual) --}}
                @if ($incidencia->imagen)
                    <div class="hci-card">
                        <div class="hci-card-header">
                            <h3 class="hci-heading-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                        clip-rule="evenodd" />
                                </svg>
                                Evidencia Fotográfica
                            </h3>
                        </div>
                        <div class="hci-card-body text-center">
                            <div class="relative inline-block">
                                <img src="{{ $incidencia->imagen }}" alt="Incidencia"
                                    class="rounded-lg shadow-lg max-w-xs mx-auto border-2 border-[#4d82bc] cursor-pointer hover:shadow-xl transition-shadow duration-300"
                                    loading="lazy" onclick="openImageModal('{{ $incidencia->imagen }}')">
                                <div
                                    class="absolute top-2 right-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs">
                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 12a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        <path fill-rule="evenodd"
                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Ver más grande
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Formulario de actualización (Ley de Fitts: Botones grandes) --}}
                <div class="hci-card">
                    <div class="hci-card-header">
                        <h3 class="hci-heading-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Actualizar Estado de la Incidencia
                        </h3>
                    </div>
                    <div class="hci-card-body px-6 py-4">
                        @php
                            $user = Auth::user();
                            $rolesPermitidos = ['administrador', 'director_administrativo', 'técnico', 'auxiliar', 'asistente_postgrado'];
                            $puedeModificar = in_array($user->rol, $rolesPermitidos);
                        @endphp
                        
                        @if($puedeModificar && !in_array($incidencia->estado, ['resuelta', 'no_resuelta']))
                            <form action="{{ route('incidencias.update', $incidencia) }}" method="POST"
                                class="space-y-6 max-w-2xl">
                                @csrf
                                @method('PUT')

                                {{-- Nro Ticket Jira --}}
                                <div>
                                    <label for="nro_ticket" class="block text-sm font-medium text-[#005187] mb-2">
                                        N° Ticket Jira (opcional)
                                    </label>
                                    <input type="text" name="nro_ticket" id="nro_ticket"
                                        value="{{ old('nro_ticket', $incidencia->nro_ticket) }}" placeholder="Ej: 2364552"
                                        class="w-full rounded-md border-[#84b6f4] shadow-sm focus:border-[#4d82bc] focus:ring-[#4d82bc] dark:bg-gray-700 dark:text-white">
                                </div>

                                {{-- Estado --}}
                                <div>
                                    <label for="estado" class="block text-sm font-medium text-[#005187] mb-2">
                                        Estado de la Incidencia <span class="text-red-500">*</span>
                                    </label>
                                    <select name="estado" id="estado" required
                                        class="w-full rounded-md border-[#84b6f4] shadow-sm focus:border-[#4d82bc] focus:ring-[#4d82bc] dark:bg-gray-700 dark:text-white">
                                        <option value="pendiente" {{ old('estado', $incidencia->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="en_revision" {{ old('estado', $incidencia->estado) == 'en_revision' ? 'selected' : '' }}>En revisión</option>
                                        <option value="resuelta" {{ old('estado', $incidencia->estado) == 'resuelta' ? 'selected' : '' }}>Resuelta</option>
                                        <option value="no_resuelta" {{ old('estado', $incidencia->estado) == 'no_resuelta' ? 'selected' : '' }}>No resuelta</option>
                                    </select>
                                </div>

                                {{-- Comentario --}}
                                <div>
                                    <label for="comentario" class="block text-sm font-medium text-[#005187] mb-2">
                                        Comentario
                                    </label>
                                    <textarea name="comentario" id="comentario" rows="4"
                                        placeholder="Agrega observaciones o motivos..."
                                        class="w-full rounded-md border-[#84b6f4] shadow-sm focus:border-[#4d82bc] focus:ring-[#4d82bc] dark:bg-gray-700 dark:text-white resize-none">{{ old('comentario', $incidencia->comentario) }}</textarea>
                                </div>

                                <div class="pt-4">
                                    <button type="submit"
                                        class="hci-button hci-lift hci-focus-ring inline-flex items-center justify-center gap-2 bg-[#3ba55d] hover:bg-[#2d864a] text-white px-6 py-3 rounded-lg shadow text-sm font-medium transition-all duration-200"
                                        title="Guardar cambios">
                                        <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-5 h-5">
                                    </button>
                                </div>
                            </form>
                        @elseif(!$puedeModificar && !in_array($incidencia->estado, ['resuelta', 'no_resuelta']))
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">Solo Lectura</h4>
                                        <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                            Tu rol actual <strong>({{ ucfirst(str_replace('_', ' ', $user->rol)) }})</strong> no tiene permisos para cambiar el estado de las incidencias. 
                                            Solo administradores, directores administrativos, técnicos, auxiliares y asistentes de postgrado pueden modificar el estado.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-yellow-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.725-1.36 3.49 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Incidencia Bloqueada</h4>
                                        <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                                            Esta incidencia ha sido marcada como <strong>{{ strtoupper(str_replace('_', ' ', $incidencia->estado)) }}</strong> y no puede modificarse.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Timeline (Ley de Miller: Información cronológica) --}}
                <div class="hci-card">
                    <div class="hci-card-header">
                        <h3 class="hci-heading-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd" />
                            </svg>
                            Historial de Cambios
                        </h3>
                    </div>
                    <div class="hci-card-body">
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach($incidencia->logs as $log)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-[#84b6f4]"
                                                    aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex items-start space-x-4">
                                                <div>
                                                    <span
                                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-[#fcffff] ring-8 ring-[#c4dafa]">
                                                        <x-estado-icon :estado="$log->estado" />
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="hci-card">
                                                        <div class="hci-card-header">
                                                            <h4 class="hci-card-title">
                                                                {{ ucfirst(str_replace('_', ' ', $log->estado)) }}
                                                            </h4>
                                                            <p class="hci-text-sm text-[#4d82bc]">
                                                                {{ $log->created_at->format('d/m/Y H:i') }} — por
                                                                {{ $log->user->name ?? 'Sistema' }}
                                                            </p>
                                                        </div>
                                                        @if($log->comentario)
                                                            <div class="hci-card-body">
                                                                <p class="hci-text">{{ $log->comentario }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Acciones (Ley de Fitts: Botones grandes y accesibles) --}}
                <div class="hci-form-actions">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('incidencias.index') }}"
                            class="hci-button hci-lift hci-focus-ring inline-flex items-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white font-medium px-6 py-3 rounded-lg shadow transition-all duration-200"
                            title="Volver a incidencias">
                            <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-5 h-5">
                        </a>

                        @if ($incidencia->estado !== 'resuelta' && $incidencia->estado !== 'no_resuelta')
                            <form action="{{ route('incidencias.destroy', $incidencia) }}" method="POST"
                                class="form-eliminar">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="hci-button hci-lift hci-focus-ring inline-flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg shadow text-sm font-medium transition-all duration-200"
                                    title="Eliminar incidencia">
                                    <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar" class="w-5 h-5">
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal para ver imagen en grande --}}
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4"
        onclick="closeImageModal()">
        <div class="relative max-w-2xl max-h-[80vh]" onclick="event.stopPropagation()">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
            <img id="modalImage" src="" alt="Imagen de la incidencia"
                class="max-w-full max-h-full rounded-lg shadow-2xl object-contain">
        </div>
    </div>

    <script>
        function openImageModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Cerrar modal con tecla ESC
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
        });

    </script>
</x-app-layout>