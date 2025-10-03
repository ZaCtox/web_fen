{{-- Dashboard de Postgrado FEN --}}
@section('title', 'Inicio')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#4d82bc]">
            Bienvenido a Postgrado FEN!
        </h2>
    </x-slot>

    @php
        $emergency = app(\App\Http\Controllers\EmergencyController::class)->active();
    @endphp

    @if($emergency)
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'warning',
                    title: '{{ $emergency->title }}',
                    html: '{!! nl2br(e($emergency->message)) !!}',
                    confirmButtonText: 'Cerrar'
                });
            });
        </script>
    @endif

    {{-- Novedades de los Programas --}}
    <div
        class="py-16 max-w-6xl mt-8 mx-auto px-6 space-y-6 text-center bg-[#fcffff] dark:bg-gray-800 border border-[#c4dafa] dark:border-gray-700 rounded-lg shadow-lg">
        <h3 class="text-xl text-[#005187] dark:text-[#84b6f4] font-semibold">Novedades de los Programas</h3>
        <p class="text-sm text-gray-600 dark:text-gray-300">Mantente al día con lo último que subimos</p>

        {{-- Carrusel con Alpine.js --}}
        <div x-data="carousel()" class="relative mt-6">
            {{-- Contenedor de tarjetas --}}
            <div class="overflow-hidden relative">
                <div class="flex transition-transform duration-500 ease-in-out"
                    :style="'transform: translateX(-' + currentIndex * (100 / visibleCards) + '%)'" x-ref="carousel">
                    {{-- Tarjetas --}}
                    <template x-for="(card, index) in cards" :key="index">
                        <div class="px-2 flex-shrink-0 w-1/3">
                            <div
                                class="bg-white dark:bg-gray-700 rounded-xl shadow-lg p-4 hover:scale-105 transform transition-transform duration-300">
                                <div
                                    class="bg-gray-200 dark:bg-gray-600 h-40 rounded-md mb-3 flex items-center justify-center text-gray-500 dark:text-gray-300 font-semibold">
                                    Imagen
                                </div>
                                <h4 class="font-semibold text-gray-800 dark:text-gray-100 mb-1" x-text="card.title">
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-2" x-text="card.desc"></p>
                                <a :href="card.link"
                                    class="text-sm text-[#005187] dark:text-[#84b6f4] hover:underline font-medium">Ver
                                    más</a>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Botones --}}
            <button @click="prev()"
                class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-[#4d82bc] hover:bg-[#005187] text-white p-3 rounded-full shadow-lg">
                &#10094;
            </button>
            <button @click="next()"
                class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-[#4d82bc] hover:bg-[#005187] text-white p-3 rounded-full shadow-lg">
                &#10095;
            </button>
        </div>
    </div>

    {{-- Alpine.js --}}
    <script>
        function carousel() {
            return {
                currentIndex: 0,
                visibleCards: 3,
                cards: [
                    { title: 'Novedad 1', desc: 'Breve descripción de la novedad 1', link: '#' },
                    { title: 'Novedad 2', desc: 'Breve descripción de la novedad 2', link: '#' },
                    { title: 'Novedad 3', desc: 'Breve descripción de la novedad 3', link: '#' },
                    { title: 'Novedad 4', desc: 'Breve descripción de la novedad 4', link: '#' },
                    { title: 'Novedad 5', desc: 'Breve descripción de la novedad 5', link: '#' }
                ],
                prev() {
                    if (this.currentIndex === 0) {
                        let remainder = this.cards.length % this.visibleCards;
                        this.currentIndex = remainder === 0 ? this.cards.length - this.visibleCards : this.cards.length - remainder;
                    } else {
                        this.currentIndex -= this.visibleCards;
                        if (this.currentIndex < 0) this.currentIndex = 0;
                    }
                },
                next() {
                    this.currentIndex += this.visibleCards;
                    if (this.currentIndex >= this.cards.length) {
                        this.currentIndex = 0;
                    }
                }
            }
        }
    </script>




    {{-- Footer --}}
    <footer>
        @include('components.footer')
    </footer>
</x-app-layout>