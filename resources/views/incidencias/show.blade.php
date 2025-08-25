<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detalle de Incidencia
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 space-y-4">

                <h3 class="text-2xl font-semibold text-gray-800 dark:text-white">
                    {{ $incidencia->titulo }}
                </h3>

                <p><strong>ID:</strong> {{ $incidencia->id }}</p>
                <p><strong>🎫 Ticket UTALCA:</strong> {{ $incidencia->nro_ticket ?? '---' }}</p>
                <p><strong>Sala:</strong> {{ $incidencia->room->name ?? 'Sin sala' }}
                    ({{ $incidencia->room->location ?? 'N/D' }})</p>

                <p><strong>Estado:</strong>
                    @php
                        $colores = [
                            'pendiente' => 'bg-yellow-100 text-yellow-800',
                            'en_revision' => 'bg-blue-100 text-blue-800',
                            'resuelta' => 'bg-green-100 text-green-800',
                            'no_resuelta' => 'bg-red-100 text-red-800',
                        ];
                    @endphp
                    <span
                        class="inline-block px-3 py-1 text-sm font-medium rounded {{ $colores[$incidencia->estado] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ strtoupper(str_replace('_', ' ', $incidencia->estado)) }}
                    </span>
                </p>

                @if($incidencia->estado === 'no_resuelta' && $incidencia->comentario)
                    <p><strong>Motivo:</strong></p>
                    <p class="bg-red-100 dark:bg-red-800 p-3 rounded text-sm text-gray-900 dark:text-white">
                        {{ $incidencia->comentario }}
                    </p>
                @endif

                <p><strong>Registrado por:</strong> {{ $incidencia->user->name ?? 'N/D' }}</p>
                <p><strong>Fecha:</strong> {{ $incidencia->created_at->format('d/m/Y H:i') }}</p>

                @if($incidencia->resuelta_en)
                    <p><strong>Resuelta el:</strong> {{ $incidencia->resuelta_en->format('d/m/Y H:i') }}</p>
                @endif

                <div>
                    <p><strong>Descripción:</strong></p>
                    <p class="bg-gray-100 dark:bg-gray-700 p-4 rounded text-gray-800 dark:text-gray-200">
                        {{ $incidencia->descripcion }}
                    </p>
                </div>

                @if ($incidencia->imagen)
                    <div class="border-t border-gray-300 pt-4">
                        <p class="font-semibold mb-2 text-gray-700 dark:text-gray-300">Imagen del problema:</p>
                        <img src="{{ $incidencia->imagen }}" alt="Incidencia" class="rounded shadow max-w-md"
                            loading="lazy">
                    </div>
                @endif

                @php
                    $dentroDePeriodo = \App\Models\Period::where('fecha_inicio', '<=', $incidencia->created_at)
                        ->where('fecha_fin', '>=', $incidencia->created_at)
                        ->exists();
                @endphp

                {{-- Formulario de actualización --}}
                {{-- Formulario de actualización --}}
                @if($dentroDePeriodo && !in_array($incidencia->estado, ['resuelta', 'no_resuelta']))
                    <form action="{{ route('incidencias.update', $incidencia) }}" method="POST"
                        class="mt-6 space-y-4 max-w-lg" x-data="{ estado: '{{ old('estado', $incidencia->estado) }}' }">
                        @csrf @method('PUT')

                        {{-- Nro Ticket --}}
                        <div>
                            <label for="nro_ticket" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                🎫 N° Ticket UTALCA
                            </label>
                            <input type="text" name="nro_ticket" id="nro_ticket"
                                value="{{ old('nro_ticket', $incidencia->nro_ticket) }}"
                                class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        </div>

                        {{-- Estado --}}
                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Estado
                            </label>
                            <select name="estado" id="estado" x-model="estado"
                                class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                required>
                                <option value="pendiente">🕒 Pendiente</option>
                                <option value="en_revision">🔍 En revisión</option>
                                <option value="resuelta">✅ Resuelta</option>
                                <option value="no_resuelta">❌ No resuelta</option>
                            </select>
                        </div>

                        {{-- Comentario si no resuelta --}}
                        <div x-show="estado === 'no_resuelta' || estado === 'en_revision'" x-transition>
                            <label for="comentario" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Comentario
                            </label>
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
                @elseif(in_array($incidencia->estado, ['resuelta', 'no_resuelta']))
                    <div class="mt-6 text-sm text-yellow-700 dark:text-yellow-300 font-medium">
                        🔒 Esta incidencia ya ha sido marcada como
                        <strong>{{ strtoupper(str_replace('_', ' ', $incidencia->estado)) }}</strong> y no puede
                        modificarse.
                    </div>
                @endif

                {{-- Botón eliminar --}}
                <div class="mt-4">
                    <form action="{{ route('incidencias.destroy', $incidencia) }}" method="POST"
                        onsubmit="return confirm('¿Estás seguro de eliminar esta incidencia?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            🗑️ Eliminar
                        </button>
                    </form>
                </div>

                <div class="mt-6">
                    <a href="{{ route('incidencias.index') }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        ⬅️ Volver al listado
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>