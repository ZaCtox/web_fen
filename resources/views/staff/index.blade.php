<!-- resources/views/staff/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Staff FEN</h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto">
        @if(session('ok'))
            <div class="mb-4 rounded-lg bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200 px-4 py-2">
                {{ session('ok') }}
            </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <form method="GET" class="flex w-full sm:w-auto gap-2">
                <input name="q" placeholder="Buscar por nombre, cargo o email" value="{{ $q }}"
                       class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                <button class="px-4 py-2 rounded-lg bg-gray-900 text-white dark:bg-gray-700">Buscar</button>
            </form>
            <a href="{{ route('staff.create') }}"
               class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Nuevo</a>
        </div>

        @if($staff->count() === 0)
            <div class="rounded-lg border border-dashed p-6 text-center text-gray-500 dark:text-gray-300">
                No hay registros.
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($staff as $p)
                <a href="{{ route('staff.show',$p) }}" class="group">
                    <div class="rounded-2xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 transition">
                        <div class="flex">
                            <!-- Izquierda -->
                            <div class="w-2/3 p-4">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:underline">
                                    {{ $p->nombre }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-300">{{ $p->cargo }}</p>
                            </div>
                            <!-- Derecha (panel celeste) -->
                            <div class="w-1/3 bg-[#12c6df] text-white p-4">
                                <div class="text-[10px] uppercase tracking-wide opacity-90">Teléfono</div>
                                <div class="text-sm mb-2 break-words">{{ $p->telefono ?: '—' }}</div>
                                <div class="text-[10px] uppercase tracking-wide opacity-90">Email</div>
                                <div class="text-sm truncate">{{ $p->email }}</div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $staff->links() }}
        </div>
    </div>
</x-app-layout>
