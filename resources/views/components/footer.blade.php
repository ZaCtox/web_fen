<footer
    class="mt-12 border-t border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm text-gray-600 dark:text-gray-300">
    <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-8">

        {{-- Columna 1: Logo, nombre y guardias --}}
        <div>
            <x-logo-fen />
            <ul class="mt-4 space-y-1 text-sm">
                <li><a href="https://maps.app.goo.gl/UiwqPCGEcV5FSAu56" class="hover:underline">Campus Lircay, Av.
                        Lircay s/n</a></li>
                <li>Talca, Región del Maule</li>
            </ul>
        </div>

        {{-- Columna 2: Contacto --}}
        <div>
            <h4 class="font-semibold mb-2 text-gray-800 dark:text-white">Contacto</h4>
            <ul class="space-y-1">
                <li>71 220 0200 (Campus Talca)</li>
                <li><a href="mailto:contactofen@utalca.cl" class="underline">contactofen@utalca.cl</a></li>
            </ul>

            <h4 class="font-semibold mt-6 mb-2 text-gray-800 dark:text-white">Guardias</h4>
            <ul class="space-y-1">
                <li>Portería Sur: 71 220 1604</li>
                <li>Portería Norte: 71 220 0467</li>
            </ul>
        </div>

        {{-- Columna 3: Enlaces públicos --}}
        <div>
            <h4 class="font-semibold mb-2 text-gray-800 dark:text-white">Sitio Web</h4>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('public.Equipo-FEN.index') }}" class="hover:underline">👥 Nuestro equipo</a>
                </li>
                <li>
                    <a href="https://fen.utalca.cl" target="_blank" class="hover:underline">🌐 Sitio oficial FEN</a>
                </li>
                <li>
                    <a href="{{ url('/') }}" class="hover:underline">🏠 Inicio Plataforma</a>
                </li>
            </ul>
        </div>
    </div>

    <div
        class="text-center py-4 text-xs border-t border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400">
        © {{ date('Y') }} Facultad de Economía y Negocios - Universidad de Talca. Todos los derechos reservados.
    </div>
</footer>