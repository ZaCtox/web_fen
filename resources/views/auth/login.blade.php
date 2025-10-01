{{-- login.blade.php --}}
@section('title', 'Inicio de Sesión')
<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-fen-light dark:bg-fen-dark px-4">
        <div class="max-w-md w-full bg-white dark:bg-gray-900 p-8 rounded-lg shadow">
            <div class="text-center mb-6">
<<<<<<< Updated upstream
                <div class="flex items-center justify-end gap-1 mb-4">
                    <!-- Botón cambio de tema -->
                    <button id="toggle-theme"
                        class="text-sm px-2 py-1 rounded bg-gray-200 dark:bg-gray-700 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        <span id="theme-icon">🌙</span>
                    </button>

                    <!-- Controles accesibilidad -->
                    <button id="decrease-font"
                        class="text-sm px-2 py-1 rounded bg-gray-200 dark:bg-gray-700 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600">A-</button>
                    <button id="increase-font"
                        class="text-sm px-2 py-1 rounded bg-gray-200 dark:bg-gray-700 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600">A+</button>
                </div>
                <div class="flex justify-center items-center gap-x-4 mb-2">
                    <x-logo-fen />
                </div>
=======
<<<<<<< Updated upstream
                <img src="{{ asset('images/logo-utalca.svg') }}" alt="FEN UTalca" class="mx-auto w-32">
>>>>>>> Stashed changes
                <h2 class="text-2xl font-semibold text-fen-red dark:text-white">Inicio de Sesión</h2>
=======
                <div class="flex items-center justify-end gap-1 mb-4">
                    <!-- Botón cambio de tema -->
                    <button id="toggle-theme"
                        class="text-sm px-2 py-1 rounded bg-gray-200 dark:bg-gray-700 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        <span id="theme-icon">🌙</span>
                    </button>

                    <!-- Controles accesibilidad -->
                    <button id="decrease-font"
                        class="text-sm px-2 py-1 rounded bg-gray-200 dark:bg-gray-700 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600">A-</button>
                    <button id="increase-font"
                        class="text-sm px-2 py-1 rounded bg-gray-200 dark:bg-gray-700 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600">A+</button>
                </div>
                <div class="flex justify-center items-center gap-x-4 mb-2">
                    <x-logo-fen />
                </div>
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
=======
<<<<<<< Updated upstream
                    <a href="{{ route('password.request') }}" class="text-sm text-fen-red dark:text-fen-yellow hover:underline">¿Olvidaste tu contraseña?</a>
=======
>>>>>>> Stashed changes
                    <a href="{{ route('password.request') }}"
                        class="text-sm text-fen-red dark:text-fen-yellow hover:underline">¿Olvidaste tu contraseña?</a>
                </div>

                <div class="space-y-3">
                    <button type="submit"
<<<<<<< Updated upstream
                        class="w-full bg-[var(--color-utalca-secondary)] hover:bg-red-800 text-white py-2 px-4 rounded-md">
=======
                        class="w-full bg-[#4d82bc] hover:bg-[#005187] text-white py-2 px-4 rounded-md">
>>>>>>> Stashed changes
                        Iniciar Sesión
                    </button>
                    <a href="{{ route('public.dashboard.index') }}"
                        class="block w-full text-center bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                        Volver
                    </a>
<<<<<<< Updated upstream
=======
>>>>>>> Stashed changes
>>>>>>> Stashed changes
                </div>

            </form>

            <div class="text-center mt-6 text-sm text-gray-600 dark:text-gray-400">
                Universidad de Talca - Facultad de Economía y Negocios
            </div>
        </div>
    </div>    
</x-guest-layout>