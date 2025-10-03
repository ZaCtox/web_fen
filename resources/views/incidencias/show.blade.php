{{-- Show de Incidencias.blade.php --}}
@section('title', 'Detalle de Incidencia')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#005187] leading-tight">
            Detalle de Incidencia
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg p-6 space-y-6">

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
                        @if($incidencia->resuelta_en)
                            <p><strong>Resuelta el:</strong> {{ $incidencia->resuelta_en->format('d/m/Y H:i') }}</p>
                        @endif
                        <p><strong>Resuelta por:</strong> {{ $incidencia->resolvedBy->name ?? '---' }}</p>
                    </div>
                </div>

                {{-- Descripción --}}
                <div>
                    <p class="font-semibold text-[#005187]">Descripción:</p>
                    <p class="bg-[#fcffff] border border-[#84b6f4] p-4 rounded text-[#005187]">
                        {{ $incidencia->descripcion }}
                    </p>
                </div>

                {{-- Imagen --}}
                @if ($incidencia->imagen)
                    <div class="border-t border-[#84b6f4] pt-4">
                        <p class="font-semibold text-[#005187] mb-2">📷 Imagen del problema:</p>
                        <img src="{{ $incidencia->imagen }}" alt="Incidencia"
                            class="rounded-lg shadow max-w-md border border-[#4d82bc]" loading="lazy">
                    </div>
                @endif

                {{-- Formulario de actualización --}}
                <div class="border-t border-[#84b6f4] pt-6">
                    @php
                        $dentroDePeriodo = \App\Models\Period::where('fecha_inicio', '<=', $incidencia->created_at)
                            ->where('fecha_fin', '>=', $incidencia->created_at)
                            ->exists();
                    @endphp

                    @if($dentroDePeriodo && !in_array($incidencia->estado, ['resuelta', 'no_resuelta']))
                                    <img src="{{ asset('icons/edit.svg') }}" alt="Actualizar" class="inline w-5 h-5">
                                    <h4 class="text-lg font-bold mb-3 text-[#005187]">Actualizar incidencia</h4>

                                    <form action="{{ route('incidencias.update', $incidencia) }}" method="POST"
                                        class="space-y-4 max-w-lg" x-data="{ estado: '{{ old('estado', $incidencia->estado) }}' }">
                                        @csrf @method('PUT')

                                        {{-- Nro Ticket Jira --}}
                                        <div>
                                            <label for="nro_ticket" class="block text-sm font-medium text-[#005187]">N° Ticket Jira
                                                (Opcional)</label>
                                            <input type="text" name="nro_ticket" id="nro_ticket"
                                                value="{{ old('nro_ticket', $incidencia->nro_ticket) }}"
                                                class="w-full rounded border-[#4d82bc] bg-[#fcffff] text-[#005187] focus:ring-[#005187] focus:border-[#005187]">
                                        </div>

                                        {{-- Estado --}}
                                        <div>
                                            <label for="estado" class="block text-sm font-medium text-[#005187]">Estado</label>
                                            <select name="estado" id="estado" x-model="estado"
                                                class="w-full rounded border-[#4d82bc] bg-[#fcffff] text-[#005187] focus:ring-[#005187] focus:border-[#005187]"
                                                required>
                                                <option value="pendiente">Pendiente</option>
                                                <option value="en_revision">En revisión</option>
                                                <option value="resuelta">Resuelta</option>
                                                <option value="no_resuelta">No resuelta</option>
                                            </select>
                                        </div>

                                        {{-- Comentario --}}
                                        <div>
                                            <label for="comentario" class="block text-sm font-medium text-[#005187]">Comentario</label>
                                            <textarea name="comentario" id="comentario" rows="3"
                                                class="w-full rounded border-[#4d82bc] bg-[#fcffff] text-[#005187] focus:ring-[#005187] focus:border-[#005187]"
                                                placeholder="Agrega observaciones o motivos...">{{ old('comentario', $incidencia->comentario) }}</textarea>
                                        </div>

                                        <div class="pt-4">
                                            <button type="submit" class="inline-flex items-center justify-center 
                           bg-[#3ba55d] hover:bg-[#2d864a] 
                           text-white px-4 py-2 rounded-lg shadow text-sm font-medium 
                           transition transform hover:scale-105">
                                                <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-5 h-5">
                                            </button>
                                        </div>
                                    </form>
                    @else
                        <div class="text-sm text-[#4d82bc] font-medium">
                            🔒 Esta incidencia ha sido marcada como
                            <strong>{{ strtoupper(str_replace('_', ' ', $incidencia->estado)) }}</strong>
                            y no puede modificarse.
                        </div>
                    @endif
                </div>

                {{-- Timeline --}}
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
                                                        {{ $log->created_at->format('d/m/Y H:i') }} — por
                                                        {{ $log->user->name ?? 'Sistema' }}
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
                    <a href="{{ route('incidencias.index') }}"
                        class="inline-flex items-center px-5 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white font-medium rounded-lg shadow transition-all duration-200">
                        <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-5 h-5">
                    </a>

                    @if ($incidencia->estado !== 'resuelta' && $incidencia->estado !== 'no_resuelta')
                        <form action="{{ route('incidencias.destroy', $incidencia) }}" method="POST" class="form-eliminar">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center justify-center px-5 py-2 bg-[#e57373] hover:bg-[#f28b82] text-white rounded-lg text-xs font-medium transition w-full sm:w-auto">
                                <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar" class="w-5 h-5">
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>