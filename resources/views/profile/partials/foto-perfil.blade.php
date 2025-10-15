{{-- Foto de Perfil --}}
<div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
    <h3 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4] mb-4">Foto de Perfil</h3>
    
    <div class="flex flex-col md:flex-row items-center gap-6">
        {{-- Preview de la foto actual --}}
        <div class="flex-shrink-0 relative group" id="foto-preview-container">
            <img id="current-foto-preview" 
                 src="{{ $user->foto ?? route('profile.get-avatar') }}" 
                 alt="{{ $user->name }}" 
                 class="w-32 h-32 rounded-full object-cover border-4 border-[#84b6f4] shadow-lg cursor-pointer hover:opacity-75 transition-all hover:scale-105"
                 onclick="openPhotoModal('{{ $user->foto ?? route('profile.get-avatar') }}')">
            {{-- Icono de lupa al hacer hover --}}
            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                <svg class="w-10 h-10 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                </svg>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 text-center mt-2">Clic para ampliar</p>
        </div>

        {{-- Formulario de actualización de foto --}}
        <div class="flex-1 w-full">
            <form action="{{ route('profile.update-foto') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="foto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Cambiar Foto de Perfil
                    </label>
                    
                    {{-- Área de drag & drop --}}
                    <div id="profile-foto-drop-zone" 
                         class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-[#4d82bc] transition-colors cursor-pointer"
                         role="button"
                         tabindex="0"
                         onclick="document.getElementById('profile-foto-input').click()"
                         ondrop="handleProfileFotoDrop(event)" 
                         ondragover="handleProfileDragOver(event)" 
                         ondragleave="handleProfileDragLeave(event)">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            <span id="profile-foto-drop-text">Arrastra tu foto aquí o haz clic para seleccionar</span>
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            JPG, JPEG, PNG, WEBP • Máximo 2MB
                        </p>
                    </div>

                    <input type="file" 
                           name="foto" 
                           id="profile-foto-input" 
                           class="hidden" 
                           accept="image/jpeg,image/jpg,image/png,image/webp"
                           onchange="handleProfileFotoSelect(event)">
                
                    <div id="profile-foto-preview-info" class="hidden mt-3 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span id="profile-foto-name" class="text-sm font-medium text-green-700 dark:text-green-300"></span>
                            </div>
                            <button type="button" onclick="clearProfileFoto()" class="text-red-500 hover:text-red-700">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    @error('foto')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow-md transition-all duration-200 font-semibold text-sm hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Actualizar Foto
                    </button>

                    @if($user->foto)
                        <button type="button" 
                                onclick="if(confirm('¿Estás seguro de que quieres eliminar tu foto de perfil?')) { document.getElementById('delete-foto-form').submit(); }"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-[#e57373] hover:bg-[#d32f2f] text-white rounded-lg shadow-md transition-all duration-200 font-semibold text-sm hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Eliminar Foto
                        </button>
                    @endif
                </div>
            </form>

            @if($user->foto)
                <form id="delete-foto-form" action="{{ route('profile.delete-foto') }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </div>
    </div>

    {{-- Configuración de Avatar (solo si no hay foto) --}}
    @if(!$user->foto)
        <div class="mt-6 p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Configuración de Avatar
            </h3>
            
            <form action="{{ route('profile.update-avatar') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                {{-- Selector de Color --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Color del Avatar
                    </label>
                    
                    {{-- Colores predefinidos --}}
                    <div class="flex flex-wrap gap-3 mb-3">
                        @php
                            $colores = [
                                '005187' => 'Azul oscuro',
                                '4d82bc' => 'Azul medio',
                                '84b6f4' => 'Azul claro',
                                '00acc1' => 'Cyan',
                                '66bb6a' => 'Verde',
                                'ffa726' => 'Naranja',
                                'ef5350' => 'Rojo',
                                'ffca28' => 'Amarillo',
                                'ab47bc' => 'Morado',
                                '78909c' => 'Gris',
                            ];
                            $colorActual = old('avatar_color', $user->avatar_color) ?? '4d82bc';
                        @endphp
                        
                        @foreach($colores as $codigo => $nombre)
                            <label class="relative cursor-pointer group">
                                <input type="radio" 
                                       name="avatar_color" 
                                       value="{{ $codigo }}"
                                       class="sr-only peer"
                                       {{ $colorActual === $codigo ? 'checked' : '' }}
                                       onchange="updateAvatarPreviewColor('{{ $codigo }}')">
                                <div class="w-12 h-12 rounded-lg border-4 transition-all duration-200 peer-checked:border-gray-900 dark:peer-checked:border-white peer-checked:scale-110 border-gray-300 dark:border-gray-600 hover:scale-105 shadow-md" 
                                     style="background-color: #{{ $codigo }};"
                                     title="{{ $nombre }}">
                                </div>
                            </label>
                        @endforeach
                    </div>
                    
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Selecciona un color para tu avatar. Si no seleccionas uno, se usará uno automático basado en tu perfil.
                    </p>
                </div>

                {{-- Botón Guardar --}}
                <div class="flex justify-end">
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-6 py-3 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow-md transition-all duration-200 font-semibold text-sm hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Guardar Color
                    </button>
                </div>
            </form>
        </div>
    @endif

    @if(session('foto-updated'))
        <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <p class="text-sm text-green-700 dark:text-green-300">
                ✓ {{ session('foto-updated') }}
            </p>
        </div>
    @endif

    @if(session('avatar-updated'))
        <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <p class="text-sm text-green-700 dark:text-green-300">
                ✓ {{ session('avatar-updated') }}
            </p>
        </div>
    @endif
</div>
