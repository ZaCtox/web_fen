/**
 * Keyboard Shortcuts - Ley de Pareto (80/20)
 * Los usuarios avanzados usan el 20% de funciones el 80% del tiempo
 * Atajos de teclado para acciones frecuentes
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // 1. Búsqueda Global (Ctrl+K o Cmd+K)
    // ==========================================
    document.addEventListener('keydown', function(e) {
        // Ctrl+K o Cmd+K - Búsqueda Global
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.querySelector('input[type="text"][placeholder*="Buscar"], input[x-model="search"]');
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
                showShortcutFeedback('Búsqueda activada');
            }
        }
        
        // Slash (/) - Enfocar búsqueda
        if (e.key === '/' && !isInputFocused()) {
            e.preventDefault();
            const searchInput = document.querySelector('input[type="text"][placeholder*="Buscar"], input[x-model="search"]');
            if (searchInput) {
                searchInput.focus();
                showShortcutFeedback('Búsqueda');
            }
        }
        
        // Esc - Cerrar modales, dropdowns, y blur inputs
        if (e.key === 'Escape') {
            // Cerrar Alpine dropdowns
            if (window.Alpine) {
                document.querySelectorAll('[x-data]').forEach(el => {
                    const component = el.__x;
                    if (component && component.$data.open !== undefined) {
                        component.$data.open = false;
                    }
                    if (component && component.$data.modalOpen !== undefined) {
                        component.$data.modalOpen = false;
                    }
                });
            }
            
            // Blur input activo
            if (document.activeElement && document.activeElement.tagName !== 'BODY') {
                document.activeElement.blur();
            }
            
            showShortcutFeedback('Cerrado');
        }
        
        // Ctrl+S - Guardar formulario
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            const submitButton = document.querySelector('button[type="submit"]:not([style*="display: none"]), button[onclick*="submitForm"]');
            if (submitButton) {
                submitButton.click();
                showShortcutFeedback('Guardando...');
            }
        }
        
        // Ctrl+Enter - Submit rápido en textareas
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            if (document.activeElement.tagName === 'TEXTAREA') {
                e.preventDefault();
                const form = document.activeElement.closest('form');
                if (form) {
                    form.submit();
                    showShortcutFeedback('Enviando...');
                }
            }
        }
        
        // Alt+N - Nuevo (ir a crear)
        if (e.altKey && e.key === 'n') {
            e.preventDefault();
            const newButton = document.querySelector('a[href*="/create"]');
            if (newButton) {
                newButton.click();
                showShortcutFeedback('Nuevo registro');
            }
        }
        
        // Alt+H - Home/Dashboard
        if (e.altKey && e.key === 'h') {
            e.preventDefault();
            window.location.href = '/dashboard';
            showShortcutFeedback('Ir a Inicio');
        }
    });
    
    // ==========================================
    // 2. Navegación con teclas de flecha en tablas
    // ==========================================
    let selectedRow = null;
    
    document.addEventListener('keydown', function(e) {
        const tbody = document.querySelector('tbody');
        if (!tbody || isInputFocused()) return;
        
        const rows = Array.from(tbody.querySelectorAll('tr'));
        if (rows.length === 0) return;
        
        // Arrow Down
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (!selectedRow) {
                selectedRow = rows[0];
            } else {
                const currentIndex = rows.indexOf(selectedRow);
                if (currentIndex < rows.length - 1) {
                    selectedRow = rows[currentIndex + 1];
                }
            }
            highlightRow(selectedRow);
            selectedRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        
        // Arrow Up
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (!selectedRow) {
                selectedRow = rows[rows.length - 1];
            } else {
                const currentIndex = rows.indexOf(selectedRow);
                if (currentIndex > 0) {
                    selectedRow = rows[currentIndex - 1];
                }
            }
            highlightRow(selectedRow);
            selectedRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        
        // Enter - Abrir registro seleccionado
        if (e.key === 'Enter' && selectedRow) {
            e.preventDefault();
            const link = selectedRow.querySelector('a[href*="/show"], a[href$="#ficha"]');
            if (link) {
                link.click();
            }
        }
    });
    
    function highlightRow(row) {
        // Remover highlight anterior
        document.querySelectorAll('tr.keyboard-selected').forEach(r => {
            r.classList.remove('keyboard-selected');
        });
        
        // Agregar highlight nuevo
        if (row) {
            row.classList.add('keyboard-selected');
        }
    }
    
    // ==========================================
    // 3. Helpers
    // ==========================================
    function isInputFocused() {
        const activeElement = document.activeElement;
        return activeElement && (
            activeElement.tagName === 'INPUT' ||
            activeElement.tagName === 'TEXTAREA' ||
            activeElement.tagName === 'SELECT' ||
            activeElement.isContentEditable
        );
    }
    
    function showShortcutFeedback(message) {
        // Crear toast minimalista
        const toast = document.createElement('div');
        toast.className = 'shortcut-toast';
        toast.textContent = message;
        document.body.appendChild(toast);
        
        // Mostrar con animación
        setTimeout(() => toast.classList.add('show'), 10);
        
        // Ocultar y eliminar
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 1500);
    }
    
    // ==========================================
    // 4. Mostrar ayuda de shortcuts (Shift+?)
    // ==========================================
    document.addEventListener('keydown', function(e) {
        if (e.shiftKey && e.key === '?') {
            e.preventDefault();
            showShortcutsHelp();
        }
    });
    
    function showShortcutsHelp() {
        const shortcuts = [
            { keys: 'Ctrl+K', desc: 'Buscar' },
            { keys: '/', desc: 'Enfocar búsqueda' },
            { keys: 'Esc', desc: 'Cerrar/Cancelar' },
            { keys: 'Ctrl+S', desc: 'Guardar' },
            { keys: 'Ctrl+Enter', desc: 'Enviar (en textarea)' },
            { keys: 'Alt+N', desc: 'Nuevo registro' },
            { keys: 'Alt+H', desc: 'Ir a Inicio' },
            { keys: '↑↓', desc: 'Navegar en tabla' },
            { keys: 'Enter', desc: 'Abrir seleccionado' },
            { keys: 'Shift+?', desc: 'Mostrar esta ayuda' }
        ];
        
        const content = shortcuts.map(s => 
            `<div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                <kbd class="px-2 py-1 text-xs font-semibold bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded">${s.keys}</kbd>
                <span class="text-sm text-gray-700 dark:text-gray-300">${s.desc}</span>
            </div>`
        ).join('');
        
        Swal.fire({
            title: '⌨️ Atajos de Teclado',
            html: `<div class="text-left">${content}</div>`,
            icon: 'info',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#4d82bc',
            width: '500px'
        });
    }
});
