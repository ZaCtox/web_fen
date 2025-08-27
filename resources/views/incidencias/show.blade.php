<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📋 Detalle de Incidencia
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg p-6 space-y-6">

                {{-- Título --}}
                <div class="border-b pb-4">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $incidencia->titulo }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Registrada el {{ $incidencia->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>

                {{-- Datos principales --}}
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <p><strong>ID:</strong> {{ $incidencia->id }}</p>
                        <p><strong>🎫 Ticket:</strong> {{ $incidencia->nro_ticket ?? '---' }}</p>
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

                    <div class="space-y-3">
                        <p><strong>Registrado por:</strong> {{ $incidencia->user->name ?? 'N/D' }}</p>

                        @if($incidencia->resuelta_en)
                            <p><strong>Resuelta el:</strong> {{ $incidencia->resuelta_en->format('d/m/Y H:i') }}</p>
                        @endif

                        <p><strong>Resuelta por:</strong>
                            {{ $incidencia->resolvedBy->name ?? 'Sin resolver' }}
                        </p>
                    </div>
                </div>

                {{-- Descripción --}}
                <div>
                    <p class="font-semibold">📝 Descripción:</p>
                    <p class="bg-gray-50 dark:bg-gray-700 p-4 rounded text-gray-800 dark:text-gray-200">
                        {{ $incidencia->descripcion }}
                    </p>
                </div>

                {{-- Imagen --}}
                @if ($incidencia->imagen)
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
                        <form action="{{ route('incidencias.update', $incidencia) }}" method="POST"
                            class="space-y-4 max-w-lg" x-data="{ estado: '{{ old('estado', $incidencia->estado) }}' }">
                            @csrf @method('PUT')

                            {{-- Nro Ticket --}}
                            <div>
                                <label for="nro_ticket" class="block text-sm font-medium">🎫 N° Ticket UTALCA</label>
                                <input type="text" name="nro_ticket" id="nro_ticket"
                                    value="{{ old('nro_ticket', $incidencia->nro_ticket) }}"
                                    class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                            </div>

                            {{-- Estado --}}
                            <div>
                                <label for="estado" class="block text-sm font-medium">Estado</label>
                                <select name="estado" id="estado" x-model="estado"
                                    class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                    required>
                                    <option value="pendiente">🕒 Pendiente</option>
                                    <option value="en_revision">🔍 En revisión</option>
                                    <option value="resuelta">✅ Resuelta</option>
                                    <option value="no_resuelta">❌ No resuelta</option>
                                </select>
                            </div>

                            {{-- Comentario --}}
                            <div>
                                <label for="comentario" class="block text-sm font-medium">Comentario</label>
                                <textarea name="comentario" id="comentario" rows="3"
                                    class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                    placeholder="Agrega observaciones o motivos...">{{ old('comentario', $incidencia->comentario) }}</textarea>
                            </div>

                            <div class="pt-4">
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    💾 Guardar cambios
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-sm text-yellow-700 dark:text-yellow-300 font-medium">
                            🔒 Esta incidencia ha sido marcada como
                            <strong>{{ strtoupper(str_replace('_', ' ', $incidencia->estado)) }}</strong>
                            y no puede modificarse.
                        </div>
                    @endif
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
                <div class="flex items-center gap-3 pt-6 border-t">
                    {{-- Eliminar --}}
                    @if ($incidencia->estado !== 'resuelta' && $incidencia->estado !== 'no_resuelta')
                        <form action="{{ route('incidencias.destroy', $incidencia) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                onclick="return confirm('¿Seguro que quieres eliminar esta incidencia?')">
                                🗑️
                            </button>
                        </form>
                    @endif


                    {{-- Volver --}}
                    <a href="{{ route('incidencias.index') }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        ⬅️ Volver al listado
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>