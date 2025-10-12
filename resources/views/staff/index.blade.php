{{-- Inicio de Staff.blade.php --}}
@section('title', 'Nuestro Equipo')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Información de Nuestro Equipo</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Nuestro Equipo', 'url' => '#']
    ]" />

    <div class="p-6 max-w-7xl mx-auto" x-data="{
            search: '',
            sort: 'nombre_asc',
            hasPhone: false,
            staff: @js($staff->map(fn($p) => [
                'id' => $p->id,
                'nombre' => $p->nombre,
                'cargo' => $p->cargo,
                'telefono' => $p->telefono,
                'email' => $p->email,
                'foto_perfil' => $p->foto_perfil,
                'show_url' => route('staff.show', $p),
            ])),
            get filtrados() {
                let arr = this.staff.filter(s => {
                    const q = this.search.toLowerCase();
                    const match = !q
                        || (s.nombre ?? '').toLowerCase().includes(q)
                        || (s.cargo ?? '').toLowerCase().includes(q)
                        || (s.email ?? '').toLowerCase().includes(q);
                    const telOk = !this.hasPhone || (s.telefono && s.telefono.trim() !== '');
                    return match && telOk;
                });

                const cmp = (a,b,f) => (a[f]||'').localeCompare(b[f]||'', undefined, {sensitivity:'base'});
                switch (this.sort) {
                    case 'nombre_asc':  arr.sort((a,b)=>cmp(a,b,'nombre')); break;
                    case 'nombre_desc': arr.sort((a,b)=>cmp(b,a,'nombre')); break;
                    case 'cargo_asc':   arr.sort((a,b)=>cmp(a,b,'cargo'));  break;
                    case 'cargo_desc':  arr.sort((a,b)=>cmp(b,a,'cargo'));  break;
                }
                return arr;
            }
         }">

        <!-- Controles -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <a href="{{ route('staff.create') }}"
                class="hci-button hci-lift hci-focus-ring inline-flex items-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-lg shadow transition-all duration-200"
                title="Agregar nuevo miembro">
                <img src="{{ asset('icons/agregar.svg') }}" alt="Agregar" class="w-5 h-5">
            </a>
            <div class="flex w-full sm:w-auto gap-3 items-center">
                <div class="relative flex-1 sm:flex-initial">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <img src="{{ asset('icons/filtro.svg') }}" alt="Buscar" class="h-4 w-4">
                    </div>
                    <input x-model="search" type="text" placeholder="Buscar por nombre, cargo o email"
                        class="w-full sm:w-[350px] pl-10 pr-4 py-2 rounded-lg border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition">
                </div>
                <button type="button" 
                        @click="search=''; sort='nombre_asc'; hasPhone=false"
                        class="px-3 py-2 bg-[#84b6f4] hover:bg-[#005187] text-[#005187] rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 transform hover:scale-105"
                        title="Limpiar filtros"
                        aria-label="Limpiar filtros">
                    <img src="{{ asset('icons/filterw.svg') }}" alt="Limpiar" class="w-5 h-5">
                </button>
            </div>

        </div>

        <!-- Meta -->
        <div class="text-sm text-gray-500 dark:text-gray-300 mb-2">
            Mostrando <span x-text="filtrados.length"></span> de {{ $staff->count() }} registros
        </div>

        <!-- Sin resultados -->
        <template x-if="filtrados.length === 0">
            <div>
                <x-empty-state
                    type="no-results"
                    icon="👥"
                    title="No se encontraron miembros del equipo"
                    message="Intenta con otros términos de búsqueda o ajusta los filtros de cargo."
                    secondaryActionText="Limpiar Filtros"
                    secondaryActionUrl="{{ route('staff.index') }}"
                    secondaryActionIcon="🔄"
                />
            </div>
        </template>

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <template x-for="p in filtrados" :key="p.id">
                <a :href="p.show_url" class="group hci-card-hover">
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
                </a>
            </template>
        </div>
    </div>
</x-app-layout>



