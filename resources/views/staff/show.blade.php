{{-- Detalle de Staff.blade.php --}}
@section('title', 'Detalle miembro')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Detalle</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Nuestro Equipo', 'url' => route('staff.index')],
        ['label' => 'Detalle', 'url' => '#']
    ]" />

    {{-- Metas para toasts --}}
    @if(session('success'))
        <meta name="session-success" content="{{ session('success') }}">
    @endif

    @if(session('error'))
        <meta name="session-error" content="{{ session('error') }}">
    @endif

    <div class="p-6 max-w-5xl mx-auto">
        <div
            class="rounded-2xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            
            {{-- Foto de perfil centrada --}}
            <div class="flex justify-center pt-8 pb-4">
                <img src="{{ $staff->foto_perfil }}" 
                     alt="Foto de {{ $staff->nombre }}" 
                     class="w-40 h-40 rounded-full object-cover border-4 border-[#84b6f4] dark:border-[#4d82bc] shadow-xl">
            </div>

            <div class="flex flex-col md:flex-row">

                {{-- Información principal --}}
                <div class="flex-1 p-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-[#005187] dark:text-[#84b6f4] text-center md:text-left">{{ $staff->nombre }}</h3>
                        <p class="text-[#4d82bc] dark:text-[#84b6f4] mb-4 text-center md:text-left">{{ $staff->cargo }}</p>
                    </div>

                    {{-- Botones (siempre al fondo) --}}
                    <div class="flex flex-wrap gap-2 mt-4">
                        {{-- Botón volver --}}
                        <a href="{{ route('staff.index') }}" class="inline-flex items-center justify-center 
               w-15 px-4 py-2 bg-[#4d82bc] hover:bg-[#005187] 
               text-white rounded-md shadow-md transition">
                            <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-5 h-5 mr-1">

                        </a>

                        {{-- Botón editar --}}
                        <a href="{{ route('staff.edit', $staff) }}" class="inline-flex items-center justify-center 
               w-15 px-4 py-2 bg-[#4d82bc] hover:bg-[#005187] 
               text-white rounded-md shadow-md transition">
                            <img src="{{ asset('icons/editw.svg') }}" alt="Editar" class="w-5 h-5">
                        </a>

                        {{-- Botón eliminar --}}
                        @php
                            $msg = "¿Seguro que deseas eliminar a {$staff->nombre}?";
                        @endphp
                        <form action="{{ route('staff.destroy', $staff) }}" method="POST" class="form-eliminar"
                            data-confirm="{{ $msg }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center 
                   w-15 px-4 py-2 bg-[#e57373] hover:bg-[#f28b82] 
                   text-white rounded-md shadow-md transition">
                                <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar" class="w-4 h-5">
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Información secundaria --}}
                <div class="md:w-80 bg-[#4d82bc] text-white p-6 mt-6 md:mt-0 rounded-lg">
                    <div class="tracking-wide opacity-90">Teléfono</div>
                    <div class="mb-4 break-words">{{ $staff->telefono ?: '—' }}</div>

                    <div class="tracking-wide opacity-90">Anexo</div>
                    <div class="mb-4 break-words">{{ $staff->anexo ?: '—' }}</div>

                    <div class="tracking-wide opacity-90">Email</div>
                    <div class="break-words">{{ $staff->email }}</div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>


