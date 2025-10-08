{{-- Formulario de Periodo.blade.php --}}
@section('title', 'Formulario Periodo Académico')

<div class="space-y-6 max-w-md mx-auto p-6 bg-[#fcffff] dark:bg-gray-800 rounded-xl shadow-md">

    {{-- Año Académico --}}
    <div>
        <label class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">Año Académico</label>
        <select id="anio-select" name="anio"
            class="mt-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] transition">
            @for ($i = 1; $i <= 2; $i++)
                <option value="{{ $i }}" {{ old('anio', optional($period)->anio) == $i ? 'selected' : '' }}>
                    Año {{ $i }}
                </option>
            @endfor
        </select>
    </div>

    {{-- Trimestre --}}
    <div>
        <label class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">Trimestre</label>
        <select id="numero-select" name="numero"
            class="mt-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] transition">
            {{-- Se rellenará dinámicamente --}}
        </select>
    </div>

    {{-- Fecha de Inicio --}}
    <div>
        <label class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">Fecha de Inicio</label>
        <input type="date" name="fecha_inicio"
            class="mt-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] transition"
            value="{{ old('fecha_inicio', optional($period)->fecha_inicio ? optional($period)->fecha_inicio->format('Y-m-d') : '') }}">
    </div>

    {{-- Fecha de Término --}}
    <div>
        <label class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">Fecha de Término</label>
        <input type="date" name="fecha_fin"
            class="mt-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] transition"
            value="{{ old('fecha_fin', optional($period)->fecha_fin ? optional($period)->fecha_fin->format('Y-m-d') : '') }}">
    </div>

    {{-- Estado --}}
    <div>
        <label class="inline-flex items-center">
            <input type="checkbox" name="activo" value="1" class="form-checkbox"
                {{ old('activo', optional($period)->activo) ? 'checked' : '' }}>
            <span class="ml-2 text-sm font-medium text-[#005187] dark:text-[#c4dafa]">Activo</span>
        </label>
    </div>

    {{-- Botones --}}
    <div class="mt-6 flex justify-between items-center">
        <a href="{{ route('periods.index') }}" class="inline-block bg-[#4d82bc] hover:bg-[#005187] 
               text-white px-4 py-2 rounded-md shadow-md transition">
            <img src="{{ asset('icons/back.svg') }}" alt="back" class="w-5 h-5">
        </a>
        <button type="submit" class="inline-flex items-center justify-center bg-[#005187] hover:bg-[#4d82bc] 
                                   text-white px-4 py-2 rounded-lg shadow text-sm font-medium 
                                   transition transform hover:scale-105">
            <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-5 h-5">
        </button>
    </div>
</div>

{{-- Script para actualizar trimestres dinámicamente --}}
<script>
console.log('🚀 Script de períodos iniciado');

(function() {
    console.log('📋 Función inmediata ejecutándose...');
    
    function inicializarTrimestres() {
        console.log('🔍 Buscando elementos...');
        
        const anioSelect = document.getElementById('anio-select');
        const numeroSelect = document.getElementById('numero-select');
        
        if (!anioSelect || !numeroSelect) {
            console.error('❌ Elementos no encontrados todavía:', {
                anioSelect: !!anioSelect,
                numeroSelect: !!numeroSelect
            });
            return false;
        }
        
        console.log('✅ Elementos encontrados:', {
            anioSelect: anioSelect,
            numeroSelect: numeroSelect,
            anioValue: anioSelect.value,
            numeroOptions: numeroSelect.options.length
        });

        const opcionesTrimestre = {
            1: [1, 2, 3],
            2: [4, 5, 6]
        };

        // Valor del trimestre a seleccionar
        const trimestreSeleccionado = @if(isset($period)) {{ old('numero', $period->numero) ?? 'null' }} @else {{ old('numero', 'null') }} @endif;
        
        console.log('📊 Datos del período:', {
            periodoExiste: @if(isset($period)) true @else false @endif,
            trimestreSeleccionado: trimestreSeleccionado,
            anioActual: anioSelect.value
        });

        function actualizarTrimestres() {
            console.log('🔄 Actualizando trimestres...');
            
            const anio = parseInt(anioSelect.value);
            const trimestres = opcionesTrimestre[anio] || [];
            
            console.log('📅 Datos para actualizar:', {
                anio: anio,
                trimestresDisponibles: trimestres,
                trimestreASeleccionar: trimestreSeleccionado
            });

            numeroSelect.innerHTML = '';

            trimestres.forEach(function (num) {
                const option = document.createElement('option');
                option.value = num;
                option.textContent = 'Trimestre ' + num;
                
                // Seleccionar el trimestre correcto
                if (trimestreSeleccionado && trimestreSeleccionado == num) {
                    option.selected = true;
                    console.log('✅ Trimestre seleccionado:', num);
                }
                
                numeroSelect.appendChild(option);
            });
            
            console.log('🎯 Estado final:', {
                anioSeleccionado: anio,
                trimestreASeleccionar: trimestreSeleccionado,
                trimestreActualmenteSeleccionado: numeroSelect.value,
                opcionesDisponibles: Array.from(numeroSelect.options).map(opt => ({value: opt.value, text: opt.text, selected: opt.selected}))
            });
        }

        anioSelect.addEventListener('change', function() {
            console.log('🔄 Año cambió a:', anioSelect.value);
            actualizarTrimestres();
        });
        
        // Ejecutar actualización
        console.log('⚡ Ejecutando actualización...');
        actualizarTrimestres();
        
        return true;
    }
    
    // Intentar múltiples veces hasta que los elementos estén disponibles
    let intentos = 0;
    const maxIntentos = 10;
    
    function intentarInicializar() {
        intentos++;
        console.log(`🔄 Intento ${intentos}/${maxIntentos}...`);
        
        if (inicializarTrimestres()) {
            console.log('✅ Inicialización exitosa!');
        } else if (intentos < maxIntentos) {
            setTimeout(intentarInicializar, 100);
        } else {
            console.error('❌ No se pudo inicializar después de', maxIntentos, 'intentos');
        }
    }
    
    // Intentar inmediatamente
    intentarInicializar();
    
    // También intentar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            console.log('📋 DOMContentLoaded disparado');
            inicializarTrimestres();
        });
    }
})();
</script>
