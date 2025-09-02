{{-- Inicio.blade.php --}}
@section('title', 'Inicio')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Bienvenida --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
                <h3 class="text-2xl font-semibold text-gray-800 dark:text-white mb-2">¡Bienvenido/a, {{ Auth::user()->name }}!</h3>
                <p class="text-gray-600 dark:text-gray-300">
                    Rol asignado:
                    <span class="font-bold text-blue-700 dark:text-blue-300">{{ ucfirst(Auth::user()->rol) }}</span>
                </p>
            </div>

            {{-- Estadísticas resumidas --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <x-dashboard-card color="bg-blue-500" icon="📋" label="Incidencias" :count="$resumen['incidencias']" />
                <x-dashboard-card color="bg-indigo-500" icon="📅" label="Eventos" :count="$resumen['eventos']" />
                <x-dashboard-card color="bg-purple-500" icon="🏫" label="Salas" :count="$resumen['salas']" />
                <x-dashboard-card color="bg-green-500" icon="👤" label="Usuarios" :count="$resumen['usuarios']" />
            </div>

            {{-- Últimas Incidencias --}}
{{--             <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Últimas Incidencias</h3>
                @if($ultimas->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">No hay incidencias registradas.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-800 dark:text-gray-200">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2">ID</th>
                                    <th class="px-4 py-2">Título</th>
                                    <th class="px-4 py-2">Estado</th>
                                    <th class="px-4 py-2">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ultimas as $incidencia)
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <td class="px-4 py-2">{{ $incidencia->id }}</td>
                                        <td class="px-4 py-2">{{ $incidencia->titulo }}</td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                {{ $incidencia->estado === 'resuelta' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                                {{ ucfirst($incidencia->estado) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">{{ $incidencia->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div> --}}
        </div>
    </div>
</x-app-layout>
