<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Metas para toasts (las usa alerts.js) --}}
    @if(session('success'))
        <meta name="session-success" content="{{ session('success') }}">
    @endif
    @if(session('error'))
        <meta name="session-error" content="{{ session('error') }}">
    @endif
    @if ($errors->any())
        <meta name="session-validate-error" content="{{ $errors->first() }}">
    @endif

    <title>@yield('title', config('app.name', 'Web FEN'))</title>

    <!-- Favicon + Fuentes -->
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/ico">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/alerts.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Evitar “flash” al cargar: aplica tema oscuro lo antes posible si corresponde -->
    <script>
        (function () {
            try {
                const stored = localStorage.getItem('theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const theme = stored || (prefersDark ? 'dark' : 'light');
                if (theme === 'dark') document.documentElement.classList.add('dark');
                // Aplica también tamaño de fuente si estaba guardado
                const fs = parseFloat(localStorage.getItem('fontSize')) || 100;
                document.documentElement.style.fontSize = fs + '%';
            } catch (e) { }
        })();
    </script>

    @stack('head')
</head>

<body class="font-sans text-gray-900 antialiased">
    <!-- Barra de accesibilidad fija (modo y tamaño) -->
    {{-- <div class="fixed top-3 right-3 z-50 flex gap-2">
        <button id="guest-toggle-theme"
            class="text-sm px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600 transition"
            type="button">
            <span id="guest-theme-icon">🌙</span>
        </button>
        <button id="guest-decrease-font"
            class="text-sm px-2 py-1 rounded bg-gray-200 dark:bg-gray-700 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600"
            type="button">A-</button>
        <button id="guest-increase-font"
            class="text-sm px-2 py-1 rounded bg-gray-200 dark:bg-gray-700 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600"
            type="button">A+</button>
    </div> --}}

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
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
    <script>
        // === Control de tamaño de fuente ===
        const root = document.querySelector("html");
        const incBtn = document.getElementById("increase-font");
        const decBtn = document.getElementById("decrease-font");

        // Valor base (1rem = 100%)
        let fontSize = parseFloat(localStorage.getItem("fontSize")) || 100;
        root.style.fontSize = fontSize + "%";

        function updateFontSize(newSize) {
            fontSize = Math.min(150, Math.max(80, newSize)); // rango entre 80% y 150%
            root.style.fontSize = fontSize + "%";
            localStorage.setItem("fontSize", fontSize);
        }

        if (incBtn) {
            incBtn.addEventListener("click", () => updateFontSize(fontSize + 10));
        }
        if (decBtn) {
            decBtn.addEventListener("click", () => updateFontSize(fontSize - 10));
        }
    </script>
    @stack('scripts')
</body>

</html>