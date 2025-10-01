{{-- Show de Incidencias.blade.php --}}
@section('title', 'Detalle de Incidencia')
<x-app-layout>
    <x-slot name="header">
<<<<<<< Updated upstream
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
<<<<<<< Updated upstream
            📋 Detalle de Incidencia
=======
=======
        <h2 class="font-semibold text-xl text-[#005187] leading-tight">
>>>>>>> Stashed changes
            Detalle de Incidencia
>>>>>>> Stashed changes
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg p-6 space-y-6">

<<<<<<< Updated upstream
                {{-- Título --}}
                <div class="border-b pb-4">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $incidencia->titulo }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Registrada el {{ $incidencia->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
=======
<<<<<<< Updated upstream
                <h3 class="text-2xl font-semibold text-gray-800 dark:text-white">
                    {{ $incidencia->titulo }}
                </h3>
>>>>>>> Stashed changes

                {{-- Datos principales --}}
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <p><strong>ID:</strong> {{ $incidencia->id }}</p>
                        <p><strong>🎫 Ticket Jira:</strong> {{ $incidencia->nro_ticket ?? '---' }}</p>
                        <p><strong>Sala:</strong> {{ $incidencia->room->name ?? 'Sin sala' }}
                            ({{ $incidencia->room->location ?? 'N/D' }})</p>

                        {{-- Estado --}}
                        @php
                            $estadoIconos = [
                                'pendiente' => '🕒',
                                'en_revision' => '🔍',
                                'resuelta' => '✅',
                                'no_resuelta' => '❌',
                            ];
                        @endphp
                        <p>
                            <strong>Estado:</strong>
                            <span class="px-3 py-1 text-sm font-medium rounded bg-gray-100 text-gray-800">
                                {{ $estadoIconos[$incidencia->estado] ?? 'ℹ️' }}
                                {{ strtoupper(str_replace('_', ' ', $incidencia->estado)) }}
                            </span>
                        </p>
                    </div>

<<<<<<< Updated upstream
                    <div class="space-y-3">
                        <p><strong>Registrado por:</strong> {{ $incidencia->user->name ?? 'N/D' }}</p>
=======
                <p><strong>Registrado por:</strong> {{ $incidencia->user->name ?? 'N/D' }}</p>
                <p><strong>Fecha:</strong> {{ $incidencia->created_at->format('d/m/Y H:i') }}</p>
=======
                {{-- Título --}}
                <div class="border-b pb-4 border-[#84b6f4]">
                    <h3 class="text-2xl font-bold text-[#005187]">
                        {{ $incidencia->titulo }}
                    </h3>
                    <p class="text-sm text-[#4d82bc]">
                        Registrada el {{ $incidencia->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>

                {{-- Datos principales --}}
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-3 text-[#005187]">
                        <p><strong>ID:</strong> {{ $incidencia->id }}</p>
                        <p><strong>Ticket Jira:</strong> {{ $incidencia->nro_ticket ?? '---' }}</p>
                        <p><strong>Sala:</strong> {{ $incidencia->room->name ?? 'Sin sala' }}
                            ({{ $incidencia->room->location ?? 'N/D' }})</p>
                        <p>
                            <strong>Estado:</strong>
                            <span class="px-3">
                                <x-estado-icon :estado="$incidencia->estado" />
                            </span>
                        </p>
                    </div>

                    <div class="space-y-3 text-[#005187]">
                        <p><strong>Registrado por:</strong> {{ $incidencia->user->name ?? 'N/D' }}</p>
>>>>>>> Stashed changes
>>>>>>> Stashed changes

                        @if($incidencia->resuelta_en)
                            <p><strong>Resuelta el:</strong> {{ $incidencia->resuelta_en->format('d/m/Y H:i') }}</p>
                        @endif

<<<<<<< Updated upstream
                        <p><strong>Resuelta por:</strong>
                            {{ $incidencia->resolvedBy->name ?? 'Sin resolver' }}
                        </p>
                    </div>
                </div>

                {{-- Descripción --}}
                <div>
                    <p class="font-semibold">📝 Descripción:</p>
                    <p class="bg-gray-50 dark:bg-gray-700 p-4 rounded text-gray-800 dark:text-gray-200">
=======
<<<<<<< Updated upstream
                <div>
                    <p><strong>Descripción:</strong></p>
                    <p class="bg-gray-100 dark:bg-gray-700 p-4 rounded text-gray-800 dark:text-gray-200">
=======
                        <p><strong>Resuelta por:</strong>
                            {{ $incidencia->resolvedBy->name ?? '---' }}
                        </p>
                    </div>
                </div>

                {{-- Descripción --}}
                <div>
                    <p class="font-semibold text-[#005187]">Descripción:</p>
                    <p class="bg-[#fcffff] border border-[#84b6f4] p-4 rounded text-[#005187]">
>>>>>>> Stashed changes
>>>>>>> Stashed changes
                        {{ $incidencia->descripcion }}
                    </p>
                </div>

                {{-- Imagen --}}
                @if ($incidencia->imagen)
<<<<<<< Updated upstream
                    <div class="border-t pt-4">
                        <p class="font-semibold text-gray-700 dark:text-gray-300 mb-2">📷 Imagen del problema:</p>
                        <img src="{{ $incidencia->imagen }}" alt="Incidencia" class="rounded-lg shadow max-w-md border"
                            loading="lazy">
                    </div>
                @endif

                {{-- Formulario de actualización --}}
                @php
                    $dentroDePeriodo = \App\Models\Period::where('fecha_inicio', '<=', $incidencia->created_at)
                        ->where('fecha_fin', '>=', $incidencia->created_at)
                        ->exists();
                @endphp

                <div class="border-t pt-6">
                    @if($dentroDePeriodo && !in_array($incidencia->estado, ['resuelta', 'no_resuelta']))
                        <h4 class="text-lg font-bold mb-3 text-gray-800 dark:text-gray-200">✏️ Actualizar incidencia</h4>
=======
<<<<<<< Updated upstream
                    <div class="border-t border-gray-300 pt-4">
                        <p class="font-semibold mb-2 text-gray-700 dark:text-gray-300">
                            Imagen del problema:
                        </p>
                        <img src="{{ $incidencia->imagen }}" alt="Incidencia" class="rounded shadow max-w-md" loading="lazy">
                    </div>
                @endif
                
                <div class="mt-6">
                    <a href="{{ route('incidencias.index') }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Volver
                    </a>
=======
                    <div class="border-t border-[#84b6f4] pt-4">
                        <p class="font-semibold text-[#005187] mb-2">📷 Imagen del problema:</p>
                        <img src="{{ $incidencia->imagen }}" alt="Incidencia"
                            class="rounded-lg shadow max-w-md border border-[#4d82bc]" loading="lazy">
                    </div>
                @endif

                {{-- Formulario de actualización --}}
                <div class="border-t border-[#84b6f4] pt-6">
                    @if($dentroDePeriodo && !in_array($incidencia->estado, ['resuelta', 'no_resuelta']))
                        <img src="{{ asset('icons/edit.svg') }}" alt="Actualizar" class="inline w-5 h-5">
                        <h4 class="text-lg font-bold mb-3 text-[#005187]">Actualizar incidencia</h4>
>>>>>>> Stashed changes
                        <form action="{{ route('incidencias.update', $incidencia) }}" method="POST"
                            class="space-y-4 max-w-lg" x-data="{ estado: '{{ old('estado', $incidencia->estado) }}' }">
                            @csrf @method('PUT')

                            {{-- Nro Ticket Jira --}}
                            <div>
<<<<<<< Updated upstream
                                <label for="nro_ticket" class="block text-sm font-medium">🎫 N° Ticket Jira</label>
                                <input type="text" name="nro_ticket" id="nro_ticket"
                                    value="{{ old('nro_ticket', $incidencia->nro_ticket) }}"
                                    class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
=======
                                <label for="nro_ticket" class="block text-sm font-medium text-[#005187]">N° Ticket
                                    Jira (Opcional)</label>
                                <input type="text" name="nro_ticket" id="nro_ticket"
                                    value="{{ old('nro_ticket', $incidencia->nro_ticket) }}"
                                    class="w-full rounded border-[#4d82bc] bg-[#fcffff] text-[#005187] focus:ring-[#005187] focus:border-[#005187]">
>>>>>>> Stashed changes
                            </div>

                            {{-- Estado --}}
                            <div>
<<<<<<< Updated upstream
                                <label for="estado" class="block text-sm font-medium">Estado</label>
                                <select name="estado" id="estado" x-model="estado"
                                    class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                    required>
                                    <option value="pendiente">🕒 Pendiente</option>
                                    <option value="en_revision">🔍 En revisión</option>
                                    <option value="resuelta">✅ Resuelta</option>
                                    <option value="no_resuelta">❌ No resuelta</option>
=======
                                <label for="estado" class="block text-sm font-medium text-[#005187]">Estado</label>
                                <select name="estado" id="estado" x-model="estado"
                                    class="w-full rounded border-[#4d82bc] bg-[#fcffff] text-[#005187] focus:ring-[#005187] focus:border-[#005187]"
                                    required>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="en_revision">En revisión</option>
                                    <option value="resuelta">Resuelta</option>
                                    <option value="no_resuelta">No resuelta</option>
>>>>>>> Stashed changes
                                </select>
                            </div>

                            {{-- Comentario --}}
                            <div>
<<<<<<< Updated upstream
                                <label for="comentario" class="block text-sm font-medium">Comentario</label>
                                <textarea name="comentario" id="comentario" rows="3"
                                    class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
=======
                                <label for="comentario" class="block text-sm font-medium text-[#005187]">Comentario</label>
                                <textarea name="comentario" id="comentario" rows="3"
                                    class="w-full rounded border-[#4d82bc] bg-[#fcffff] text-[#005187] focus:ring-[#005187] focus:border-[#005187]"
>>>>>>> Stashed changes
                                    placeholder="Agrega observaciones o motivos...">{{ old('comentario', $incidencia->comentario) }}</textarea>
                            </div>

                            <div class="pt-4">
                                <button type="submit"
<<<<<<< Updated upstream
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    💾 Guardar cambios
=======
                                    class="bg-[#005187] hover:bg-[#4d82bc] text-white font-bold py-2 px-4 rounded">
                                    <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="inline w-5 h-5">
>>>>>>> Stashed changes
                                </button>
                            </div>
                        </form>
                    @else
<<<<<<< Updated upstream
                        <div class="text-sm text-yellow-700 dark:text-yellow-300 font-medium">
=======
                        <div class="text-sm text-[#4d82bc] font-medium">
>>>>>>> Stashed changes
                            🔒 Esta incidencia ha sido marcada como
                            <strong>{{ strtoupper(str_replace('_', ' ', $incidencia->estado)) }}</strong>
                            y no puede modificarse.
                        </div>
                    @endif
<<<<<<< Updated upstream
=======
                </div>

                {{-- 🚀 Timeline --}}
                <div class="border-t border-[#84b6f4] pt-6">
                    <img src="{{ asset('icons/chat.svg') }}" alt="Historial" class="w-5 h-5">
                    <h4 class="text-lg font-bold mb-4 text-[#005187]">Historial de Cambios</h4>

                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @foreach($incidencia->logs as $log)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-[#84b6f4]"
                                                aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex items-start space-x-3">
                                            {{-- Icono según estado --}}
                                            <div>
                                                <span
                                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-[#fcffff] ring-8 ring-[#c4dafa]">
                                                    <x-estado-icon :estado="$log->estado" />
                                                </span>
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <div>
                                                    <p class="text-sm font-medium text-[#005187]">
                                                        {{ ucfirst(str_replace('_', ' ', $log->estado)) }}
                                                    </p>
                                                    <p class="mt-0.5 text-xs text-[#4d82bc]">
                                                        {{ $log->created_at->format('d/m/Y H:i') }}
                                                        — por {{ $log->user->name ?? 'Sistema' }}
                                                    </p>
                                                </div>
                                                @if($log->comentario)
                                                    <div
                                                        class="mt-2 text-sm text-[#005187] bg-[#fcffff] border border-[#84b6f4] p-2 rounded">
                                                        {{ $log->comentario }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="flex justify-between items-center pt-6 border-t border-[#84b6f4]">
                    {{-- Volver --}}
                    <a href="{{ route('incidencias.index') }}"
                        class="inline-flex items-center px-5 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white font-medium rounded-lg shadow transition-all duration-200">
                        <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-5 h-5">
                    </a>

                    {{-- Eliminar --}}
                    @if ($incidencia->estado !== 'resuelta' && $incidencia->estado !== 'no_resuelta')
                        <form action="{{ route('incidencias.destroy', $incidencia) }}" method="POST" class="form-eliminar">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 rounded-lg bg-[#4d82bc] hover:bg-[#005187] font-medium text-center">
                                <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar" class="w-6 h-6">
                            </button>
                        </form>
                    @endif
>>>>>>> Stashed changes
>>>>>>> Stashed changes
                </div>

                {{-- 🚀 Timeline SIEMPRE visible --}}
                <div class="border-t pt-6">
                    <h4 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-200">📌 Historial de Cambios</h4>

                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @foreach($incidencia->logs as $log)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-600"
                                                aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex items-start space-x-3">
                                            {{-- Icono según estado --}}
                                            <div>
                                                @php
                                                    $icons = [
                                                        'pendiente' => '🕒',
                                                        'en_revision' => '🔍',
                                                        'resuelta' => '✅',
                                                        'no_resuelta' => '❌',
                                                    ];
                                                @endphp
                                                <span
                                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 ring-8 ring-white dark:ring-gray-800">
                                                    {{ $icons[$log->estado] ?? 'ℹ️' }}
                                                </span>
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ ucfirst(str_replace('_', ' ', $log->estado)) }}
                                                    </p>
                                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $log->created_at->format('d/m/Y H:i') }}
                                                        — por {{ $log->user->name ?? 'Sistema' }}
                                                    </p>
                                                </div>
                                                @if($log->comentario)
                                                    <div
                                                        class="mt-2 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 p-2 rounded">
                                                        {{ $log->comentario }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                {{-- Acciones --}}
                <div class="flex flex-wrap gap-3 pt-6 border-t">
                    {{-- Eliminar --}}
                    @if ($incidencia->estado !== 'resuelta' && $incidencia->estado !== 'no_resuelta')
                        <form action="{{ route('incidencias.destroy', $incidencia) }}" method="POST" class="form-eliminar">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 font-medium text-center">
                                🗑️
                            </button>
                        </form>
                    @endif

                    {{-- Volver --}}
                    <a href="{{ route('incidencias.index') }}"
                        class="px-4 py-2 rounded-lg bg-gray-600 text-white hover:bg-gray-700 font-medium text-center">
                        ⬅️ Volver al listado
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>