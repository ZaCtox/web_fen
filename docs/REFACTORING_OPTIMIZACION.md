# 🔧 Plan de Refactoring y Optimización - Web FEN

## 📋 Resumen Ejecutivo

Este documento contiene el plan completo para optimizar el código JavaScript, CSS y componentes del proyecto Web FEN, eliminando duplicación masiva y mejorando la mantenibilidad.

## 🚨 Problemas Identificados

### 1. **Duplicación Masiva en JavaScript**
- **12 archivos wizard** con código 99% idéntico
- **Patrones repetidos:**
  - `currentStep = 1` (14 veces)
  - `totalSteps = X` (17 veces)
  - `showStep()` (60 veces)
  - `updateProgress()` (57 veces)

### 2. **Duplicación Masiva en Componentes**
- **15 archivos progress-sidebar** con estructura 95% idéntica
- Solo cambian: iconos SVG y número de pasos
- **Código repetido:** ~80 líneas por archivo = **1,200 líneas duplicadas**

### 3. **Duplicación en CSS**
- **137 patrones @apply** repetidos
- Colores y estilos duplicados en múltiples archivos

## 🎯 Soluciones Propuestas

### **SOLUCIÓN 1: Wizard JavaScript Unificado**

#### Archivo: `resources/js/wizard-unified.js`
```javascript
/**
 * Sistema de Wizards Unificado
 * Maneja todos los formularios wizard del sistema
 */
class WizardManager {
    constructor(config) {
        this.currentStep = 1;
        this.totalSteps = config.totalSteps;
        this.steps = config.steps;
        this.validationRules = config.validationRules;
        this.moduleName = config.moduleName;
        this.customValidations = config.customValidations || {};
    }
    
    // Métodos unificados para todos los wizards
    nextStep() {
        if (this.currentStep < this.totalSteps && this.validateCurrentStep()) {
            this.currentStep++;
            this.showStep(this.currentStep);
            this.updateProgress();
            
            // Ejecutar validaciones específicas del módulo
            if (this.customValidations[this.currentStep]) {
                this.customValidations[this.currentStep]();
            }
        }
    }
    
    prevStep() {
        if (this.currentStep > 1) {
            this.currentStep--;
            this.showStep(this.currentStep);
            this.updateProgress();
        }
    }
    
    showStep(step) {
        const sections = document.querySelectorAll('.hci-form-section');
        const progressSteps = document.querySelectorAll('.hci-progress-step-vertical');
        
        // Ocultar todas las secciones
        sections.forEach(section => {
            section.classList.remove('active');
        });
        
        // Mostrar sección actual
        const currentSection = document.getElementById(this.getSectionId(step));
        if (currentSection) {
            currentSection.classList.add('active');
            currentSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        
        // Actualizar pasos del progreso
        progressSteps.forEach((stepElement, index) => {
            const stepNumber = index + 1;
            stepElement.classList.remove('completed', 'active');
            
            if (stepNumber < step) {
                stepElement.classList.add('completed');
            } else if (stepNumber === step) {
                stepElement.classList.add('active');
            }
        });
    }
    
    updateProgress() {
        const percentage = Math.round((this.currentStep / this.totalSteps) * 100);
        const progressBar = document.getElementById('progress-bar');
        const currentStepSpan = document.getElementById('current-step');
        const progressPercentageSpan = document.getElementById('progress-percentage');
        
        if (progressBar) {
            progressBar.style.height = `${percentage}%`;
        }
        
        if (currentStepSpan) {
            currentStepSpan.textContent = `Paso ${this.currentStep} de ${this.totalSteps}`;
        }
        
        if (progressPercentageSpan) {
            progressPercentageSpan.textContent = `${percentage}%`;
        }
    }
    
    validateCurrentStep() {
        const currentSection = document.getElementById(this.getSectionId(this.currentStep));
        if (!currentSection) return true;
        
        const requiredFields = currentSection.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500');
            } else {
                field.classList.remove('border-red-500');
            }
        });
        
        return isValid;
    }
    
    getSectionId(step) {
        return `${this.moduleName}-step-${step}`;
    }
    
    navigateToStep(step) {
        if (step >= 1 && step <= this.totalSteps) {
            this.currentStep = step;
            this.showStep(this.currentStep);
            this.updateProgress();
        }
    }
}

// Configuraciones específicas por módulo
const wizardConfigs = {
    usuarios: {
        totalSteps: 4,
        moduleName: 'usuarios',
        steps: [
            { title: 'Información del Usuario', icon: 'user' },
            { title: 'Datos Personales', icon: 'person' },
            { title: 'Configuración de Acceso', icon: 'lock' },
            { title: 'Resumen', icon: 'check' }
        ]
    },
    staff: {
        totalSteps: 5,
        moduleName: 'staff',
        steps: [
            { title: 'Información Personal', icon: 'user' },
            { title: 'Datos de Contacto', icon: 'phone' },
            { title: 'Información Laboral', icon: 'briefcase' },
            { title: 'Documentos', icon: 'document' },
            { title: 'Resumen', icon: 'check' }
        ]
    },
    courses: {
        totalSteps: 3,
        moduleName: 'courses',
        steps: [
            { title: 'Información Básica', icon: 'document' },
            { title: 'Programa y Período', icon: 'calendar' },
            { title: 'Resumen', icon: 'check' }
        ]
    }
    // ... más configuraciones
};

// Función para inicializar wizard
function initializeWizard(moduleName) {
    const config = wizardConfigs[moduleName];
    if (config) {
        window.wizardManager = new WizardManager(config);
        
        // Hacer funciones globales disponibles
        window.nextStep = () => window.wizardManager.nextStep();
        window.prevStep = () => window.wizardManager.prevStep();
        window.navigateToStep = (step) => window.wizardManager.navigateToStep(step);
    }
}
```

