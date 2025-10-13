@props(['cohortes' => collect(), 'cohorteSeleccionada' => null, 'periodos' => collect()])

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4">
    {{-- Selector de ciclo --}}
    <div class="mb-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div class="flex-1">
                <label for="cohorte-filter" class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4] mb-2">
                    Ciclo Académico:
                </label>
                <select id="cohorte-filter" name="cohorte" required
                    onchange="const params = new URLSearchParams(); params.set('cohorte', this.value); window.location.search = params.toString();"
                    class="w-full sm:w-64 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-700 text-[#005187] dark:text-[#84b6f4] px-4 py-2.5 focus:ring-[#4d82bc] focus:border-[#4d82bc] font-medium"
                    aria-describedby="cohorte-status"
                    aria-required="true">
                    @if(isset($cohortes))
                        @foreach($cohortes as $cohorte)
                            <option value="{{ $cohorte }}" {{ $cohorte == $cohorteSeleccionada ? 'selected' : '' }}>
                                {{ $cohorte }} {{ $cohorte == $cohortes->first() ? '(Actual)' : '' }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="text-sm" id="cohorte-status" role="status" aria-live="polite">
                @if(isset($cohorteSeleccionada))
                    @if($cohorteSeleccionada != $cohortes->first())
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 rounded text-xs">
                            ⚠️ Pasado
                        </span>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-4">
        <div>
            <label for="magister-filter" class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4] mb-2">
                Programa:
            </label>
            <select id="magister-filter" name="magister"
                class="w-full sm:w-80 rounded-lg border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-700 text-[#005187] dark:text-white px-4 py-2.5 text-base focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition font-medium">
                <option value="">Todos</option>
                @foreach(\App\Models\Magister::orderBy('orden')->get() as $m)
                    <option value="{{ $m->id }}" {{ $m->id == 1 ? 'selected' : '' }}>
                        {{ $m->nombre }}
                    </option>
                @endforeach
            </select>

        </div>

        <div>
            <label for="room-filter" class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4] mb-2">
                Sala:
            </label>
            <select id="room-filter" name="room_id" class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-700 text-[#005187] dark:text-white px-3 py-2 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition">
                <option value="">Todas</option>
                @foreach(\App\Models\Room::orderBy('name')->get() as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="anio-filter" class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4] mb-2">
                Año:
            </label>
            <select id="anio-filter" name="anio" class="w-full sm:w-32 rounded-lg border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-700 text-[#005187] dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition font-medium text-base">
                <option value="">Todos</option>
                @foreach($periodos->pluck('anio')->unique()->sort()->values() as $anio)
                    <option value="{{ $anio }}">Año {{ $anio }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="trimestre-filter" class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4] mb-2">
                Trimestre:
            </label>
            <select id="trimestre-filter" name="trimestre" class="w-full sm:w-44 rounded-lg border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-700 text-[#005187] dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition font-medium text-base">
                <option value="">Todos</option>
                @foreach($periodos->pluck('numero')->unique()->sort()->values() as $trimestre)
                    <option value="{{ $trimestre }}">Trimestre {{ $trimestre }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-800 dark:text-white invisible">
                &nbsp; <!-- Para alinear el botón con los selects -->
            </label>
            <button id="clear-filters" type="button"
                class="mt-1 bg-[#84b6f4] hover:bg-[#005187] text-[#005187] px-4 py-2 rounded-lg shadow text-sm transition transform hover:scale-105"
                title="Limpiar filtros">
                <img src="{{ asset('icons/filterw.svg') }}" alt="Limpiar filtros" class="w-5 h-5">
            </button>
        </div>

    </div>





