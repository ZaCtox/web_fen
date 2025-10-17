{{-- Modal para Agregar/Editar Sesión --}}
<div x-show="showModal"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;"
     x-data="{
         bloques: [],
         usarBloques: false,
         agregarBloque(tipo) {
             this.bloques.push({
                 tipo: tipo,
                 inicio: '',
                 fin: '',
                 nombre: ''
             });
         },
         eliminarBloque(index) {
             this.bloques.splice(index, 1);
         },
         obtenerIconoBloque(tipo) {
             if (tipo === 'clase') return '📚';
             if (tipo === 'coffee_break') return '☕';
             if (tipo === 'lunch_break') return '🍽️';
             return '⏰';
         },
         obtenerNombreBloque(tipo) {
             if (tipo === 'clase') return 'Clase';
             if (tipo === 'coffee_break') return 'Coffee Break';
             if (tipo === 'lunch_break') return 'Lunch Break';
             return 'Otro';
         },
         bloquesJSON() {
             return JSON.stringify(this.bloques);
         },
         cargarBloquesTemplate() {
             // Template para sábados (09:00 - 16:30)
             this.bloques = [
                 { tipo: 'clase', inicio: '09:00', fin: '10:30', nombre: '' },
                 { tipo: 'coffee_break', inicio: '10:30', fin: '11:00', nombre: '' },
                 { tipo: 'clase', inicio: '11:00', fin: '13:30', nombre: '' },
                 { tipo: 'lunch_break', inicio: '13:30', fin: '14:30', nombre: '' },
                 { tipo: 'clase', inicio: '14:30', fin: '15:30', nombre: '' },
                 { tipo: 'clase', inicio: '15:30', fin: '16:30', nombre: '' }
             ];
         }
     }">
    
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        {{-- Overlay --}}
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showModal = false"></div>

        {{-- Modal Panel --}}
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-2xl font-bold text-[#005187] dark:text-[#c4dafa] mb-4 flex items-center gap-2" x-text="modalMode === 'add' ? '➕ Nueva Sesión' : (modalMode === 'grabacion' ? '🎥 Agregar Grabación' : '✏️ Editar Sesión')">
                        </h3>

                        {{-- Formulario dinámico --}}
                        <template x-if="modalMode === 'grabacion'">
                            <form method="POST" :action="`/sesiones/${editingSesion}/grabacion`" class="space-y-4">
                                @csrf
                                @method('PATCH')
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        📹 URL de la Grabación (YouTube)
                                    </label>
                                    <input type="url" 
                                           name="url_grabacion" 
                                           required 
                                           placeholder="https://www.youtube.com/watch?v=..."
                                           class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-[#4d82bc] focus:ring focus:ring-[#4d82bc]/50">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Copia y pega el enlace completo del video de YouTube
                                    </p>
                                </div>

                                <div class="flex gap-3 mt-6">
                                    <button type="submit"
                                            class="flex-1 inline-flex justify-center items-center gap-2 px-4 py-2 bg-[#3ba55d] hover:bg-[#2d864a] text-white font-medium rounded-lg shadow transition-all duration-200">
                                        <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-5 h-5">
                                        Guardar Grabación
                                    </button>
                                    <button type="button" 
                                            @click="showModal = false"
                                            class="px-4 py-2 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-medium rounded-lg transition-all duration-200">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </template>

                        <template x-if="modalMode === 'add'">
                            <form method="POST" action="{{ route('clases.sesiones.store', $clase) }}" class="space-y-4" @submit="if(usarBloques && bloques.length > 0) { $event.target.querySelector('input[name=bloques_horarios]').value = bloquesJSON(); }">
                                @csrf
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        📅 Fecha de la Sesión *
                                    </label>
                                    <input type="date" 
                                           name="fecha" 
                                           required 
                                           class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-[#4d82bc] focus:ring focus:ring-[#4d82bc]/50">
                                </div>

                                {{-- Toggle para usar bloques horarios --}}
                                <div class="border-t border-b border-gray-200 dark:border-gray-600 py-3">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" 
                                               x-model="usarBloques" 
                                               class="w-5 h-5 text-[#4d82bc] rounded focus:ring-[#4d82bc]">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            ⏰ Usar bloques horarios (con breaks)
                                        </span>
                                    </label>
                                </div>

                                {{-- Sección de bloques horarios --}}
                                <div x-show="usarBloques" x-transition class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Define bloques de clase y breaks</p>
                                        <button type="button" 
                                                @click="cargarBloquesTemplate()"
                                                class="text-xs px-3 py-1 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded transition">
                                            🎯 Template Sábado
                                        </button>
                                    </div>

                                    {{-- Lista de bloques --}}
                                    <div class="space-y-2 max-h-64 overflow-y-auto">
                                        <template x-for="(bloque, index) in bloques" :key="index">
                                            <div class="flex items-center gap-2 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                <span x-text="obtenerIconoBloque(bloque.tipo)" class="text-xl"></span>
                                                <select x-model="bloque.tipo" class="text-sm rounded border-gray-300 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                                    <option value="clase">Clase</option>
                                                    <option value="coffee_break">Coffee Break</option>
                                                    <option value="lunch_break">Lunch Break</option>
                                                </select>
                                                <input type="time" 
                                                       x-model="bloque.inicio" 
                                                       placeholder="Inicio"
                                                       class="text-sm rounded border-gray-300 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                                <span class="text-gray-500">-</span>
                                                <input type="time" 
                                                       x-model="bloque.fin" 
                                                       placeholder="Fin"
                                                       class="text-sm rounded border-gray-300 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                                <button type="button" 
                                                        @click="eliminarBloque(index)"
                                                        class="p-1 text-red-600 hover:bg-red-100 dark:hover:bg-red-900/30 rounded transition">
                                                    ❌
                                                </button>
                                            </div>
                                        </template>
                                    </div>

                                    {{-- Botones para agregar bloques --}}
                                    <div class="flex gap-2 flex-wrap">
                                        <button type="button" 
                                                @click="agregarBloque('clase')"
                                                class="text-xs px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded transition">
                                            📚 + Clase
                                        </button>
                                        <button type="button" 
                                                @click="agregarBloque('coffee_break')"
                                                class="text-xs px-3 py-1 bg-amber-100 hover:bg-amber-200 text-amber-700 rounded transition">
                                            ☕ + Coffee Break
                                        </button>
                                        <button type="button" 
                                                @click="agregarBloque('lunch_break')"
                                                class="text-xs px-3 py-1 bg-orange-100 hover:bg-orange-200 text-orange-700 rounded transition">
                                            🍽️ + Lunch Break
                                        </button>
                                    </div>

                                    <input type="hidden" name="bloques_horarios" value="">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        📊 Estado *
                                    </label>
                                    <select name="estado" 
                                            required
                                            class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-[#4d82bc] focus:ring focus:ring-[#4d82bc]/50">
                                        <option value="pendiente">⏳ Pendiente</option>
                                        <option value="completada">✅ Completada</option>
                                        <option value="cancelada">❌ Cancelada</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        📹 URL de Grabación (opcional)
                                    </label>
                                    <input type="url" 
                                           name="url_grabacion" 
                                           placeholder="https://www.youtube.com/watch?v=..."
                                           class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-[#4d82bc] focus:ring focus:ring-[#4d82bc]/50">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        📝 Observaciones (opcional)
                                    </label>
                                    <textarea name="observaciones" 
                                              rows="3" 
                                              placeholder="Notas adicionales sobre la sesión..."
                                              class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-[#4d82bc] focus:ring focus:ring-[#4d82bc]/50"></textarea>
                                </div>

                                <div class="flex gap-3 mt-6">
                                    <button type="submit"
                                            class="flex-1 inline-flex justify-center items-center gap-2 px-4 py-2 bg-[#3ba55d] hover:bg-[#2d864a] text-white font-medium rounded-lg shadow transition-all duration-200">
                                        <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-5 h-5">
                                        Crear Sesión
                                    </button>
                                    <button type="button" 
                                            @click="showModal = false; usarBloques = false; bloques = []"
                                            class="px-4 py-2 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-medium rounded-lg transition-all duration-200">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </template>

                        <template x-if="modalMode === 'edit'">
                            {{-- Formulario de edición se poblará con JavaScript --}}
                            <div id="edit-form-container">
                                @foreach($clase->sesiones as $sesion)
                                    <form method="POST" 
                                          action="{{ route('sesiones.update', $sesion) }}" 
                                          class="space-y-4" 
                                          x-show="editingSesion === {{ $sesion->id }}"
                                          x-data="{
                                              bloques{{ $sesion->id }}: {{ json_encode($sesion->bloques_horarios ?? []) }},
                                              usarBloques{{ $sesion->id }}: {{ $sesion->tiene_bloques ? 'true' : 'false' }}
                                          }"
                                          @submit="if(usarBloques{{ $sesion->id }} && bloques{{ $sesion->id }}.length > 0) { $event.target.querySelector('input[name=bloques_horarios]').value = JSON.stringify(bloques{{ $sesion->id }}); }"
                                          style="display: none;">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                📅 Fecha de la Sesión *
                                            </label>
                                            <input type="date" 
                                                   name="fecha" 
                                                   value="{{ $sesion->fecha->format('Y-m-d') }}"
                                                   required 
                                                   class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-[#4d82bc] focus:ring focus:ring-[#4d82bc]/50">
                                        </div>

                                        {{-- Toggle para bloques horarios --}}
                                        <div class="border-t border-b border-gray-200 dark:border-gray-600 py-3">
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="checkbox" 
                                                       x-model="usarBloques{{ $sesion->id }}" 
                                                       class="w-5 h-5 text-[#4d82bc] rounded focus:ring-[#4d82bc]">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    ⏰ Usar bloques horarios (con breaks)
                                                </span>
                                            </label>
                                        </div>

                                        {{-- Sección bloques --}}
                                        <div x-show="usarBloques{{ $sesion->id }}" x-transition class="space-y-3">
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Editar bloques de clase y breaks</p>
                                            
                                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                                <template x-for="(bloque, index) in bloques{{ $sesion->id }}" :key="index">
                                                    <div class="flex items-center gap-2 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                        <span x-text="obtenerIconoBloque(bloque.tipo)" class="text-xl"></span>
                                                        <select x-model="bloque.tipo" class="text-sm rounded border-gray-300 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                                            <option value="clase">Clase</option>
                                                            <option value="coffee_break">Coffee Break</option>
                                                            <option value="lunch_break">Lunch Break</option>
                                                        </select>
                                                        <input type="time" x-model="bloque.inicio" class="text-sm rounded border-gray-300 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                                        <span class="text-gray-500">-</span>
                                                        <input type="time" x-model="bloque.fin" class="text-sm rounded border-gray-300 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                                        <button type="button" @click="bloques{{ $sesion->id }}.splice(index, 1)" class="p-1 text-red-600 hover:bg-red-100 dark:hover:bg-red-900/30 rounded transition">❌</button>
                                                    </div>
                                                </template>
                                            </div>

                                            <div class="flex gap-2 flex-wrap">
                                                <button type="button" @click="bloques{{ $sesion->id }}.push({tipo: 'clase', inicio: '', fin: '', nombre: ''})" class="text-xs px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded transition">📚 + Clase</button>
                                                <button type="button" @click="bloques{{ $sesion->id }}.push({tipo: 'coffee_break', inicio: '', fin: '', nombre: ''})" class="text-xs px-3 py-1 bg-amber-100 hover:bg-amber-200 text-amber-700 rounded transition">☕ + Coffee Break</button>
                                                <button type="button" @click="bloques{{ $sesion->id }}.push({tipo: 'lunch_break', inicio: '', fin: '', nombre: ''})" class="text-xs px-3 py-1 bg-orange-100 hover:bg-orange-200 text-orange-700 rounded transition">🍽️ + Lunch Break</button>
                                            </div>

                                            <input type="hidden" name="bloques_horarios" value="">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                📊 Estado *
                                            </label>
                                            <select name="estado" 
                                                    required
                                                    class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-[#4d82bc] focus:ring focus:ring-[#4d82bc]/50">
                                                <option value="pendiente" {{ $sesion->estado === 'pendiente' ? 'selected' : '' }}>⏳ Pendiente</option>
                                                <option value="completada" {{ $sesion->estado === 'completada' ? 'selected' : '' }}>✅ Completada</option>
                                                <option value="cancelada" {{ $sesion->estado === 'cancelada' ? 'selected' : '' }}>❌ Cancelada</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                📹 URL de Grabación (opcional)
                                            </label>
                                            <input type="url" 
                                                   name="url_grabacion" 
                                                   value="{{ $sesion->url_grabacion }}"
                                                   placeholder="https://www.youtube.com/watch?v=..."
                                                   class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-[#4d82bc] focus:ring focus:ring-[#4d82bc]/50">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                📝 Observaciones (opcional)
                                            </label>
                                            <textarea name="observaciones" 
                                                      rows="3" 
                                                      placeholder="Notas adicionales sobre la sesión..."
                                                      class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-[#4d82bc] focus:ring focus:ring-[#4d82bc]/50">{{ $sesion->observaciones }}</textarea>
                                        </div>

                                        <div class="flex gap-3 mt-6">
                                            <button type="submit"
                                                    class="flex-1 inline-flex justify-center items-center gap-2 px-4 py-2 bg-[#3ba55d] hover:bg-[#2d864a] text-white font-medium rounded-lg shadow transition-all duration-200">
                                                <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-5 h-5">
                                                Actualizar Sesión
                                            </button>
                                            <button type="button" 
                                                    @click="showModal = false"
                                                    class="px-4 py-2 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-medium rounded-lg transition-all duration-200">
                                                Cancelar
                                            </button>
                                        </div>
                                    </form>
                                @endforeach
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




