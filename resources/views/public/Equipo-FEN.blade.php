{{-- Nuestro equipo FEN --}}
@section('title', 'Equipo Fen')
<x-app-layout>
    <x-slot name="header">
<<<<<<< Updated upstream
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Información de nuestro equipo</h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto"
         x-data="{
=======
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Información de nuestro equipo</h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto" x-data="{
>>>>>>> Stashed changes
            search: '',
            modalOpen: false,
            seleccionado: null,
            staff: @js($staff),
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

        {{-- Buscador dinámico --}}
        <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
            <input x-model="search" name="q" placeholder="Buscar por nombre, cargo o email"
                class="flex-1 px-4 py-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
        </form>

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

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-6">
            <template x-for="p in filtrados" :key="p.id">
                <div @click="openModal(p)"
                    class="cursor-pointer hover:shadow-lg transition rounded-2xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <div class="flex">
                        <div class="w-2/3 p-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white" x-text="p.nombre"></h3>
                            <p class="text-sm text-gray-500 dark:text-gray-300" x-text="p.cargo"></p>
                        </div>
<<<<<<< Updated upstream
                        <div class="w-1/3 bg-[#12c6df] text-white p-4">
=======
                        <div class="w-1/3 bg-[#4d82bc] text-white p-4">
>>>>>>> Stashed changes
                            <div class="text-[13px] tracking-wide opacity-90">Teléfono</div>
                            <div class="text-sm mb-2 break-words" x-text="p.telefono || '—'"></div>
                            <div class="text-[13px] tracking-wide opacity-90">Email</div>
                            <div class="text-sm truncate" x-text="p.email" :title="p.email"></div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Modal detalle --}}
        <div x-show="modalOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl w-full max-w-xl relative">
                <button @click="modalOpen = false"
                    class="absolute top-2 right-2 text-gray-700 dark:text-gray-200 hover:text-red-500">
                    ✖
                </button>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2" x-text="seleccionado?.nombre"></h2>
                <p class="text-gray-600 dark:text-gray-300 mb-4" x-text="seleccionado?.cargo"></p>

<<<<<<< Updated upstream
                <div class="bg-[#12c6df] text-white p-4 rounded-lg space-y-2">
=======
                <div class="bg-[#4d82bc] text-white p-4 rounded-lg space-y-2">
>>>>>>> Stashed changes
                    <div>
                        <div class="text-[13px] tracking-wide opacity-90">Teléfono</div>
                        <div class="text-sm" x-text="seleccionado?.telefono || '—'"></div>
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