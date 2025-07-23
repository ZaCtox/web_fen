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
                <p class="text-gray-600 dark:text-gray-300">Rol asignado: 
                    <span class="font-bold text-blue-700 dark:text-blue-300">{{ ucfirst(Auth::user()->rol) }}</span>
                </p>
            </div>

            {{-- Estadísticas resumidas --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-blue-500 text-white p-5 rounded-lg shadow flex items-center gap-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9.75 9.75h.008v.008H9.75V9.75zM14.25 9.75h.008v.008h-.008V9.75zM9.75 14.25h.008v.008H9.75v-.008zM14.25 14.25h.008v.008h-.008v-.008z"></path>
                        <path d="M3 3.75A.75.75 0 013.75 3h16.5a.75.75 0 01.75.75V21l-5.25-3.75L9.75 21l-6.75-4.5V3.75z"></path>
                    </svg>
                    <div>
                        <p class="text-lg font-bold">{{ $resumen['incidencias'] ?? 0 }}</p>
                        <p class="text-sm">Incidencias</p>
                    </div>
                </div>

                <div class="bg-indigo-500 text-white p-5 rounded-lg shadow flex items-center gap-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 002-2v-5H3v5a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <p class="text-lg font-bold">{{ $resumen['eventos'] ?? 0 }}</p>
                        <p class="text-sm">Eventos</p>
                    </div>
                </div>

                <div class="bg-purple-500 text-white p-5 rounded-lg shadow flex items-center gap-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 7h18M3 12h18M3 17h18"></path>
                    </svg>
                    <div>
                        <p class="text-lg font-bold">{{ $resumen['salas'] ?? 0 }}</p>
                        <p class="text-sm">Salas</p>
                    </div>
                </div>

                <div class="bg-green-500 text-white p-5 rounded-lg shadow flex items-center gap-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 4.5v15m7.5-7.5h-15"></path>
                    </svg>
                    <div>
                        <p class="text-lg font-bold">{{ $resumen['usuarios'] ?? 0 }}</p>
                        <p class="text-sm">Usuarios</p>
                    </div>
                </div>
            </div>

            {{-- Cierre de sesión --}}
            <div class="text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mt-8 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
