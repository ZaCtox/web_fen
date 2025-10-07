<x-app-layout>
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

    @include('usuarios.form-wizard')
</x-app-layout>