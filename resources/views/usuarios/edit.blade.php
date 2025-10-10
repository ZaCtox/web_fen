<x-app-layout>
    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Usuarios', 'url' => route('usuarios.index')],
        ['label' => 'Editar Usuario', 'url' => '#']
    ]" />
    
    @include('usuarios.form-wizard', ['usuario' => $usuario])
</x-app-layout>


