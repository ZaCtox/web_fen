{{-- Componente de Botón Scroll to Top Reutilizable --}}
@props([
    'threshold' => 300, // Píxeles de scroll para que aparezca
    'position' => 'bottom-left' // 'bottom-right' o 'bottom-left'
])

@php
    $positionClasses = match($position) {
        'bottom-right' => 'bottom-6 right-6',
        'bottom-left' => 'bottom-6 left-6',
        default => 'bottom-6 left-6'
    };
@endphp

{{-- Botón flotante de Scroll to Top --}}
<button id="scrollToTopBtn"
        class="fixed {{ $positionClasses }} z-50 opacity-0 invisible w-12 h-12 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-full shadow-lg transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
        title="Volver arriba"
        aria-label="Volver arriba">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
    </svg>
</button>

<script>
(function() {
    function initScrollToTop() {
        const scrollBtn = document.getElementById('scrollToTopBtn');
        const threshold = {{ $threshold }};
        
        if (!scrollBtn) return;

        // Mostrar/ocultar botón según scroll
        window.addEventListener('scroll', () => {
            const scrollPos = window.pageYOffset;
            
            if (scrollPos > threshold) {
                scrollBtn.classList.remove('opacity-0', 'invisible');
                scrollBtn.classList.add('opacity-100', 'visible');
            } else {
                scrollBtn.classList.add('opacity-0', 'invisible');
                scrollBtn.classList.remove('opacity-100', 'visible');
            }
        });

        // Scroll suave al hacer click
        scrollBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // También funciona con Enter/Space cuando está enfocado
        scrollBtn.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });
    }
    
    // Ejecutar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initScrollToTop);
    } else {
        initScrollToTop();
    }
})();
</script>




