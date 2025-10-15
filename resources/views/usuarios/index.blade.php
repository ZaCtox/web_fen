{{-- resources/views/usuarios/index.blade.php --}}
@section('title', 'Usuarios')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#c9e4ff]">Gestión de Usuarios</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Usuarios', 'url' => '#']
    ]" />

    <div class="p-6 max-w-7xl mx-auto" x-data="{
            search: '',
            sort: 'nombre_asc',
            usuarios: @js($usuarios->map(fn($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'rol' => $u->rol ?? 'sin_rol',
                'rol_display' => ucfirst(str_replace('_', ' ', $u->rol ?? 'Sin rol')),
                'last_login_at' => $u->last_login_at ? $u->last_login_at->diffForHumans([
                    'options' => Carbon\Carbon::JUST_NOW | Carbon\Carbon::ONE_DAY_WORDS,
                    'short' => false
                ]) : 'Nunca',
                'foto' => $u->foto,
                'avatar_url' => $u->foto ?? $u->generateAvatarUrl(),
                'initials' => strtoupper(
                    (count(explode(' ', $u->name)) >= 2) 
                        ? substr(explode(' ', $u->name)[0], 0, 1) . substr(explode(' ', $u->name)[1], 0, 1)
                        : substr($u->name, 0, 2)
                ),
                'avatar_color' => ['bg-blue-500', 'bg-purple-500', 'bg-pink-500', 'bg-indigo-500', 'bg-teal-500', 'bg-cyan-500', 'bg-emerald-500', 'bg-amber-500'][$u->id % 8],
            ])),
            authId: {{ auth()->id() }},
            get filtrados() {
                let arr = this.usuarios.filter(u => {
                    const q = this.search.toLowerCase();
                    return !q
                        || (u.name ?? '').toLowerCase().includes(q)
                        || (u.email ?? '').toLowerCase().includes(q)
                        || (u.rol ?? '').toLowerCase().includes(q);
                });

                const cmp = (a,b,f) => (a[f]||'').localeCompare(b[f]||'', undefined, {sensitivity:'base'});
                switch (this.sort) {
                    case 'nombre_asc':  arr.sort((a,b)=>cmp(a,b,'name')); break;
                    case 'nombre_desc': arr.sort((a,b)=>cmp(b,a,'name')); break;
                    case 'email_asc':   arr.sort((a,b)=>cmp(a,b,'email'));  break;
                    case 'email_desc':  arr.sort((a,b)=>cmp(b,a,'email'));  break;
                    case 'rol_asc':     arr.sort((a,b)=>cmp(a,b,'rol'));  break;
                    case 'rol_desc':    arr.sort((a,b)=>cmp(b,a,'rol'));  break;
                }
                return arr;
            },
            getRolBadgeColor(rol) {
                const colors = {
                    'administrador': 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700',
                    'director_programa': 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 border-purple-300 dark:border-purple-700',
                    'director_administrativo': 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border-blue-300 dark:border-blue-700',
                    'asistente_programa': 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700',
                    'asistente_postgrado': 'bg-teal-100 text-teal-800 dark:bg-teal-900/30 dark:text-teal-300 border-teal-300 dark:border-teal-700',
                    'docente': 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300 border-indigo-300 dark:border-indigo-700',
                    'técnico': 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300 border-orange-300 dark:border-orange-700',
                    'auxiliar': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 border-yellow-300 dark:border-yellow-700',
                    'sin_rol': 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300 border-gray-300 dark:border-gray-700'
                };
                return colors[rol] || colors['sin_rol'];
            }
         }">

        <!-- Controles -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <a href="{{ route('register') }}"
                class="inline-flex items-center justify-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white px-6 py-3 rounded-lg shadow-md transition-all duration-200 font-semibold text-sm hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 hci-button-ripple hci-glow"
                aria-label="Agregar nuevo usuario">
                <img src="{{ asset('icons/agregar.svg') }}" alt="" class="w-5 h-5">
                Agregar Usuario
            </a>
            
            <div class="flex gap-3 items-center w-full sm:w-auto">
                <div class="relative flex-1 sm:flex-initial">
                    <label for="search-usuarios" class="sr-only">Buscar usuarios</label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <img src="{{ asset('icons/filtro.svg') }}" alt="" class="h-5 w-5 opacity-60">
                    </div>
                    <input id="search-usuarios"
                           x-model="search" 
                           type="text" 
                           role="search"
                           aria-label="Buscar usuarios por nombre, correo o rol"
                           placeholder="Buscar por nombre, correo o rol"
                           class="w-full sm:w-[350px] pl-10 pr-4 py-3 rounded-lg border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition hci-input-focus">
                </div>
                
                <select x-model="sort" 
                        class="px-4 py-3 pr-10 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[160px] hci-focus-ring"
                        aria-label="Ordenar usuarios">
                    <option value="nombre_asc">Nombre A-Z</option>
                    <option value="nombre_desc">Nombre Z-A</option>
                    <option value="email_asc">Email A-Z</option>
                    <option value="email_desc">Email Z-A</option>
                    <option value="rol_asc">Rol A-Z</option>
                    <option value="rol_desc">Rol Z-A</option>
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
                    title="No se encontraron usuarios"
                    message="Intenta con otros términos de búsqueda o ajusta los filtros."
                    secondaryActionText="Limpiar Filtros"
                    secondaryActionUrl="{{ route('usuarios.index') }}"
                    secondaryActionIcon="🔄"
                />
            </div>
        </template>

        <!-- Grid de Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6 stagger-animation">
            <template x-for="(usuario, index) in filtrados" :key="usuario.id">
                <div class="group hci-fade-in" 
                     :style="`animation-delay: ${index * 0.1}s`">
                    <div class="cursor-pointer transition-all duration-300 rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:shadow-xl hover:-translate-y-1 hover:border-[#4d82bc]/40">
                        
                        {{-- Avatar con foto o iniciales --}}
                        <div class="flex justify-center pt-5 pb-3">
                            <div class="relative">
                                {{-- Avatar con foto o generado --}}
                                <img :src="usuario.avatar_url" 
                                     :alt="usuario.name"
                                     class="w-20 h-20 rounded-full object-cover shadow-lg border-4 border-white dark:border-gray-700 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                
                                {{-- Indicador de usuario autenticado --}}
                                <div x-show="usuario.id === authId" 
                                     class="absolute -bottom-1 -right-1 w-7 h-7 bg-green-500 rounded-full flex items-center justify-center border-4 border-white dark:border-gray-800 shadow-lg"
                                     title="Este eres tú">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Información --}}
                        <div class="px-4 pb-4 text-center">
                            <h3 class="text-base font-bold text-[#005187] dark:text-[#c9e4ff] mb-1 line-clamp-1" x-text="usuario.name"></h3>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-3 line-clamp-1" x-text="usuario.email"></p>
                            
                            {{-- Badge de Rol --}}
                            <div class="flex justify-center mb-3">
                                <span :class="getRolBadgeColor(usuario.rol)" 
                                      class="px-3 py-1 rounded-full text-xs font-semibold border"
                                      x-text="usuario.rol_display"></span>
                            </div>
                            
                            {{-- Última conexión --}}
                            <div class="flex items-center justify-center gap-1 text-xs text-gray-500 dark:text-gray-400 mb-4">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
                                </svg>
                                <span x-text="usuario.last_login_at"></span>
                            </div>

                            {{-- Acciones --}}
                            <div x-show="usuario.id !== authId" 
                                 class="flex gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                                {{-- Editar --}}
                                <a :href="`/usuarios/${usuario.id}/edit`"
                                   class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-[#84b6f4] hover:bg-[#4d82bc] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-sm font-medium hci-button-ripple min-h-[40px]"
                                   :aria-label="'Editar usuario ' + usuario.name">
                                    <img src="{{ asset('icons/editw.svg') }}" alt="" class="w-4 h-4 flex-shrink-0">
                                </a>

                                {{-- Eliminar --}}
                                <form :action="`/usuarios/${usuario.id}`" method="POST" 
                                      class="form-eliminar flex-1"
                                      data-confirm="¿Estás seguro de que quieres eliminar este usuario? Esta acción no se puede deshacer.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-[#e57373] hover:bg-[#d32f2f] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 text-sm font-medium hci-button-ripple min-h-[40px]"
                                            :aria-label="'Eliminar usuario ' + usuario.name">
                                        <img src="{{ asset('icons/trashw.svg') }}" alt="" class="w-4 h-4 flex-shrink-0">
                                    </button>
                                </form>
                            </div>

                            {{-- Mensaje para usuario autenticado --}}
                            <div x-show="usuario.id === authId" 
                                 class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-xs text-gray-500 dark:text-gray-400 italic">
                                    👤 Este es tu usuario
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</x-app-layout>


