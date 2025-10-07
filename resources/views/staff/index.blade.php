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
                class="inline-flex items-center bg-[#4d82bc] hover:bg-[#005187] ml-4 text-white px-4 py-2 rounded-lg shadow transition transform hover:scale-105">
                <img src="{{ asset('icons/agregar.svg') }}" alt="nueva" class="w-5 h-5">
            </a>
            <div class="flex w-full sm:w-auto gap-3 items-center">
                <input x-model="search" type="text" placeholder="Buscar por nombre, cargo o email"
                    class="w-full sm:w-[350px] px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                <button type="button" @click="search=''; sort='nombre_asc'; hasPhone=false"
                    class="px-3 py-2 rounded-lg bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100">
                    Limpiar
                </button>
            </div>

        </div>

        <!-- Meta -->
        <div class="text-sm text-gray-500 dark:text-gray-300 mb-2">
            Mostrando <span x-text="filtrados.length"></span> de {{ $staff->count() }} registros
        </div>

        <!-- Sin resultados -->
        <template x-if="filtrados.length === 0">
            <div class="rounded-lg border border-dashed p-6 text-center text-gray-500 dark:text-gray-300">
                No hay registros que coincidan.
            </div>
        </template>

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-6">
            <template x-for="p in filtrados" :key="p.id">
                <a :href="p.show_url" class="group hci-card-hover">
                    <div
                        class="cursor-pointer hci-lift transition-all duration-300 rounded-2xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:shadow-xl">
                        <div class="flex">
                            <div class="w-2/3 p-4">
                                <h3 class="text-lg font-bold text-[#005187] dark:text-[#84b6f4] group-hover:text-[#4d82bc] transition-colors duration-200" x-text="p.nombre"></h3>
                                <p class="text-sm text-[#4d82bc] dark:text-[#84b6f4] group-hover:text-[#005187] transition-colors duration-200" x-text="p.cargo"></p>
                            </div>
                            <div class="w-1/3 bg-[#4d82bc] group-hover:bg-[#005187] text-white p-4 transition-colors duration-200">
                                <div class="text-[13px] tracking-wide opacity-90">Teléfono</div>
                                <div class="text-sm mb-2 break-words" x-text="p.telefono || '—'"></div>
                                <div class="text-[13px] tracking-wide opacity-90">Email</div>
                                <div class="text-sm truncate" x-text="p.email" :title="p.email"></div>
                            </div>
                        </div>
                    </div>
                </a>
            </template>
        </div>
    </div>
</x-app-layout>
