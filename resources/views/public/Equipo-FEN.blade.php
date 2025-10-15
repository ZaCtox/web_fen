{{-- Nuestro equipo FEN --}}
@section('title', 'Equipo Fen')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#c9e4ff]">Información de nuestro equipo</h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto" x-data="{
            search: '',
            sort: 'nombre_asc',
            modalOpen: false,
            seleccionado: null,
            equipo: @js($staff->map(function($s) {
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
                let arr = this.equipo.filter(p => {
                    const q = this.search.toLowerCase();
                    return p.nombre.toLowerCase().includes(q) ||
                           p.cargo.toLowerCase().includes(q) ||
                           p.email.toLowerCase().includes(q);
                });
                
                // Aplicar ordenamiento
                const cmp = (a,b,f) => (a[f]||'').localeCompare(b[f]||'', undefined, {sensitivity:'base'});
                switch (this.sort) {
                    case 'nombre_asc':  arr.sort((a,b)=>cmp(a,b,'nombre')); break;
                    case 'nombre_desc': arr.sort((a,b)=>cmp(b,a,'nombre')); break;
                    case 'cargo_asc':   arr.sort((a,b)=>cmp(a,b,'cargo'));  break;
                    case 'cargo_desc':  arr.sort((a,b)=>cmp(b,a,'cargo'));  break;
                }
                return arr;
            },
            openModal(p) {
                this.seleccionado = p;
                this.modalOpen = true;
            }
         }">

        <!-- Controles -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 mb-6">
            <div class="flex gap-3 items-center w-full sm:w-auto">
                <div class="relative flex-1 sm:flex-initial">
                    <label for="search-equipo" class="sr-only">Buscar en el equipo</label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <img src="{{ asset('icons/filtro.svg') }}" alt="" class="h-5 w-5 opacity-60">
                    </div>
                    <input id="search-equipo" 
                           x-model="search" 
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
                        class="p-3 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 hci-button-ripple"
                        title="Limpiar búsqueda y ordenamiento"
                        aria-label="Limpiar búsqueda y ordenamiento">
                    <img src="{{ asset('icons/filterw.svg') }}" alt="Limpiar" class="w-5 h-5">
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 stagger-animation">
            <template x-for="(p, index) in filtrados" :key="p.id">
                <div @click="openModal(p)" 
                     class="group hci-fade-in" 
                     :style="`animation-delay: ${index * 0.1}s`">
                    <div
                        class="cursor-pointer transition-all duration-300 rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:shadow-xl hover:-translate-y-1 hover:border-[#4d82bc]/40">
                        
                        {{-- Foto de perfil --}}
                        <div class="flex justify-center pt-5 pb-3">
                            <img :src="p.foto_perfil" 
                                 :alt="'Foto de ' + p.nombre" 
                                 loading="lazy"
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
                </div>
            </template>
        </div>

        {{-- Modal detalle --}}
        <div x-show="modalOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" @click.self="modalOpen = false" @keydown.escape.window="modalOpen = false">
            <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-xl w-full max-w-2xl relative">
                <button @click="modalOpen = false"
                    class="absolute top-4 right-4 w-10 h-10 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-full flex items-center justify-center transition-colors hci-icon-hover hci-button-ripple"
                    title="Cerrar"
                    aria-label="Cerrar modal">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                {{-- Foto de perfil en modal --}}
                <div class="flex justify-center mb-4">
                    <img :src="seleccionado?.foto_perfil" 
                         :alt="'Foto de ' + seleccionado?.nombre" 
                         class="w-32 h-32 rounded-full object-cover border-4 border-[#84b6f4] shadow-xl">
                </div>

                <h2 class="text-2xl font-bold text-[#005187] dark:text-[#c9e4ff] mb-2 text-center" x-text="seleccionado?.nombre"></h2>
                <p class="text-lg text-[#4d82bc] dark:text-[#a8d1f7] mb-6 text-center" x-text="seleccionado?.cargo"></p>

                <div class="space-y-3">
                    {{-- Teléfono clickeable --}}
                    <template x-if="seleccionado?.telefono && seleccionado?.telefono !== '—'">
                        <a :href="'tel:' + seleccionado?.telefono"
                           class="bg-[#c4dafa] dark:bg-[#4d82bc]/20 rounded-xl p-4 border border-[#84b6f4]/30 hover:bg-[#b8d4f4] dark:hover:bg-[#4d82bc]/30 transition-all duration-200 group cursor-pointer hover:shadow-md hover:scale-[1.02] block hci-button-ripple hci-glow"
                           :aria-label="'Llamar a ' + seleccionado?.nombre">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-[#4d82bc] rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-[#005187] transition-colors">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-medium text-[#4d82bc] dark:text-[#84b6f4] uppercase tracking-wide">TELÉFONO</div>
                                        <div class="text-sm font-semibold text-[#005187] dark:text-[#c9e4ff] group-hover:text-[#003d6b] dark:group-hover:text-[#e6f3ff] transition-colors" x-text="seleccionado?.telefono"></div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-[#4d82bc] dark:text-[#84b6f4] group-hover:text-[#005187] dark:group-hover:text-[#c9e4ff] transition-colors">
                                    <span class="text-xs font-medium">Llamar</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </template>
                    <template x-if="!seleccionado?.telefono || seleccionado?.telefono === '—'">
                        <div class="bg-gray-100 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-300 dark:border-gray-600">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-400 dark:bg-gray-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Teléfono</div>
                                    <div class="text-sm font-semibold text-gray-500 dark:text-gray-400">No registrado</div>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Anexo (no clickeable, icono hashtag) --}}
                    <div class="bg-[#c4dafa] dark:bg-[#4d82bc]/20 rounded-xl p-4 border border-[#84b6f4]/30">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#4d82bc] rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9.243 3.03a1 1 0 01.727 1.213L9.53 6h2.94l.56-2.243a1 1 0 111.94.486L14.53 6H17a1 1 0 110 2h-2.97l-1 4H15a1 1 0 110 2h-2.47l-.56 2.242a1 1 0 11-1.94-.485L10.47 14H7.53l-.56 2.242a1 1 0 11-1.94-.485L5.47 14H3a1 1 0 110-2h2.97l1-4H5a1 1 0 110-2h2.47l.56-2.243a1 1 0 011.213-.727zM9.03 8l-1 4h2.938l1-4H9.031z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-[#005187] dark:text-[#c9e4ff] uppercase tracking-wide">Anexo</div>
                                <div class="text-sm font-semibold text-[#005187] dark:text-[#c9e4ff]" x-text="seleccionado?.anexo || 'No registrado'"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Email clickeable directo --}}
                    <a :href="'mailto:' + seleccionado?.email"
                       class="bg-[#c4dafa] dark:bg-[#4d82bc]/20 rounded-xl p-4 border border-[#84b6f4]/30 hover:bg-[#b8d4f4] dark:hover:bg-[#4d82bc]/30 transition-all duration-200 group cursor-pointer hover:shadow-md hover:scale-[1.02] block hci-button-ripple hci-glow"
                       :aria-label="'Enviar correo a ' + seleccionado?.email">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#4d82bc] rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-[#005187] transition-colors">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-medium text-[#4d82bc] dark:text-[#84b6f4] uppercase tracking-wide">EMAIL</div>
                                    <div class="text-sm font-semibold text-[#005187] dark:text-[#c9e4ff] break-all group-hover:text-[#003d6b] dark:group-hover:text-[#e6f3ff] transition-colors" x-text="seleccionado?.email"></div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-[#4d82bc] dark:text-[#84b6f4] group-hover:text-[#005187] dark:group-hover:text-[#c9e4ff] transition-colors">
                                <span class="text-xs font-medium">Enviar</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('components.footer')
</x-app-layout>