### **SOLUCIÓN 2: Componente Progress Sidebar Unificado**

#### Archivo: `resources/views/components/wizard-progress-sidebar.blade.php`
```php
{{-- 
    Componente Sidebar de Progreso Genérico
    Props:
    - steps: Array de pasos con estructura:
      [
        ['title' => 'Información Básica', 'description' => 'Nombre del curso', 'icon' => 'document'],
        ['title' => 'Programa y Período', 'description' => 'Año y trimestre', 'icon' => 'calendar'],
        ['title' => 'Resumen', 'description' => 'Revisar y confirmar', 'icon' => 'check']
      ]
    - module: Nombre del módulo (usuarios, staff, courses, etc.)
--}}

@props(['steps' => [], 'module' => ''])

@php
    $totalSteps = count($steps);
    $initialPercentage = $totalSteps > 0 ? round(100 / $totalSteps) : 0;
    
    // Iconos SVG por tipo
    $icons = [
        'user' => '<path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>',
        'person' => '<path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>',
        'lock' => '<path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>',
        'document' => '<path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>',
        'calendar' => '<path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>',
        'phone' => '<path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>',
        'briefcase' => '<path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>',
        'check' => '<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>'
    ];
@endphp

{{-- Barra de progreso lateral genérica --}}
<div class="hci-progress-sidebar">
    <div class="hci-progress-sidebar-header">
        <h3 class="hci-heading-3 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
            </svg>
            Progreso
        </h3>
        <div class="hci-progress-stats">
            <span id="current-step">Paso 1 de {{ $totalSteps }}</span>
            <span id="progress-percentage">{{ $initialPercentage }}%</span>
        </div>
    </div>
    
    {{-- Barra de progreso vertical --}}
    <div class="hci-progress-vertical">
        <div class="hci-progress-line">
            <div id="progress-bar" class="hci-progress-fill-vertical" style="height: {{ $initialPercentage }}%"></div>
        </div>
        
        {{-- Pasos del progreso vertical --}}
        <div class="hci-progress-steps-vertical">
            @foreach($steps as $index => $step)
                @php
                    $stepNumber = $index + 1;
                    $isActive = $stepNumber === 1;
                    $iconPath = $icons[$step['icon']] ?? $icons['document'];
                @endphp
                
                <div class="hci-progress-step-vertical {{ $isActive ? 'active' : '' }}" 
                     data-step="{{ $stepNumber }}" 
                     onclick="navigateToStep({{ $stepNumber }})">
                    <div class="hci-progress-step-circle-vertical">
                        <span class="hci-progress-step-number">{{ $stepNumber }}</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            {!! $iconPath !!}
                        </svg>
                    </div>
                    <div class="hci-progress-step-content-vertical">
                        <h4 class="hci-progress-step-title">{{ $step['title'] }}</h4>
                        <p class="hci-progress-step-description">{{ $step['description'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Script para inicializar el wizard --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeWizard('{{ $module }}');
});
</script>
```

