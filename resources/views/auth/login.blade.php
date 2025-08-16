<!-- resources/views/auth/login.blade.php -->
{{-- login.blade.php --}}
@section('title', 'Inicio de Sesión')
<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-fen-light dark:bg-fen-dark px-4">
        <div class="max-w-md w-full bg-white dark:bg-gray-900 p-8 rounded-lg shadow">
            <div class="text-center mb-6">
                <div class="flex justify-center items-center gap-x-4 mb-2">
                    <img src="{{ asset('images/logo-utalca.svg') }}" alt="UTalca" class="w-16 h-auto">
                    <img src="{{ asset('images/logo-fen.png') }}" alt="FEN" class="w-32 h-auto">
                </div>
                <h2 class="text-2xl font-semibold text-fen-red dark:text-white">Inicio de Sesión</h2>
            </div>


            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo
                        institucional</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md dark:bg-gray-800 dark:text-white">
                </div>

                <div>
                    <label for="password"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña</label>
                    <input id="password" type="password" name="password" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md dark:bg-gray-800 dark:text-white">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                        <input type="checkbox" name="remember" class="mr-1">
                        Recuérdame
                    </label>
                    <a href="{{ route('password.request') }}"
                        class="text-sm text-fen-red dark:text-fen-yellow hover:underline">¿Olvidaste tu contraseña?</a>
                </div>

                <div class="space-y-3">
                    <button type="submit"
                        class="w-full bg-[var(--color-utalca-secondary)] hover:bg-red-800 text-white py-2 px-4 rounded-md">
                        Iniciar Sesión
                    </button>
                    <a href="{{ route('public.dashboard.index') }}"
                        class="block w-full text-center bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                        Volver
                    </a>
                </div>

            </form>

            <div class="text-center mt-6 text-sm text-gray-600 dark:text-gray-400">
                Universidad de Talca - Facultad de Economía y Negocios
            </div>
        </div>
    </div>
</x-guest-layout>