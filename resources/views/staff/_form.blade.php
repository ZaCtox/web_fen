<!-- resources/views/staff/_form.blade.php -->
@php($editing = isset($staff))
<form method="POST" action="{{ $editing ? route('staff.update',$staff) : route('staff.store') }}" class="space-y-4">
    @csrf
    @if($editing) @method('PUT') @endif

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nombre</label>
        <input type="text" name="nombre"
               class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
               value="{{ old('nombre', $staff->nombre ?? '') }}" required>
        @error('nombre')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Cargo</label>
        <input type="text" name="cargo"
               class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
               value="{{ old('cargo', $staff->cargo ?? '') }}" required>
        @error('cargo')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Teléfono</label>
            <input type="text" name="telefono" pattern="\+56\s?\d{1,2}\s?\d{4}\s?\d{4}"
                   class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                   value="{{ old('telefono', $staff->telefono ?? '') }}">
            @error('telefono')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Correo electrónico</label>
            <input type="email" name="email"
                   class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                   value="{{ old('email', $staff->email ?? '') }}" required>
            @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
            {{ $editing ? 'Actualizar' : 'Crear' }}
        </button>
        <a href="{{ route('staff.index') }}"
           class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800">
           Cancelar
        </a>

        @if($editing)
            <form method="POST" action="{{ route('staff.destroy',$staff) }}"
                  x-data
                  onsubmit="return false"
                  @submit="
                    if (confirm('¿Eliminar este registro?')) { $el.submit = null; $el.submit(); }
                  "
                  class="inline">
                @csrf @method('DELETE')
                <button class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                    Eliminar
                </button>
            </form>
        @endif
    </div>
</form>
