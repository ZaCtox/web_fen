{{-- Editar Emergencia.blade.php --}}
@section('title', 'Editar Emergencia')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Editar Emergencia</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto px-4">
        <div class="bg-[#fcffff] dark:bg-gray-900 shadow-lg rounded-lg p-6 border border-[#c4dafa] dark:border-gray-700 transition">
            <form action="{{ route('emergencies.update', $emergency) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Título --}}
                <div>
                    <label class="block text-sm font-semibold mb-2 text-[#005187] dark:text-[#84b6f4]">Título</label>
                    <input type="text" name="title" value="{{ old('title', $emergency->title) }}"
                        class="w-full rounded-md border border-[#c4dafa] dark:border-gray-700 dark:bg-gray-800 dark:text-white px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#84b6f4] transition"
                        maxlength="100" required>
                    @error('title') 
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                {{-- Mensaje --}}
                <div>
                    <label class="block text-sm font-semibold mb-2 text-[#005187] dark:text-[#84b6f4]">Mensaje</label>
                    <textarea name="message" rows="5"
                        class="w-full rounded-md border border-[#c4dafa] dark:border-gray-700 dark:bg-gray-800 dark:text-white px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#84b6f4] transition"
                        required>{{ old('message', $emergency->message) }}</textarea>
                    @error('message') 
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                {{-- Información de expiración y estado --}}
                <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                    <span>
                        Expira: {{ optional($emergency->expires_at)->format('d/m/Y H:i') ?? '—' }}
                    </span>
                    @if(!$emergency->active)
                        <span class="px-2 py-1 text-xs rounded bg-[#c4dafa] text-[#005187] font-medium">Inactiva</span>
                    @endif
                </div>

                {{-- Botones --}}
                <div class="flex items-center justify-between mt-6">
                    {{-- Volver a la izquierda --}}
                    <a href="{{ route('emergencies.index') }}"
                        class="inline-flex items-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-md shadow-md font-medium transition transform hover:scale-105">
                        <img src="{{ asset('icons/back.svg') }}" alt="back" class="w-5 h-5">
                    </a>

                    {{-- Desactivar y Guardar a la derecha --}}
                    <div class="flex items-center gap-2">
                        @if($emergency->active)
                            <form action="{{ route('emergencies.deactivate', $emergency) }}" method="POST"
                                onsubmit="return confirm('¿Desactivar esta emergencia?')">
                                @csrf 
                                @method('PATCH')
                                <button type="submit"
                                    class="inline-flex items-center justify-center bg-[#005187] hover:bg-[#4d82bc] text-white px-4 py-2 rounded-lg shadow-md text-sm font-medium transition transform hover:scale-105 gap-2">
                                    <img src="{{ asset('icons/pausew.svg') }}" alt="Pausar" class="w-5 h-5">
                                </button>
                            </form>
                        @endif

                        <button type="submit"
                            class="inline-flex items-center justify-center bg-[#005187] hover:bg-[#4d82bc] text-white px-4 py-2 rounded-lg shadow-md text-sm font-medium transition transform hover:scale-105 gap-2">
                            <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-5 h-5">
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
