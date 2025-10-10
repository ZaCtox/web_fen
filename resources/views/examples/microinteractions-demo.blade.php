<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🎨 Microinteracciones HCI
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-hci-microinteractions-demo />
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/microinteractions.js')
    @endpush
</x-app-layout>



