{{-- 
    🎨 Componente de demostración de microinteracciones HCI
    Implementa las leyes de HCI a través de microinteracciones
--}}

<div class="hci-microinteractions-demo space-y-8 p-6">
    {{-- Header --}}
    <div class="text-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
            🎨 Microinteracciones HCI
        </h2>
        <p class="text-gray-600 dark:text-gray-400">
            Implementando las leyes de HCI a través de microinteracciones
        </p>
    </div>

    {{-- Ley de Fitts - Botones grandes y accesibles --}}
    <div class="hci-card hci-card-hover p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            🎯 Ley de Fitts - Botones grandes y accesibles
        </h3>
        <div class="flex flex-wrap gap-4">
            <button class="hci-button hci-button-ripple bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium">
                Botón con Ripple
            </button>
            <button class="hci-button bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-medium">
                Botón Hover
            </button>
            <button class="hci-button bg-purple-500 hover:bg-purple-600 text-white px-6 py-3 rounded-lg font-medium">
                Botón Active
            </button>
        </div>
    </div>

    {{-- Ley de Miller - Formularios con agrupación --}}
    <div class="hci-card hci-card-hover p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            📝 Ley de Miller - Formularios con agrupación lógica
        </h3>
        <form class="hci-form space-y-4 max-w-md">
            <div class="space-y-2">
                <label class="hci-label block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nombre completo
                </label>
                <input type="text" class="hci-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none" required>
            </div>
            <div class="space-y-2">
                <label class="hci-label block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Email
                </label>
                <input type="email" class="hci-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none" required>
            </div>
            <div class="space-y-2">
                <label class="hci-label block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Teléfono
                </label>
                <input type="tel" class="hci-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none">
            </div>
            <button type="submit" class="hci-button hci-button-ripple bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium w-full">
                Enviar Formulario
            </button>
        </form>
    </div>

    {{-- Ley de Prägnanz - Animaciones limpias --}}
    <div class="hci-card hci-card-hover p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            🎨 Ley de Prägnanz - Animaciones limpias
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="hci-animate-on-scroll bg-gradient-to-r from-blue-400 to-blue-600 text-white p-4 rounded-lg text-center">
                <h4 class="font-semibold">Fade In</h4>
                <p class="text-sm opacity-90">Aparece suavemente</p>
            </div>
            <div class="hci-animate-on-scroll bg-gradient-to-r from-green-400 to-green-600 text-white p-4 rounded-lg text-center">
                <h4 class="font-semibold">Slide In</h4>
                <p class="text-sm opacity-90">Desliza desde la derecha</p>
            </div>
            <div class="hci-animate-on-scroll bg-gradient-to-r from-purple-400 to-purple-600 text-white p-4 rounded-lg text-center">
                <h4 class="font-semibold">Bounce In</h4>
                <p class="text-sm opacity-90">Rebota al aparecer</p>
            </div>
        </div>
    </div>

    {{-- Estados de carga --}}
    <div class="hci-card hci-card-hover p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            ⏳ Estados de carga
        </h3>
        <div class="flex flex-wrap gap-4">
            <div class="hci-skeleton w-32 h-8"></div>
            <div class="hci-skeleton-avatar"></div>
            <div class="space-y-2">
                <div class="hci-skeleton-text w-48"></div>
                <div class="hci-skeleton-text w-32"></div>
                <div class="hci-skeleton-text w-40"></div>
            </div>
        </div>
    </div>

    {{-- Progress indicators --}}
    <div class="hci-card hci-card-hover p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            📊 Indicadores de progreso
        </h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Progreso: 75%</label>
                <div class="hci-progress-bar h-2">
                    <div class="hci-progress-fill bg-blue-500" style="width: 75%"></div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Carga: 45%</label>
                <div class="hci-progress-bar h-2">
                    <div class="hci-progress-fill bg-green-500" style="width: 45%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Notificaciones --}}
    <div class="hci-card hci-card-hover p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            🔔 Notificaciones animadas
        </h3>
        <div class="space-y-2">
            <button onclick="Microinteractions.showToast('¡Operación exitosa!', 'success')" 
                    class="hci-button bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm">
                Mostrar Toast de Éxito
            </button>
            <button onclick="Microinteractions.showToast('Error en la operación', 'error')" 
                    class="hci-button bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">
                Mostrar Toast de Error
            </button>
            <button onclick="Microinteractions.showToast('Información importante', 'info')" 
                    class="hci-button bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                Mostrar Toast de Info
            </button>
        </div>
    </div>

    {{-- Enlaces con hover --}}
    <div class="hci-card hci-card-hover p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            🔗 Enlaces con microinteracciones
        </h3>
        <div class="space-y-2">
            <a href="#" class="hci-link text-blue-500 hover:text-blue-700 font-medium">
                Enlace con efecto de subrayado
            </a>
            <br>
            <a href="#" class="hci-link text-green-500 hover:text-green-700 font-medium">
                Otro enlace con animación
            </a>
        </div>
    </div>
</div>

{{-- Estilos adicionales para el demo --}}
<style>
.hci-microinteractions-demo {
    max-width: 1200px;
    margin: 0 auto;
}

.hci-card {
    @apply bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700;
}

.hci-toast {
    @apply fixed top-4 right-4 z-50 px-4 py-3 rounded-lg text-white font-medium shadow-lg;
}

.hci-toast-success {
    @apply bg-green-500;
}

.hci-toast-error {
    @apply bg-red-500;
}

.hci-toast-info {
    @apply bg-blue-500;
}

@keyframes hci-fade-out {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-20px);
    }
}
</style>



