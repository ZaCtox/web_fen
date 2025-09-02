(function () {
    const html = document.documentElement;

    // --- Tema claro/oscuro ---
    const themeBtn = document.getElementById('guest-toggle-theme');
    const themeIcon = document.getElementById('guest-theme-icon');

    function applyTheme(theme) {
        if (theme === 'dark') {
            html.classList.add('dark');
            if (themeIcon) themeIcon.textContent = '☀️';
        } else {
            html.classList.remove('dark');
            if (themeIcon) themeIcon.textContent = '🌙';
        }
    }

    const storedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const currentTheme = storedTheme || (prefersDark ? 'dark' : 'light');
    applyTheme(currentTheme);

    themeBtn?.addEventListener('click', () => {
        const newTheme = html.classList.contains('dark') ? 'light' : 'dark';
        applyTheme(newTheme);
        localStorage.setItem('theme', newTheme);
    });

    // --- Tamaño de fuente ---
    const incBtn = document.getElementById('guest-increase-font');
    const decBtn = document.getElementById('guest-decrease-font');

    let fontSize = parseFloat(localStorage.getItem('fontSize')) || 100;
    html.style.fontSize = fontSize + '%';

    function updateFontSize(newSize) {
        fontSize = Math.min(150, Math.max(80, newSize)); // 80% - 150%
        html.style.fontSize = fontSize + '%';
        localStorage.setItem('fontSize', fontSize);
    }

    incBtn?.addEventListener('click', () => updateFontSize(fontSize + 10));
    decBtn?.addEventListener('click', () => updateFontSize(fontSize - 10));
})();