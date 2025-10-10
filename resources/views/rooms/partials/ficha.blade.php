{{-- Ficha de Sala --}}
@section('title', 'Ficha de la Sala')

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Ficha Técnica de la Sala: {{ $room->name }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 space-y-6">
        {{-- Información general --}}
        <div class="space-y-4 p-6 bg-[#fcffff] dark:bg-gray-800 rounded-lg shadow-lg border border-[#c4dafa]">
            <p><strong class="text-[#005187]">Ubicación:</strong> {{ $room->location }}</p>
            <p><strong class="text-[#005187]">Capacidad:</strong> {{ $room->capacity }}</p>
            <p><strong class="text-[#005187]">Descripción:</strong> {{ $room->description ?? 'Sin descripción' }}</p>

            {{-- Características --}}
            <h3 class="text-lg font-semibold text-[#005187] mt-6">Características</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                @foreach([
                    'calefaccion' => 'Calefacción',
                    'energia_electrica' => 'Energía eléctrica',
                    'existe_aseo' => 'Aseo disponible',
                    'plumones' => 'Plumones',
                    'borrador' => 'Borrador',
                    'pizarra_limpia' => 'Pizarra limpia',
                    'computador_funcional' => 'Computador funcional',
                    'cables_computador' => 'Cables para computador',
                    'control_remoto_camara' => 'Control remoto de cámara',
                    'televisor_funcional' => 'Televisor funcional'
                ] as $campo => $label)
                    <div class="flex items-center space-x-2 p-2 bg-[#c4dafa]/40 rounded">
                        <span class="font-semibold">{{ $label }}:</span>
                        <span class="text-lg">
                            @if($room->$campo)
                                <img src="https://img.icons8.com/ios-filled/50/4d82bc/checkmark.png" 
                alt="Sí" class="w-5 h-5 inline-block">
                            @else
                                ❌
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Botón volver --}}
        <div>
            <a href="{{ route('public.rooms.index') }}"
               class="inline-flex items-center px-5 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white font-medium rounded-lg shadow transition-all duration-200">
                <!-- Icono de flecha izquierda -->
                <img src="{{ asset('icons/back.svg') }}" alt="check" class="w-5 h-5">
            </a>
        </div>
    </div>
</x-app-layout>



