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

    {{-- Novedades Urgentes --}}
    @if(isset($novedadesUrgentes) && $novedadesUrgentes->count() > 0)
    <div class="py-6 max-w-6xl mt-8 mx-auto px-6 space-y-4">
        <h3 class="text-2xl text-[#005187] dark:text-[#84b6f4] font-bold flex items-center">
            <span class="text-3xl mr-2">🔴</span>
            Anuncios Importantes
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($novedadesUrgentes as $novedad)
            <div class="bg-gradient-to-br from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 border-2 border-red-300 dark:border-red-700 rounded-lg p-5 shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-start">
                    <span class="text-4xl mr-3">{{ $novedad->icono ?? '📢' }}</span>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-800 dark:text-gray-100 mb-2 text-lg">
                            {{ $novedad->titulo }}
                        </h4>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-3 line-clamp-3">
                            {{ Str::limit($novedad->contenido, 120) }}
                        </p>
                        @if($novedad->fecha_expiracion && $novedad->fecha_expiracion instanceof \Carbon\Carbon)
                        <p class="text-xs text-red-600 dark:text-red-400 mb-2">
                            ⏰ Hasta: {{ $novedad->fecha_expiracion->format('d/m/Y') }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Novedades Generales --}}
    @if(isset($novedades) && $novedades->count() > 0)
    <div class="py-8 max-w-6xl mt-8 mx-auto px-6 space-y-6 bg-[#fcffff] dark:bg-gray-800 border border-[#c4dafa] dark:border-gray-700 rounded-lg shadow-lg">
        <div class="text-center">
            <h3 class="text-2xl text-[#005187] dark:text-[#84b6f4] font-bold">Novedades y Actividades</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">Mantente informado sobre eventos, seminarios y actualizaciones de la facultad</p>
        </div>

        {{-- Grid de Novedades --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
            @foreach($novedades as $novedad)
            <div class="bg-white dark:bg-gray-700 rounded-xl shadow-lg overflow-hidden hover:scale-105 transform transition-all duration-300 border border-gray-200 dark:border-gray-600">
                {{-- Header con icono y tipo --}}
                <div class="p-4 text-center" style="background-color: {{ $novedad->color ?? '#4d82bc' }}22;">
                    <span class="text-5xl">{{ $novedad->icono ?? '📰' }}</span>
                    <div class="mt-2">
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full" 
                              style="background-color: {{ $novedad->color ?? '#4d82bc' }}; color: white;">
                            {{ ucfirst($novedad->tipo_novedad) }}
                        </span>
                    </div>
                </div>

                {{-- Contenido --}}
                <div class="p-4">
                    <h4 class="font-bold text-gray-800 dark:text-gray-100 mb-2 text-base line-clamp-2">
                        {{ $novedad->titulo }}
                    </h4>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-3 line-clamp-3">
                        {{ Str::limit($novedad->contenido, 100) }}
                    </p>

                    {{-- Información adicional --}}
                    <div class="space-y-2 text-xs text-gray-500 dark:text-gray-400">
                        @if($novedad->magister)
                        <div class="flex items-center">
                            <span class="mr-1">🎓</span>
                            <span>{{ Str::limit($novedad->magister->nombre, 30) }}</span>
                        </div>
                        @endif
                        @if($novedad->fecha_expiracion && $novedad->fecha_expiracion instanceof \Carbon\Carbon)
                        <div class="flex items-center">
                            <span class="mr-1">📅</span>
                            <span>Hasta: {{ $novedad->fecha_expiracion->format('d/m/Y') }}</span>
                        </div>
                        @endif
                        <div class="flex items-center text-gray-400">
                            <span class="mr-1">🕐</span>
                            <span>{{ $novedad->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Botón Ver Todas --}}
        <div class="text-center mt-6">
            <a href="{{ route('public.novedades') }}" 
               class="inline-block px-6 py-3 bg-[#4d82bc] hover:bg-[#005187] text-white font-semibold rounded-lg shadow-md transition-colors duration-300">
                Ver Todas las Novedades →
            </a>
        </div>
    </div>
    @else
    <div class="py-16 max-w-6xl mt-8 mx-auto px-6 text-center bg-[#fcffff] dark:bg-gray-800 border border-[#c4dafa] dark:border-gray-700 rounded-lg shadow-lg">
        <span class="text-6xl">📰</span>
        <h3 class="text-xl text-[#005187] dark:text-[#84b6f4] font-semibold mt-4">Próximamente</h3>
        <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">Las novedades se publicarán próximamente</p>
    </div>
    @endif




    {{-- Footer --}}
    <footer>
        @include('components.footer')
    </footer>
</x-app-layout>