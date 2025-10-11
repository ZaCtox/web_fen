/**
 * Helper para actualizar el progreso visual del sidebar en los wizards
 * 
 * Actualiza las clases CSS de los pasos para mostrar:
 * - Pasos anteriores: verde con checkmark (completed)
 * - Paso actual: azul con número (active)
 * - Pasos futuros: gris con número (sin clase)
 * 
 * @param {number} currentStep - Número del paso actual (1-indexed)
 */
export function updateWizardProgress(currentStep) {
    const progressSteps = document.querySelectorAll('.hci-progress-step-vertical');
    
    progressSteps.forEach((stepElement, index) => {
        const stepNumber = index + 1;
        
        // Remover todas las clases de estado primero
        stepElement.classList.remove('completed', 'active');
        
        if (stepNumber < currentStep) {
            // Pasos anteriores: completados (verde con checkmark ✓)
            stepElement.classList.add('completed');
        } else if (stepNumber === currentStep) {
            // Paso actual: activo (azul con número)
            stepElement.classList.add('active');
        }
        // Pasos futuros: sin clase especial (gris con número)
    });
}

/**
 * Función alternativa para wizards sin módulos ES6
 * Usar directamente en archivos JS tradicionales
 */
window.updateWizardProgressSteps = function(currentStep) {
    const progressSteps = document.querySelectorAll('.hci-progress-step-vertical');
    
    progressSteps.forEach((stepElement, index) => {
        const stepNumber = index + 1;
        
        // Remover todas las clases de estado primero
        stepElement.classList.remove('completed', 'active');
        
        if (stepNumber < currentStep) {
            // Pasos anteriores: completados (verde con checkmark ✓)
            stepElement.classList.add('completed');
        } else if (stepNumber === currentStep) {
            // Paso actual: activo (azul con número)
            stepElement.classList.add('active');
        }
        // Pasos futuros: sin clase especial (gris con número)
    });
};

