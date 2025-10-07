{{-- Inicio.blade.php --}}
@section('title', 'Inicio')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-[#005187] dark:text-[#84b6f4]">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Bienvenida --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded p-6 hci-card-hover">
                <h3 class="text-2xl font-semibold text-gray-800 dark:text-white mb-2">¡Bienvenido/a, {{ Auth::user()->name }}!</h3>
                <p class="text-gray-600 dark:text-gray-300">
                    Rol asignado:
                    <span class="font-bold text-blue-700 dark:text-blue-300">{{ ucfirst(Auth::user()->rol) }}</span>
                </p>
            </div>

            {{-- Demo de Sistema de Feedback --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded p-6 hci-card-hover">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Sistema de Feedback HCI</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Notificaciones -->
                    <button 
                        onclick="showSuccess('Operación completada exitosamente!', 'Éxito')"
                        class="hci-button hci-lift hci-focus-ring px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-200"
                    >
                        Mostrar Éxito
                    </button>
                    
                    <button 
                        onclick="showError('Ha ocurrido un error inesperado', 'Error')"
                        class="hci-button hci-lift hci-focus-ring px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200"
                    >
                        Mostrar Error
                    </button>
                    
                    <button 
                        onclick="showWarning('Esta acción requiere confirmación', 'Advertencia')"
                        class="hci-button hci-lift hci-focus-ring px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-all duration-200"
                    >
                        Mostrar Advertencia
                    </button>
                    
                    <button 
                        onclick="showInfo('Información importante del sistema', 'Información')"
                        class="hci-button hci-lift hci-focus-ring px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200"
                    >
                        Mostrar Info
                    </button>
                </div>
                
                <!-- Loading States -->
                <div class="mt-6">
                    <h4 class="text-lg font-medium text-gray-800 dark:text-white mb-3">Estados de Loading</h4>
                    <div class="flex space-x-4">
                        <button 
                            onclick="this.innerHTML='<div class=\'hci-spinner w-4 h-4 mr-2\'></div>Procesando...'; setTimeout(() => this.innerHTML='Botón con Loading', 3000)"
                            class="hci-button hci-lift hci-focus-ring px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all duration-200"
                        >
                            Botón con Loading
                        </button>
                        
                        <button 
                            onclick="this.classList.add('hci-loading'); setTimeout(() => this.classList.remove('hci-loading'), 3000)"
                            class="hci-button hci-lift hci-focus-ring px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all duration-200"
                        >
                            Loading State
                        </button>
                    </div>
                </div>
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
