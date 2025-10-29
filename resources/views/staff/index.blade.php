{{-- Inicio de Staff.blade.php --}}
@section('title', 'Nuestro Equipo')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#c9e4ff]">Información de Nuestro Equipo</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Nuestro Equipo', 'url' => '#']
    ]" />

    <div class="p-6 max-w-7xl mx-auto" x-data="{
            search: '',
            sort: 'nombre_asc',
            equipo: @js($staff->map(fn($p) => [
                'id' => $p->id,
                'nombre' => $p->nombre,
                'cargo' => $p->cargo,
                'telefono' => $p->telefono,
                'email' => $p->email,
                'foto_perfil' => $p->foto_perfil,
                'show_url' => route('staff.show', $p),
            ])),
            get filtrados() {
                let arr = this.equipo.filter(s => {
                    const q = this.search.toLowerCase();
                    return !q
                        || (s.nombre ?? '').toLowerCase().includes(q)
                        || (s.cargo ?? '').toLowerCase().includes(q)
                        || (s.email ?? '').toLowerCase().includes(q);
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
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            @if(!tieneRol(['director_programa']))
            <a href="{{ route('staff.create') }}"
                class="inline-flex items-center justify-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white px-6 py-3 rounded-lg shadow-md transition-all duration-200 font-semibold text-sm hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 hci-button-ripple hci-glow"
                aria-label="Agregar nuevo miembro del equipo">
                <img src="{{ asset('icons/agregar.svg') }}" alt="Agregar" class="w-5 h-5">
                Agregar Miembro
            </a>
            @endif
            
            <div class="flex gap-3 items-center w-full sm:w-auto">
                <div class="relative flex-1 sm:flex-initial">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <img src="{{ asset('icons/filtro.svg') }}" alt="" class="h-5 w-5 opacity-60">
                    </div>
                    <input x-model="search" 
                           type="text" 
                           role="search"
                           aria-label="Buscar miembros del equipo por nombre, cargo o email"
                           placeholder="Buscar por nombre, cargo o email"
                           class="w-full sm:w-[350px] pl-10 pr-4 py-3 rounded-lg border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition hci-input-focus">
                </div>
                
                <select x-model="sort" 
                        class="px-4 py-3 pr-10 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[160px] hci-focus-ring"
                        aria-label="Ordenar miembros del equipo">
                    <option value="nombre_asc">Nombre A-Z</option>
                    <option value="nombre_desc">Nombre Z-A</option>
                    <option value="cargo_asc">Cargo A-Z</option>
                    <option value="cargo_desc">Cargo Z-A</option>
                </select>
                
                <button type="button" 
                        @click="search=''; sort='nombre_asc'"
                        class="p-3 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 hover:scale-105 hci-button-ripple hci-glow"
                        title="Limpiar búsqueda y ordenamiento"
                        aria-label="Limpiar búsqueda y ordenamiento">
                    <img src="{{ asset('icons/filterw.svg') }}" alt="Limpiar" class="w-5 h-5">
                </button>
            </div>
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 stagger-animation">
            <template x-for="(p, index) in filtrados" :key="p.id">
                <a :href="p.show_url" 
                   class="group hci-fade-in" 
                   :style="`animation-delay: ${index * 0.1}s`">
                    <div
                        class="cursor-pointer transition-all duration-300 rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:shadow-xl hover:-translate-y-1 hover:border-[#4d82bc]/40">
                        
                        {{-- Foto de perfil --}}
                        <div class="flex justify-center pt-5 pb-3">
                            <img :src="p.foto_perfil" 
                                 :alt="'Foto de ' + p.nombre" 
                                 class="w-20 h-20 rounded-full object-cover border-4 border-[#84b6f4] shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                        </div>

                        {{-- Información --}}
                        <div class="px-4 pb-4 text-center">
                            <h3 class="text-base font-bold text-[#005187] dark:text-[#c9e4ff] mb-1 line-clamp-1" x-text="p.nombre"></h3>
                            <p class="text-sm text-[#4d82bc] dark:text-[#a8d1f7] mb-3 line-clamp-2 min-h-[2.5rem]" x-text="p.cargo"></p>
                            
                            {{-- Indicadores compactos --}}
                            <div class="flex justify-center gap-3 text-xs text-gray-600 dark:text-gray-400 pt-3 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-1" title="Tiene email registrado">
                                    <svg class="w-4 h-4 text-[#4d82bc] dark:text-[#a8d1f7]" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                    <span class="font-medium">Email</span>
                                </div>
                                <div class="flex items-center gap-1" :class="p.telefono ? 'opacity-100' : 'opacity-40'" :title="p.telefono ? 'Tiene teléfono registrado' : 'Sin teléfono'">
                                    <svg class="w-4 h-4" :class="p.telefono ? 'text-[#4d82bc] dark:text-[#a8d1f7]' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                    <span class="font-medium">Teléfono</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </template>
        </div>
    </div>
</x-app-layout>
