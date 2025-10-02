<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex space-x-4 shrink-0 flex items-center">
                <a href="{{ route('public.dashboard.index') }}">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                </a>
            </div>

            <!-- Links -->
            @if(Auth::check())
                <div class="hidden space-x-6 lg:-my-px lg:ms-10 lg:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Inicio</x-nav-link>

                    @if(tieneRol(['administrador', 'director_programa', 'asistente_programa', 'asistente_postgrado']))
                        <x-nav-link :href="route('calendario')" :active="request()->routeIs('calendario')">Calendario</x-nav-link>
                    @endif

                    @if(tieneRol(['administrador', 'asistente_programa']))
                        <x-nav-link :href="route('rooms.index')" :active="request()->routeIs('rooms.index')">Salas</x-nav-link>
                    @endif

                    @if(tieneRol(['administrador', 'director_programa', 'asistente_programa', 'tecnico', 'auxiliar', 'asistente_postgrado']))
                        <x-nav-link :href="route('incidencias.index')" :active="request()->routeIs('incidencias.index')">Incidencias</x-nav-link>
                    @endif

                    @if(tieneRol(['administrador', 'director_programa', 'asistente_programa', 'asistente_postgrado']))
                        <x-nav-link :href="route('clases.index')" :active="request()->routeIs('clases.index')">Clases</x-nav-link>
                    @endif

                    @if(tieneRol(['administrador', 'director_programa', 'asistente_programa', 'asistente_postgrado']))
                        <x-nav-link :href="route('informes.index')" :active="request()->routeIs('informes.index')">Archivos</x-nav-link>
                    @endif

                    @if(tieneRol('administrador'))
                        <x-nav-link :href="route('periods.index')" :active="request()->routeIs('periods.index')">Periodos</x-nav-link>
                    @endif

                    @if(tieneRol(['administrador', 'director_programa', 'asistente_programa']))
                        <x-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.index')">Cursos</x-nav-link>
                    @endif

                    @if(tieneRol('administrador'))
                        <x-nav-link :href="route('staff.index')" :active="request()->routeIs('staff.index')">Nuestro Equipo</x-nav-link>
                        <x-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.index')">Usuarios</x-nav-link>
                    @endif

                    @if(tieneRol(['administrador', 'director_programa', 'asistente_programa', 'asistente_postgrado']))
                        <x-nav-link :href="route('emergencies.index')" :active="request()->routeIs('emergencies.index')">Emergencias</x-nav-link>
                    @endif
                </div>
            @else
                <!-- Links públicos -->
                <nav class="hidden lg:flex space-x-4">
                    <x-nav-link :href="route('public.dashboard.index')" :active="request()->routeIs('public.dashboard.index')">Inicio</x-nav-link>
                    <x-nav-link :href="route('public.calendario.index')" :active="request()->routeIs('public.calendario.index')">Calendario</x-nav-link>
                    <x-nav-link :href="route('public.Equipo-FEN.index')" :active="request()->routeIs('public.Equipo-FEN.index')">Nuestro Equipo</x-nav-link>
                    <x-nav-link :href="route('public.rooms.index')" :active="request()->routeIs('public.rooms.index')">Salas</x-nav-link>
                    <x-nav-link :href="route('public.courses.index')" :active="request()->routeIs('public.courses.index')">Cursos</x-nav-link>
                    <x-nav-link :href="route('public.informes.index')" :active="request()->routeIs('public.informes.index')">Archivos</x-nav-link>
                </nav>
            @endif

            <!-- Usuario -->
            <div class="hidden lg:flex lg:items-center lg:ms-6">
                @if(Auth::check())
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md 
                                    text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 
                                    focus:outline-none transition">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 
                                                10.586l3.293-3.293a1 1 0 111.414 1.414l-4 
                                                4a1 1 0 01-1.414 0l-4-4a1 1 0 
                                                010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.index')">Perfil</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Cerrar Sesión
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}"
                        class="text-sm bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded">Iniciar Sesión</a>
                @endif
            </div>

            <!-- Botón hamburguesa -->
            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md 
                    text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 
                    hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'block': ! open}" class="block" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'block': open}" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Overlay -->
    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
        @click="open = false"></div>

    <!-- Drawer / Menú lateral responsive -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-x-full"
        x-transition:enter-end="opacity-100 transform translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-x-0"
        x-transition:leave-end="opacity-0 transform -translate-x-full"
        class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 z-50 overflow-y-auto shadow-lg lg:hidden">

        <div class="pt-5 pb-3 flex flex-col space-y-1 px-4">
            @if(Auth::check())
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Inicio</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('calendario')" :active="request()->routeIs('calendario')">Calendario</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('rooms.index')" :active="request()->routeIs('rooms.index')">Salas</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('incidencias.index')" :active="request()->routeIs('incidencias.index')">Incidencias</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('clases.index')" :active="request()->routeIs('clases.index')">Clases</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('informes.index')" :active="request()->routeIs('informes.index')">Archivos</x-responsive-nav-link>
                
                @if(tieneRol('administrador'))
                    <x-responsive-nav-link :href="route('periods.index')" :active="request()->routeIs('periods.index')">Periodos</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.index')">Cursos</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('staff.index')" :active="request()->routeIs('staff.index')">Nuestro Equipo</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.index')">Usuarios</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('emergencies.index')" :active="request()->routeIs('emergencies.index')">Emergencias</x-responsive-nav-link>
                @endif
            @else
                <x-responsive-nav-link :href="route('public.dashboard.index')" :active="request()->routeIs('public.dashboard.index')">Inicio</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('public.calendario.index')" :active="request()->routeIs('public.calendario.index')">Calendario</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('public.Equipo-FEN.index')" :active="request()->routeIs('public.Equipo-FEN.index')">Nuestro Equipo</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('public.rooms.index')" :active="request()->routeIs('public.rooms.index')">Salas</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('public.courses.index')" :active="request()->routeIs('public.courses.index')">Cursos</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('public.informes.index')" :active="request()->routeIs('public.informes.index')">Archivos</x-responsive-nav-link>
                
                <a href="{{ route('login') }}" class="mt-4 block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded text-center">Iniciar Sesión</a>
            @endif

            @if(Auth::check())
                <div class="mt-auto border-t border-gray-200 dark:border-gray-600 p-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('profile.index')">Perfil</x-responsive-nav-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Cerrar Sesión</x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</nav>
