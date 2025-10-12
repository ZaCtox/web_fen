{{-- Nuestro equipo FEN --}}
@section('title', 'Equipo Fen')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Información de nuestro equipo</h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto" x-data="{
            search: '',
            modalOpen: false,
            seleccionado: null,
            staff: @js($staff->map(function($s) {
                return [
                    'id' => $s->id,
                    'nombre' => $s->nombre,
                    'cargo' => $s->cargo,
                    'telefono' => $s->telefono,
                    'anexo' => $s->anexo,
                    'email' => $s->email,
                    'foto_perfil' => $s->foto_perfil
                ];
            })),
            get filtrados() {
                const q = this.search.toLowerCase();
                return this.staff.filter(p =>
                    p.nombre.toLowerCase().includes(q) ||
                    p.cargo.toLowerCase().includes(q) ||
                    p.email.toLowerCase().includes(q)
                );
            },
            openModal(p) {
                this.seleccionado = p;
                this.modalOpen = true;
            }
         }">

        <!-- Controles -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div class="flex w-full sm:w-auto gap-3 items-center">
                <div class="relative">
                    <label for="search-staff" class="sr-only">Buscar en el equipo</label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <img src="{{ asset('icons/filtro.svg') }}" alt="Buscar" class="h-4 w-4 text-gray-400">
                    </div>
                    <input id="search-staff" 
                           x-model="search" 
                           type="text" 
                           placeholder="Buscar por nombre, cargo o email"
                           class="w-full sm:w-[350px] pl-10 pr-4 py-2 rounded-lg border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-800 text-[#005187] dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition">
                </div>
                <button type="button" @click="search=''"
                    class="bg-[#84b6f4] hover:bg-[#005187] text-[#005187] px-4 py-2 rounded-lg shadow text-sm transition transform hover:scale-105"
                    title="Limpiar búsqueda">
                    <img src="{{ asset('icons/filterw.svg') }}" alt="Limpiar filtros" class="w-5 h-5">
                </button>
            </div>
        </div>

        {{-- Sin resultados --}}
        @if($staff->count() === 0)
            <div class="rounded-lg border border-dashed p-6 text-center text-gray-500 dark:text-gray-300">
                😕 No hay registros que coincidan con tu búsqueda.
            </div>
        @endif

        {{-- Grid interactivo --}}
        <template x-if="filtrados.length === 0">
            <div class="rounded-lg border border-dashed p-6 text-center text-gray-500 dark:text-gray-300">
                😕 No hay registros que coincidan con tu búsqueda.
            </div>
        </template>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <template x-for="p in filtrados" :key="p.id">
                <div @click="openModal(p)" class="group hci-card-hover">
                    <div
                        class="cursor-pointer hci-lift transition-all duration-300 rounded-2xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:shadow-xl">
                        
                        {{-- Foto de perfil --}}
                        <div class="flex justify-center pt-6 pb-4">
                            <img :src="p.foto_perfil" 
                                 :alt="'Foto de ' + p.nombre" 
                                 class="w-24 h-24 rounded-full object-cover border-4 border-[#84b6f4] shadow-lg group-hover:border-[#4d82bc] transition-colors duration-200">
                        </div>

                        <div class="p-4 text-center">
                            <h3 class="text-lg font-bold text-[#005187] dark:text-[#84b6f4] group-hover:text-[#4d82bc] transition-colors duration-200" x-text="p.nombre"></h3>
                            <p class="text-sm text-[#4d82bc] dark:text-[#84b6f4] group-hover:text-[#005187] transition-colors duration-200 mb-4" x-text="p.cargo"></p>
                            
                            <div class="bg-[#4d82bc]/10 rounded-lg p-3 text-left space-y-1">
                                <div class="text-xs text-gray-600 dark:text-gray-400">
                                    <span class="font-semibold">Email:</span>
                                    <span class="text-xs break-all" x-text="p.email"></span>
                                </div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">
                                    <span class="font-semibold">Teléfono:</span>
                                    <span x-text="p.telefono || '—'"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Modal detalle --}}
        <div x-show="modalOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="modalOpen = false">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl w-full max-w-xl relative">
                <button @click="modalOpen = false"
                    class="absolute top-2 right-2 text-gray-700 dark:text-gray-200 hover:text-red-500"
                    title="Cerrar">
                    <img src="{{ asset('icons/no_resuelta.svg') }}" alt="Cerrar modal" class="w-8 h-8">
                </button>

                {{-- Foto de perfil en modal --}}
                <div class="flex justify-center mb-4">
                    <img :src="seleccionado?.foto_perfil" 
                         :alt="'Foto de ' + seleccionado?.nombre" 
                         class="w-32 h-32 rounded-full object-cover border-4 border-[#84b6f4] shadow-xl">
                </div>

                <h2 class="text-2xl font-bold text-[#005187] dark:text-[#84b6f4] mb-2 text-center" x-text="seleccionado?.nombre"></h2>
                <p class="text-[#4d82bc] dark:text-[#84b6f4] mb-4 text-center" x-text="seleccionado?.cargo"></p>

                <div class="bg-[#4d82bc] text-white p-4 rounded-lg space-y-2">
                    <div>
                        <div class="text-[13px] tracking-wide opacity-90">Teléfono</div>
                        <div class="text-sm" x-text="seleccionado?.telefono || '—'"></div>
                    </div>
                     <div>
                        <div class="text-[13px] tracking-wide opacity-90">Anexo</div>
                        <div class="text-sm" x-text="seleccionado?.anexo || '—'"></div>
                    </div>
                    <div>
                        <div class="text-[13px] tracking-wide opacity-90">Email</div>
                        <div class="text-sm break-all" x-text="seleccionado?.email"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.footer')

</x-app-layout>


