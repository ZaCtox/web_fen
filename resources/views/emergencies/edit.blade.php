{{-- Editar Emergencia.blade.php --}}
@section('title', 'Editar Emergencia')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Editar Emergencia</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto px-4">
        <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
            <form action="{{ route('emergencies.update', $emergency) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium mb-1">Título</label>
                    <input type="text" name="title" value="{{ old('title', $emergency->title) }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700"
                           maxlength="100" required>
                    @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Mensaje</label>
                    <textarea name="message" rows="5"
                              class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700"
                              required>{{ old('message', $emergency->message) }}</textarea>
                    @error('message') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Expira: {{ optional($emergency->expires_at)->format('d/m/Y H:i') ?? '—' }}
                        @if(!$emergency->active)
                            <span class="ml-2 px-2 py-1 text-xs rounded bg-gray-200 text-gray-800">Inactiva</span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        @if($emergency->active)
                            <form action="{{ route('emergencies.deactivate', $emergency) }}" method="POST"
                                  onsubmit="return confirm('¿Desactivar esta emergencia?')">
                                @csrf @method('PATCH')
                                <button type="submit" class="px-3 py-2 rounded bg-yellow-600 text-white hover:bg-yellow-700">
                                    Desactivar
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('emergencies.index') }}"
                           class="px-4 py-2 rounded bg-gray-200 text-gray-800 hover:bg-gray-300">Volver</a>
                        <button class="px-4 py-2 rounded bg-emerald-600 text-white hover:bg-emerald-700">
                            Guardar cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
