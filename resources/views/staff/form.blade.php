{{-- Formulario de Staff.blade.php --}}
@section('title', isset($staff) ? 'Editar miembro del Staff' : 'Crear miembro del Staff')

@php
    $editing = isset($staff);
@endphp

<form method="POST" action="{{ $editing ? route('staff.update', $staff) : route('staff.store') }}"
    class="space-y-6 max-w-2xl mx-auto bg-white dark:bg-gray-900 p-6 rounded-2xl shadow">
    @csrf
    @if($editing) @method('PUT') @endif

    {{-- Nombre --}}
    <div>
        <label for="nombre" class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4]">
            Nombre
        </label>
        <input type="text" id="nombre" name="nombre" class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 
                      dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#84b6f4] focus:border-[#84b6f4]"
            value="{{ old('nombre', $staff->nombre ?? '') }}" required placeholder="Ej: Juan Pérez">
        @error('nombre')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Cargo --}}
    <div>
        <label for="cargo" class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4]">
            Cargo
        </label>
        <input type="text" id="cargo" name="cargo" class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 
                      dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#84b6f4] focus:border-[#84b6f4]"
            value="{{ old('cargo', $staff->cargo ?? '') }}" required placeholder="Ej: Coordinador Académico">
        @error('cargo')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Teléfono y Correo --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

        {{-- Correo --}}
        <div>
            <label for="email" class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4]">
                Correo electrónico
            </label>
            <input type="email" id="email" name="email" class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 
                          dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#84b6f4] focus:border-[#84b6f4]"
                value="{{ old('email', $staff->email ?? '') }}" required placeholder="ejemplo@correo.com">
            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Anexo --}}
        <div>
            <label for="anexo" class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4]">
                Anexo <span class="text-gray-500 text-xs">(opcional)</span>
            </label>
            <input type="text" id="anexo" name="anexo" maxlength="5" placeholder="Ej: 1234" class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 
                  dark:bg-gray-800 dark:text-gray-100 
                  focus:ring-2 focus:ring-[#84b6f4] focus:border-[#84b6f4]"
                value="{{ old('anexo', $staff->anexo ?? '') }}">
            @error('anexo')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>


    {{-- Teléfono --}}
    <div>
        <label for="telefono" class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4]">
            Teléfono
        </label>
        <input type="text" id="telefono" name="telefono" pattern="^(\+56\s?9\d{8}|\+56\s?712\d{6}|9\d{8}|712\d{6})$"
            title="Ingrese un teléfono válido: celular +56 9XXXXXXXX o fijo +56 712XXXXXX"
            placeholder="Ej: +56 9 12345678 o +56 712345678" class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 
                  dark:bg-gray-800 dark:text-gray-100 
                  focus:ring-2 focus:ring-[#84b6f4] focus:border-[#84b6f4]"
            value="{{ old('telefono', $staff->telefono ?? '') }}" required>
        <small class="text-xs text-gray-500 dark:text-gray-400">
            Ejemplo celular: +56 9 12345678 | Ejemplo fijo: +56 712345678
        </small>
        @error('telefono')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>


    {{-- Botones --}}
    <div class="mt-6 flex justify-between items-center">
        <a href="{{ route('staff.index') }}" class="inline-block bg-[#4d82bc] hover:bg-[#005187] 
               text-white px-4 py-2 rounded-md shadow-md transition">
            <img src="{{ asset('icons/back.svg') }}" alt="back" class="w-5 h-5">
        </a>
        <button type="submit" class="inline-flex items-center justify-center 
           bg-[#3ba55d] hover:bg-[#2d864a] 
           text-white px-4 py-2 rounded-lg shadow text-sm font-medium 
           transition transform hover:scale-105">
            <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-5 h-5">
        </button>
    </div>
</form>