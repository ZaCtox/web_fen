<nav x-data="{ 
    open: false, 
    searchOpen: false,
    searchQuery: '',
    userMenuOpen: false
}" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
    
    <!-- Barra superior -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            <!-- Logo y búsqueda -->
            <div class="flex items-center space-x-4 flex-1">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('public.dashboard.index') }}" class="flex items-center space-x-2 group">
                        <x-application-logo class="h-8 w-8 transition-transform duration-200 group-hover:scale-110" />
                        <span class="text-xl font-bold text-gray-900 dark:text-white hidden sm:block">FEN</span>
                    </a>
                </div>
                
                <!-- Búsqueda global (solo en desktop) -->
                @if(Auth::check())
                    <div class="hidden lg:block flex-1 max-w-md">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <img src="{{ asset('icons/filtro.svg') }}" alt="Buscar" class="h-4 w-4 text-gray-400">
                            </div>
                            <input 
                                x-model="searchQuery"
                                @focus="searchOpen = true"
                                @blur="setTimeout(() => searchOpen = false, 200)"
                                type="text" 
                                placeholder="Buscar en el sistema..."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition-all duration-200">
                            
                            <!-- Resultados de búsqueda -->
                            <div x-show="searchOpen && searchQuery.length > 2" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 max-h-64 overflow-y-auto">
                                <!-- Resultados de búsqueda aquí -->
                                <div class="p-4 text-sm text-gray-500 dark:text-gray-400">
                                    Búsqueda: "{{ $searchQuery ?? '' }}"
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Navegación principal -->
            <div class="hidden lg:flex lg:items-center lg:space-x-1">
                @if(Auth::check())
                    <!-- Menú principal agrupado por categorías -->
                    <x-hci-nav-group title="Principal" icon="home">
                        <x-hci-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
                            Inicio
                        </x-hci-nav-link>
                        <x-hci-nav-link :href="route('calendario')" :active="request()->routeIs('calendario')" icon="clock">
                            Calendario
                        </x-hci-nav-link>
                    </x-hci-nav-group>
                    
                    <x-hci-nav-group title="Gestión" icon="carpeta">
                        @if(tieneRol(['administrador', 'asistente_programa']))
                            <x-hci-nav-link :href="route('rooms.index')" :active="request()->routeIs('rooms.index')" icon="class">
                                Salas
                            </x-hci-nav-link>
                        @endif
                        
                        @if(tieneRol(['administrador', 'director_programa', 'asistente_programa', 'asistente_postgrado']))
                            <x-hci-nav-link :href="route('clases.index')" :active="request()->routeIs('clases.index')" icon="class">
                                Clases
                            </x-hci-nav-link>
                        @endif
                        
                        @if(tieneRol(['administrador', 'director_programa', 'asistente_programa']))
                            <x-hci-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.index')" icon="carpeta">
                                Cursos
                            </x-hci-nav-link>
                        @endif
                    </x-hci-nav-group>
                    
                    <x-hci-nav-group title="Soporte" icon="chat">
                        @if(tieneRol(['administrador', 'director_programa', 'asistente_programa', 'técnico', 'auxiliar', 'asistente_postgrado']))
                            <x-hci-nav-link :href="route('incidencias.index')" :active="request()->routeIs('incidencias.index')" icon="chat" badge="3">
                                Incidencias
                            </x-hci-nav-link>
                        @endif
                        
                        @if(tieneRol(['administrador', 'director_programa', 'asistente_programa', 'asistente_postgrado']))
                            <x-hci-nav-link :href="route('informes.index')" :active="request()->routeIs('informes.index')" icon="ficha">
                                Archivos
                            </x-hci-nav-link>
                        @endif
                        
                        @if(tieneRol(['administrador', 'director_programa', 'asistente_programa', 'asistente_postgrado']))
                            <x-hci-nav-link :href="route('emergencies.index')" :active="request()->routeIs('emergencies.index')" icon="chat">
                                Emergencias
                            </x-hci-nav-link>
                        @endif
                    </x-hci-nav-group>
                    
                    @if(tieneRol('administrador'))
                        <x-hci-nav-group title="Administración" icon="edit">
                            <x-hci-nav-link :href="route('periods.index')" :active="request()->routeIs('periods.index')" icon="clock">
                                Períodos
                            </x-hci-nav-link>
                            <x-hci-nav-link :href="route('staff.index')" :active="request()->routeIs('staff.index')" icon="edit">
                                Equipo
                            </x-hci-nav-link>
                            <x-hci-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.index')" icon="edit">
                                Usuarios
                            </x-hci-nav-link>
                        </x-hci-nav-group>
                    @endif
                    
                    @if(tieneRol('asistente_postgrado'))
                        <x-hci-nav-group title="Postgrado" icon="ficha">
                            <x-hci-nav-link :href="route('bitacoras.index')" :active="request()->routeIs('bitacoras.index')" icon="ficha">
                                Bitácoras
                            </x-hci-nav-link>
                        </x-hci-nav-group>
                    @endif
                @else
                    <!-- Menú público -->
                    <x-hci-nav-group title="Navegación" icon="home">
                        <x-hci-nav-link :href="route('public.dashboard.index')" :active="request()->routeIs('public.dashboard.index')" icon="home">
                            Inicio
                        </x-hci-nav-link>
                        <x-hci-nav-link :href="route('public.calendario.index')" :active="request()->routeIs('public.calendario.index')" icon="clock">
                            Calendario
                        </x-hci-nav-link>
                        <x-hci-nav-link :href="route('public.Equipo-FEN.index')" :active="request()->routeIs('public.Equipo-FEN.index')" icon="edit">
                            Equipo
                        </x-hci-nav-link>
                        <x-hci-nav-link :href="route('public.rooms.index')" :active="request()->routeIs('public.rooms.index')" icon="class">
                            Salas
                        </x-hci-nav-link>
                        <x-hci-nav-link :href="route('public.courses.index')" :active="request()->routeIs('public.courses.index')" icon="carpeta">
                            Cursos
                        </x-hci-nav-link>
                        <x-hci-nav-link :href="route('public.informes.index')" :active="request()->routeIs('public.informes.index')" icon="ficha">
                            Archivos
                        </x-hci-nav-link>
                    </x-hci-nav-group>
                @endif
            </div>
            
            <!-- Usuario y controles -->
            <div class="flex items-center space-x-4">
                @if(Auth::check())
                    <!-- Notificaciones -->
                    <button class="relative p-2 text-gray-500 dark:text-gray-400 hover:text-[#4d82bc] dark:hover:text-[#4d82bc] transition-colors duration-200 group">
                        <img src="{{ asset('icons/chat.svg') }}" alt="Notificaciones" class="w-5 h-5 group-hover:scale-110 transition-transform duration-200">
                        <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse">3</span>
                    </button>
                    
                    <!-- Menú de usuario -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 group">
                            <div class="w-8 h-8 bg-[#4d82bc] rounded-full flex items-center justify-center text-white text-sm font-medium group-hover:scale-105 transition-transform duration-200">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Dropdown del usuario -->
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                            <div class="py-1">
                                <a href="{{ route('profile.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <img src="{{ asset('icons/edit.svg') }}" alt="Perfil" class="w-4 h-4 mr-3">
                                    Perfil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <img src="{{ asset('icons/back.svg') }}" alt="Cerrar Sesión" class="w-4 h-4 mr-3">
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white text-sm font-medium rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2">
                        <img src="{{ asset('icons/check.svg') }}" alt="Iniciar Sesión" class="w-4 h-4 mr-2">
                        Iniciar Sesión
                    </a>
                @endif
                
                <!-- Botón hamburguesa para móvil -->
                <button @click="open = !open" class="lg:hidden p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:text-[#4d82bc] dark:hover:text-[#4d82bc] hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Menú móvil -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-full"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-full"
         class="lg:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        
        <div class="px-4 py-4 space-y-4">
            @if(Auth::check())
                <!-- Búsqueda móvil -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <img src="{{ asset('icons/filtro.svg') }}" alt="Buscar" class="h-4 w-4 text-gray-400">
                    </div>
                    <input type="text" placeholder="Buscar..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#4d82bc]">
                </div>
                
                <!-- Enlaces móviles agrupados -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Principal</h3>
                        <div class="space-y-1">
                            <x-hci-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
                                Inicio
                            </x-hci-nav-link>
                            <x-hci-nav-link :href="route('calendario')" :active="request()->routeIs('calendario')" icon="clock">
                                Calendario
                            </x-hci-nav-link>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Gestión</h3>
                        <div class="space-y-1">
                            @if(tieneRol(['administrador', 'asistente_programa']))
                                <x-hci-nav-link :href="route('rooms.index')" :active="request()->routeIs('rooms.index')" icon="class">
                                    Salas
                                </x-hci-nav-link>
                            @endif
                            @if(tieneRol(['administrador', 'director_programa', 'asistente_programa', 'asistente_postgrado']))
                                <x-hci-nav-link :href="route('clases.index')" :active="request()->routeIs('clases.index')" icon="class">
                                    Clases
                                </x-hci-nav-link>
                            @endif
                            @if(tieneRol(['administrador', 'director_programa', 'asistente_programa']))
                                <x-hci-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.index')" icon="carpeta">
                                    Cursos
                                </x-hci-nav-link>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Soporte</h3>
                        <div class="space-y-1">
                            @if(tieneRol(['administrador', 'director_programa', 'asistente_programa', 'técnico', 'auxiliar', 'asistente_postgrado']))
                                <x-hci-nav-link :href="route('incidencias.index')" :active="request()->routeIs('incidencias.index')" icon="chat" badge="3">
                                    Incidencias
                                </x-hci-nav-link>
                            @endif
                            @if(tieneRol(['administrador', 'director_programa', 'asistente_programa', 'asistente_postgrado']))
                                <x-hci-nav-link :href="route('informes.index')" :active="request()->routeIs('informes.index')" icon="ficha">
                                    Archivos
                                </x-hci-nav-link>
                            @endif
                            @if(tieneRol(['administrador', 'director_programa', 'asistente_programa', 'asistente_postgrado']))
                                <x-hci-nav-link :href="route('emergencies.index')" :active="request()->routeIs('emergencies.index')" icon="chat">
                                    Emergencias
                                </x-hci-nav-link>
                            @endif
                        </div>
                    </div>
                    
                    @if(tieneRol('administrador'))
                        <div>
                            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Administración</h3>
                            <div class="space-y-1">
                                <x-hci-nav-link :href="route('periods.index')" :active="request()->routeIs('periods.index')" icon="clock">
                                    Períodos
                                </x-hci-nav-link>
                                <x-hci-nav-link :href="route('staff.index')" :active="request()->routeIs('staff.index')" icon="edit">
                                    Equipo
                                </x-hci-nav-link>
                                <x-hci-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.index')" icon="edit">
                                    Usuarios
                                </x-hci-nav-link>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <!-- Menú público móvil -->
                <div class="space-y-1">
                    <x-hci-nav-link :href="route('public.dashboard.index')" :active="request()->routeIs('public.dashboard.index')" icon="home">
                        Inicio
                    </x-hci-nav-link>
                    <x-hci-nav-link :href="route('public.calendario.index')" :active="request()->routeIs('public.calendario.index')" icon="clock">
                        Calendario
                    </x-hci-nav-link>
                    <x-hci-nav-link :href="route('public.Equipo-FEN.index')" :active="request()->routeIs('public.Equipo-FEN.index')" icon="edit">
                        Equipo
                    </x-hci-nav-link>
                    <x-hci-nav-link :href="route('public.rooms.index')" :active="request()->routeIs('public.rooms.index')" icon="class">
                        Salas
                    </x-hci-nav-link>
                    <x-hci-nav-link :href="route('public.courses.index')" :active="request()->routeIs('public.courses.index')" icon="carpeta">
                        Cursos
                    </x-hci-nav-link>
                    <x-hci-nav-link :href="route('public.informes.index')" :active="request()->routeIs('public.informes.index')" icon="ficha">
                        Archivos
                    </x-hci-nav-link>
                </div>
            @endif
        </div>
    </div>
</nav>



