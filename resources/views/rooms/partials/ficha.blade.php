{{-- Ficha Técnica de Sala (Vista Completa) --}}
@section('title', 'Ficha de la Sala')

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Ficha Técnica de la Sala: {{ $room->name }}
        </h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Salas', 'url' => route('public.rooms.index')],
        ['label' => $room->name, 'url' => '#']
    ]" />

    <div class="p-6 max-w-7xl mx-auto">
        {{-- Sticky Header con Botón Volver --}}
        <div class="sticky top-0 z-10 bg-white dark:bg-gray-900 py-4 mb-6 -mx-6 px-6 border-b border-gray-200 dark:border-gray-700 shadow-sm">
            <a href="{{ route('public.rooms.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-sm font-medium"
               aria-label="Volver a salas">
                <img src="{{ asset('icons/back.svg') }}" alt="" class="w-5 h-5">
            </a>
        </div>

        <div class="space-y-6">
            {{-- Información General --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4] mb-4">Información General</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-[#4d82bc]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-xl">📍</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Ubicación</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $room->location }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-[#4d82bc]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-xl">👥</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Capacidad</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $room->capacity }} personas</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-[#4d82bc]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-xl">📝</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Descripción</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $room->description ?? 'Sin descripción' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Características --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4] mb-4">Características y Equipamiento</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach([
                        'calefaccion' => ['label' => 'Calefacción', 'icon' => '🔥'],
                        'energia_electrica' => ['label' => 'Energía eléctrica', 'icon' => '⚡'],
                        'existe_aseo' => ['label' => 'Aseo disponible', 'icon' => '🧹'],
                        'plumones' => ['label' => 'Plumones', 'icon' => '🖊️'],
                        'borrador' => ['label' => 'Borrador', 'icon' => '🧽'],
                        'pizarra_limpia' => ['label' => 'Pizarra limpia', 'icon' => '📋'],
                        'computador_funcional' => ['label' => 'Computador funcional', 'icon' => '💻'],
                        'cables_computador' => ['label' => 'Cables para computador', 'icon' => '🔌'],
                        'control_remoto_camara' => ['label' => 'Control remoto de cámara', 'icon' => '📹'],
                        'televisor_funcional' => ['label' => 'Televisor funcional', 'icon' => '📺']
                    ] as $campo => $data)
                        <div class="flex items-center gap-3 p-3 rounded-lg {{ $room->$campo ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : 'bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600' }}">
                            <span class="text-2xl">{{ $data['icon'] }}</span>
                            <div class="flex-1">
                                <p class="text-sm font-medium {{ $room->$campo ? 'text-green-900 dark:text-green-100' : 'text-gray-600 dark:text-gray-400' }}">
                                    {{ $data['label'] }}
                                </p>
                            </div>
                            <span class="text-lg">
                                @if($room->$campo)
                                    <span class="text-green-600">✓</span>
                                @else
                                    <span class="text-gray-400">✗</span>
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
