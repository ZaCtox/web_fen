{{-- Formulario de Salas.blade.php --}}
@section('title', 'Formulario Salas')
@csrf

{{-- 🏫 Datos generales --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div>
        <label for="name" class="block text-sm font-medium text-[#005187]">Nombre</label>
        <input type="text" name="name" id="name" required
            value="{{ old('name', $room->name ?? '') }}"
            class="mt-1 block w-full rounded-md border-[#84b6f4] shadow-sm 
                   bg-[#fcffff] text-[#005187] 
                   focus:ring-[#4d82bc] focus:border-[#4d82bc]">
    </div>

    <div>
        <label for="location" class="block text-sm font-medium text-[#005187]">Ubicación</label>
        <input type="text" name="location" id="location"
            value="{{ old('location', $room->location ?? '') }}"
            class="mt-1 block w-full rounded-md border-[#84b6f4] shadow-sm 
                   bg-[#fcffff] text-[#005187] 
                   focus:ring-[#4d82bc] focus:border-[#4d82bc]">
    </div>

    <div>
        <label for="capacity" class="block text-sm font-medium text-[#005187]">Capacidad</label>
        <input type="number" name="capacity" id="capacity" min="1"
            value="{{ old('capacity', $room->capacity ?? '') }}"
            class="mt-1 block w-full rounded-md border-[#84b6f4] shadow-sm 
                   bg-[#fcffff] text-[#005187] 
                   focus:ring-[#4d82bc] focus:border-[#4d82bc]">
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-[#005187]">Descripción</label>
        <textarea name="description" id="description" rows="3"
            class="mt-1 block w-full rounded-md border-[#84b6f4] shadow-sm 
                   bg-[#fcffff] text-[#005187] 
                   focus:ring-[#4d82bc] focus:border-[#4d82bc]">{{ old('description', $room->description ?? '') }}</textarea>
    </div>
</div>

{{-- ⚙️ Condiciones de la Sala --}}
<hr class="my-6 border-[#84b6f4]">
<h3 class="text-lg font-semibold mb-4 text-[#005187] border-b border-[#84b6f4] pb-2">
    ⚙️ Condiciones de la Sala
</h3>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @php
        $condiciones = [
            'calefaccion' => 'Calefacción',
            'energia_electrica' => 'Energía Eléctrica',
            'existe_aseo' => 'Aseo Disponible',
            'plumones' => 'Plumones',
            'borrador' => 'Borrador',
            'pizarra_limpia' => 'Pizarra Limpia',
            'computador_funcional' => 'Computador Funcional',
            'cables_computador' => 'Cables del Computador',
            'control_remoto_camara' => 'Control Remoto de Cámara',
            'televisor_funcional' => 'Televisor Funcional',
        ];
    @endphp

    @foreach ($condiciones as $campo => $label)
        <label class="flex items-center space-x-2">
            <input type="checkbox" name="{{ $campo }}" id="{{ $campo }}"
                {{ old($campo, $room->$campo ?? false) ? 'checked' : '' }}
                class="rounded border-[#84b6f4] text-[#005187] focus:ring-[#4d82bc] bg-[#fcffff]">
            <span class="text-sm text-[#005187]">{{ $label }}</span>
        </label>
    @endforeach
</div>

{{-- 💾 Botón de guardar --}}
<div class="mt-6 flex justify-between items-center">
    <a href="{{ route('rooms.index') }}"
        class="inline-block bg-[#4d82bc] hover:bg-[#005187] 
               text-white px-4 py-2 rounded-md shadow-md transition">
        <img src="{{ asset('icons/back.svg') }}" alt="back" class="w-5 h-5">
    </a>
   <button type="submit" 
    class="inline-flex items-center justify-center 
           bg-[#3ba55d] hover:bg-[#2d864a] 
           text-white px-4 py-2 rounded-lg shadow text-sm font-medium 
           transition transform hover:scale-105">
    <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-5 h-5">
</button>

</div>
