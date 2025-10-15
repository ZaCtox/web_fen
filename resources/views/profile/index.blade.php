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
        @include('profile.partials.foto-perfil')

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

<script>
// Actualizar preview del avatar en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const currentFotoPreview = document.getElementById('current-foto-preview');
    const userInitials = '{{ $user->iniciales }}';
    
    // Función para actualizar el preview cuando se selecciona un color
    window.updateAvatarPreviewColor = function(color) {
        const avatarUrl = `https://ui-avatars.com/api/?name=${userInitials}&background=${color}&color=ffffff&size=300&bold=true&font-size=0.4`;
        
        if (currentFotoPreview) {
            currentFotoPreview.src = avatarUrl + '&_t=' + Date.now();
            currentFotoPreview.setAttribute('onclick', `openPhotoModal('${avatarUrl}')`);
        }
    };
});
</script>

