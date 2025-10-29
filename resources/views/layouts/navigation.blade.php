<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 fixed top-0 left-0 right-0 z-50">
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
                <div class="hidden items-center gap-4 lg:-my-px lg:ms-10 lg:flex">
                    @php
                        $esVisor = false;
                        $canClases = tieneRol(['director_administrativo','director_programa','asistente_programa','decano','asistente_postgrado','docente']);
                        $canCourses = tieneRol(['director_administrativo','director_programa','asistente_programa','decano']);
                        $canPeriods = tieneRol(['director_administrativo','decano']);
                        $canRooms = tieneRol(['director_administrativo','asistente_programa','decano']);
                        $showAcademica = $canClases || $canCourses || $canPeriods || $canRooms;

                        $canIncidencias = tieneRol(['director_administrativo','director_programa','asistente_programa','técnico','auxiliar','decano','asistente_postgrado']);
                        $canInformes = tieneRol(['director_administrativo','director_programa','asistente_programa','decano','asistente_postgrado']);
                        $canEmergencias = tieneRol(['director_administrativo','director_programa','asistente_programa','decano','asistente_postgrado']);
                        $canBitacoras = tieneRol(['asistente_postgrado','decano']);
                        $showSoporte = $canIncidencias || $canInformes || $canEmergencias || $canBitacoras;
                    @endphp
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Inicio</x-nav-link>

                @if(tieneRol(['director_administrativo', 'director_programa', 'asistente_programa', 'decano', 'asistente_postgrado', 'docente']))
                    <x-nav-link :href="route('calendario')" :active="request()->routeIs('calendario')">Calendario</x-nav-link>
                @endif

                    <!-- Gestión Académica -->
                    @if($showAcademica)
                    <div class="relative" x-data="{open:false}" @mouseenter="open=true" @mouseleave="open=false">
                        <button @click="open=!open" 
                                class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium rounded-md 
                                       text-gray-700 dark:text-gray-300
                                       hover:bg-gray-100 dark:hover:bg-gray-700 
                                       focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-1
                                       transition-all duration-200">
                            <span>Gestión Académica</span>
                            <svg class="w-4 h-4 transition-transform duration-200" 
                                 :class="{'rotate-180': open}"
                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" 
                             @click.away="open=false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                             x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="absolute z-40 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="py-1">
                                @if($canClases)
                                    <a href="{{ route('clases.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Clases</a>
                                @endif
                                @if($canCourses)
                                    <a href="{{ route('courses.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Módulos</a>
                                @endif
                                @if($canPeriods)
                                    <a href="{{ route('periods.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Periodos</a>
                                @endif
                                @if($canRooms)
                                    <a href="{{ route('rooms.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Salas</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Soporte -->
                    @if($showSoporte)
                    <div class="relative" x-data="{open:false}" @mouseenter="open=true" @mouseleave="open=false">
                        <button @click="open=!open" 
                                class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium rounded-md 
                                       text-gray-700 dark:text-gray-300
                                       hover:bg-gray-100 dark:hover:bg-gray-700 
                                       focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-1
                                       transition-all duration-200">
                            <span>Soporte</span>
                            <svg class="w-4 h-4 transition-transform duration-200" 
                                 :class="{'rotate-180': open}"
                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" 
                             @click.away="open=false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                             x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="absolute z-40 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="py-1">
                                @if($canIncidencias)
                                    <a href="{{ route('incidencias.index') }}" class="flex items-center justify-between px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <span>Incidencias</span>
                                        @php $pendingCount = \App\Models\Incident::whereNotIn('estado', ['resuelta','no_resuelta'])->count(); @endphp
                                        @if($pendingCount>0)
                                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">{{ $pendingCount }}</span>
                                        @endif
                                    </a>
                                @endif
                                @if($canInformes)
                                    <a href="{{ route('informes.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Registros</a>
                                @endif
                                @if($canEmergencias)
                                    <a href="{{ route('emergencies.index') }}" class="flex items-center justify-between px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <span>Emergencias</span>
                                        @php 
                                            $activeEmergencies = \App\Models\Emergency::where('active', true)
                                                ->where(function($q) {
                                                    $q->whereNull('expires_at')
                                                      ->orWhere('expires_at', '>', now());
                                                })->count(); 
                                        @endphp
                                        @if($activeEmergencies > 0)
                                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">{{ $activeEmergencies }}</span>
                                        @endif
                                    </a>
                                @endif
                                @if($canBitacoras)
                                    <a href="{{ route('daily-reports.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Reportes Diarios</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Administración -->
                    @if(tieneRol(['director_administrativo', 'decano', 'asistente_postgrado']))
                    <div class="relative" x-data="{open:false}" @mouseenter="open=true" @mouseleave="open=false">
                        <button @click="open=!open" 
                                class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium rounded-md 
                                       text-gray-700 dark:text-gray-300
                                       hover:bg-gray-100 dark:hover:bg-gray-700 
                                       focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-1
                                       transition-all duration-200">
                            <span>Administración</span>
                            <svg class="w-4 h-4 transition-transform duration-200" 
                                 :class="{'rotate-180': open}"
                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" 
                             @click.away="open=false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                             x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="absolute z-40 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="py-1">
                                @if(tieneRol(['director_administrativo', 'decano', 'asistente_postgrado']))
                                    <a href="{{ route('novedades.index') }}" class="flex items-center justify-between px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <span>Novedades</span>
                                        @php 
                                            $urgentNews = \App\Models\Novedad::where('es_urgente', true)
                                                ->where(function($q) {
                                                    $q->whereNull('fecha_expiracion')
                                                      ->orWhere('fecha_expiracion', '>', now());
                                                })->count(); 
                                        @endphp
                                        @if($urgentNews > 0)
                                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">{{ $urgentNews }}</span>
                                        @endif
                                    </a>
                                @endif
                                @if(tieneRol(['director_administrativo','decano']) || tieneRol('director_programa'))
                                    <a href="{{ route('staff.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Nuestro Equipo</a>
                                @endif
                                @if(tieneRol(['director_administrativo','decano']))
                                    <a href="{{ route('usuarios.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Usuarios</a>
                                @endif
                                @if(tieneRol(['director_administrativo', 'decano', 'director_programa', 'asistente_postgrado']))
                                    <a href="{{ route('analytics.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Estadísticas</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            @else
                <!-- Links públicos -->
                <nav class="hidden lg:flex space-x-4">
                    <x-nav-link :href="route('public.dashboard.index')" :active="request()->routeIs('public.dashboard.index')">Inicio</x-nav-link>
                    <x-nav-link :href="route('public.calendario.index')" :active="request()->routeIs('public.calendario.index')">Calendario</x-nav-link>
                    <x-nav-link :href="route('public.Equipo-FEN.index')" :active="request()->routeIs('public.Equipo-FEN.index')">Nuestro Equipo</x-nav-link>
                    <x-nav-link :href="route('public.rooms.index')" :active="request()->routeIs('public.rooms.index')">Salas</x-nav-link>
                    <x-nav-link :href="route('public.courses.index')" :active="request()->routeIs('public.courses.index')">Módulos</x-nav-link>
                    <x-nav-link :href="route('public.informes.index')" :active="request()->routeIs('public.informes.index')">Registros</x-nav-link>
                </nav>
            @endif

            <!-- Usuario -->
            <div class="hidden lg:flex lg:items-center lg:ms-6">
                @if(Auth::check())
                    <!-- Notificaciones (tabla personalizada notifications: user_id, read, title, message, incident_id) -->
                    @php
                        $unreadNotifications = \DB::table('notifications')
                            ->select(['id','title','message','incident_id','created_at'])
                            ->where('user_id', auth()->id())
                            ->where('read', 0)
                            ->orderBy('created_at', 'desc')
                            ->get();
                    @endphp
                    <div class="relative mr-3" x-data="{ 
                        open: false,
                        notifications: @js($unreadNotifications->map(function($n) {
                            return [
                                'id' => $n->id,
                                'title' => $n->title ?? 'Notificación',
                                'message' => $n->message ?? '',
                                'incident_id' => $n->incident_id ?? null,
                                'created_at' => \Carbon\Carbon::parse($n->created_at)->diffForHumans(),
                            ];
                        }))
                    }">
                        <button @click="open = !open" 
                                class="relative p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 focus:outline-none transition">
                            <img src="{{ asset('icons/notification.svg') }}" alt="Notificaciones" class="w-6 h-6">
                            <span x-show="notifications.length > 0" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full" x-text="notifications.length"></span>
                        </button>

                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl z-50 border border-gray-200 dark:border-gray-700">
                            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Notificaciones</h3>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <template x-if="notifications.length === 0">
                                    <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                                        No tienes notificaciones nuevas
                                    </div>
                                </template>
                                <template x-for="notification in notifications" :key="notification.id">
                                    <a href="#" 
                                       @click.prevent="fetch(`/notifications/${notification.id}/read`, {method: 'PATCH', headers: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}}).then(()=>{ open=false; window.location.href = `/incidencias/${notification.incident_id}` })"
                                       class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700 transition">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="notification.title"></p>
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1" x-text="notification.message"></p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1" x-text="notification.created_at"></p>
                                            </div>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </div>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-3 px-3 py-2 border border-transparent rounded-lg
                                    text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 
                                    hover:bg-gray-50 dark:hover:bg-gray-700 
                                    focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2
                                    transition-all duration-200 ease-in-out
                                    hover:shadow-md">
                                @php
                                    $user = Auth::user();
                                    $nameParts = explode(' ', $user->name);
                                    $initials = '';
                                    if (count($nameParts) >= 2) {
                                        $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                                    } else {
                                        $initials = strtoupper(substr($user->name, 0, 2));
                                    }
                                    // Color basado en el ID del usuario para consistencia
                                    $colors = [
                                        'bg-blue-500', 'bg-purple-500', 'bg-pink-500', 'bg-indigo-500', 
                                        'bg-teal-500', 'bg-cyan-500', 'bg-emerald-500', 'bg-amber-500'
                                    ];
                                    $avatarColor = $colors[$user->id % count($colors)];
                                @endphp
                                <!-- Avatar con foto o iniciales -->
                                <img src="{{ $user->foto ?? $user->generateAvatarUrl() }}" 
                                     alt="{{ $user->name }}" 
                                     class="w-10 h-10 rounded-full object-cover shadow-inner">
                                <!-- Nombre y rol -->
                                <div class="flex flex-col items-start text-left">
                                    <span class="text-sm font-medium">{{ $user->name }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        @if($user->roles && $user->roles->isNotEmpty())
                                            {{ ucfirst(str_replace('_', ' ', $user->roles->first()->name)) }}
                                        @else
                                            Usuario
                                        @endif
                                    </span>
                                </div>
                                <!-- Icono flecha -->
                                <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                            </div>
                            
                            <x-dropdown-link :href="route('profile.index')" class="flex items-center gap-2">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Perfil
                            </x-dropdown-link>

                            {{-- Separador --}}
                            <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>

                            {{-- Controles de Personalización --}}
                            <div class="px-4 py-3">
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Personalización</p>
                                
                                {{-- Modo Oscuro --}}
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                        </svg>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Modo Oscuro</span>
                                    </div>
                                    <button id="toggle-theme-nav"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-200 dark:bg-[#4d82bc] transition-colors focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2">
                                        <span id="theme-toggle-dot" class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform translate-x-1 dark:translate-x-6"></span>
                                    </button>
                                </div>

                                {{-- Tamaño de Fuente --}}
                                <div class="mb-2">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                                        </svg>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Tamaño de Fuente</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button id="decrease-font-nav"
                                            class="flex-1 text-sm px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition font-semibold">
                                            A-
                                        </button>
                                        <button id="increase-font-nav"
                                            class="flex-1 text-sm px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition font-semibold">
                                            A+
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Separador --}}
                            <div class="border-t border-gray-200 dark:border-gray-700"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="flex items-center gap-2 text-red-600 dark:text-red-400">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Cerrar Sesión
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <!-- Controles de accesibilidad para visitantes públicos -->
                    <div class="flex items-center gap-3 mr-3 pr-3 border-r border-gray-300 dark:border-gray-600">
                        <!-- Aumentar fuente -->
                        <button id="increase-font-nav" 
                                class="p-2 text-gray-600 dark:text-gray-400 hover:text-[#4d82bc] dark:hover:text-[#4d82bc] hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200"
                                title="Aumentar tamaño de fuente">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <text x="2" y="18" font-size="14" font-weight="bold" fill="currentColor">A+</text>
                            </svg>
                        </button>
                        
                        <!-- Disminuir fuente -->
                        <button id="decrease-font-nav" 
                                class="p-2 text-gray-600 dark:text-gray-400 hover:text-[#4d82bc] dark:hover:text-[#4d82bc] hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200"
                                title="Disminuir tamaño de fuente">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <text x="3" y="18" font-size="14" font-weight="bold" fill="currentColor">A-</text>
                            </svg>
                        </button>
                        
                        <!-- Toggle tema oscuro -->
                        <button id="toggle-theme-nav" 
                                class="p-2 text-gray-600 dark:text-gray-400 hover:text-[#4d82bc] dark:hover:text-[#4d82bc] hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200"
                                title="Cambiar tema">
                            <svg class="w-5 h-5 hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                                <!-- Sol (modo claro) -->
                                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"/>
                            </svg>
                            <svg class="w-5 h-5 dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                                <!-- Luna (modo oscuro) -->
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                            </svg>
                        </button>
                    </div>
                    
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 text-sm bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
                        title="Acceder al sistema">
                        Iniciar Sesión
                    </a>
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
                @php
                    $esVisor = false;
                    $canClases = tieneRol(['director_administrativo','director_programa','asistente_programa','decano','asistente_postgrado','docente']);
                    $canCourses = tieneRol(['director_administrativo','director_programa','asistente_programa','decano']);
                    $canPeriods = tieneRol(['director_administrativo','decano']);
                    $canRooms = tieneRol(['director_administrativo','asistente_programa','decano']);
                    $showAcademica = $canClases || $canCourses || $canPeriods || $canRooms;

                    $canIncidencias = tieneRol(['director_administrativo','director_programa','asistente_programa','técnico','auxiliar','decano','asistente_postgrado']);
                    $canInformes = tieneRol(['director_administrativo','director_programa','asistente_programa','decano','asistente_postgrado']);
                    $canEmergencias = tieneRol(['director_administrativo','director_programa','asistente_programa','decano','asistente_postgrado']);
                    $canBitacoras = tieneRol(['asistente_postgrado','decano']);
                    $showSoporte = $canIncidencias || $canInformes || $canEmergencias || $canBitacoras;
                @endphp

                <!-- Enlaces principales -->
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Inicio</x-responsive-nav-link>
                
                @if(tieneRol(['director_administrativo', 'director_programa', 'asistente_programa', 'decano', 'asistente_postgrado', 'docente']))
                    <x-responsive-nav-link :href="route('calendario')" :active="request()->routeIs('calendario')">Calendario</x-responsive-nav-link>
                @endif

                <!-- Gestión Académica -->
                @if($showAcademica)
                <div class="border-t border-gray-200 dark:border-gray-600 pt-2 mt-2" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-3 text-sm font-medium rounded-lg
                                   text-gray-700 dark:text-gray-300
                                   hover:bg-blue-50 dark:hover:bg-blue-900/20 
                                   hover:text-blue-700 dark:hover:text-blue-300
                                   transition-all duration-300 ease-in-out
                                   {{ request()->routeIs(['clases.*', 'courses.*', 'periods.*', 'rooms.*']) ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : '' }}">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span>Gestión Académica</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-300 ease-in-out" 
                             :class="{'rotate-180': open}"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="ml-4 mt-2 space-y-1">
                        @if($canClases)
                            <x-responsive-nav-link :href="route('clases.index')" :active="request()->routeIs('clases.*')" class="flex items-center gap-2">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                Clases
                            </x-responsive-nav-link>
                        @endif
                        @if($canCourses)
                            <x-responsive-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.*')" class="flex items-center gap-2">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Módulos
                            </x-responsive-nav-link>
                        @endif
                        @if($canPeriods)
                            <x-responsive-nav-link :href="route('periods.index')" :active="request()->routeIs('periods.*')" class="flex items-center gap-2">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Periodos
                            </x-responsive-nav-link>
                        @endif
                        @if($canRooms)
                            <x-responsive-nav-link :href="route('rooms.index')" :active="request()->routeIs('rooms.*')" class="flex items-center gap-2">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Salas
                            </x-responsive-nav-link>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Soporte -->
                @if($showSoporte)
                <div class="border-t border-gray-200 dark:border-gray-600 pt-2 mt-2" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-3 text-sm font-medium rounded-lg
                                   text-gray-700 dark:text-gray-300
                                   hover:bg-orange-50 dark:hover:bg-orange-900/20 
                                   hover:text-orange-700 dark:hover:text-orange-300
                                   transition-all duration-300 ease-in-out
                                   {{ request()->routeIs(['incidencias.*', 'informes.*', 'emergencies.*', 'daily-reports.*']) ? 'bg-orange-50 dark:bg-orange-900/20 text-orange-700 dark:text-orange-300' : '' }}">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z" />
                            </svg>
                            <span>Soporte</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-300 ease-in-out" 
                             :class="{'rotate-180': open}"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="ml-4 mt-2 space-y-1">
                        @if($canIncidencias)
                            <div class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-all duration-200">
                                <x-responsive-nav-link :href="route('incidencias.index')" :active="request()->routeIs('incidencias.*')" class="flex items-center gap-2">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    Incidencias
                                </x-responsive-nav-link>
                                @php $pendingCount = \App\Models\Incident::whereNotIn('estado', ['resuelta','no_resuelta'])->count(); @endphp
                                @if($pendingCount > 0)
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold text-white bg-red-600 rounded-full animate-pulse">{{ $pendingCount }}</span>
                                @endif
                            </div>
                        @endif
                        @if($canInformes)
                            <x-responsive-nav-link :href="route('informes.index')" :active="request()->routeIs('informes.*')" class="flex items-center gap-2">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Registros
                            </x-responsive-nav-link>
                        @endif
                        @if($canEmergencias)
                            <x-responsive-nav-link :href="route('emergencies.index')" :active="request()->routeIs('emergencies.*')" class="flex items-center gap-2">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                Emergencias
                            </x-responsive-nav-link>
                        @endif
                        @if($canBitacoras)
                            <x-responsive-nav-link :href="route('daily-reports.index')" :active="request()->routeIs('daily-reports.*')" class="flex items-center gap-2">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Reportes Diarios
                            </x-responsive-nav-link>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Administración -->
                @if(tieneRol(['director_administrativo','decano','asistente_postgrado']))
                <div class="border-t border-gray-200 dark:border-gray-600 pt-2 mt-2" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-3 py-3 text-sm font-medium rounded-lg
                                   text-gray-700 dark:text-gray-300
                                   hover:bg-green-50 dark:hover:bg-green-900/20 
                                   hover:text-green-700 dark:hover:text-green-300
                                   transition-all duration-300 ease-in-out
                                   {{ request()->routeIs(['staff.*', 'usuarios.*', 'novedades.*', 'analytics.*']) ? 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300' : '' }}">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Administración</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-300 ease-in-out" 
                             :class="{'rotate-180': open}"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="ml-4 mt-2 space-y-1">
                        @if(tieneRol(['director_administrativo','decano']) || tieneRol('director_programa'))
                            <x-responsive-nav-link :href="route('staff.index')" :active="request()->routeIs('staff.*')" class="flex items-center gap-2">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Nuestro Equipo
                            </x-responsive-nav-link>
                        @endif
                        @if(tieneRol(['director_administrativo','decano']))
                            <x-responsive-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')" class="flex items-center gap-2">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                                Usuarios
                            </x-responsive-nav-link>
                        @endif
                        @if(tieneRol(['director_administrativo','decano','asistente_postgrado']))
                            <div class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200">
                                <x-responsive-nav-link :href="route('novedades.index')" :active="request()->routeIs('novedades.*')" class="flex items-center gap-2">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V9.75a9 9 0 00-9 9 9 9 0 009 9v3.882M13 5.882V9.75a9 9 0 019 9 9 9 0 00-9 9v3.882" />
                                    </svg>
                                    Novedades
                                </x-responsive-nav-link>
                                @php 
                                    $urgentNews = \App\Models\Novedad::where('es_urgente', true)
                                        ->where(function($q) {
                                            $q->whereNull('fecha_expiracion')
                                              ->orWhere('fecha_expiracion', '>', now());
                                        })->count(); 
                                @endphp
                                @if($urgentNews > 0)
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold text-white bg-red-600 rounded-full animate-pulse">{{ $urgentNews }}</span>
                                @endif
                            </div>
                        @endif
                        @if(tieneRol(['director_administrativo','decano','director_programa','asistente_postgrado']))
                            <x-responsive-nav-link :href="route('analytics.index')" :active="request()->routeIs('analytics.*')" class="flex items-center gap-2">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Estadísticas
                            </x-responsive-nav-link>
                        @endif
                    </div>
                </div>
                @endif
            @else
                <!-- Controles de accesibilidad para visitantes móvil -->
                <div class="px-3 py-4 border-b border-gray-200 dark:border-gray-600 mb-2">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Personalización</p>
                    
                    <!-- Modo Oscuro -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Modo Oscuro</span>
                        </div>
                        <button onclick="document.getElementById('toggle-theme-nav').click()"
                            class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-200 dark:bg-[#4d82bc] transition-colors focus:outline-none">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform translate-x-1 dark:translate-x-6"></span>
                        </button>
                    </div>

                    <!-- Tamaño de Fuente -->
                    <div class="mb-2">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Tamaño de Fuente</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="document.getElementById('decrease-font-nav').click()"
                                class="flex-1 text-sm px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition font-semibold">
                                A-
                            </button>
                            <button onclick="document.getElementById('increase-font-nav').click()"
                                class="flex-1 text-sm px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition font-semibold">
                                A+
                            </button>
                        </div>
                    </div>
                </div>
                
                <x-responsive-nav-link :href="route('public.dashboard.index')" :active="request()->routeIs('public.dashboard.index')">Inicio</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('public.calendario.index')" :active="request()->routeIs('public.calendario.index')">Calendario</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('public.Equipo-FEN.index')" :active="request()->routeIs('public.Equipo-FEN.index')">Nuestro Equipo</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('public.rooms.index')" :active="request()->routeIs('public.rooms.index')">Salas</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('public.courses.index')" :active="request()->routeIs('public.courses.index')">Módulos</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('public.informes.index')" :active="request()->routeIs('public.informes.index')">Registros</x-responsive-nav-link>
                
                <a href="{{ route('login') }}" 
                   class="mt-4 inline-flex items-center justify-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white px-6 py-3 rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
                   title="Acceder al sistema">
                    Iniciar Sesión
                </a>
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



