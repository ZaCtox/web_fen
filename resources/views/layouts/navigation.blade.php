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
                <div class="hidden items-center gap-4 lg:-my-px lg:ms-10 lg:flex">
                    @php
                        $canClases = tieneRol(['administrador','director_programa','asistente_programa','director_administrativo','asistente_postgrado']);
                        $canCourses = tieneRol(['administrador','director_programa','asistente_programa']);
                        $canPeriods = tieneRol('administrador');
                        $canRooms = tieneRol(['administrador','asistente_programa']);
                        $showAcademica = $canClases || $canCourses || $canPeriods || $canRooms;

                        $canIncidencias = tieneRol(['administrador','director_programa','asistente_programa','técnico','auxiliar','asistente_postgrado']);
                        $canInformes = tieneRol(['administrador','director_programa','asistente_programa','asistente_postgrado']);
                        $canEmergencias = tieneRol(['administrador','director_programa','asistente_programa','asistente_postgrado']);
                        $canBitacoras = tieneRol('asistente_postgrado');
                        $showSoporte = $canIncidencias || $canInformes || $canEmergencias || $canBitacoras;
                    @endphp
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Inicio</x-nav-link>

                    @if(tieneRol(['administrador', 'director_programa', 'asistente_programa', 'director_administrativo', 'asistente_postgrado']))
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
                                    <a href="{{ route('courses.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Cursos</a>
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
                                            <span class="von-restorff-badge von-restorff-badge-critical">{{ $pendingCount }}</span>
                                        @endif
                                    </a>
                                @endif
                                @if($canInformes)
                                    <a href="{{ route('informes.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Archivos</a>
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
                                            <span class="von-restorff-badge von-restorff-badge-critical von-restorff-glow">{{ $activeEmergencies }}</span>
                                        @endif
                                    </a>
                                @endif
                                @if($canBitacoras)
                                    <a href="{{ route('bitacoras.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Bitácoras</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Administración -->
                    @if(tieneRol('administrador'))
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
                                @if(tieneRol('administrador'))
                                    <a href="{{ route('staff.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Nuestro Equipo</a>
                                    <a href="{{ route('usuarios.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Usuarios</a>
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
                    <x-nav-link :href="route('public.courses.index')" :active="request()->routeIs('public.courses.index')">Cursos</x-nav-link>
                    <x-nav-link :href="route('public.informes.index')" :active="request()->routeIs('public.informes.index')">Archivos</x-nav-link>
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
                                <!-- Avatar con iniciales -->
                                <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $avatarColor }} text-white font-semibold text-sm shadow-inner">
                                    {{ $initials }}
                                </div>
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
                @php
                    $canClases = tieneRol(['administrador','director_programa','asistente_programa','director_administrativo','asistente_postgrado']);
                    $canCourses = tieneRol(['administrador','director_programa','asistente_programa']);
                    $canPeriods = tieneRol('administrador');
                    $canRooms = tieneRol(['administrador','asistente_programa']);
                    $showAcademica = $canClases || $canCourses || $canPeriods || $canRooms;

                    $canIncidencias = tieneRol(['administrador','director_programa','asistente_programa','técnico','auxiliar','asistente_postgrado']);
                    $canInformes = tieneRol(['administrador','director_programa','asistente_programa','asistente_postgrado']);
                    $canEmergencias = tieneRol(['administrador','director_programa','asistente_programa','asistente_postgrado']);
                    $canBitacoras = tieneRol('asistente_postgrado');
                    $showSoporte = $canIncidencias || $canInformes || $canEmergencias || $canBitacoras;
                @endphp

                <!-- Enlaces principales -->
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Inicio</x-responsive-nav-link>
                
                @if(tieneRol(['administrador', 'director_programa', 'asistente_programa', 'director_administrativo', 'asistente_postgrado']))
                    <x-responsive-nav-link :href="route('calendario')" :active="request()->routeIs('calendario')">Calendario</x-responsive-nav-link>
                @endif

                <!-- Gestión Académica -->
                @if($showAcademica)
                <div class="border-t border-gray-200 dark:border-gray-600 pt-2 mt-2">
                    <div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Gestión Académica</div>
                    @if($canClases)
                        <x-responsive-nav-link :href="route('clases.index')" :active="request()->routeIs('clases.index')">Clases</x-responsive-nav-link>
                    @endif
                    @if($canCourses)
                        <x-responsive-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.index')">Cursos</x-responsive-nav-link>
                    @endif
                    @if($canPeriods)
                        <x-responsive-nav-link :href="route('periods.index')" :active="request()->routeIs('periods.index')">Periodos</x-responsive-nav-link>
                    @endif
                    @if($canRooms)
                        <x-responsive-nav-link :href="route('rooms.index')" :active="request()->routeIs('rooms.index')">Salas</x-responsive-nav-link>
                    @endif
                </div>
                @endif

                <!-- Soporte -->
                @if($showSoporte)
                <div class="border-t border-gray-200 dark:border-gray-600 pt-2 mt-2">
                    <div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Soporte</div>
                    @if($canIncidencias)
                        <div class="flex items-center justify-between px-3 py-2">
                            <x-responsive-nav-link :href="route('incidencias.index')" :active="request()->routeIs('incidencias.index')">Incidencias</x-responsive-nav-link>
                            @php $pendingCount = \App\Models\Incident::whereNotIn('estado', ['resuelta','no_resuelta'])->count(); @endphp
                            @if($pendingCount > 0)
                                <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold text-white bg-red-600 rounded-full">{{ $pendingCount }}</span>
                            @endif
                        </div>
                    @endif
                    @if($canInformes)
                        <x-responsive-nav-link :href="route('informes.index')" :active="request()->routeIs('informes.index')">Archivos</x-responsive-nav-link>
                    @endif
                    @if($canEmergencias)
                        <x-responsive-nav-link :href="route('emergencies.index')" :active="request()->routeIs('emergencies.index')">Emergencias</x-responsive-nav-link>
                    @endif
                    @if($canBitacoras)
                        <x-responsive-nav-link :href="route('bitacoras.index')" :active="request()->routeIs('bitacoras.index')">Bitácoras</x-responsive-nav-link>
                    @endif
                </div>
                @endif

                <!-- Administración -->
                @if(tieneRol('administrador'))
                <div class="border-t border-gray-200 dark:border-gray-600 pt-2 mt-2">
                    <div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Administración</div>
                    <x-responsive-nav-link :href="route('staff.index')" :active="request()->routeIs('staff.index')">Nuestro Equipo</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.index')">Usuarios</x-responsive-nav-link>
                </div>
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
