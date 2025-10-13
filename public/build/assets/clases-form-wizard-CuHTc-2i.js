let g=1,I=4,E=[];window.nextStep=function(){g<I&&H()&&(g++,C(g),q(g),g===3&&P())};window.prevStep=function(){g>1&&(g--,C(g),q(g))};window.navigateToStep=function(e){(e<=g||H())&&(g=e,C(e),q(e))};window.cancelForm=function(){window.hasUnsavedChanges&&window.hasUnsavedChanges()?window.showUnsavedChangesModal(window.location.origin+"/clases"):window.location.href=window.location.origin+"/clases"};window.submitForm=function(){if(console.log("🚀 submitForm() llamado"),console.log("📍 currentStep:",g,"totalSteps:",I),H()){console.log("✅ Validación pasada");const e=document.querySelector(".hci-form");if(!e){console.error("❌ No se encontró el formulario .hci-form");return}console.log("📋 Formulario encontrado:",e),console.log("📋 Action:",e.action),console.log("📋 Method:",e.method);const a=new FormData(e);console.log("📦 Datos del formulario:");for(let[o,r]of a.entries())console.log(`  ${o}:`,r);if(!document.getElementById("form-loading-overlay")){const o=document.createElement("div");o.id="form-loading-overlay",o.className="loading-overlay",o.innerHTML=`
                <div class="loading-overlay-content">
                    <div class="inline-block w-12 h-12 animate-spin rounded-full border-4 border-solid border-[#4d82bc] border-r-transparent"></div>
                    <p class="text-gray-700 dark:text-gray-300 font-medium">Procesando...</p>
                </div>
            `,document.body.appendChild(o)}console.log("🔄 Enviando formulario..."),e.submit(),console.log("✅ form.submit() ejecutado")}else console.warn("⚠️ Validación falló, no se puede enviar el formulario")};function C(e){document.querySelectorAll(".hci-form-section").forEach(o=>o.classList.remove("active"));const a=document.getElementById(F(e));a&&(a.classList.add("active"),a.scrollIntoView({behavior:"smooth",block:"start"})),window.updateWizardProgressSteps&&window.updateWizardProgressSteps(e)}function q(e){const a=e/I*100,o=document.getElementById("progress-bar");o&&(o.style.height=a+"%");const r=document.getElementById("current-step");r&&(r.textContent=`Paso ${e} de ${I}`);const s=document.getElementById("progress-percentage");s&&(s.textContent=Math.round(a)+"%");const i=document.getElementById("prev-btn"),d=document.getElementById("next-btn"),l=document.getElementById("submit-btn");i&&(i.style.display=e>1?"flex":"none"),d&&l&&(e===I?(d.classList.add("hidden"),l.classList.remove("hidden")):(d.classList.remove("hidden"),l.classList.add("hidden")))}function F(e){return I===2?["general","resumen"][e-1]:["general","config-sesiones","detalles-sesiones","resumen"][e-1]}function H(){console.log("🔍 Validando paso:",g);const e=document.getElementById(F(g));if(!e)return console.log("⚠️ Sección no encontrada para paso",g),!0;const a=e.querySelectorAll("input[required], select[required], textarea[required]");console.log(`📋 Campos requeridos encontrados: ${a.length}`);let o=!0;if(a.forEach(r=>{if(r.type==="checkbox"&&r.name==="dias_semana[]"){const s=document.querySelectorAll('input[name="dias_semana[]"]:checked').length>0;console.log("☑️ Días seleccionados:",s),s||(o=!1,A("Debes seleccionar al menos un día de la semana."));return}r.value.trim()?(console.log(`✅ Campo OK: ${r.name||r.id} = ${r.value}`),N(r)):(console.log(`❌ Campo vacío: ${r.name||r.id}`),o=!1,Z(r))}),g===3){console.log("🔍 Validando sesiones...");const r=X();console.log(`📊 Sesiones válidas: ${r}`),r||(o=!1)}return o?console.log("✅ Validación exitosa para paso",g):(console.log("❌ Validación falló"),A("Completa los campos requeridos.")),o}function A(e){let a=document.getElementById("step-error-message");if(!a){a=document.createElement("div"),a.id="step-error-message",a.className="hci-error-message";const o=document.querySelector(".hci-container");o&&o.insertBefore(a,document.querySelector(".hci-wizard-layout"))}a.innerHTML=`<div class="hci-error-content"><span class="hci-error-icon">⚠️</span><span class="hci-error-text">${e}</span></div>`,setTimeout(()=>a&&a.remove(),4e3)}function Z(e){const a=e.checkValidity(),o=e.closest(".hci-field"),r=o==null?void 0:o.querySelector(".hci-field-error");if(a)N(e);else if(e.classList.add("border-red-500"),e.classList.remove("border-gray-300"),!r){const s=document.createElement("div");s.className="hci-field-error",s.textContent=e.validationMessage||"Campo requerido",o==null||o.appendChild(s)}}function N(e){e.classList.remove("border-red-500"),e.classList.add("border-gray-300");const a=e.closest(".hci-field"),o=a==null?void 0:a.querySelector(".hci-field-error");o&&o.remove()}function P(){var s,i;const e=parseInt((s=document.getElementById("num_sesiones"))==null?void 0:s.value)||0,a=(i=document.getElementById("fecha_inicio"))==null?void 0:i.value,o=Array.from(document.querySelectorAll('input[name="dias_semana[]"]:checked')).map(d=>d.value);if(!e||!a||o.length===0)return;const r=W(a,o,e);E=r,G(r),B()}function W(e,a,o){const r=[];let s=new Date(e+"T00:00:00");const i={Viernes:5,Sábado:6},d=a.map(n=>i[n]).sort();let l=0;for(;l<o;){const n=s.getDay();if(d.includes(n)){const t=Object.keys(i).find(u=>i[u]===n);r.push({fecha:s.toISOString().split("T")[0],dia:t,numero:l+1}),l++}s.setDate(s.getDate()+1)}return r}function G(e){var i,d,l;const a=document.getElementById("sesiones-container");if(!a)return;const o=((i=document.getElementById("room_id"))==null?void 0:i.value)||"",r=((d=document.getElementById("url_zoom"))==null?void 0:d.value)||"",s=((l=document.getElementById("period_id"))==null?void 0:l.value)||"";a.innerHTML=e.map((n,t)=>`
        <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg p-6" data-sesion-index="${t}">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Sesión #${n.numero} - ${n.dia} ${U(n.fecha)}
                </h4>
                <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                    ${n.dia}
                </span>
            </div>
            
            <input type="hidden" name="sesiones[${t}][fecha]" value="${n.fecha}">
            <input type="hidden" name="sesiones[${t}][dia]" value="${n.dia}">
            <input type="hidden" name="sesiones[${t}][estado]" value="pendiente">
            <input type="hidden" name="sesiones[${t}][numero_sesion]" value="${n.numero}">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="hci-field">
                    <label class="hci-label" for="sesiones_${t}_hora_inicio">
                        Hora Inicio <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="sesiones[${t}][hora_inicio]" id="sesiones_${t}_hora_inicio" 
                           class="hci-input" required
                           value="${n.dia==="Viernes"?"18:30":"09:00"}">
                </div>
                
                <div class="hci-field">
                    <label class="hci-label" for="sesiones_${t}_hora_fin">
                        Hora Fin <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="sesiones[${t}][hora_fin]" id="sesiones_${t}_hora_fin" 
                           class="hci-input" required
                           value="${n.dia==="Viernes"?"21:30":"14:00"}">
                </div>
                
                <div class="hci-field">
                    <label class="hci-label" for="sesiones_${t}_modalidad">
                        Modalidad <span class="text-red-500">*</span>
                    </label>
                    <select name="sesiones[${t}][modalidad]" id="sesiones_${t}_modalidad" 
                            class="hci-select sesion-modalidad" data-index="${t}" required>
                        <option value="">-- Seleccionar --</option>
                        <option value="presencial">Presencial</option>
                        <option value="hibrida" ${n.dia==="Sábado"?"selected":""}>Híbrida</option>
                        <option value="online" ${n.dia==="Viernes"?"selected":""}>Online</option>
                    </select>
                </div>
                
                <div class="hci-field sesion-room-field" data-index="${t}" style="display: ${n.dia==="Viernes"?"none":"block"};">
                    <label class="hci-label" for="sesiones_${t}_room_id">
                        Sala ${n.dia==="Sábado"||n.dia==="Viernes"?"":'<span class="text-red-500">*</span>'}
                    </label>
                    <select name="sesiones[${t}][room_id]" id="sesiones_${t}_room_id" 
                            class="hci-select sesion-room-select">
                        <option value="">-- Usar sala principal --</option>
                        ${K(o)}
                    </select>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Dejar vacío para usar la sala principal</p>
                </div>
                
                <div class="hci-field md:col-span-2">
                    <label class="hci-label" for="sesiones_${t}_url_zoom">
                        URL Zoom ${n.dia==="Viernes"?'<span class="text-red-500">*</span>':""}
                    </label>
                    <input type="url" name="sesiones[${t}][url_zoom]" id="sesiones_${t}_url_zoom" 
                           class="hci-input sesion-zoom-input" placeholder="https://zoom.us/j/..."
                           value="${r}">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Dejar vacío para usar el Zoom principal</p>
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
            
            {{-- Botones para ver horarios y salas disponibles --}}
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
            
            {{-- Slots de horarios disponibles --}}
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
            
            {{-- Salas disponibles --}}
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
            
            {{-- Indicador de disponibilidad --}}
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
    `).join(""),a.querySelectorAll(".sesion-modalidad").forEach(n=>{n.addEventListener("change",t=>{const u=t.target.dataset.index;j(u),L(u,s,o)})}),e.forEach((n,t)=>{const u=document.getElementById(`sesiones_${t}_hora_inicio`),v=document.getElementById(`sesiones_${t}_hora_fin`),h=document.getElementById(`sesiones_${t}_room_id`);u&&u.addEventListener("change",()=>L(t,s,o)),v&&v.addEventListener("change",()=>L(t,s,o)),h&&h.addEventListener("change",()=>L(t,s,o)),j(t),L(t,s,o)}),a.querySelectorAll(".btn-ver-horarios").forEach(n=>{n.addEventListener("click",()=>{const t=parseInt(n.dataset.index);J(t,s,o)})}),a.querySelectorAll(".btn-ver-salas").forEach(n=>{n.addEventListener("click",()=>{const t=parseInt(n.dataset.index);Q(t,s,o)})})}function K(e){const a=document.getElementById("room_id");return a?Array.from(a.options).filter(o=>o.value!=="").map(o=>`<option value="${o.value}" ${o.value===e?"selected":""}>${o.text}</option>`).join(""):""}function j(e){var d,l,n;const a=document.getElementById(`sesiones_${e}_modalidad`),o=a==null?void 0:a.value,r=document.querySelector(`.sesion-room-field[data-index="${e}"]`),s=document.getElementById(`sesiones_${e}_room_id`),i=document.getElementById(`sesiones_${e}_url_zoom`);if(o==="online"){if(r&&(r.style.display="none"),s&&s.removeAttribute("required"),i){i.setAttribute("required","required");const t=(d=i.closest(".hci-field"))==null?void 0:d.querySelector("label");t&&(t.innerHTML='URL Zoom <span class="text-red-500">*</span>')}}else if(o==="hibrida"){if(r&&(r.style.display="block"),s&&s.removeAttribute("required"),i){i.removeAttribute("required");const t=(l=i.closest(".hci-field"))==null?void 0:l.querySelector("label");t&&(t.innerHTML="URL Zoom")}}else if(o==="presencial"&&(r&&(r.style.display="block"),s&&s.removeAttribute("required"),i)){i.removeAttribute("required");const t=(n=i.closest(".hci-field"))==null?void 0:n.querySelector("label");t&&(t.innerHTML="URL Zoom")}}function X(){let e=!0;return E.forEach((a,o)=>{var d,l,n;const r=(d=document.getElementById(`sesiones_${o}_hora_inicio`))==null?void 0:d.value,s=(l=document.getElementById(`sesiones_${o}_hora_fin`))==null?void 0:l.value,i=(n=document.getElementById(`sesiones_${o}_modalidad`))==null?void 0:n.value;(!r||!s||!i)&&(e=!1),r&&s&&r>=s&&(e=!1,A(`Sesión ${o+1}: La hora fin debe ser posterior a la hora inicio`))}),e}function U(e){return new Date(e+"T00:00:00").toLocaleDateString("es-CL",{day:"2-digit",month:"2-digit",year:"numeric"})}function B(){var h,y,b,m;const e=c=>{var p,f,_;return((_=(f=(p=c==null?void 0:c.options)==null?void 0:p[c.selectedIndex])==null?void 0:f.textContent)==null?void 0:_.trim())||""},a=e(document.getElementById("magister")),o=e(document.getElementById("course_id")),r=((h=document.getElementById("anio"))==null?void 0:h.value)||"",s=((y=document.getElementById("trimestre"))==null?void 0:y.value)||"",i=r&&s?`${r} - Trimestre ${s}`:"—",d=((b=document.getElementById("encargado"))==null?void 0:b.value)||"",l=e(document.getElementById("room_id"))||"Sin sala asignada",n=((m=document.getElementById("url_zoom"))==null?void 0:m.value)||"No asignado",t=c=>document.getElementById(c);t("resumen-programa")&&(t("resumen-programa").textContent=a||"—"),t("resumen-curso")&&(t("resumen-curso").textContent=o||"—"),t("resumen-periodo")&&(t("resumen-periodo").textContent=i),t("resumen-encargado")&&(t("resumen-encargado").textContent=d||"—"),t("resumen-sala-principal")&&(t("resumen-sala-principal").textContent=l),t("resumen-zoom-principal")&&(t("resumen-zoom-principal").textContent=n);const u=t("resumen-total-sesiones");u&&(u.textContent=E.length);const v=t("resumen-sesiones-lista");v&&E.length>0&&(v.innerHTML=E.map((c,p)=>{var M,S,k;const f=((M=document.getElementById(`sesiones_${p}_hora_inicio`))==null?void 0:M.value)||"—",_=((S=document.getElementById(`sesiones_${p}_hora_fin`))==null?void 0:S.value)||"—",x=((k=document.getElementById(`sesiones_${p}_modalidad`))==null?void 0:k.value)||"—",w={online:"bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200",presencial:"bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200",hibrida:"bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-200"}[x]||"bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200";return`
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/30 rounded border border-gray-200 dark:border-gray-600">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            Sesión ${c.numero} - ${c.dia} ${U(c.fecha)}
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            ${f} - ${_}
                        </p>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded ${w}">
                        ${x.charAt(0).toUpperCase()+x.slice(1)}
                    </span>
                </div>
            `}).join(""))}let z={};async function L(e,a,o){var m,c,p,f,_,x;const r=(m=document.getElementById(`sesiones_${e}_modalidad`))==null?void 0:m.value,s=(c=document.getElementById(`sesiones_${e}_hora_inicio`))==null?void 0:c.value,i=(p=document.getElementById(`sesiones_${e}_hora_fin`))==null?void 0:p.value,l=((f=document.getElementById(`sesiones_${e}_room_id`))==null?void 0:f.value)||o,n=(_=E[e])==null?void 0:_.fecha,t=(x=E[e])==null?void 0:x.dia,u=document.getElementById(`disponibilidad_${e}`);if(!u)return;const v=u.querySelector(".disponibilidad-loading"),h=u.querySelector(".disponibilidad-disponible"),y=u.querySelector(".disponibilidad-ocupada"),b=()=>{u.classList.add("hidden"),v.classList.add("hidden"),h.classList.add("hidden"),y.classList.add("hidden")};if(r==="online"||!s||!i||!l||!a||!n){b();return}z[e]&&clearTimeout(z[e]),z[e]=setTimeout(async()=>{try{u.classList.remove("hidden"),b(),u.classList.remove("hidden"),v.classList.remove("hidden");const w=new URLSearchParams({period_id:a,room_id:l,dia:t,hora_inicio:s,hora_fin:i,modality:r}),S=await(await fetch(`/salas/disponibilidad?${w}`,{headers:{Accept:"application/json"}})).json();if(v.classList.add("hidden"),S.available)h.classList.remove("hidden");else{y.classList.remove("hidden");const k=y.querySelector(".disponibilidad-conflictos");if(k&&S.conflicts&&S.conflicts.length>0){const R=S.conflicts.map($=>{const O=$.modalidad==="online"?"🌐":$.modalidad==="hibrida"?"🔀":"🏢";let V=$.fecha;try{V=new Date($.fecha).toLocaleDateString("es-CL",{year:"numeric",month:"2-digit",day:"2-digit"})}catch(D){console.error("Error formateando fecha:",D)}return`
                            <div class="mt-2 text-xs">
                                ${O} <strong>${$.course_nombre}</strong><br>
                                👤 ${$.encargado}<br>
                                🕐 ${$.hora_inicio} - ${$.hora_fin} (${$.dia})<br>
                                📅 Sesión del ${V}
                            </div>
                        `}).join('<hr class="my-2 border-red-200 dark:border-red-700">');k.innerHTML=`<strong>⚠️ Conflicto de horario detectado:</strong>${R}
                        <p class="mt-2 text-xs italic">Esta sala ya tiene una clase programada en el mismo día y horario.</p>`}else k.textContent="Hay un conflicto de horario en esta sala"}}catch(w){console.error("Error al verificar disponibilidad:",w),b()}},500)}async function J(e,a,o){var t,u,v;const r=(t=document.getElementById(`sesiones_${e}_modalidad`))==null?void 0:t.value,i=((u=document.getElementById(`sesiones_${e}_room_id`))==null?void 0:u.value)||o,d=(v=E[e])==null?void 0:v.dia,l=document.getElementById(`horarios_${e}`),n=document.getElementById(`slots_${e}`);if(!(!l||!n)){if(r==="online"||!i||!d){l.classList.add("hidden");return}try{n.innerHTML='<span class="text-sm text-gray-600 dark:text-gray-400">Buscando horarios disponibles...</span>',l.classList.remove("hidden");const h=new URLSearchParams({period_id:a,room_id:i,dia:d,modality:r,desde:"08:00",hasta:"22:00",min_block:60,blocks:1}),b=await(await fetch(`/salas/horarios?${h}`,{headers:{Accept:"application/json"}})).json();b.slots&&b.slots.length>0?(n.innerHTML=b.slots.map(m=>{const c=[];let p=m.start;for(;T(p,60)<=m.end;){const f=p,_=T(p,180);_<=m.end&&c.push({inicio:f,fin:_}),p=T(p,15)}return c.map(f=>`
                    <button type="button"
                            class="slot-btn px-3 py-2 text-sm font-medium text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/40 transition-colors"
                            data-index="${e}"
                            data-inicio="${f.inicio}"
                            data-fin="${f.fin}"
                            title="Haz clic para usar este horario">
                        🕐 ${f.inicio} - ${f.fin}
                    </button>
                `).join("")}).join(""),n.querySelectorAll(".slot-btn").forEach(m=>{m.addEventListener("click",()=>{const c=parseInt(m.dataset.index),p=m.dataset.inicio,f=m.dataset.fin;document.getElementById(`sesiones_${c}_hora_inicio`).value=p,document.getElementById(`sesiones_${c}_hora_fin`).value=f,l.classList.add("hidden"),L(c,a,o),m.classList.add("ring-2","ring-green-500"),setTimeout(()=>m.classList.remove("ring-2","ring-green-500"),1e3)})})):n.innerHTML='<span class="text-sm text-yellow-600 dark:text-yellow-400">⚠️ No hay horarios disponibles con estas características</span>'}catch(h){console.error("Error al obtener horarios disponibles:",h),n.innerHTML='<span class="text-sm text-red-600 dark:text-red-400">❌ Error al cargar horarios</span>'}}}async function Q(e,a,o){var t,u,v,h;const r=(t=document.getElementById(`sesiones_${e}_modalidad`))==null?void 0:t.value,s=(u=document.getElementById(`sesiones_${e}_hora_inicio`))==null?void 0:u.value,i=(v=document.getElementById(`sesiones_${e}_hora_fin`))==null?void 0:v.value,d=(h=E[e])==null?void 0:h.dia,l=document.getElementById(`salas_${e}`),n=document.getElementById(`salas_list_${e}`);if(!(!l||!n)){if(r==="online"||!s||!i||!d){l.classList.add("hidden"),r==="online"&&(n.innerHTML='<span class="text-sm text-gray-600 dark:text-gray-400">Las clases online no requieren sala física</span>',l.classList.remove("hidden"));return}try{n.innerHTML='<span class="text-sm text-gray-600 dark:text-gray-400">Buscando salas disponibles...</span>',l.classList.remove("hidden");const y=new URLSearchParams({dia:d,hora_inicio:s,hora_fin:i,modalidad:r}),m=await(await fetch(`/salas/disponibles?${y}`,{headers:{Accept:"application/json"}})).json();m.salas&&m.salas.length>0?(n.innerHTML=m.salas.map(c=>`
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
            `).join(""),n.querySelectorAll(".sala-btn").forEach(c=>{c.addEventListener("click",()=>{const p=parseInt(c.dataset.index),f=c.dataset.salaId,_=c.dataset.salaName,x=document.getElementById(`sesiones_${p}_room_id`);x&&(x.value=f),l.classList.add("hidden"),L(p,a,o),c.classList.add("ring-2","ring-purple-500"),setTimeout(()=>c.classList.remove("ring-2","ring-purple-500"),1e3),console.log(`✅ Sala seleccionada: ${_}`)})})):n.innerHTML=`
                <div class="col-span-2 text-center p-6">
                    <svg class="w-12 h-12 mx-auto text-yellow-500 mb-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">No hay salas disponibles</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Todas las salas están ocupadas en este horario (${s} - ${i})</p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-2">💡 Prueba cambiando el horario o día</p>
                </div>
            `}catch(y){console.error("Error al obtener salas disponibles:",y),n.innerHTML='<span class="text-sm text-red-600 dark:text-red-400">❌ Error al cargar salas</span>'}}}function T(e,a){const[o,r]=(e||"00:00").split(":").map(Number),s=new Date(2e3,1,1,o,r,0);s.setMinutes(s.getMinutes()+a);const i=d=>d<10?"0"+d:""+d;return`${i(s.getHours())}:${i(s.getMinutes())}`}document.addEventListener("DOMContentLoaded",()=>{const e=document.querySelector('[data-editing="true"]')!==null;if(I=e?2:4,e){document.querySelectorAll(".step-creation-only").forEach(u=>u.style.display="none");const i=document.querySelector('[data-step="4"]');if(i){const u=i.querySelector(".hci-progress-step-number");u&&(u.textContent="2"),i.setAttribute("data-step","2"),i.setAttribute("onclick","navigateToStep(2)")}const d=document.getElementById("total-steps");d&&(d.textContent="2");const l=document.getElementById("current-step");l&&(l.textContent="Paso 1 de 2");const n=document.getElementById("progress-percentage");n&&(n.textContent="50%");const t=document.getElementById("progress-bar");t&&(t.style.height="50%"),window.updateWizardProgressSteps=function(u){document.querySelectorAll('.hci-progress-step-vertical:not([style*="display: none"])').forEach((h,y)=>{const b=y+1;h.classList.remove("completed","active"),b<u?h.classList.add("completed"):b===u&&h.classList.add("active")})}}const a=document.querySelector(".hci-form");a&&a.addEventListener("keydown",s=>{if(s.key==="Enter"&&s.target.tagName!=="TEXTAREA")return s.preventDefault(),!1});const o=document.querySelector(".hci-form-section .hci-field-error, .hci-form-section .text-red-600, .hci-form-section .border-red-500");if(o){const s=o.closest(".hci-form-section");s&&s.dataset.step&&(g=parseInt(s.dataset.step)||1)}if(C(g),q(g),document.querySelectorAll(".hci-input, .hci-select, .hci-textarea, select, input").forEach(s=>s.addEventListener("input",B)),document.addEventListener("change",B),!e){const s=document.getElementById("num_sesiones"),i=document.getElementById("fecha_inicio"),d=document.querySelectorAll('input[name="dias_semana[]"]');[s,i,...d].forEach(l=>{l&&l.addEventListener("change",P)})}B()});
