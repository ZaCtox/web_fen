let p=1,w=4,$=[];window.nextStep=function(){p<w&&H()&&(p++,B(p),C(p),p===3&&P())};window.prevStep=function(){p>1&&(p--,B(p),C(p))};window.navigateToStep=function(e){(e<=p||H())&&(p=e,B(e),C(e))};window.cancelForm=function(){window.hasUnsavedChanges&&window.hasUnsavedChanges()?window.showUnsavedChangesModal(window.location.origin+"/clases"):window.location.href=window.location.origin+"/clases"};window.submitForm=function(){if(console.log("🚀 submitForm() llamado"),console.log("📍 currentStep:",p,"totalSteps:",w),H()){console.log("✅ Validación pasada");const e=document.querySelector(".hci-form");if(!e){console.error("❌ No se encontró el formulario .hci-form");return}console.log("📋 Formulario encontrado:",e),console.log("📋 Action:",e.action),console.log("📋 Method:",e.method);const a=new FormData(e);console.log("📦 Datos del formulario:");for(let[s,n]of a.entries())console.log(`  ${s}:`,n);if(!document.getElementById("form-loading-overlay")){const s=document.createElement("div");s.id="form-loading-overlay",s.className="loading-overlay",s.innerHTML=`
                <div class="loading-overlay-content">
                    <div class="inline-block w-12 h-12 animate-spin rounded-full border-4 border-solid border-[#4d82bc] border-r-transparent"></div>
                    <p class="text-gray-700 dark:text-gray-300 font-medium">Procesando...</p>
                </div>
            `,document.body.appendChild(s)}console.log("🔄 Enviando formulario..."),e.submit(),console.log("✅ form.submit() ejecutado")}else console.warn("⚠️ Validación falló, no se puede enviar el formulario")};function B(e){document.querySelectorAll(".hci-form-section").forEach(s=>s.classList.remove("active"));const a=document.getElementById(F(e));a&&(a.classList.add("active"),a.scrollIntoView({behavior:"smooth",block:"start"})),window.updateWizardProgressSteps&&window.updateWizardProgressSteps(e)}function C(e){const a=e/w*100,s=document.getElementById("progress-bar");s&&(s.style.height=a+"%");const n=document.getElementById("current-step");n&&(n.textContent=`Paso ${e} de ${w}`);const o=document.getElementById("progress-percentage");o&&(o.textContent=Math.round(a)+"%");const t=document.getElementById("prev-btn"),i=document.getElementById("next-btn"),r=document.getElementById("submit-btn");t&&(t.style.display=e>1?"flex":"none"),i&&r&&(e===w?(i.classList.add("hidden"),r.classList.remove("hidden")):(i.classList.remove("hidden"),r.classList.add("hidden")))}function F(e){return w===2?["general","resumen"][e-1]:["general","config-sesiones","detalles-sesiones","resumen"][e-1]}function H(){console.log("🔍 Validando paso:",p);const e=document.getElementById(F(p));if(!e)return console.log("⚠️ Sección no encontrada para paso",p),!0;const a=e.querySelectorAll("input[required], select[required], textarea[required]");console.log(`📋 Campos requeridos encontrados: ${a.length}`);let s=!0;if(a.forEach(n=>{if(n.type==="checkbox"&&n.name==="dias_semana[]"){const o=document.querySelectorAll('input[name="dias_semana[]"]:checked').length>0;console.log("☑️ Días seleccionados:",o),o||(s=!1,A("Debes seleccionar al menos un día de la semana."));return}n.value.trim()?(console.log(`✅ Campo OK: ${n.name||n.id} = ${n.value}`),N(n)):(console.log(`❌ Campo vacío: ${n.name||n.id}`),s=!1,Z(n))}),p===3){console.log("🔍 Validando sesiones...");const n=X();console.log(`📊 Sesiones válidas: ${n}`),n||(s=!1)}return s?console.log("✅ Validación exitosa para paso",p):(console.log("❌ Validación falló"),A("Completa los campos requeridos.")),s}function A(e){let a=document.getElementById("step-error-message");if(!a){a=document.createElement("div"),a.id="step-error-message",a.className="hci-error-message";const s=document.querySelector(".hci-container");s&&s.insertBefore(a,document.querySelector(".hci-wizard-layout"))}a.innerHTML=`<div class="hci-error-content"><span class="hci-error-icon">⚠️</span><span class="hci-error-text">${e}</span></div>`,setTimeout(()=>a&&a.remove(),4e3)}function Z(e){const a=e.checkValidity(),s=e.closest(".hci-field"),n=s==null?void 0:s.querySelector(".hci-field-error");if(a)N(e);else if(e.classList.add("border-red-500"),e.classList.remove("border-gray-300"),!n){const o=document.createElement("div");o.className="hci-field-error",o.textContent=e.validationMessage||"Campo requerido",s==null||s.appendChild(o)}}function N(e){e.classList.remove("border-red-500"),e.classList.add("border-gray-300");const a=e.closest(".hci-field"),s=a==null?void 0:a.querySelector(".hci-field-error");s&&s.remove()}function P(){var o,t;const e=parseInt((o=document.getElementById("num_sesiones"))==null?void 0:o.value)||0,a=(t=document.getElementById("fecha_inicio"))==null?void 0:t.value,s=Array.from(document.querySelectorAll('input[name="dias_semana[]"]:checked')).map(i=>i.value);if(!e||!a||s.length===0)return;const n=W(a,s,e);$=n,G(n),I()}function W(e,a,s){const n=[];let o=new Date(e+"T00:00:00");const t={Viernes:5,Sábado:6},i=a.map(d=>t[d]).sort();let r=0;for(;r<s;){const d=o.getDay();if(i.includes(d)){const l=Object.keys(t).find(m=>t[m]===d);n.push({fecha:o.toISOString().split("T")[0],dia:l,numero:r+1}),r++}o.setDate(o.getDate()+1)}return n}function G(e){var n;const a=document.getElementById("sesiones-container");if(!a)return;const s=((n=document.getElementById("period_id"))==null?void 0:n.value)||"";a.innerHTML=e.map((o,t)=>`
        <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg p-6" data-sesion-index="${t}">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Sesión #${o.numero} - ${o.dia} ${O(o.fecha)}
                </h4>
                <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                    ${o.dia}
                </span>
            </div>
            
            <input type="hidden" name="sesiones[${t}][fecha]" value="${o.fecha}">
            <input type="hidden" name="sesiones[${t}][dia]" value="${o.dia}">
            <input type="hidden" name="sesiones[${t}][estado]" value="pendiente">
            <input type="hidden" name="sesiones[${t}][numero_sesion]" value="${o.numero}">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="hci-field">
                    <label class="hci-label" for="sesiones_${t}_hora_inicio">
                        Hora Inicio <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="sesiones[${t}][hora_inicio]" id="sesiones_${t}_hora_inicio" 
                           class="hci-input" required
                           value="${o.dia==="Viernes"?"18:30":"09:00"}">
                </div>
                
                <div class="hci-field">
                    <label class="hci-label" for="sesiones_${t}_hora_fin">
                        Hora Fin <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="sesiones[${t}][hora_fin]" id="sesiones_${t}_hora_fin" 
                           class="hci-input" required
                           value="${o.dia==="Viernes"?"21:30":"14:00"}">
                </div>
                
                <div class="hci-field">
                    <label class="hci-label" for="sesiones_${t}_modalidad">
                        Modalidad <span class="text-red-500">*</span>
                    </label>
                    <select name="sesiones[${t}][modalidad]" id="sesiones_${t}_modalidad" 
                            class="hci-select sesion-modalidad" data-index="${t}" required>
                        <option value="">-- Seleccionar --</option>
                        <option value="presencial">Presencial</option>
                        <option value="hibrida" ${o.dia==="Sábado"?"selected":""}>Híbrida</option>
                        <option value="online" ${o.dia==="Viernes"?"selected":""}>Online</option>
                    </select>
                </div>
                
                <div class="hci-field sesion-room-field" data-index="${t}" style="display: ${o.dia==="Viernes"?"none":"block"};">
                    <label class="hci-label" for="sesiones_${t}_room_id">
                        Sala ${o.dia==="Sábado"||o.dia==="Viernes"?"":'<span class="text-red-500">*</span>'}
                    </label>
                    <select name="sesiones[${t}][room_id]" id="sesiones_${t}_room_id" 
                            class="hci-select sesion-room-select">
                        <option value="">-- Seleccionar sala --</option>
                        ${K("")}
                    </select>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Selecciona una sala para esta sesión</p>
                </div>
                
                <div class="hci-field md:col-span-2">
                    <label class="hci-label" for="sesiones_${t}_url_zoom">
                        URL Zoom ${o.dia==="Viernes"?'<span class="text-red-500">*</span>':""}
                    </label>
                    <input type="url" name="sesiones[${t}][url_zoom]" id="sesiones_${t}_url_zoom" 
                           class="hci-input sesion-zoom-input" placeholder="https://zoom.us/j/...">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Solo para sesiones online o híbridas</p>
                </div>
                
                <div class="hci-field md:col-span-3">
                    <label class="hci-label" for="sesiones_${t}_observaciones">
                        Observaciones (opcional)
                    </label>
                    <textarea name="sesiones[${t}][observaciones]" id="sesiones_${t}_observaciones" 
                              class="hci-input" rows="2" 
                              placeholder="Notas adicionales sobre esta sesión..."></textarea>
                </div>
            </div>
            
            <!-- Botones para ver horarios y salas disponibles -->
            <div class="mt-4 flex gap-2">
                <button type="button" 
                        class="btn-ver-horarios px-3 py-2 text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors flex items-center gap-2"
                        data-index="${t}"
                        title="Ver horarios disponibles en esta sala">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    Ver horarios
                </button>
                <button type="button" 
                        class="btn-ver-salas px-3 py-2 text-xs font-medium text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/40 transition-colors flex items-center gap-2"
                        data-index="${t}"
                        title="Ver salas disponibles en este horario">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    Ver salas
                </button>
            </div>
            
            <!-- Slots de horarios disponibles -->
            <div id="horarios_${t}" class="mt-3 hidden">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <h5 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Horarios disponibles (haz clic para seleccionar):
                    </h5>
                    <div id="slots_${t}" class="flex flex-wrap gap-2"></div>
                </div>
            </div>
            
            <!-- Salas disponibles -->
            <div id="salas_${t}" class="mt-3 hidden">
                <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                    <h5 class="text-sm font-semibold text-purple-900 dark:text-purple-100 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Salas disponibles (haz clic para seleccionar):
                    </h5>
                    <div id="salas_list_${t}" class="grid grid-cols-1 md:grid-cols-2 gap-2"></div>
                </div>
            </div>
            
            <!-- Indicador de disponibilidad -->
            <div id="disponibilidad_${t}" class="mt-4 hidden">
                <div class="flex items-center gap-2 p-3 rounded-lg disponibilidad-loading hidden">
                    <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Verificando disponibilidad...</span>
                </div>
                
                <div class="flex items-start gap-2 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 disponibilidad-disponible hidden">
                    <svg class="w-5 h-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">Sala disponible</p>
                        <p class="text-xs text-green-600 dark:text-green-300 mt-1">No hay conflictos de horario</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-2 p-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 disponibilidad-ocupada hidden">
                    <svg class="w-5 h-5 text-red-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">Sala ocupada</p>
                        <p class="text-xs text-red-600 dark:text-red-300 mt-1 disponibilidad-conflictos"></p>
                    </div>
                </div>
            </div>
        </div>
    `).join(""),a.querySelectorAll(".sesion-modalidad").forEach(o=>{o.addEventListener("change",t=>{const i=t.target.dataset.index;D(i),L(i,s,roomIdGlobal)})}),e.forEach((o,t)=>{const i=document.getElementById(`sesiones_${t}_hora_inicio`),r=document.getElementById(`sesiones_${t}_hora_fin`),d=document.getElementById(`sesiones_${t}_room_id`);i&&i.addEventListener("change",()=>L(t,s,"")),r&&r.addEventListener("change",()=>L(t,s,"")),d&&d.addEventListener("change",()=>L(t,s,"")),D(t),L(t,s,"")}),a.querySelectorAll(".btn-ver-horarios").forEach(o=>{o.addEventListener("click",()=>{const t=parseInt(o.dataset.index);J(t,s,roomIdGlobal)})}),a.querySelectorAll(".btn-ver-salas").forEach(o=>{o.addEventListener("click",()=>{const t=parseInt(o.dataset.index);Q(t,s,"")})})}function K(e){if(window.ROOMS&&Array.isArray(window.ROOMS))return window.ROOMS.map(s=>`<option value="${s.id}" ${s.id==e?"selected":""}>${s.name}</option>`).join("");const a=document.querySelector('select[name*="room"]');return a?Array.from(a.options).filter(s=>s.value!=="").map(s=>`<option value="${s.value}" ${s.value===e?"selected":""}>${s.text}</option>`).join(""):'<option value="">No hay salas disponibles</option>'}function D(e){var i,r,d;const a=document.getElementById(`sesiones_${e}_modalidad`),s=a==null?void 0:a.value,n=document.querySelector(`.sesion-room-field[data-index="${e}"]`),o=document.getElementById(`sesiones_${e}_room_id`),t=document.getElementById(`sesiones_${e}_url_zoom`);if(s==="online"){if(n&&(n.style.display="none"),o&&o.removeAttribute("required"),t){t.setAttribute("required","required");const l=(i=t.closest(".hci-field"))==null?void 0:i.querySelector("label");l&&(l.innerHTML='URL Zoom <span class="text-red-500">*</span>')}}else if(s==="hibrida"){if(n&&(n.style.display="block"),o&&o.removeAttribute("required"),t){t.removeAttribute("required");const l=(r=t.closest(".hci-field"))==null?void 0:r.querySelector("label");l&&(l.innerHTML="URL Zoom")}}else if(s==="presencial"&&(n&&(n.style.display="block"),o&&o.removeAttribute("required"),t)){t.removeAttribute("required");const l=(d=t.closest(".hci-field"))==null?void 0:d.querySelector("label");l&&(l.innerHTML="URL Zoom")}}function X(){let e=!0;return $.forEach((a,s)=>{var i,r,d;const n=(i=document.getElementById(`sesiones_${s}_hora_inicio`))==null?void 0:i.value,o=(r=document.getElementById(`sesiones_${s}_hora_fin`))==null?void 0:r.value,t=(d=document.getElementById(`sesiones_${s}_modalidad`))==null?void 0:d.value;(!n||!o||!t)&&(e=!1),n&&o&&n>=o&&(e=!1,A(`Sesión ${s+1}: La hora fin debe ser posterior a la hora inicio`))}),e}function O(e){return new Date(e+"T00:00:00").toLocaleDateString("es-CL",{day:"2-digit",month:"2-digit",year:"numeric"})}function I(){var f,y,b;const e=u=>{var c,g,h;return((h=(g=(c=u==null?void 0:u.options)==null?void 0:c[u.selectedIndex])==null?void 0:g.textContent)==null?void 0:h.trim())||""},a=e(document.getElementById("magister")),s=e(document.getElementById("course_id")),n=((f=document.getElementById("anio"))==null?void 0:f.value)||"",o=((y=document.getElementById("trimestre"))==null?void 0:y.value)||"",t=n&&o?`${n} - Trimestre ${o}`:"—",i=((b=document.getElementById("encargado"))==null?void 0:b.value)||"",r="Se configuran por sesión",d="Se configuran por sesión",l=u=>document.getElementById(u);l("resumen-programa")&&(l("resumen-programa").textContent=a||"—"),l("resumen-curso")&&(l("resumen-curso").textContent=s||"—"),l("resumen-periodo")&&(l("resumen-periodo").textContent=t),l("resumen-encargado")&&(l("resumen-encargado").textContent=i||"—"),l("resumen-sala-principal")&&(l("resumen-sala-principal").textContent=r),l("resumen-zoom-principal")&&(l("resumen-zoom-principal").textContent=d);const m=l("resumen-total-sesiones");m&&(m.textContent=$.length);const v=l("resumen-sesiones-lista");v&&$.length>0&&(v.innerHTML=$.map((u,c)=>{var k,q,E;const g=((k=document.getElementById(`sesiones_${c}_hora_inicio`))==null?void 0:k.value)||"—",h=((q=document.getElementById(`sesiones_${c}_hora_fin`))==null?void 0:q.value)||"—",x=((E=document.getElementById(`sesiones_${c}_modalidad`))==null?void 0:E.value)||"—",S={online:"bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200",presencial:"bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200",hibrida:"bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-200"}[x]||"bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200";return`
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/30 rounded border border-gray-200 dark:border-gray-600">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            Sesión ${u.numero} - ${u.dia} ${O(u.fecha)}
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            ${g} - ${h}
                        </p>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded ${S}">
                        ${x.charAt(0).toUpperCase()+x.slice(1)}
                    </span>
                </div>
            `}).join(""))}let z={};async function L(e,a,s){var u,c,g,h,x,S;const n=(u=document.getElementById(`sesiones_${e}_modalidad`))==null?void 0:u.value,o=(c=document.getElementById(`sesiones_${e}_hora_inicio`))==null?void 0:c.value,t=(g=document.getElementById(`sesiones_${e}_hora_fin`))==null?void 0:g.value,r=((h=document.getElementById(`sesiones_${e}_room_id`))==null?void 0:h.value)||s,d=(x=$[e])==null?void 0:x.fecha,l=(S=$[e])==null?void 0:S.dia,m=document.getElementById(`disponibilidad_${e}`);if(!m)return;const v=m.querySelector(".disponibilidad-loading"),f=m.querySelector(".disponibilidad-disponible"),y=m.querySelector(".disponibilidad-ocupada"),b=()=>{m.classList.add("hidden"),v.classList.add("hidden"),f.classList.add("hidden"),y.classList.add("hidden")};if(n==="online"||!o||!t||!r||!a||!d){b();return}z[e]&&clearTimeout(z[e]),z[e]=setTimeout(async()=>{try{m.classList.remove("hidden"),b(),m.classList.remove("hidden"),v.classList.remove("hidden");const k=new URLSearchParams({period_id:a,room_id:r,dia:l,hora_inicio:o,hora_fin:t,modality:n}),E=await(await fetch(`/salas/disponibilidad?${k}`,{headers:{Accept:"application/json"}})).json();if(v.classList.add("hidden"),E.available)f.classList.remove("hidden");else{y.classList.remove("hidden");const M=y.querySelector(".disponibilidad-conflictos");if(M&&E.conflicts&&E.conflicts.length>0){const R=E.conflicts.map(_=>{const U=_.modalidad==="online"?"🌐":_.modalidad==="hibrida"?"🔀":"🏢";let V=_.fecha;try{V=new Date(_.fecha).toLocaleDateString("es-CL",{year:"numeric",month:"2-digit",day:"2-digit"})}catch(j){console.error("Error formateando fecha:",j)}return`
                            <div class="mt-2 text-xs">
                                ${U} <strong>${_.course_nombre}</strong><br>
                                👤 ${_.encargado}<br>
                                🕐 ${_.hora_inicio} - ${_.hora_fin} (${_.dia})<br>
                                📅 Sesión del ${V}
                            </div>
                        `}).join('<hr class="my-2 border-red-200 dark:border-red-700">');M.innerHTML=`<strong>⚠️ Conflicto de horario detectado:</strong>${R}
                        <p class="mt-2 text-xs italic">Esta sala ya tiene una clase programada en el mismo día y horario.</p>`}else M.textContent="Hay un conflicto de horario en esta sala"}}catch(k){console.error("Error al verificar disponibilidad:",k),b()}},500)}async function J(e,a,s){var l,m,v;const n=(l=document.getElementById(`sesiones_${e}_modalidad`))==null?void 0:l.value,t=((m=document.getElementById(`sesiones_${e}_room_id`))==null?void 0:m.value)||s,i=(v=$[e])==null?void 0:v.dia,r=document.getElementById(`horarios_${e}`),d=document.getElementById(`slots_${e}`);if(!(!r||!d)){if(n==="online"||!t||!i){r.classList.add("hidden");return}try{d.innerHTML='<span class="text-sm text-gray-600 dark:text-gray-400">Buscando horarios disponibles...</span>',r.classList.remove("hidden");const f=new URLSearchParams({period_id:a,room_id:t,dia:i,modality:n,desde:"08:00",hasta:"22:00",min_block:60,blocks:1}),b=await(await fetch(`/salas/horarios?${f}`,{headers:{Accept:"application/json"}})).json();b.slots&&b.slots.length>0?(d.innerHTML=b.slots.map(u=>{const c=[];let g=u.start;for(;T(g,60)<=u.end;){const h=g,x=T(g,180);x<=u.end&&c.push({inicio:h,fin:x}),g=T(g,15)}return c.map(h=>`
                    <button type="button"
                            class="slot-btn px-3 py-2 text-sm font-medium text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/40 transition-colors"
                            data-index="${e}"
                            data-inicio="${h.inicio}"
                            data-fin="${h.fin}"
                            title="Haz clic para usar este horario">
                        🕐 ${h.inicio} - ${h.fin}
                    </button>
                `).join("")}).join(""),d.querySelectorAll(".slot-btn").forEach(u=>{u.addEventListener("click",()=>{const c=parseInt(u.dataset.index),g=u.dataset.inicio,h=u.dataset.fin;document.getElementById(`sesiones_${c}_hora_inicio`).value=g,document.getElementById(`sesiones_${c}_hora_fin`).value=h,r.classList.add("hidden"),L(c,a,s),u.classList.add("ring-2","ring-green-500"),setTimeout(()=>u.classList.remove("ring-2","ring-green-500"),1e3)})})):d.innerHTML='<span class="text-sm text-yellow-600 dark:text-yellow-400">⚠️ No hay horarios disponibles con estas características</span>'}catch(f){console.error("Error al obtener horarios disponibles:",f),d.innerHTML='<span class="text-sm text-red-600 dark:text-red-400">❌ Error al cargar horarios</span>'}}}async function Q(e,a,s){var l,m,v,f;const n=(l=document.getElementById(`sesiones_${e}_modalidad`))==null?void 0:l.value,o=(m=document.getElementById(`sesiones_${e}_hora_inicio`))==null?void 0:m.value,t=(v=document.getElementById(`sesiones_${e}_hora_fin`))==null?void 0:v.value,i=(f=$[e])==null?void 0:f.dia,r=document.getElementById(`salas_${e}`),d=document.getElementById(`salas_list_${e}`);if(!(!r||!d)){if(n==="online"||!o||!t||!i){r.classList.add("hidden"),n==="online"&&(d.innerHTML='<span class="text-sm text-gray-600 dark:text-gray-400">Las clases online no requieren sala física</span>',r.classList.remove("hidden"));return}try{d.innerHTML='<span class="text-sm text-gray-600 dark:text-gray-400">Buscando salas disponibles...</span>',r.classList.remove("hidden");const y=new URLSearchParams({dia:i,hora_inicio:o,hora_fin:t,modalidad:n}),u=await(await fetch(`/salas/disponibles?${y}`,{headers:{Accept:"application/json"}})).json();u.salas&&u.salas.length>0?(d.innerHTML=u.salas.map(c=>`
                <button type="button"
                        class="sala-btn text-left p-3 bg-white dark:bg-gray-800 border-2 border-purple-200 dark:border-purple-700 rounded-lg hover:border-purple-400 dark:hover:border-purple-500 hover:shadow-md transition-all"
                        data-index="${e}"
                        data-sala-id="${c.id}"
                        data-sala-name="${c.name}"
                        title="Haz clic para seleccionar esta sala">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="font-semibold text-purple-900 dark:text-purple-100">${c.name}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                📍 ${c.location}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                👥 Capacidad: ${c.capacity} personas
                            </p>
                        </div>
                        <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </button>
            `).join(""),d.querySelectorAll(".sala-btn").forEach(c=>{c.addEventListener("click",()=>{const g=parseInt(c.dataset.index),h=c.dataset.salaId,x=c.dataset.salaName,S=document.getElementById(`sesiones_${g}_room_id`);S&&(S.value=h),r.classList.add("hidden"),L(g,a,s),c.classList.add("ring-2","ring-purple-500"),setTimeout(()=>c.classList.remove("ring-2","ring-purple-500"),1e3),console.log(`✅ Sala seleccionada: ${x}`)})})):d.innerHTML=`
                <div class="col-span-2 text-center p-6">
                    <svg class="w-12 h-12 mx-auto text-yellow-500 mb-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">No hay salas disponibles</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Todas las salas están ocupadas en este horario (${o} - ${t})</p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-2">💡 Prueba cambiando el horario o día</p>
                </div>
            `}catch(y){console.error("Error al obtener salas disponibles:",y),d.innerHTML='<span class="text-sm text-red-600 dark:text-red-400">❌ Error al cargar salas</span>'}}}function T(e,a){const[s,n]=(e||"00:00").split(":").map(Number),o=new Date(2e3,1,1,s,n,0);o.setMinutes(o.getMinutes()+a);const t=i=>i<10?"0"+i:""+i;return`${t(o.getHours())}:${t(o.getMinutes())}`}document.addEventListener("DOMContentLoaded",()=>{const e=document.querySelector('[data-editing="true"]')!==null;if(w=e?2:4,e){document.querySelectorAll(".step-creation-only").forEach(m=>m.style.display="none");const t=document.querySelector('[data-step="4"]');if(t){const m=t.querySelector(".hci-progress-step-number");m&&(m.textContent="2"),t.setAttribute("data-step","2"),t.setAttribute("onclick","navigateToStep(2)")}const i=document.getElementById("total-steps");i&&(i.textContent="2");const r=document.getElementById("current-step");r&&(r.textContent="Paso 1 de 2");const d=document.getElementById("progress-percentage");d&&(d.textContent="50%");const l=document.getElementById("progress-bar");l&&(l.style.height="50%"),window.updateWizardProgressSteps=function(m){document.querySelectorAll('.hci-progress-step-vertical:not([style*="display: none"])').forEach((f,y)=>{const b=y+1;f.classList.remove("completed","active"),b<m?f.classList.add("completed"):b===m&&f.classList.add("active")})}}const a=document.querySelector(".hci-form");a&&a.addEventListener("keydown",o=>{if(o.key==="Enter"&&o.target.tagName!=="TEXTAREA")return o.preventDefault(),!1});const s=document.querySelector(".hci-form-section .hci-field-error, .hci-form-section .text-red-600, .hci-form-section .border-red-500");if(s){const o=s.closest(".hci-form-section");o&&o.dataset.step&&(p=parseInt(o.dataset.step)||1)}if(B(p),C(p),document.querySelectorAll(".hci-input, .hci-select, .hci-textarea, select, input").forEach(o=>o.addEventListener("input",I)),document.addEventListener("change",I),!e){const o=document.getElementById("num_sesiones"),t=document.getElementById("fecha_inicio"),i=document.querySelectorAll('input[name="dias_semana[]"]');[o,t,...i].forEach(r=>{r&&r.addEventListener("change",P)})}I()});
