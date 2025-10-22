{{-- Editar Curso con Principios HCI --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]"></h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Módulos', 'url' => route('courses.index')],
        ['label' => 'Editar módulo', 'url' => '#']
    ]" />

    <div class="p-6 max-w-full mx-auto">
        @include('courses.form-wizard', [
            'course' => $course,
            'magisters' => $magisters,
            'periods' => $periods,
            'allCourses' => $allCourses
        ])
    </div>
</x-app-layout>

<script>
    window.PERIODS = @json($periods);
</script>


