<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Estilos / Scripts -->
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

    <!-- Estilos / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/alerts.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        {{-- <div>
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div> --}}

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>

</html>