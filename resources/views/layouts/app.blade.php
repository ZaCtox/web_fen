<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Web FEN'))</title>

    <style>
        [x-cloak] {
            display: none !important
        }
    </style>


    <!-- Icono -->
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/ico">

    <!-- Fuente -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- SweetAlert2 (global) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Flashes globales (success / error) -->
    @if(session('success'))
        <meta name="session-success" content="{{ session('success') }}">
    @endif
    @if(session('error'))
        <meta name="session-error" content="{{ session('error') }}">
    @endif

    <!-- Vite: CSS + JS principal + alerts.js -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/alerts.js'])

    @stack('head') {{-- Por si necesitas inyectar algo extra desde vistas hijas --}}
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                @php $rol = Auth::check() ? Auth::user()->rol : null; @endphp

                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    {{ $header }}

                    <div class="flex flex-wrap items-center gap-2">
                        @if($rol === 'administrativo')
                            <a href="{{ route('register') }}"
                                class="text-sm px-3 py-2 rounded bg-indigo-600 hover:bg-indigo-700 text-white font-medium whitespace-nowrap">
                                👤➕ Registrar Usuario
                            </a>
                        @endif

                        <button id="toggle-theme"
                            class="text-sm px-3 py-2 rounded bg-gray-200 dark:bg-gray-700 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                            <span id="theme-icon">🌙</span>
                        </button>
                    </div>
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <script>
        const html = document.documentElement;
        const toggleBtn = document.getElementById('toggle-theme');
        const icon = document.getElementById('theme-icon');

        function applyTheme(theme) {
            if (theme === 'dark') {
                html.classList.add('dark');
                icon.textContent = '☀️';
            } else {
                html.classList.remove('dark');
                icon.textContent = '🌙';
            }
        }

        // Cargar tema guardado
        const storedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const currentTheme = storedTheme || (prefersDark ? 'dark' : 'light');
        applyTheme(currentTheme);

        // Toggle al hacer clic
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                const newTheme = html.classList.contains('dark') ? 'light' : 'dark';
                applyTheme(newTheme);
                localStorage.setItem('theme', newTheme);
            });
        }
    </script>

    @yield('scripts')
    @stack('scripts')
</body>

</html>