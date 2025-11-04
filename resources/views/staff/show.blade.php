{{-- Detalle de Staff.blade.php --}}
@section('title', 'Detalle miembro')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#c9e4ff]">Detalle del Miembro</h2>
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

    <!-- Header sticky con acciones -->
    <div class="sticky top-0 z-10 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm mb-6">
        <div class="p-4 max-w-5xl mx-auto">
            <div class="flex items-center justify-between gap-3">
                <!-- Navegación -->
                <a href="{{ route('staff.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-sm font-medium"
                   aria-label="Volver al listado de miembros del equipo">
                    <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-5 h-5">
                </a>
                
                <!-- Acciones principales -->
                @if(tieneRol(['director_administrativo','decano']))
                <div class="flex gap-3">
                    <a href="{{ route('staff.edit', $staff) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-[#84b6f4] hover:bg-[#4d82bc] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-sm font-semibold"
                       aria-label="Editar información de {{ $staff->nombre }}">
                        <img src="{{ asset('icons/editw.svg') }}" alt="Editar" class="w-5 h-5">
                    </a>
                    
                    @php
                        $msg = "¿Seguro que deseas eliminar a {$staff->nombre}?";
                    @endphp
                    <form action="{{ route('staff.destroy', $staff) }}" method="POST" class="form-eliminar"
                          data-confirm="{{ $msg }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-[#e57373] hover:bg-[#d32f2f] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 text-sm font-semibold"
                                aria-label="Eliminar a {{ $staff->nombre }}">
                            <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar" class="w-5 h-5">
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="p-6 max-w-5xl mx-auto">
        <div class="rounded-2xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            
            {{-- Foto de perfil centrada --}}
            <div class="flex justify-center pt-8 pb-4">
                <img src="{{ $staff->foto_perfil }}" 
                     alt="Foto de {{ $staff->nombre }}" 
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($staff->nombre) }}&background=84b6f4&color=000000&size=300&bold=true'"
                     class="w-40 h-40 rounded-full object-cover border-4 border-[#84b6f4] dark:border-[#4d82bc] shadow-xl">
            </div>

            {{-- Botón para eliminar foto (solo si tiene foto) --}}
            @if($staff->foto && tieneRol(['director_administrativo','decano']))
                <div class="flex justify-center pb-4">
                    <form action="{{ route('staff.delete-foto', $staff) }}" method="POST" id="delete-foto-form-show">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                                onclick="if(confirm('¿Estás seguro de que quieres eliminar la foto de {{ $staff->nombre }}? Se generará un avatar con las iniciales.')) { document.getElementById('delete-foto-form-show').submit(); }"
                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-[#e57373] hover:bg-[#d32f2f] text-white rounded-lg shadow-sm transition-all duration-200 text-xs font-medium hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Eliminar Foto
                        </button>
                    </form>
                </div>
            @endif

            {{-- Información principal centrada --}}
            <div class="text-center mb-8">
                <h3 class="text-3xl font-bold text-[#005187] dark:text-[#c9e4ff] mb-2">{{ $staff->nombre }}</h3>
                <p class="text-lg text-[#4d82bc] dark:text-[#a8d1f7]">{{ $staff->cargo }}</p>
            </div>

            {{-- Información de contacto como tarjetas clickeables --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 px-6">
                {{-- Teléfono clickeable --}}
                @if($staff->telefono)
                <a href="tel:{{ $staff->telefono }}" 
                   class="bg-[#c4dafa] dark:bg-[#4d82bc]/20 rounded-xl p-4 border border-[#84b6f4]/30 hover:bg-[#b8d4f4] dark:hover:bg-[#4d82bc]/30 transition-colors group"
                   aria-label="Llamar a {{ $staff->nombre }} al {{ $staff->telefono }}">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#4d82bc] rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-[#005187] dark:text-[#c9e4ff] uppercase tracking-wide">Teléfono</div>
                            <div class="text-sm font-semibold text-[#005187] dark:text-[#c9e4ff] group-hover:underline">{{ $staff->telefono }}</div>
                        </div>
                    </div>
                </a>
                @else
                <div class="bg-gray-100 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-300 dark:border-gray-600">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-400 dark:bg-gray-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Teléfono</div>
                            <div class="text-sm font-semibold text-gray-500 dark:text-gray-400">No registrado</div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Anexo (no clickeable, solo informativo) --}}
                <div class="bg-[#c4dafa] dark:bg-[#4d82bc]/20 rounded-xl p-4 border border-[#84b6f4]/30">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#4d82bc] rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.243 3.03a1 1 0 01.727 1.213L9.53 6h2.94l.56-2.243a1 1 0 111.94.486L14.53 6H17a1 1 0 110 2h-2.97l-1 4H15a1 1 0 110 2h-2.47l-.56 2.242a1 1 0 11-1.94-.485L10.47 14H7.53l-.56 2.242a1 1 0 11-1.94-.485L5.47 14H3a1 1 0 110-2h2.97l1-4H5a1 1 0 110-2h2.47l.56-2.243a1 1 0 011.213-.727zM9.03 8l-1 4h2.938l1-4H9.031z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-[#005187] dark:text-[#c9e4ff] uppercase tracking-wide">Anexo</div>
                            <div class="text-sm font-semibold text-[#005187] dark:text-[#c9e4ff]">{{ $staff->anexo ?: 'No registrado' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Email clickeable --}}
                <a href="mailto:{{ $staff->email }}" 
                   class="bg-[#c4dafa] dark:bg-[#4d82bc]/20 rounded-xl p-4 border border-[#84b6f4]/30 hover:bg-[#b8d4f4] dark:hover:bg-[#4d82bc]/30 transition-colors group md:col-span-1"
                   aria-label="Enviar correo a {{ $staff->email }}">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#4d82bc] rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-xs font-medium text-[#005187] dark:text-[#c9e4ff] uppercase tracking-wide">Email</div>
                            <div class="text-sm font-semibold text-[#005187] dark:text-[#c9e4ff] break-all group-hover:underline">{{ $staff->email }}</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
