@section('title', 'Editar Malla Curricular')
<x-app-layout>
    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Mallas Curriculares', 'url' => route('mallas-curriculares.index')],
        ['label' => $mallaCurricular->nombre, 'url' => route('mallas-curriculares.show', $mallaCurricular)],
        ['label' => 'Editar', 'url' => '#']
    ]" />

    {{-- Metas para toasts (las usa alerts.js) --}}
    @if(session('success'))
        <meta name="session-success" content="{{ session('success') }}">
    @endif
    @if(session('error'))
        <meta name="session-error" content="{{ session('error') }}">
    @endif
    @if($errors->any())
        <meta name="session-validate-error" content="{{ $errors->first() }}">
    @endif

    @include('mallas-curriculares.form-wizard', ['mallaCurricular' => $mallaCurricular, 'magisters' => $magisters])
</x-app-layout>




