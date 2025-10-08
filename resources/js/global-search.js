/* ==========================================
   GLOBAL SEARCH - HCI Principles
   - Instant access (Ctrl+K)
   - Keyboard-first navigation
   - Grouped results
   ========================================== */

class GlobalSearch {
    constructor() {
        this.isOpen = false;
        this.query = '';
        this.results = [];
        this.selectedIndex = 0;
        this.recentSearches = [];
        this.searchDelay = null;
        this.storageKey = 'globalSearchRecent';
        
        this.init();
    }

    init() {
        this.loadRecentSearches();
        this.createModal();
        this.setupEventListeners();
    }

    /**
     * Crear modal de búsqueda
     */
    createModal() {
        const modal = document.createElement('div');
        modal.className = 'global-search-overlay';
        modal.innerHTML = `
            <div class="global-search-modal">
                <div class="global-search-input-wrapper">
                    <svg class="global-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input 
                        type="text" 
                        class="global-search-input" 
                        placeholder="Buscar en todo..."
                        autocomplete="off"
                        spellcheck="false"
                    />
                    <span class="global-search-shortcut">
                        <kbd>Ctrl</kbd>+<kbd>K</kbd>
                    </span>
                </div>
                
                <div class="global-search-hints">
                    <span class="global-search-hint">
                        <kbd>↑</kbd><kbd>↓</kbd> Navegar
                    </span>
                    <span class="global-search-hint">
                        <kbd>↵</kbd> Abrir
                    </span>
                    <span class="global-search-hint">
                        <kbd>Esc</kbd> Cerrar
                    </span>
                </div>
                
                <div class="global-search-results"></div>
            </div>
        `;
        
        document.body.appendChild(modal);
        this.modal = modal;
        this.input = modal.querySelector('.global-search-input');
        this.resultsContainer = modal.querySelector('.global-search-results');
        
        // Click en overlay para cerrar
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                this.close();
            }
        });
    }

    /**
     * Configurar event listeners
     */
    setupEventListeners() {
        // Ctrl+K para abrir
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                this.toggle();
            }
            
            // ESC para cerrar
            if (e.key === 'Escape' && this.isOpen) {
                this.close();
            }
        });

        // Input de búsqueda
        this.input.addEventListener('input', (e) => {
            this.query = e.target.value;
            this.handleSearch();
        });

        // Navegación por teclado en modal
        this.input.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                this.selectNext();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                this.selectPrevious();
            } else if (e.key === 'Enter') {
                e.preventDefault();
                this.selectCurrent();
            }
        });
    }

    /**
     * Toggle modal
     */
    toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }

    /**
     * Abrir modal
     */
    open() {
        this.isOpen = true;
        this.modal.classList.add('active');
        this.input.value = '';
        this.query = '';
        this.selectedIndex = 0;
        
        // Focus en input
        setTimeout(() => {
            this.input.focus();
        }, 100);
        
        // Mostrar búsquedas recientes si no hay query
        this.showRecentSearches();
        
        // Prevenir scroll del body
        document.body.style.overflow = 'hidden';
        
        // Emitir evento
        window.dispatchEvent(new CustomEvent('globalSearchOpened'));
    }

    /**
     * Cerrar modal
     */
    close() {
        this.isOpen = false;
        this.modal.classList.remove('active');
        
        // Restaurar scroll del body
        document.body.style.overflow = '';
        
        // Emitir evento
        window.dispatchEvent(new CustomEvent('globalSearchClosed'));
    }

    /**
     * Manejar búsqueda con debounce
     */
    handleSearch() {
        clearTimeout(this.searchDelay);
        
        if (!this.query.trim()) {
            this.showRecentSearches();
            return;
        }
        
        // Mostrar loading
        this.showLoading();
        
        // Debounce de 300ms
        this.searchDelay = setTimeout(() => {
            this.performSearch(this.query);
        }, 300);
    }

    /**
     * Realizar búsqueda
     */
    async performSearch(query) {
        try {
            // Búsqueda en ruta web con CSRF
            const response = await fetch(`/search?q=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                credentials: 'same-origin'
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            this.results = this.groupResults(data.results || []);
            this.renderResults();
            
            // Guardar en historial
            this.addToRecentSearches(query);
            
        } catch (error) {
            console.error('Error en búsqueda:', error);
            
            // Mostrar mensaje de error amigable
            this.showError('No se pudo realizar la búsqueda. Intenta nuevamente.');
            
            // Fallback: Búsqueda local (si existe cache)
            // this.results = this.searchLocally(query);
            // this.renderResults();
        }
    }

    /**
     * Búsqueda local (fallback o complemento)
     */
    searchLocally(query) {
        const lowerQuery = query.toLowerCase();
        const results = [];
        
        // Buscar en datos cacheados o DOM
        // Esto es un ejemplo, ajusta según tus necesidades
        
        // Salas
        const rooms = this.getCachedData('rooms') || [];
        const roomResults = rooms.filter(room => 
            room.name?.toLowerCase().includes(lowerQuery) ||
            room.location?.toLowerCase().includes(lowerQuery)
        ).map(room => ({
            type: 'room',
            id: room.id,
            title: room.name,
            description: room.location,
            url: `/rooms/${room.id}`
        }));
        
        // Cursos
        const courses = this.getCachedData('courses') || [];
        const courseResults = courses.filter(course =>
            course.nombre?.toLowerCase().includes(lowerQuery)
        ).map(course => ({
            type: 'course',
            id: course.id,
            title: course.nombre,
            description: course.magister?.nombre || '',
            url: `/courses/${course.id}`
        }));
        
        // Staff
        const staff = this.getCachedData('staff') || [];
        const staffResults = staff.filter(member =>
            member.nombre?.toLowerCase().includes(lowerQuery) ||
            member.cargo?.toLowerCase().includes(lowerQuery) ||
            member.email?.toLowerCase().includes(lowerQuery)
        ).map(member => ({
            type: 'staff',
            id: member.id,
            title: member.nombre,
            description: member.cargo,
            url: `/staff/${member.id}`
        }));
        
        results.push(...roomResults, ...courseResults, ...staffResults);
        
        return this.groupResults(results);
    }

    /**
     * Agrupar resultados por tipo
     */
    groupResults(results) {
        const grouped = {};
        
        results.forEach(result => {
            const type = result.type || 'other';
            if (!grouped[type]) {
                grouped[type] = [];
            }
            grouped[type].push(result);
        });
        
        return grouped;
    }

    /**
     * Renderizar resultados
     */
    renderResults() {
        if (Object.keys(this.results).length === 0) {
            this.showEmpty();
            return;
        }
        
        const typeConfig = {
            room: { icon: '🚪', label: 'Salas' },
            course: { icon: '📚', label: 'Cursos' },
            magister: { icon: '🎓', label: 'Magisters' },
            staff: { icon: '👥', label: 'Equipo' },
            incident: { icon: '🚨', label: 'Incidencias' },
            emergency: { icon: '⚡', label: 'Emergencias' },
            clase: { icon: '📅', label: 'Clases' },
            period: { icon: '📆', label: 'Períodos' },
            user: { icon: '👤', label: 'Usuarios' }
        };
        
        let html = '';
        let itemIndex = 0;
        
        Object.entries(this.results).forEach(([type, items]) => {
            const config = typeConfig[type] || { icon: '📋', label: type };
            
            html += `
                <div class="global-search-group">
                    <div class="global-search-group-title">
                        <span class="global-search-group-icon">${config.icon}</span>
                        <span>${config.label}</span>
                        <span class="global-search-group-count">${items.length}</span>
                    </div>
            `;
            
            items.forEach((item) => {
                const isSelected = itemIndex === this.selectedIndex;
                html += `
                    <a href="${item.url}" 
                       class="global-search-item ${isSelected ? 'selected' : ''}" 
                       data-index="${itemIndex}">
                        <div class="global-search-item-icon">${config.icon}</div>
                        <div class="global-search-item-content">
                            <div class="global-search-item-title">${this.highlightQuery(item.title)}</div>
                            ${item.description ? `<div class="global-search-item-description">${item.description}</div>` : ''}
                        </div>
                        ${item.badge ? `<span class="global-search-item-badge">${item.badge}</span>` : ''}
                    </a>
                `;
                itemIndex++;
            });
            
            html += `</div>`;
        });
        
        this.resultsContainer.innerHTML = html;
        this.setupResultsEventListeners();
    }

    /**
     * Resaltar query en resultados
     */
    highlightQuery(text) {
        if (!this.query) return text;
        
        const regex = new RegExp(`(${this.query})`, 'gi');
        return text.replace(regex, '<span class="search-highlight">$1</span>');
    }

    /**
     * Configurar event listeners de resultados
     */
    setupResultsEventListeners() {
        const items = this.resultsContainer.querySelectorAll('.global-search-item');
        
        items.forEach((item, index) => {
            item.addEventListener('mouseenter', () => {
                this.selectedIndex = index;
                this.updateSelection();
            });
            
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const url = item.getAttribute('href');
                this.close();
                window.location.href = url;
            });
        });
    }

    /**
     * Navegar a siguiente resultado
     */
    selectNext() {
        const totalItems = this.getTotalItems();
        if (totalItems === 0) return;
        
        this.selectedIndex = (this.selectedIndex + 1) % totalItems;
        this.updateSelection();
        this.scrollToSelected();
    }

    /**
     * Navegar a resultado anterior
     */
    selectPrevious() {
        const totalItems = this.getTotalItems();
        if (totalItems === 0) return;
        
        this.selectedIndex = (this.selectedIndex - 1 + totalItems) % totalItems;
        this.updateSelection();
        this.scrollToSelected();
    }

    /**
     * Seleccionar resultado actual
     */
    selectCurrent() {
        const items = this.resultsContainer.querySelectorAll('.global-search-item');
        const selected = items[this.selectedIndex];
        
        if (selected) {
            selected.click();
        }
    }

    /**
     * Actualizar selección visual
     */
    updateSelection() {
        const items = this.resultsContainer.querySelectorAll('.global-search-item');
        
        items.forEach((item, index) => {
            if (index === this.selectedIndex) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
        });
    }

    /**
     * Scroll al elemento seleccionado
     */
    scrollToSelected() {
        const items = this.resultsContainer.querySelectorAll('.global-search-item');
        const selected = items[this.selectedIndex];
        
        if (selected) {
            selected.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
        }
    }

    /**
     * Obtener total de items
     */
    getTotalItems() {
        return this.resultsContainer.querySelectorAll('.global-search-item').length;
    }

    /**
     * Mostrar loading
     */
    showLoading() {
        this.resultsContainer.innerHTML = `
            <div class="global-search-loading">
                <div class="global-search-spinner"></div>
            </div>
        `;
    }

    /**
     * Mostrar estado vacío
     */
    showEmpty() {
        this.resultsContainer.innerHTML = `
            <div class="global-search-empty">
                <svg class="global-search-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="global-search-empty-title">No se encontraron resultados</div>
                <div class="global-search-empty-description">Intenta con otros términos de búsqueda</div>
            </div>
        `;
    }

    /**
     * Mostrar error
     */
    showError(message) {
        this.resultsContainer.innerHTML = `
            <div class="global-search-empty">
                <svg class="global-search-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="global-search-empty-title">Error en la búsqueda</div>
                <div class="global-search-empty-description">${message}</div>
            </div>
        `;
    }

    /**
     * Mostrar búsquedas recientes
     */
    showRecentSearches() {
        if (this.recentSearches.length === 0) {
            this.resultsContainer.innerHTML = `
                <div class="global-search-empty">
                    <svg class="global-search-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <div class="global-search-empty-title">Busca en toda la plataforma</div>
                    <div class="global-search-empty-description">Salas, cursos, staff, incidencias y más...</div>
                </div>
            `;
            return;
        }
        
        let html = '<div class="global-search-recent">';
        html += '<div class="global-search-recent-title">🕐 Búsquedas Recientes</div>';
        
        this.recentSearches.forEach((search, index) => {
            html += `
                <div class="global-search-recent-item" data-query="${search}">
                    <svg class="global-search-recent-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="global-search-recent-text">${search}</span>
                    <button class="global-search-recent-remove" data-index="${index}">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
        });
        
        html += '</div>';
        
        this.resultsContainer.innerHTML = html;
        
        // Event listeners para búsquedas recientes
        const recentItems = this.resultsContainer.querySelectorAll('.global-search-recent-item');
        recentItems.forEach(item => {
            item.addEventListener('click', (e) => {
                if (!e.target.closest('.global-search-recent-remove')) {
                    const query = item.dataset.query;
                    this.input.value = query;
                    this.query = query;
                    this.handleSearch();
                }
            });
        });
        
        // Event listeners para eliminar
        const removeButtons = this.resultsContainer.querySelectorAll('.global-search-recent-remove');
        removeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.stopPropagation();
                const index = parseInt(button.dataset.index);
                this.removeRecentSearch(index);
            });
        });
    }

    /**
     * Agregar a búsquedas recientes
     */
    addToRecentSearches(query) {
        if (!query.trim()) return;
        
        // Remover si ya existe
        this.recentSearches = this.recentSearches.filter(s => s !== query);
        
        // Agregar al inicio
        this.recentSearches.unshift(query);
        
        // Limitar a 5
        this.recentSearches = this.recentSearches.slice(0, 5);
        
        // Guardar
        this.saveRecentSearches();
    }

    /**
     * Remover búsqueda reciente
     */
    removeRecentSearch(index) {
        this.recentSearches.splice(index, 1);
        this.saveRecentSearches();
        this.showRecentSearches();
    }

    /**
     * Guardar búsquedas recientes
     */
    saveRecentSearches() {
        try {
            localStorage.setItem(this.storageKey, JSON.stringify(this.recentSearches));
        } catch (e) {
            console.warn('No se pudieron guardar las búsquedas recientes:', e);
        }
    }

    /**
     * Cargar búsquedas recientes
     */
    loadRecentSearches() {
        try {
            const saved = localStorage.getItem(this.storageKey);
            if (saved) {
                this.recentSearches = JSON.parse(saved);
            }
        } catch (e) {
            console.warn('No se pudieron cargar las búsquedas recientes:', e);
            this.recentSearches = [];
        }
    }

    /**
     * Obtener datos cacheados (para búsqueda local)
     */
    getCachedData(type) {
        try {
            const cached = localStorage.getItem(`searchCache_${type}`);
            if (cached) {
                const data = JSON.parse(cached);
                // Verificar si el cache está expirado (24 horas)
                if (Date.now() - data.timestamp < 24 * 60 * 60 * 1000) {
                    return data.items;
                }
            }
        } catch (e) {
            console.warn('Error al cargar cache:', e);
        }
        return null;
    }

    /**
     * Cache datos para búsqueda local
     */
    cacheData(type, items) {
        try {
            localStorage.setItem(`searchCache_${type}`, JSON.stringify({
                items,
                timestamp: Date.now()
            }));
        } catch (e) {
            console.warn('Error al guardar cache:', e);
        }
    }
}

// Inicializar búsqueda global
document.addEventListener('DOMContentLoaded', () => {
    window.globalSearch = new GlobalSearch();
    
    // Cachear datos para búsqueda local (opcional)
    // Esto se puede ejecutar al cargar la página o bajo demanda
    // window.globalSearch.cacheData('rooms', roomsData);
    // window.globalSearch.cacheData('courses', coursesData);
    // etc...
});

// Exportar para uso en módulos
export default GlobalSearch;