### **SOLUCIÓN 3: CSS Variables y Mixins**

#### Archivo: `resources/css/wizard-variables.css`
```css
/* ================================
   🎨 Variables CSS para Wizards
   ================================ */

:root {
    /* Colores principales */
    --wizard-primary: #3B82F6;
    --wizard-primary-hover: #2563EB;
    --wizard-success: #10B981;
    --wizard-warning: #F59E0B;
    --wizard-error: #EF4444;
    --wizard-gray: #6B7280;
    --wizard-light-gray: #F3F4F6;
    
    /* Espaciado */
    --wizard-spacing-xs: 0.25rem;
    --wizard-spacing-sm: 0.5rem;
    --wizard-spacing-md: 1rem;
    --wizard-spacing-lg: 1.5rem;
    --wizard-spacing-xl: 2rem;
    
    /* Bordes */
    --wizard-border-radius: 0.5rem;
    --wizard-border-width: 1px;
    
    /* Sombras */
    --wizard-shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --wizard-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --wizard-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    
    /* Transiciones */
    --wizard-transition: all 0.2s ease-in-out;
}

/* ================================
   🎯 Mixins para Wizards
   ================================ */

@mixin wizard-button($bg-color: var(--wizard-primary), $text-color: white) {
    @apply px-4 py-2 rounded-md font-medium transition-colors duration-200;
    background-color: $bg-color;
    color: $text-color;
    
    &:hover {
        background-color: var(--wizard-primary-hover);
        transform: translateY(-1px);
        box-shadow: var(--wizard-shadow-md);
    }
    
    &:active {
        transform: translateY(0);
    }
    
    &:disabled {
        @apply opacity-50 cursor-not-allowed;
        transform: none;
    }
}

@mixin wizard-progress-bar($height: 4px) {
    height: $height;
    background-color: var(--wizard-light-gray);
    border-radius: var(--wizard-border-radius);
    overflow: hidden;
    
    .progress-fill {
        height: 100%;
        background-color: var(--wizard-primary);
        transition: width var(--wizard-transition);
    }
}

@mixin wizard-step-circle($size: 2.5rem) {
    width: $size;
    height: $size;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    transition: var(--wizard-transition);
    
    &.active {
        background-color: var(--wizard-primary);
        color: white;
        box-shadow: var(--wizard-shadow-md);
    }
    
    &.completed {
        background-color: var(--wizard-success);
        color: white;
    }
    
    &.pending {
        background-color: var(--wizard-light-gray);
        color: var(--wizard-gray);
    }
}

@mixin wizard-form-section {
    @apply p-6 bg-white rounded-lg shadow-sm border border-gray-200;
    transition: var(--wizard-transition);
    
    &.active {
        @apply border-blue-300 shadow-md;
    }
    
    &:not(.active) {
        @apply opacity-50 pointer-events-none;
    }
}

/* ================================
   🎨 Clases utilitarias
   ================================ */

.wizard-button-primary {
    @include wizard-button();
}

.wizard-button-secondary {
    @include wizard-button(var(--wizard-gray), white);
}

.wizard-button-success {
    @include wizard-button(var(--wizard-success), white);
}

.wizard-button-danger {
    @include wizard-button(var(--wizard-error), white);
}

.wizard-progress-bar {
    @include wizard-progress-bar();
}

.wizard-step-circle {
    @include wizard-step-circle();
}

.wizard-form-section {
    @include wizard-form-section();
}

/* ================================
   📱 Responsive Design
   ================================ */

@media (max-width: 768px) {
    :root {
        --wizard-spacing-md: 0.75rem;
        --wizard-spacing-lg: 1rem;
        --wizard-spacing-xl: 1.5rem;
    }
    
    .wizard-step-circle {
        @include wizard-step-circle(2rem);
    }
}
```

