{{-- Inicio de Salas.blade.php --}}
@section('title', 'Salas')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Salas Registradas
        </h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Salas', 'url' => '#']
    ]" />

    <div class="p-6" x-data="{
            search: '',
            esVisor: {{ tieneRol('visor') ? 'true' : 'false' }},
            salas: @js($rooms->items()),
            get filtradas() {
                return this.salas.filter(s =>
                    s.name.toLowerCase().includes(this.search.toLowerCase()) ||
                    s.location.toLowerCase().includes(this.search.toLowerCase())
                );
            }
        }">

        {{-- Header acciones --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            {{-- Botón Agregar (Izquierda) --}}
            @if(!tieneRol('visor'))
            <a href="{{ route('rooms.create') }}"
                class="inline-flex items-center justify-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white px-6 py-3 rounded-lg shadow-md transition-all duration-200 font-semibold text-sm hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 hci-button-ripple hci-glow"
                aria-label="Agregar nueva sala">
                <img src="{{ asset('icons/agregar.svg') }}" alt="" class="w-5 h-5">
                Agregar Sala
            </a>
            @endif

            {{-- Filtros (Derecha) --}}
            <div class="flex gap-3 items-center w-full sm:w-auto">
                <div class="relative flex-1 sm:flex-initial">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <img src="{{ asset('icons/filtro.svg') }}" alt="" class="h-5 w-5 opacity-60">
                    </div>
                    <input x-model="search" 
                           type="text" 
                           id="search-rooms"
                           role="search"
                           aria-label="Buscar salas por nombre o ubicación"
                           placeholder="Buscar por nombre o ubicación"
                           class="w-full sm:w-[350px] pl-10 pr-4 py-3 rounded-lg border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition hci-input-focus">
                </div>
                
                <button type="button" 
                        @click="search=''"
                        class="p-3 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 hover:scale-105 hci-button-ripple hci-glow"
                        title="Limpiar búsqueda"
                        aria-label="Limpiar búsqueda">
                    <img src="{{ asset('icons/filterw.svg') }}" alt="" class="w-5 h-5">
                </button>
            </div>
        </div>

        {{-- Tabla de resultados --}}
        <template x-if="filtradas.length > 0">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto text-sm text-[#005187] dark:text-[#fcffff]">
                    <thead class="bg-[#c4dafa]/50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Nombre</th>
                            <th class="px-4 py-2 text-left">Ubicación</th>
                            <th class="px-4 py-2 text-left">Ficha Técnica</th>
                            <th class="px-4 py-2 text-left">Clases Asignadas</th>
                            <th class="px-4 py-2 text-right w-40">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="room in filtradas" :key="room.id">
                            <tr class="border-b border-[#c4dafa]/60 dark:border-gray-600 
                                       hover:bg-[#e3f2fd] dark:hover:bg-gray-700 
                                       hover:border-l-4 hover:border-l-[#4d82bc]
                                       hover:-translate-y-0.5 hover:shadow-md
                                       transition-all duration-200 group cursor-pointer"
                                @click="window.location=`/rooms/${room.id}`">
                                <td class="px-4 py-2 font-medium group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200"
                                    x-text="room.name"></td>
                                <td class="px-4 py-2 group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200"
                                    x-text="room.location"></td>
                                <td class="px-4 py-2" @click.stop>
                                    <a :href="`/rooms/${room.id}#ficha`"
                                        class="inline-flex items-center justify-center p-2 bg-[#84b6f4] hover:bg-[#4d82bc] text-white rounded-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#84b6f4] focus:ring-offset-1"
                                        title="Ver ficha técnica">
                                        <img src="{{ asset('icons/ficha.svg') }}" alt="" class="w-5 h-5">
                                    </a>
                                </td>
                                <td class="px-4 py-2" @click.stop>
                                    <a :href="`/rooms/${room.id}#clases`"
                                        class="inline-flex items-center justify-center p-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-1"
                                        title="Ver clases asignadas">
                                        <img src="{{ asset('icons/class.svg') }}" alt="" class="w-5 h-5">
                                    </a>
                                </td>
                                <td class="px-4 py-2" @click.stop>
                                    <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-2" x-show="!esVisor">
                                        {{-- Botón Editar --}}
                                        <a :href="`/rooms/${room.id}/edit`" 
                                           class="inline-flex items-center justify-center p-2.5 bg-[#84b6f4] hover:bg-[#4d82bc] text-white rounded-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#84b6f4] focus:ring-offset-1"
                                           title="Editar sala">
                                            <img src="{{ asset('icons/editw.svg') }}" alt="" class="w-5 h-5">
                                        </a>

                                        {{-- Botón Eliminar --}}
                                        <form :action="`/rooms/${room.id}`" method="POST" class="form-eliminar inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center p-2.5 bg-[#e57373] hover:bg-[#d32f2f] text-white rounded-lg transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1"
                                                    title="Eliminar sala">
                                                <img src="{{ asset('icons/trashw.svg') }}" alt="" class="w-5 h-5">
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </template>

        {{-- Sin resultados --}}
        <template x-if="filtradas.length === 0">
            <div>
                <x-empty-state type="no-results" icon="🔍" title="No se encontraron salas"
                    message="Intenta con otros términos de búsqueda o ajusta los filtros seleccionados."
                    secondaryActionText="Limpiar Búsqueda" secondaryActionUrl="{{ route('rooms.index') }}"
                    secondaryActionIcon="🔄" />
            </div>
        </template>

        {{-- Paginación (server-side) --}}
        <div class="mt-6">
            {{ $rooms->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
