<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4]">
            {{ __('Mi Perfil') }}
        </h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Mi Perfil', 'url' => '#']
    ]" />

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Foto de Perfil --}}
        <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <h3 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4] mb-4">Foto de Perfil</h3>
            
            <div class="flex flex-col md:flex-row items-center gap-6">
                {{-- Preview de la foto actual --}}
                <div class="flex-shrink-0 relative group" id="foto-preview-container">
                    <img id="current-foto-preview" 
                         src="{{ $user->foto ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=84b6f4&color=000000&size=300&bold=true' }}" 
                         alt="{{ $user->name }}" 
                         class="w-32 h-32 rounded-full object-cover border-4 border-[#84b6f4] shadow-lg cursor-pointer hover:opacity-75 transition-all hover:scale-105"
                         onclick="openPhotoModal('{{ $user->foto ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=84b6f4&color=000000&size=300&bold=true' }}')">
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
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-md transition-all duration-200 font-semibold text-sm hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
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

            @if(session('foto-updated'))
                <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <p class="text-sm text-green-700 dark:text-green-300">
                        ✓ {{ session('foto-updated') }}
                    </p>
                </div>
            @endif
        </div>

        {{-- Información de usuario --}}
        <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <h3 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4]">Información de la Cuenta</h3>
            <p class="mt-2 text-gray-600 dark:text-gray-400"><span class="font-semibold text-[#4d82bc] dark:text-[#84b6f4]">Nombre:</span>
                {{ $user->name }}</p>
            <p class="mt-1 text-gray-600 dark:text-gray-400"><span class="font-semibold text-[#4d82bc] dark:text-[#84b6f4]">Correo:</span>
                {{ $user->email }}</p>
            <p class="mt-1 text-gray-600 dark:text-gray-400"><span class="font-semibold text-[#4d82bc] dark:text-[#84b6f4]">Rol:</span>
                {{ ucfirst(str_replace('_', ' ', $user->rol)) }}</p>
        </div>

        {{-- Actualización de contraseña --}}
        <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-xl space-y-4">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Eliminar cuenta --}}
{{--         <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-xl space-y-4">
                @include('profile.partials.delete-user-form')
            </div>
        </div> --}}

    </div>
</x-app-layout>

<script>
// Funciones de drag & drop para foto de perfil
function handleProfileDragOver(e) {
    e.preventDefault();
    e.stopPropagation();
    document.getElementById('profile-foto-drop-zone').classList.add('border-[#4d82bc]', 'bg-blue-50', 'dark:bg-blue-900/20');
}

function handleProfileDragLeave(e) {
    e.preventDefault();
    e.stopPropagation();
    document.getElementById('profile-foto-drop-zone').classList.remove('border-[#4d82bc]', 'bg-blue-50', 'dark:bg-blue-900/20');
}

function handleProfileFotoDrop(e) {
    e.preventDefault();
    e.stopPropagation();
    
    document.getElementById('profile-foto-drop-zone').classList.remove('border-[#4d82bc]', 'bg-blue-50', 'dark:bg-blue-900/20');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        handleProfileFotoFile(files[0]);
    }
}

function handleProfileFotoSelect(e) {
    const file = e.target.files[0];
    if (file) {
        handleProfileFotoFile(file);
    }
}

function validateProfileImageFile(file) {
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    if (!allowedTypes.includes(file.type)) {
        alert('Por favor, selecciona una imagen JPG, PNG o WEBP.');
        return false;
    }
    
    if (file.size > 2 * 1024 * 1024) {
        alert('La imagen no puede exceder los 2MB.');
        return false;
    }
    
    return true;
}

function handleProfileFotoFile(file) {
    if (validateProfileImageFile(file)) {
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        document.getElementById('profile-foto-input').files = dataTransfer.files;
        
        const previewUrl = URL.createObjectURL(file);
        const previewImg = document.getElementById('current-foto-preview');
        if (previewImg) {
            previewImg.src = previewUrl;
            // Actualizar el onclick para mostrar la nueva foto en el modal
            previewImg.onclick = function() { openPhotoModal(previewUrl); };
        }
        
        document.getElementById('profile-foto-name').textContent = file.name;
        document.getElementById('profile-foto-preview-info').classList.remove('hidden');
        document.getElementById('profile-foto-drop-text').textContent = 'Foto seleccionada';
    }
}

function clearProfileFoto() {
    document.getElementById('profile-foto-input').value = '';
    document.getElementById('profile-foto-preview-info').classList.add('hidden');
    document.getElementById('profile-foto-drop-text').textContent = 'Arrastra tu foto aquí o haz clic para seleccionar';
}

// Modal para ampliar foto
function openPhotoModal(photoUrl) {
    const modal = document.getElementById('photo-modal');
    const modalImg = document.getElementById('modal-photo');
    
    if (modal && modalImg) {
        modalImg.src = photoUrl;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Animación de entrada
        setTimeout(() => {
            modal.classList.add('opacity-100');
            modal.querySelector('.relative').classList.add('scale-100');
        }, 10);
    }
}

function closePhotoModal() {
    const modal = document.getElementById('photo-modal');
    if (modal) {
        // Animación de salida
        modal.classList.remove('opacity-100');
        modal.querySelector('.relative').classList.remove('scale-100');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 200);
    }
}

// Cerrar modal con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePhotoModal();
    }
});
</script>

<!-- Modal para ampliar foto -->
<div id="photo-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 transition-opacity duration-200" style="background-color: rgba(0,0,0,0.9);" onclick="closePhotoModal()">
    <div class="relative max-w-4xl max-h-screen transform scale-95 transition-transform duration-200" onclick="event.stopPropagation()">
        <button onclick="closePhotoModal()" class="absolute -top-12 right-0 text-white hover:text-gray-300 bg-black bg-opacity-50 hover:bg-opacity-70 rounded-full p-3 transition-all hover:scale-110" title="Cerrar (ESC)">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <img id="modal-photo" src="" alt="Foto ampliada" class="max-w-full max-h-screen rounded-lg shadow-2xl">
        <p class="text-white text-center mt-4 text-sm opacity-75">🔍 Presiona ESC o haz clic fuera para cerrar</p>
    </div>
</div>

