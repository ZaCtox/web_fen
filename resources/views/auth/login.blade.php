{{-- login.blade.php --}}
@section('title', 'Inicio de Sesión')
<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-fen-light dark:bg-fen-dark px-4 transition">

        {{-- Barra de accesibilidad --}}
        <div class="flex justify-end w-full max-w-md mb-3 space-x-3 text-gray-600 dark:text-gray-300">
            <button id="toggleTheme"
                class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition">🌙</button>
            <button id="fontDecrease"
                class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition text-lg font-bold">A-</button>
            <button id="fontIncrease"
                class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition text-lg font-bold">A+</button>
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
                    <label for="email" class="block text-sm font-medium text-[#005187] dark:text-[#c4dafa]">
                        Correo institucional
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md dark:bg-gray-800 dark:text-white transition">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-[#005187] dark:text-[#c4dafa]">
                        Contraseña
                    </label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md dark:bg-gray-800 dark:text-white pr-10 transition">
                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-500 dark:text-gray-300 hover:scale-110 transition-transform">
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
                        class="w-full bg-[#4d82bc] hover:bg-[#005187] text-white py-2 px-4 rounded-md transition text-base">
                        Iniciar Sesión
                    </button>
                    <a href="{{ route('public.dashboard.index') }}" id="btnBack"
                        class="block w-full text-center bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition text-base">
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
            });

            // Tema oscuro/claro
            const toggleTheme = document.getElementById("toggleTheme");
            if (localStorage.getItem("theme") === "dark") {
                document.documentElement.classList.add("dark");
                toggleTheme.textContent = "☀️";
            }
            toggleTheme.addEventListener("click", () => {
                document.documentElement.classList.toggle("dark");
                const isDark = document.documentElement.classList.contains("dark");
                toggleTheme.textContent = isDark ? "☀️" : "🌙";
                localStorage.setItem("theme", isDark ? "dark" : "light");
            });

            // --- Cambio de tamaño de letra en todo el login con transición ---
            const html = document.documentElement;
            html.style.transition = "font-size 0.3s ease";
            let fontSize = parseFloat(localStorage.getItem("fontSize")) || 100;
            html.style.fontSize = fontSize + "%";

            document.getElementById("fontIncrease").addEventListener("click", () => {
                if (fontSize < 150) {
                    fontSize += 5;
                    html.style.fontSize = fontSize + "%";
                    localStorage.setItem("fontSize", fontSize);
                }
            });
            document.getElementById("fontDecrease").addEventListener("click", () => {
                if (fontSize > 80) {
                    fontSize -= 5;
                    html.style.fontSize = fontSize + "%";
                    localStorage.setItem("fontSize", fontSize);
                }
            });
        });
    </script>
</x-guest-layout>