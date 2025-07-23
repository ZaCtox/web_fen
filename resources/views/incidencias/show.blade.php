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

                <p><strong>Sala:</strong> {{ $incidencia->room->name ?? 'Sin sala' }} ({{ $incidencia->room->location ?? 'N/D' }})</p>

                <p><strong>Estado:</strong>
                    @if($incidencia->estado === 'resuelta')
                        <span class="inline-block px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded">
                            Resuelta
                        </span>
                    @else
                        <span class="inline-block px-3 py-1 text-sm font-medium bg-yellow-100 text-yellow-800 rounded">
                            Pendiente
                        </span>
                    @endif
                </p>

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
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