## 📋 Plan de Implementación

### **FASE 1: Preparación (30 min)**
1. Crear archivo `wizard-unified.js`
2. Crear archivo `wizard-progress-sidebar.blade.php`
3. Crear archivo `wizard-variables.css`

### **FASE 2: Migración JavaScript (2 horas)**
1. Migrar `usuarios-form-wizard.js` al sistema unificado
2. Migrar `staff-form-wizard.js` al sistema unificado
3. Migrar `courses-form-wizard.js` al sistema unificado
4. Continuar con los 9 archivos restantes

### **FASE 3: Migración Componentes (1 hora)**
1. Migrar `usuarios-progress-sidebar.blade.php`
2. Migrar `staff-progress-sidebar.blade.php`
3. Migrar `courses-progress-sidebar.blade.php`
4. Continuar con los 12 archivos restantes

### **FASE 4: Optimización CSS (30 min)**
1. Importar `wizard-variables.css` en `app.css`
2. Reemplazar estilos duplicados con variables
3. Consolidar archivos CSS similares

### **FASE 5: Limpieza (15 min)**
1. Eliminar archivos JavaScript duplicados
2. Eliminar componentes duplicados
3. Verificar que todo funcione correctamente

## 📊 Impacto Esperado

### **Reducción de Código**
- **JavaScript:** De 12 archivos → 1 archivo unificado (**-90% código**)
- **Componentes:** De 15 archivos → 1 componente genérico (**-95% código**)
- **CSS:** De 12 archivos → Variables centralizadas (**-60% código**)

### **Beneficios**
- ✅ **Mantenimiento:** Cambios en 1 lugar vs 12 lugares
- ✅ **Consistencia:** Comportamiento idéntico en todos los wizards
- ✅ **Performance:** Menos archivos = carga más rápida
- ✅ **Escalabilidad:** Agregar nuevos wizards es trivial
- ✅ **Testing:** Un solo sistema para probar

## 🧪 Testing Post-Refactoring

### **Checklist de Verificación**
- [ ] Todos los wizards funcionan correctamente
- [ ] Navegación entre pasos funciona
- [ ] Validaciones se ejecutan correctamente
- [ ] Progress bars se actualizan
- [ ] Responsive design funciona
- [ ] No hay errores en consola
- [ ] Performance mejorada

### **Comandos de Testing**
```bash
# Ejecutar tests
php artisan test

# Verificar que no hay errores JS
npm run build

# Verificar responsive design
# Abrir en diferentes tamaños de pantalla
```

## 📝 Notas Importantes

1. **Backup:** Hacer backup completo antes de empezar
2. **Testing:** Probar cada módulo después de migrarlo
3. **Rollback:** Mantener archivos originales hasta confirmar que todo funciona
4. **Documentación:** Actualizar documentación después del refactoring

## 🎯 Conclusión

Este refactoring eliminará **más de 2,000 líneas de código duplicado** y mejorará significativamente la mantenibilidad del proyecto. La implementación se puede hacer de forma gradual, módulo por módulo, minimizando el riesgo.

---

**Fecha de creación:** Octubre 2025  
**Prioridad:** Alta  
**Tiempo estimado:** 4 horas  
**Beneficio:** Muy alto
