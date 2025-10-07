// Wizard de Clases (4 pasos) reutilizando lógica existente
let currentStep = 1;
const totalSteps = 4;

document.addEventListener('DOMContentLoaded', () => {
    showStep(1);
    updateProgress(1);

    // Reusar lógica previa
    // Estas funciones están en resources/js/clases/form.js
    // Se cargan por Vite también, así que solo necesitamos que el DOM esté listo
    // y que el resumen se actualice en tiempo real.

    const inputs = document.querySelectorAll('.hci-input, .hci-select, .hci-textarea, select, input');
    inputs.forEach(el => el.addEventListener('input', updateSummary));
    document.addEventListener('change', updateSummary);
    updateSummary();
});

window.nextStep = function() { if (currentStep < totalSteps && validateCurrentStep()) { currentStep++; showStep(currentStep); updateProgress(currentStep);} }
window.prevStep = function() { if (currentStep > 1) { currentStep--; showStep(currentStep); updateProgress(currentStep);} }
window.navigateToStep = function(step) { if (step <= currentStep || validateCurrentStep()) { currentStep = step; showStep(step); updateProgress(step);} }
window.cancelForm = function() { 
    if (window.hasUnsavedChanges && window.hasUnsavedChanges()) {
        window.showUnsavedChangesModal(window.location.origin + '/clases');
    } else {
        window.location.href = window.location.origin + '/clases';
    }
}
window.submitForm = function() { if (validateCurrentStep()) { document.querySelector('.hci-form').submit(); } }

function showStep(step){
    document.querySelectorAll('.hci-form-section').forEach(s => s.classList.remove('active'));
    const sec = document.getElementById(getSectionId(step));
    if (sec) { sec.classList.add('active'); sec.scrollIntoView({behavior:'smooth', block:'start'}); }
    document.querySelectorAll('.hci-progress-step-vertical').forEach((p,i)=>{
        if(i+1<=step){ p.classList.add('completed','active'); } else { p.classList.remove('completed','active'); }
    });
}

function updateProgress(step){
    const pct = (step/totalSteps)*100;
    const bar = document.getElementById('progress-bar'); if(bar) bar.style.height = pct+'%';
    const txt = document.getElementById('current-step'); if(txt) txt.textContent = `Paso ${step} de ${totalSteps}`;
    const per = document.getElementById('progress-percentage'); if(per) per.textContent = Math.round(pct)+'%';
}

function getSectionId(step){ return ['programa','sala','horario','resumen'][step-1]; }

function validateCurrentStep(){
    const sec = document.getElementById(getSectionId(currentStep));
    const reqs = sec.querySelectorAll('input[required], select[required], textarea[required]');
    let ok = true; reqs.forEach(f=>{ if(!f.value.trim()){ ok=false; validateField(f);} else { clearFieldError(f); } });
    if(!ok) showStepError('Completa los campos requeridos.');
    return ok;
}

function showStepError(msg){
    let d = document.getElementById('step-error-message');
    if(!d){ d=document.createElement('div'); d.id='step-error-message'; d.className='hci-error-message'; document.querySelector('.hci-container').insertBefore(d, document.querySelector('.hci-wizard-layout')); }
    d.innerHTML = `<div class="hci-error-content"><span class="hci-error-icon">⚠️</span><span class="hci-error-text">${msg}</span></div>`;
    setTimeout(()=>d&&d.remove(), 4000);
}

function validateField(f){
    const ok = f.checkValidity();
    const wrap = f.closest('.hci-field');
    const err = wrap?.querySelector('.hci-field-error');
    if(!ok){ f.classList.add('border-red-500'); f.classList.remove('border-gray-300'); if(!err){ const e=document.createElement('div'); e.className='hci-field-error'; e.textContent=f.validationMessage||'Campo requerido'; wrap?.appendChild(e);} }
    else { clearFieldError(f); }
}
function clearFieldError(f){ f.classList.remove('border-red-500'); f.classList.add('border-gray-300'); const wrap=f.closest('.hci-field'); const err=wrap?.querySelector('.hci-field-error'); if(err) err.remove(); }

function updateSummary(){
    const getSelText = (sel) => sel?.options?.[sel.selectedIndex]?.textContent?.trim() || '';
    const programa = getSelText(document.getElementById('magister'));
    const curso = getSelText(document.getElementById('course_id'));
    const periodo = document.getElementById('periodo_info')?.options?.[0]?.textContent?.replace('📘 ','') || '';
    const tipo = getSelText(document.querySelector('select[name="tipo"]'));
    const modalidad = getSelText(document.getElementById('modality'));
    const sala = getSelText(document.getElementById('room_id'));
    const dia = document.querySelector('select[name="dia"]')?.value || '';
    const hi = document.querySelector('input[name="hora_inicio"]')?.value || '';
    const hf = document.querySelector('input[name="hora_fin"]')?.value || '';
    const zoom = document.querySelector('input[name="url_zoom"]')?.value || '';

    const byId = id => document.getElementById(id);
    if(byId('resumen-programa')) byId('resumen-programa').textContent = programa || '—';
    if(byId('resumen-curso')) byId('resumen-curso').textContent = curso || '—';
    if(byId('resumen-periodo')) byId('resumen-periodo').textContent = periodo || '—';
    if(byId('resumen-tipo')) byId('resumen-tipo').textContent = tipo || '—';
    if(byId('resumen-modalidad')) byId('resumen-modalidad').textContent = modalidad || '—';
    if(byId('resumen-sala')) byId('resumen-sala').textContent = sala || (modalidad==='online' ? '— (Online)' : '—');
    if(byId('resumen-horario')) byId('resumen-horario').textContent = (dia && hi && hf) ? `${dia} ${hi}–${hf}` : '—';
    if(byId('resumen-zoom')) byId('resumen-zoom').textContent = zoom || '—';
}


