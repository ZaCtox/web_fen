{{-- Formulario de Salas con Principios HCI - Estructura Wizard --}}
@section('title', isset($room) ? 'Editar Sala' : 'Crear Sala')

@php
    $editing = isset($room);
@endphp

{{-- Breadcrumb (Ley de Jakob) --}}
<x-hci-breadcrumb 
    :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Salas', 'url' => route('rooms.index')],
        ['label' => $editing ? 'Editar Sala' : 'Nueva Sala', 'url' => '#']
    ]"
/>

{{-- Contenedor principal con principios HCI --}}
<div class="hci-container">
    <div class="hci-section">
        <h1 class="hci-heading-1 flex items-center">
            @if($editing)
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
                Editar Sala
            @else
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                </svg>
                Nueva Sala
            @endif
        </h1>
        <p class="hci-text">
            {{ $editing ? 'Modifica la información de la sala.' : 'Crea una nueva sala con información organizada y estructurada.' }}
        </p>
    </div>

    {{-- Layout principal con progreso lateral --}}
    <div class="hci-wizard-layout">
        {{-- Barra de progreso lateral izquierda --}}
        <x-rooms-progress-sidebar />

        {{-- Contenido principal del formulario --}}
        <div class="hci-form-content">
            <form class="hci-form" method="POST" action="{{ $editing ? route('rooms.update', $room) : route('rooms.store') }}">
                @csrf
                @if($editing) @method('PUT') @endif

                {{-- Sección 1: Información Básica --}}
                <x-hci-form-section 
                    :step="1" 
                    title="Información Básica" 
                    description="Datos principales de la sala"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z' clip-rule='evenodd'/></svg>"
                    section-id="basica"
                    :is-active="true"
                    :is-first="true"
                    :editing="$editing"
                >
                    <div class="space-y-6">
                        <x-hci-field 
                            name="name"
                            label="Nombre de la Sala"
                            placeholder="Ej: Sala A-101, Laboratorio 1, Auditorio Principal"
                            value="{{ old('name', $room->name ?? '') }}"
                            :required="true"
                            icon=""
                            help="Nombre único que identifique la sala"
                            maxlength="100"
                            style="width: 500px !important;"
                        />

                        <x-hci-field 
                            name="location"
                            label="Ubicación"
                            placeholder="Ej: Edificio A, Piso 2, Ala Norte"
                            value="{{ old('location', $room->location ?? '') }}"
                            icon=""
                            help="Ubicación física dentro del campus"
                            maxlength="150"
                            style="width: 500px !important;"
                        />
                    </div>
                </x-hci-form-section>

                {{-- Sección 2: Detalles --}}
                <x-hci-form-section 
                    :step="2" 
                    title="Detalles" 
                    description="Capacidad, estado y tipo"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
                    section-id="detalles"
                    content-class="items-start gap-24 xl:gap-32 2xl:gap-40"
                    :editing="$editing"
                >
<div class="space-y-8">
    <div class="w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 xl:gap-16 items-start">
            <div class="lg:col-span-3">
                <x-hci-field 
                    name="capacity"
                    type="number"
                    label="Capacidad"
                    placeholder="Ej: 30, 50, 100"
                    value="{{ old('capacity', $room->capacity ?? '') }}"
                    :required="true"
                    icon=""
                    help=""
                    min="1"
                    max="1000"
                    style="width: 140px !important;"
                />
            </div>
            <div class="lg:col-span-9">
                <x-hci-field 
                    name="description"
                    type="textarea"
                    label="Descripción"
                    placeholder="Características especiales, equipamiento, etc."
                    value="{{ old('description', $room->description ?? '') }}"
                    icon=""
                    help="Información adicional sobre la sala"
                    rows="3"
                    style="min-width: 480px !important; width: 100% !important;"
                />
            </div>
        </div>
                    </div>
                </div>
                </x-hci-form-section>

                {{-- Sección 3: Equipamiento --}}
                <x-hci-form-section 
                    :step="3" 
                    title="Equipamiento" 
                    description="Selecciona las condiciones disponibles"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
                    section-id="equipamiento"
                    :editing="$editing"
                >
                    @php
                        $condiciones = [
                            'calefaccion' => 'Calefacción',
                            'energia_electrica' => 'Energía Eléctrica',
                            'existe_aseo' => 'Aseo Disponible',
                            'plumones' => 'Plumones',
                            'borrador' => 'Borrador',
                            'pizarra_limpia' => 'Pizarra Limpia',
                            'computador_funcional' => 'Computador Funcional',
                            'cables_computador' => 'Cables del Computador',
                            'control_remoto_camara' => 'Control Remoto de Cámara',
                            'televisor_funcional' => 'Televisor Funcional',
                        ];
                    @endphp
                    <div class="space-y-6">
                        {{-- Contenedor especial tipo tarjeta para mejorar legibilidad (excepción de estilo) --}}
                        <div class="rounded-xl border border-[#c4dafa]/60 bg-[#fcffff] p-6 shadow-sm">
                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach ($condiciones as $campo => $label)
                                <label class="flex items-center space-x-3 p-4 rounded-lg border border-[#c4dafa]/50 hover:bg-[#c4dafa]/20 transition-colors">
                                    <input type="checkbox" name="{{ $campo }}" id="{{ $campo }}"
                                        {{ old($campo, $room->$campo ?? false) ? 'checked' : '' }}
                                        class="hci-checkbox">
                                    <span class="text-sm text-[#005187] leading-snug">{{ $label }}</span>
                                </label>
                            @endforeach
                            </div>
                        </div>
                    </div>
                </x-hci-form-section>

                {{-- Sección 4: Resumen --}}
                <x-hci-form-section 
                    :step="4" 
                    title="Resumen" 
                    description="Revisa y confirma los datos antes de enviar"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
                    section-id="resumen"
                    :is-last="true"
                    :editing="$editing"
                >
                    <div class="bg-[#c4dafa]/30 dark:bg-[#84b6f4]/10 rounded-lg p-6 border border-[#84b6f4]/30 w-full">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Nombre</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-name">—</p>
                            </div>
                            <div class="md:col-span-1 lg:col-span-2 bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Ubicación</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg break-words" id="resumen-location">—</p>
                            </div>
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Capacidad</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-capacity">—</p>
                            </div>
                            <div class="md:col-span-2 lg:col-span-3 xl:col-span-3 bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Descripción</span>
                                <p class="text-gray-900 dark:text-white font-medium" id="resumen-description">—</p>
                            </div>
                            <div class="md:col-span-2 lg:col-span-3 xl:col-span-3 bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Equipamiento</span>
                                <div id="resumen-equipamiento" class="flex flex-wrap gap-2">—</div>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-[#fcffff] dark:bg-gray-800 rounded-lg border border-[#84b6f4]/20">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <strong>Nota:</strong> Revisa que toda la información sea correcta antes de guardar.
                            </p>
                        </div>
                    </div>
                </x-hci-form-section>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    @vite('resources/js/rooms-form-wizard.js')
@endpush


