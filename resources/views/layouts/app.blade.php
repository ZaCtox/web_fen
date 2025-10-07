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

    <!-- Vite: CSS + JS principal + alerts.js + HCI principles -->
    @vite(['resources/css/app.css', 'resources/css/hci-principles.css', 'resources/js/app.js', 'resources/js/alerts.js', 'resources/js/hci-principles.js'])

    @stack('head') {{-- Por si necesitas inyectar algo extra desde vistas hijas --}}
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        <!-- Header con controles de accesibilidad -->
        <header class="bg-white dark:bg-gray-800 shadow">
            @php $rol = Auth::check() ? Auth::user()->rol : null; @endphp

            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                @isset($header)
                    {{ $header }}
                @else
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            @yield('title', 'Sistema FEN')
                        </h1>
                    </div>
                @endisset
            </div>
        </header>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <script>
        // === Control de Tema (Dark/Light Mode) ===
        const html = document.documentElement;
        const toggleBtnNav = document.getElementById('toggle-theme-nav');
        const toggleDot = document.getElementById('theme-toggle-dot');

        function applyTheme(theme) {
            if (theme === 'dark') {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
        }

        // Cargar tema guardado
        const storedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const currentTheme = storedTheme || (prefersDark ? 'dark' : 'light');
        applyTheme(currentTheme);

        // Toggle al hacer clic (navbar)
        if (toggleBtnNav) {
            toggleBtnNav.addEventListener('click', () => {
                const newTheme = html.classList.contains('dark') ? 'light' : 'dark';
                applyTheme(newTheme);
                localStorage.setItem('theme', newTheme);
            });
        }

        // === Control de tamaño de fuente ===
        const root = document.querySelector("html");
        const incBtnNav = document.getElementById("increase-font-nav");
        const decBtnNav = document.getElementById("decrease-font-nav");

        // Valor base (1rem = 100%)
        let fontSize = parseFloat(localStorage.getItem("fontSize")) || 100;
        root.style.fontSize = fontSize + "%";

        function updateFontSize(newSize) {
            fontSize = Math.min(150, Math.max(80, newSize)); // rango entre 80% y 150%
            root.style.fontSize = fontSize + "%";
            localStorage.setItem("fontSize", fontSize);
        }

        if (incBtnNav) {
            incBtnNav.addEventListener("click", () => updateFontSize(fontSize + 10));
        }
        if (decBtnNav) {
            decBtnNav.addEventListener("click", () => updateFontSize(fontSize - 10));
        }
    </script>


    @yield('scripts')
    @stack('scripts')
    
    <!-- Sistema de Notificaciones Global -->
    <x-hci-notification-system />
</body>

</html>