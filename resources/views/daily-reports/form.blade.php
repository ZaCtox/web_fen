{{-- Formulario de Reportes Diarios con Principios HCI --}}
@section('title', isset($dailyReport) ? 'Editar Reporte Diario' : 'Crear Reporte Diario')

@php
    $editing = isset($dailyReport);
@endphp

{{-- Layout genérico del wizard con datos reactivos --}}
<div x-data="{
    formData: {
        title: '{{ old('title', $dailyReport->title ?? $tituloSugerido ?? '') }}',
        report_date: '{{ old('report_date', isset($dailyReport) ? $dailyReport->report_date->format('Y-m-d') : ($today ?? '')) }}',
        summary: '{{ old('summary', $dailyReport->summary ?? '') }}'
    }
}">
<x-hci-wizard-layout 
    title="Reporte Diario"
    :editing="$editing"
    createDescription="Registra un nuevo reporte diario con todas las observaciones del día."
    editDescription="Modifica la información del reporte diario."
    sidebarComponent="daily-reports-progress-sidebar"
    :formAction="$editing ? route('daily-reports.update', $dailyReport) : route('daily-reports.store')"
    :formMethod="$editing ? 'PUT' : 'POST'"
    formEnctype="multipart/form-data"
>

    {{-- Sección 1: Información del Reporte --}}
    <x-hci-form-section 
        :step="1" 
        title="Información del Reporte" 
        description="Datos básicos del reporte diario"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z' clip-rule='evenodd'/></svg>"
        section-id="informacion"
        :is-active="true"
        :is-first="true"
        :editing="$editing"
    >
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Título del Reporte <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="title"
                   id="title"
                   x-model="formData.title"
                   placeholder="Ej: Reporte Jueves 29 de Octubre 2025"
                   required
                   maxlength="255"
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition">
        </div>

        <div>
            <label for="report_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Fecha del Reporte <span class="text-red-500">*</span>
            </label>
            <input type="date" 
                   name="report_date"
                   id="report_date"
                   x-model="formData.report_date"
                   required
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition">
        </div>

        <div class="md:col-span-2">
            <label for="summary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Resumen General (Opcional)
            </label>
            <textarea 
                   name="summary" 
                   id="summary"
                   x-model="formData.summary"
                   placeholder="Resumen general de las observaciones del día..."
                   rows="3"
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition resize-none"></textarea>
        </div>
    </x-hci-form-section>

    {{-- Sección 2: Observaciones del Día --}}
    <x-hci-form-section 
        :step="2" 
        title="Observaciones del Día" 
        description="Agrega todas las observaciones realizadas durante el día"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 2a1 1 0 000 2h2a1 1 0 100-2H9z'/><path fill-rule='evenodd' d='M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z' clip-rule='evenodd'/></svg>"
        section-id="observaciones"
        :editing="$editing"
    >
        <div class="w-full flex flex-col gap-">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Lista de Observaciones</h4>
                <button type="button " 
                        onclick="window.addEntryToContainer()"
                        class="hci-button hci-lift hci-focus-ring inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition-all duration-200">
                    <img src="{{ asset('icons/agregar.svg') }}" alt="Agregar" class="w-4 h-4">
                    <span>Agregar Observación</span>
                </button>
            </div>
            
            <div id="entradas-container" class="flex flex-col gap-4">
                {{-- Las entradas se agregarán dinámicamente aquí --}}
            </div>
        </div>
    </x-hci-form-section>

    {{-- Sección 3: Resumen Final --}}
    <x-hci-form-section 
        :step="3" 
        title="Resumen y Confirmación" 
        description="Revisa la información antes de guardar"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' clip-rule='evenodd'/></svg>"
        section-id="resumen"
        :is-last="true"
        :editing="$editing"
    >
        <div class="bg-[#c4dafa]/30 dark:bg-[#84b6f4]/10 rounded-lg p-6 border border-[#84b6f4]/30 w-full">                        
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Título -->
                <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                    <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Título del Reporte</span>
                    <p class="text-gray-900 dark:text-white font-medium" x-text="formData.title || 'No ingresado'"></p>
                </div>

                <!-- Fecha -->
                <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                    <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Fecha del Reporte</span>
                    <p class="text-gray-900 dark:text-white font-medium" x-text="formData.report_date || 'No ingresada'"></p>
                </div>

                <!-- Resumen -->
                <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20 md:col-span-2">
                    <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Resumen General</span>
                    <p class="text-gray-900 dark:text-white font-medium" x-text="formData.summary || 'No especificado'"></p>
                </div>
            </div>

            <div class="mt-6 p-4 bg-[#fcffff] dark:bg-gray-800 rounded-lg border border-[#84b6f4]/20">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    <strong>Nota:</strong> Revisa que toda la información sea correcta antes de proceder. 
                    {{ $editing ? 'Los cambios se aplicarán inmediatamente.' : 'Se creará un nuevo reporte diario.' }}
                </p>
            </div>
        </div>
    </x-hci-form-section>
</x-hci-wizard-layout>
</div>

{{-- Incluir JavaScript del wizard --}}
@push('scripts')
    @php
        $entriesData = [];
        if (isset($dailyReport) && $dailyReport->entries) {
            foreach ($dailyReport->entries as $entry) {
                $entriesData[] = [
                    'location_type' => $entry->location_type,
                    'room_id' => $entry->room_id,
                    'location_detail' => $entry->location_detail,
                    'observation' => $entry->observation,
                    'photo_url' => $entry->photo_url,
                ];
            }
        }
    @endphp
    <script>
        // Datos globales para el wizard
        window.dailyReportsData = {
            rooms: @json($rooms ?? []),
            entries: @json($entriesData)
        };
    </script>
    @vite('resources/js/daily-reports-form-wizard.js')
@endpush

