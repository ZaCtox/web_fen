@php
    $editing = $editing ?? false;
    $period = $period ?? null;
@endphp


{{-- Contenedor principal con principios HCI --}}
<div class="hci-container">
    <div class="hci-section">
        <h1 class="hci-heading-1 flex items-center">
            @if($editing)
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
                Editar Período Académico
            @else
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
                Crear Período Académico
            @endif
        </h1>
        <p class="hci-text">
            {{ $editing ? 'Modifica la información del período académico.' : 'Registra un nuevo período académico con información organizada.' }}
        </p>
    </div>

    {{-- Layout principal con progreso lateral --}}
    <div class="hci-wizard-layout">
        {{-- Barra de progreso lateral izquierda --}}
        <x-periods-progress-sidebar />

        {{-- Contenido principal del formulario --}}
        <div class="hci-form-content">
            <form class="hci-form" method="POST" action="{{ $editing ? route('periods.update', $period) : route('periods.store') }}">
                @csrf
                @if($editing) @method('PUT') @endif

                {{-- Paso 1: Información Básica --}}
                <x-hci-form-section 
                    :step="1" 
                    title="Información Básica" 
                    description="Define el año académico y trimestre del período"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
                    section-id="informacion"
                    :editing="$editing ?? false"
                    :isActive="true"
                    :isFirst="true"
                >
                    <div class="flex flex-col md:flex-row md:items-start md:gap-16">
                        <div class="w-full md:w-auto">
                            <x-hci-field 
                                name="anio" 
                                type="select" 
                                label="Año Académico" 
                                :required="true"
                                icon=""
                                help="Selecciona el año del programa (Año 1: Trimestres I, II, III | Año 2: Trimestres IV, V, VI)"
                                id="anio-select"
                                style="width: 250px !important;"
                            >
                                <option value="">-- Selecciona un Año --</option>
                                <option value="1" {{ old('anio', $period->anio ?? '') == '1' ? 'selected' : '' }}>Año 1</option>
                                <option value="2" {{ old('anio', $period->anio ?? '') == '2' ? 'selected' : '' }}>Año 2</option>
                            </x-hci-field>
                        </div>

                        <div class="w-full md:w-auto">
                            <x-hci-field 
                                name="cohorte" 
                                type="select" 
                                label="ciclo Académica" 
                                :required="true"
                                icon=""
                                help="Selecciona la ciclo académica (período completo del programa)"
                                id="ciclo-select"
                                style="width: 250px !important;"
                            >
                                <option value="">-- Selecciona una ciclo --</option>
                                <option value="2025-2026" {{ old('cohorte', $period->cohorte ?? '') == '2025-2026' ? 'selected' : '' }}>2025-2026 (Actual)</option>
                                <option value="2024-2025" {{ old('cohorte', $period->cohorte ?? '') == '2024-2025' ? 'selected' : '' }}>2024-2025 (Pasada)</option>
                                <option value="2026-2027" {{ old('cohorte', $period->cohorte ?? '') == '2026-2027' ? 'selected' : '' }}>2026-2027 (Futura)</option>
                            </x-hci-field>
                        </div>

                        <div class="w-full md:w-auto">
                            <x-hci-field 
                                name="numero" 
                                type="select" 
                                label="Trimestre" 
                                :required="true"
                                icon=""
                                help="Selecciona el trimestre del año"
                                id="numero-select"
                                style="width: 300px !important;"
                                value="{{ old('numero', $period->numero ?? '') }}"
                            >
                                <option value="">-- Selecciona un trimestre --</option>
                                @php
                                    $anioSeleccionado = old('anio', $period->anio ?? '');
                                    $trimestres = $anioSeleccionado == '1' ? [1, 2, 3] : ($anioSeleccionado == '2' ? [4, 5, 6] : []);
                                @endphp
                                @foreach($trimestres as $trimestre)
                                    <option value="{{ $trimestre }}" {{ old('numero', $period->numero ?? '') == $trimestre ? 'selected' : '' }}>
                                        Trimestre {{ $trimestre }}
                                    </option>
                                @endforeach
                            </x-hci-field>
                        </div>
                    </div>
                </x-hci-form-section>

                {{-- Paso 2: Fechas --}}
                <x-hci-form-section 
                    :step="2" 
                    title="Fechas del Período" 
                    description="Establece las fechas de inicio y término del período académico"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zM4 7h12v9a1 1 0 01-1 1H5a1 1 0 01-1-1V7z'/></svg>"
                    section-id="fechas"
                    :editing="$editing ?? false"
                >
                    <div class="flex flex-col md:flex-row md:items-start md:gap-16">
                        <div class="w-full md:w-auto">
                            <x-hci-field 
                                name="fecha_inicio" 
                                type="date" 
                                label="Fecha de Inicio" 
                                :required="true"
                                icon=""
                                help="Fecha de inicio del período académico"
                                value="{{ old('fecha_inicio', $period?->fecha_inicio?->format('Y-m-d') ?? '') }}"
                                style="width: 300px !important;"
                            />
                        </div>

                        <div class="w-full md:w-auto">
                            <x-hci-field 
                                name="fecha_fin" 
                                type="date" 
                                label="Fecha de Término" 
                                :required="true"
                                icon=""
                                help="Fecha de término del período académico"
                                value="{{ old('fecha_fin', $period?->fecha_fin?->format('Y-m-d') ?? '') }}"
                                style="width: 300px !important;"
                            />
                        </div>
                    </div>
                </x-hci-form-section>

                {{-- Paso 3: Resumen --}}
                <x-hci-form-section 
                    :step="3" 
                    title="Resumen" 
                    description="Revisa la información antes de guardar"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
                    section-id="resumen"
                    :editing="$editing ?? false"
                    :isLast="true"
                >
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Año Académico</h4>
                            <p id="summary-anio" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Trimestre</h4>
                            <p id="summary-trimestre" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg md:col-span-2">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Fecha de Inicio</h4>
                            <p id="summary-fecha-inicio" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Fecha de Término</h4>
                            <p id="summary-fecha-fin" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                        </div>
                    </div>
                </x-hci-form-section>
            </form>
        </div>
    </div>
</div>

{{-- Script manejado por periods-form-wizard.js --}}






