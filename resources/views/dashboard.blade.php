<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold">Bienvenido, {{ Auth::user()->name }}</h3>

                <div class="bg-blue-100 border border-blue-300 text-blue-800 px-4 py-2 rounded">
                    Estás registrado como: <strong>{{ ucfirst(Auth::user()->rol) }}</strong>
                </div>

                <a href="{{ route('incidencias.index') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">
                    Ir a Bitácora de Incidencias
                </a>
                <a href="{{ route('calendario') }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded inline-block ml-2">
                    Ver Calendario Académico
                </a>

                <a href="{{ route('rooms.index') }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded inline-block ml-2">
                    Ver Salas
                </a>

                @if(Auth::user()->rol === 'docente' || Auth::user()->rol === 'administrativo')
                    <a href="{{ route('register') }}"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-block ml-2">
                        Registrar Nuevo Usuario
                    </a>
                @endif



                <form method="POST" action="{{ route('logout') }}" class="inline-block mt-2">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Últimas Incidencias Registradas</h3>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">Título</th>
                        <th class="px-4 py-2 text-left">Sala</th>
                        <th class="px-4 py-2 text-left">Estado</th>
                        <th class="px-4 py-2 text-left">Fecha</th>
                        <th class="px-4 py-2 text-left">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($ultimas as $incidencia)
                        <tr>
                            <td class="px-4 py-2">{{ $incidencia->titulo }}</td>
                            <td class="px-4 py-2">{{ $incidencia->sala }}</td>
                            <td class="px-4 py-2">
                                @if($incidencia->estado === 'resuelta')
                                    <span class="text-green-600 dark:text-green-400">Resuelta</span>
                                @else
                                    <span class="text-yellow-600 dark:text-yellow-400">Pendiente</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $incidencia->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('incidencias.show', $incidencia) }}"
                                    class="text-indigo-600 hover:underline">Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">
                                No hay incidencias registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>