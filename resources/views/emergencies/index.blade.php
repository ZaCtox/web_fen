{{-- Inicio Emergencia.blade.php --}}
@section('title', 'Emergencias')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Emergencias</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Emergencias', 'url' => '#']
    ]" />

    @php
        $activeEmergency = app(\App\Http\Controllers\EmergencyController::class)->active();
    @endphp

    @if($activeEmergency)
        <meta name="active-emergency-title" content="{{ $activeEmergency->title }}">
        <meta name="active-emergency-message" content="{{ $activeEmergency->message }}">
    @endif

    <div class="py-6 max-w-7xl mx-auto px-4" 
         x-data="{
             busqueda: '',
             filtroEstado: 'todas',
             emergencias: {{ $emergencies->map(function($e) {
                 $isExpired = $e->expires_at && $e->expires_at->isPast();
                 return [
                     'id' => $e->id,
                     'title' => $e->title,
                     'message' => $e->message,
                     'active' => $e->active,
                     'expires_at' => $e->expires_at ? $e->expires_at->format('d/m/Y H:i') : null,
                     'expires_timestamp' => $e->expires_at ? $e->expires_at->timestamp : null,
                     'creator' => $e->creator->name ?? '—',
                     'isExpired' => $isExpired,
                     'estado' => $e->active && !$isExpired ? 'activa' : ($isExpired ? 'expirada' : 'inactiva'),
                     'show_url' => route('emergencies.show', $e),
                     'edit_url' => route('emergencies.edit', $e),
                     'toggle_url' => route('emergencies.toggleActive', $e),
                     'destroy_url' => route('emergencies.destroy', $e)
                 ];
             })->toJson() }},
             get filtradas() {
                 return this.emergencias.filter(e => {
                     const matchBusqueda = e.title.toLowerCase().includes(this.busqueda.toLowerCase()) || 
                                          e.message.toLowerCase().includes(this.busqueda.toLowerCase());
                     const matchEstado = this.filtroEstado === 'todas' || e.estado === this.filtroEstado;
                     return matchBusqueda && matchEstado;
                 });
             },
             get estadisticas() {
                 return {
                     total: this.emergencias.length,
                     activas: this.emergencias.filter(e => e.estado === 'activa').length,
                     expiradas: this.emergencias.filter(e => e.estado === 'expirada').length,
                     inactivas: this.emergencias.filter(e => e.estado === 'inactiva').length
                 };
             }
         }">
        {{-- Mostrar alerta de emergencia activa --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const titleMeta = document.querySelector('meta[name="active-emergency-title"]');
                const msgMeta = document.querySelector('meta[name="active-emergency-message"]');
                if (titleMeta && msgMeta) {
                    Swal.fire({
                        icon: 'warning',
                        title: titleMeta.content,
                        html: msgMeta.content.replace(/\n/g, '<br>'),
                        confirmButtonText: 'Cerrar'
                    });
                }
            });
        </script>

        <!-- Controles -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            @if(tieneRol('director_administrativo'))
            <a href="{{ route('emergencies.create') }}"
                class="inline-flex items-center justify-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white px-6 py-3 rounded-lg shadow-md transition-all duration-200 font-semibold text-sm hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 hci-button-ripple hci-glow"
                aria-label="Registrar nueva emergencia">
                <img src="{{ asset('icons/agregar.svg') }}" alt="" class="w-5 h-5">
                Agregar Emergencia
            </a>
            @endif
            
            <div class="flex gap-3 items-center w-full sm:w-auto">
                <div class="relative flex-1 sm:flex-initial">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <img src="{{ asset('icons/filtro.svg') }}" alt="" class="h-5 w-5 opacity-60">
                    </div>
                    <input x-model="busqueda" 
                           type="text" 
                           role="search"
                           aria-label="Buscar emergencias por título o mensaje"
                           placeholder="Buscar por título o mensaje"
                           class="w-full sm:w-[350px] pl-10 pr-4 py-3 rounded-lg border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition hci-input-focus">
                </div>
                
                <select x-model="filtroEstado" 
                        class="px-4 py-3 pr-10 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[160px] hci-focus-ring"
                        aria-label="Filtrar por estado">
                    <option value="todas">Todas</option>
                    <option value="activa">Activas</option>
                    <option value="expirada">Expiradas</option>
                    <option value="inactiva">Inactivas</option>
                </select>
                
                <button type="button" 
                        @click="busqueda = ''; filtroEstado = 'todas'"
                        class="p-3 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 hover:scale-105 hci-button-ripple hci-glow"
                        title="Limpiar búsqueda y filtros"
                        aria-label="Limpiar búsqueda y filtros">
                    <img src="{{ asset('icons/filterw.svg') }}" alt="" class="w-5 h-5">
                </button>
            </div>
        </div>

        {{-- Estadísticas --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            {{-- Total --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total</p>
                        <p class="text-2xl font-bold text-[#005187] dark:text-[#84b6f4]" x-text="estadisticas.total"></p>
                    </div>
                    <div class="w-12 h-12 bg-[#4d82bc]/10 rounded-full flex items-center justify-center">
                        <span class="text-2xl">📊</span>
                    </div>
                </div>
            </div>

            {{-- Activas --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Activas</p>
                        <p class="text-2xl font-bold text-red-600" x-text="estadisticas.activas"></p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center">
                        <span class="text-2xl">🚨</span>
                    </div>
                </div>
            </div>

            {{-- Expiradas --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Expiradas</p>
                        <p class="text-2xl font-bold text-gray-600 dark:text-gray-400" x-text="estadisticas.expiradas"></p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                        <span class="text-2xl">⏰</span>
                    </div>
                </div>
            </div>

            {{-- Inactivas --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Inactivas</p>
                        <p class="text-2xl font-bold text-gray-500" x-text="estadisticas.inactivas"></p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                        <span class="text-2xl">⏸️</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de emergencias mejorada --}}
        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="min-w-full table-auto text-sm text-[#005187] dark:text-[#fcffff]">
                <thead class="bg-gradient-to-r from-[#c4dafa]/50 to-[#e3f2fd] dark:from-gray-700 dark:to-gray-600 border-b-2 border-[#4d82bc]/20">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Título</th>
                        <th class="px-4 py-3 text-left font-semibold hidden md:table-cell">Mensaje</th>
                        <th class="px-4 py-3 text-center font-semibold">Estado</th>
                        <th class="px-4 py-3 text-left font-semibold hidden lg:table-cell">Expira</th>
                        <th class="px-4 py-3 text-left font-semibold hidden xl:table-cell">Creada por</th>
                        <th class="px-4 py-3 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-if="filtradas.length === 0">
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="text-5xl mb-4">🔍</span>
                                    <p class="text-gray-600 dark:text-gray-400 text-lg font-medium">No se encontraron emergencias</p>
                                    <p class="text-gray-500 dark:text-gray-500 text-sm mt-2">Intenta con otros términos de búsqueda o filtros</p>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <template x-for="(emergencia, index) in filtradas" :key="emergencia.id">
                        <tr class="border-t border-[#c4dafa]/40 dark:border-gray-700 
                                   hover:bg-[#e3f2fd] dark:hover:bg-gray-700/50
                                   hover:border-l-4 hover:border-l-[#4d82bc]
                                   hover:shadow-md
                                   transition-all duration-200 group cursor-pointer"
                            :class="emergencia.estado === 'activa' ? 'bg-red-50/30 dark:bg-red-900/10' : ''"
                            @click="window.location.href = emergencia.show_url">
                            
                            {{-- Título --}}
                            <td class="px-4 py-3">
                                <div class="font-medium group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200" 
                                     x-text="emergencia.title"></div>
                                {{-- Mostrar mensaje en móvil --}}
                                <div class="md:hidden text-xs text-gray-600 dark:text-gray-400 mt-1 line-clamp-2" 
                                     x-text="emergencia.message.substring(0, 80) + (emergencia.message.length > 80 ? '...' : '')"></div>
                            </td>

                            {{-- Mensaje (oculto en móvil) --}}
                            <td class="px-4 py-3 hidden md:table-cell">
                                <div class="text-gray-700 dark:text-gray-300 line-clamp-2" 
                                     x-text="emergencia.message.substring(0, 120) + (emergencia.message.length > 120 ? '...' : '')"></div>
                            </td>

                            {{-- Estado --}}
                            <td class="px-4 py-3 text-center">
                                <template x-if="emergencia.estado === 'activa'">
                                    <span class="inline-flex items-center justify-center px-3 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border border-red-200 dark:border-red-800">
                                        🚨
                                    </span>
                                </template>
                                <template x-if="emergencia.estado === 'expirada'">
                                    <span class="inline-flex items-center justify-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                        ⏰
                                    </span>
                                </template>
                                <template x-if="emergencia.estado === 'inactiva'">
                                    <span class="inline-flex items-center justify-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
                                        ⏸️
                                    </span>
                                </template>
                            </td>

                            {{-- Expira (oculto en móvil y tablet) --}}
                            <td class="px-4 py-3 hidden lg:table-cell">
                                <span x-text="emergencia.expires_at || '—'" class="text-gray-700 dark:text-gray-300"></span>
                            </td>

                            {{-- Creada por (oculto en móvil, tablet y desktop pequeño) --}}
                            <td class="px-4 py-3 hidden xl:table-cell">
                                <span x-text="emergencia.creator" class="text-gray-700 dark:text-gray-300"></span>
                            </td>

                            {{-- Acciones --}}
                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-2" @click.stop>
                                    {{-- Editar --}}
                                    <a :href="emergencia.edit_url" 
                                       class="inline-flex items-center justify-center px-3 py-2 bg-[#84b6f4] hover:bg-[#4d82bc] text-white rounded-lg text-xs font-medium transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-1"
                                       title="Editar emergencia">
                                        <img src="{{ asset('icons/editw.svg') }}" alt="" class="w-4 h-4">
                                    </a>

                                    {{-- Activar/Desactivar (solo si no está expirada) --}}
                                    <template x-if="emergencia.estado !== 'expirada'">
                                        <form :action="emergencia.toggle_url" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    :class="emergencia.estado === 'activa' 
                                                        ? 'bg-[#ffa726] hover:bg-[#ff9800] focus:ring-orange-400' 
                                                        : 'bg-green-600 hover:bg-green-700 focus:ring-green-500'"
                                                    class="inline-flex items-center justify-center px-3 py-2 text-white rounded-lg text-xs font-medium transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-1"
                                                    :title="emergencia.estado === 'activa' ? 'Desactivar emergencia' : 'Activar emergencia'">
                                                <img :src="emergencia.estado === 'activa' 
                                                          ? '{{ asset('icons/desactivarw.svg') }}' 
                                                          : '{{ asset('icons/activarw.svg') }}'" 
                                                     alt="" class="w-4 h-4">
                                            </button>
                                        </form>
                                    </template>

                                    {{-- Eliminar --}}
                                    <form :action="emergencia.destroy_url" method="POST"
                                          class="form-eliminar inline"
                                          data-confirm="¿Estás seguro de que quieres eliminar esta emergencia? Esta acción no se puede deshacer.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center justify-center px-3 py-2 bg-[#e57373] hover:bg-[#d32f2f] text-white rounded-lg text-xs font-medium transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1"
                                                title="Eliminar emergencia">
                                            <img src="{{ asset('icons/trashw.svg') }}" alt="" class="w-4 h-4">
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- Contador de resultados --}}
        <div class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400">
            <span x-text="`Mostrando ${filtradas.length} de ${emergencias.length} emergencia${emergencias.length !== 1 ? 's' : ''}`"></span>
        </div>
    </div>
</x-app-layout>


