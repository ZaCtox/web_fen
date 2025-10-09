{{-- login.blade.php --}}
@section('title', 'Inicio de Sesión')
<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-fen-light dark:bg-fen-dark px-4 transition">

        {{-- Barra de accesibilidad --}}
        <div class="flex justify-end w-full max-w-md mb-3 space-x-3 text-gray-600 dark:text-gray-300" x-data="{
            theme: localStorage.getItem('theme') || 'light',
            fontSize: parseFloat(localStorage.getItem('fontSize')) || 100,
            toggleTheme() {
                this.theme = this.theme === 'dark' ? 'light' : 'dark';
                document.documentElement.classList.toggle('dark', this.theme === 'dark');
                localStorage.setItem('theme', this.theme);
            },
            changeFontSize(delta) {
                this.fontSize = Math.min(150, Math.max(80, this.fontSize + delta));
                document.documentElement.style.fontSize = this.fontSize + '%';
                localStorage.setItem('fontSize', this.fontSize);
            }
        }" x-init="
            document.documentElement.classList.toggle('dark', theme === 'dark');
            document.documentElement.style.fontSize = fontSize + '%';
        ">
            <button @click="toggleTheme()"
                class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
                :title="theme === 'dark' ? 'Cambiar a tema claro' : 'Cambiar a tema oscuro'"
                :aria-label="theme === 'dark' ? 'Cambiar a tema claro' : 'Cambiar a tema oscuro'">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" x-show="theme === 'dark'">
                    <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"/>
                </svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" x-show="theme === 'light'">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                </svg>
            </button>
            <button @click="changeFontSize(-5)"
                class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition text-lg font-bold focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
                title="Disminuir tamaño de fuente"
                aria-label="Disminuir tamaño de fuente">A-</button>
            <button @click="changeFontSize(5)"
                class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition text-lg font-bold focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
                title="Aumentar tamaño de fuente"
                aria-label="Aumentar tamaño de fuente">A+</button>
        </div>



        {{-- Card login --}}

        <div class="max-w-md w-full bg-white dark:bg-gray-900 p-8 rounded-lg shadow transition text-base"
            id="loginCard">
            {{-- Dentro del card login --}}
            <div class="w-full flex justify-center mb-4">
                <x-logo-fen class="w-32 h-auto" />
            </div>
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-[#005187] dark:text-[#c4dafa] mb-2">
                        Correo institucional
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-2 border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-800 text-[#005187] dark:text-white rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-[#005187] dark:text-[#c4dafa] mb-2">
                        Contraseña
                    </label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required
                            class="w-full px-4 py-2 pr-10 border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-800 text-[#005187] dark:text-white rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition">
                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-500 dark:text-gray-300 hover:scale-110 transition-transform focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 rounded"
                            title="Mostrar/ocultar contraseña"
                            aria-label="Mostrar contraseña">
                            <img id="iconPassword" src="{{ asset('icons/nover.svg') }}" alt="Mostrar contraseña"
                                class="w-5 h-5 transition">
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                        <input type="checkbox" name="remember" class="mr-1"> Recuérdame
                    </label>
                    <a href="{{ route('password.request') }}"
                        class="text-sm text-fen-red dark:text-fen-yellow hover:underline">¿Olvidaste tu contraseña?</a>
                </div>

                <div class="space-y-3">
                    <button type="submit" id="btnLogin"
                        class="w-full inline-flex items-center justify-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white py-3 px-4 rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-base font-medium"
                        title="Acceder al sistema">
                        Iniciar Sesión
                    </button>
                    <a href="{{ route('public.dashboard.index') }}" id="btnBack"
                        class="block w-full text-center inline-flex items-center justify-center gap-2 bg-[#c4dafa] hover:bg-[#84b6f4] text-[#005187] px-4 py-3 rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-base font-medium"
                        title="Volver al inicio">
                        Volver
                    </a>
                </div>
            </form>

            <div class="text-center mt-6 text-sm text-gray-600 dark:text-gray-400">
                Universidad de Talca - Facultad de Economía y Negocios
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Mostrar/ocultar contraseña
            const passwordInput = document.getElementById("password");
            const toggleBtn = document.getElementById("togglePassword");
            const icon = document.getElementById("iconPassword");

            toggleBtn.addEventListener("click", () => {
                const type = passwordInput.type === "password" ? "text" : "password";
                passwordInput.type = type;
                icon.src = type === "text" ? "{{ asset('icons/ver.svg') }}" : "{{ asset('icons/nover.svg') }}";
                icon.alt = type === "text" ? "Ocultar contraseña" : "Mostrar contraseña";
                toggleBtn.setAttribute('aria-label', type === "text" ? "Ocultar contraseña" : "Mostrar contraseña");
            });
        });
    </script>
</x-guest-layout>