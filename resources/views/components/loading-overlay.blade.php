{{-- Componente Loading Overlay - Efecto Doherty --}}
@props(['message' => 'Cargando...'])

<div x-show="loading" 
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-cloak
     class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl flex flex-col items-center gap-4 max-w-sm">
        <x-loading-spinner size="lg" color="blue" />
        <p class="text-gray-700 dark:text-gray-300 font-medium">{{ $message }}</p>
    </div>
</div>
