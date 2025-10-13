{{-- 
    Componente Wizard Layout Genérico
    Props requeridas:
    - title: Título base del recurso (ej: "Clase", "Curso", "Sala")
    - editing: Boolean que indica si se está editando
    - description: Descripción para crear o editar
    - sidebarComponent: Nombre del componente de sidebar (ej: "classes-progress-sidebar")
    - formAction: Ruta del formulario
    - formMethod: Método HTTP (POST, PUT)
    - formId: ID del formulario (opcional)
    - formDataAttributes: Array de data-attributes para el form (opcional)
--}}

@props([
    'title' => '',
    'editing' => false,
    'createDescription' => '',
    'editDescription' => '',
    'sidebarComponent' => '', // Nombre del componente específico (si existe)
    'steps' => [], // Array de pasos para sidebar genérico
    'formAction' => '',
    'formMethod' => 'POST',
    'formId' => '',
    'formDataAttributes' => [],
    'formEnctype' => '' // Para formularios con archivos
])

{{-- Contenedor principal con principios HCI --}}
<div class="hci-container">
    <div class="hci-section">
        <h1 class="hci-heading-1 flex items-center">
            @if($editing)
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
                Editar {{ $title }}
            @else
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                </svg>
                Nuevo {{ $title }}
            @endif
        </h1>
        <p class="hci-text">
            {{ $editing ? $editDescription : $createDescription }}
        </p>
    </div>

    {{-- Layout principal con progreso lateral --}}
    <div class="hci-wizard-layout">
        {{-- Barra de progreso lateral izquierda --}}
        @if($sidebarComponent)
            {{-- Usa sidebar específico si se proporciona --}}
            <x-dynamic-component :component="$sidebarComponent" />
        @else
            {{-- Usa sidebar genérico con los pasos proporcionados --}}
            <x-hci-progress-sidebar :steps="$steps" />
        @endif

        {{-- Contenido principal del formulario --}}
        <div class="hci-form-content">
            <form 
                @if($formId) id="{{ $formId }}" @endif
                class="hci-form" 
                method="POST" 
                action="{{ $formAction }}"
                @if($editing) data-editing="true" @endif
                @if($formEnctype) enctype="{{ $formEnctype }}" @endif
                @foreach($formDataAttributes as $key => $value)
                    @if($key === 'enctype')
                        enctype="{{ $value }}"
                    @else
                        data-{{ $key }}="{{ $value }}"
                    @endif
                @endforeach
            >
                @csrf
                @if($editing && $formMethod === 'PUT') 
                    @method('PUT') 
                @endif

                {{-- Contenido del slot (las secciones del wizard) --}}
                {{ $slot }}

            </form>
        </div>
    </div>
</div>

