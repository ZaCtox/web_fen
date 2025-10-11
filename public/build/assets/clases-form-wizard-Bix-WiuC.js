let g=1,k=4,E=[];window.nextStep=function(){g<k&&A()&&(g++,C(g),q(g),g===3&&P())};window.prevStep=function(){g>1&&(g--,C(g),q(g))};window.navigateToStep=function(e){(e<=g||A())&&(g=e,C(e),q(e))};window.cancelForm=function(){window.hasUnsavedChanges&&window.hasUnsavedChanges()?window.showUnsavedChangesModal(window.location.origin+"/clases"):window.location.href=window.location.origin+"/clases"};window.submitForm=function(){if(console.log("🚀 submitForm() llamado"),console.log("📍 currentStep:",g,"totalSteps:",k),A()){console.log("✅ Validación pasada");const e=document.querySelector(".hci-form");if(!e){console.error("❌ No se encontró el formulario .hci-form");return}console.log("📋 Formulario encontrado:",e),console.log("📋 Action:",e.action),console.log("📋 Method:",e.method);const s=new FormData(e);console.log("📦 Datos del formulario:");for(let[o,n]of s.entries())console.log(`  ${o}:`,n);if(!document.getElementById("form-loading-overlay")){const o=document.createElement("div");o.id="form-loading-overlay",o.className="loading-overlay",o.innerHTML=`
                <div class="loading-overlay-content">
                    <div class="inline-block w-12 h-12 animate-spin rounded-full border-4 border-solid border-[#4d82bc] border-r-transparent"></div>
                    <p class="text-gray-700 dark:text-gray-300 font-medium">Procesando...</p>
                </div>
            `,document.body.appendChild(o)}console.log("🔄 Enviando formulario..."),e.submit(),console.log("✅ form.submit() ejecutado")}else console.warn("⚠️ Validación falló, no se puede enviar el formulario")};function C(e){document.querySelectorAll(".hci-form-section").forEach(o=>o.classList.remove("active"));const s=document.getElementById(F(e));s&&(s.classList.add("active"),s.scrollIntoView({behavior:"smooth",block:"start"})),window.updateWizardProgressSteps&&window.updateWizardProgressSteps(e)}function q(e){const s=e/k*100,o=document.getElementById("progress-bar");o&&(o.style.height=s+"%");const n=document.getElementById("current-step");n&&(n.textContent=`Paso ${e} de ${k}`);const a=document.getElementById("progress-percentage");a&&(a.textContent=Math.round(s)+"%");const r=document.getElementById("prev-btn"),c=document.getElementById("next-btn"),l=document.getElementById("submit-btn");r&&(r.style.display=e>1?"flex":"none"),c&&l&&(e===k?(c.classList.add("hidden"),l.classList.remove("hidden")):(c.classList.remove("hidden"),l.classList.add("hidden")))}function F(e){return k===2?["general","resumen"][e-1]:["general","config-sesiones","detalles-sesiones","resumen"][e-1]}function A(){console.log("🔍 Validando paso:",g);const e=document.getElementById(F(g));if(!e)return console.log("⚠️ Sección no encontrada para paso",g),!0;const s=e.querySelectorAll("input[required], select[required], textarea[required]");console.log(`📋 Campos requeridos encontrados: ${s.length}`);let o=!0;if(s.forEach(n=>{if(n.type==="checkbox"&&n.name==="dias_semana[]"){const a=document.querySelectorAll('input[name="dias_semana[]"]:checked').length>0;console.log("☑️ Días seleccionados:",a),a||(o=!1,H("Debes seleccionar al menos un día de la semana."));return}n.value.trim()?(console.log(`✅ Campo OK: ${n.name||n.id} = ${n.value}`),N(n)):(console.log(`❌ Campo vacío: ${n.name||n.id}`),o=!1,Z(n))}),g===3){console.log("🔍 Validando sesiones...");const n=X();console.log(`📊 Sesiones válidas: ${n}`),n||(o=!1)}return o?console.log("✅ Validación exitosa para paso",g):(console.log("❌ Validación falló"),H("Completa los campos requeridos.")),o}function H(e){let s=document.getElementById("step-error-message");if(!s){s=document.createElement("div"),s.id="step-error-message",s.className="hci-error-message";const o=document.querySelector(".hci-container");o&&o.insertBefore(s,document.querySelector(".hci-wizard-layout"))}s.innerHTML=`<div class="hci-error-content"><span class="hci-error-icon">⚠️</span><span class="hci-error-text">${e}</span></div>`,setTimeout(()=>s&&s.remove(),4e3)}function Z(e){const s=e.checkValidity(),o=e.closest(".hci-field"),n=o==null?void 0:o.querySelector(".hci-field-error");if(s)N(e);else if(e.classList.add("border-red-500"),e.classList.remove("border-gray-300"),!n){const a=document.createElement("div");a.className="hci-field-error",a.textContent=e.validationMessage||"Campo requerido",o==null||o.appendChild(a)}}function N(e){e.classList.remove("border-red-500"),e.classList.add("border-gray-300");const s=e.closest(".hci-field"),o=s==null?void 0:s.querySelector(".hci-field-error");o&&o.remove()}function P(){var a,r;const e=parseInt((a=document.getElementById("num_sesiones"))==null?void 0:a.value)||0,s=(r=document.getElementById("fecha_inicio"))==null?void 0:r.value,o=Array.from(document.querySelectorAll('input[name="dias_semana[]"]:checked')).map(c=>c.value);if(!e||!s||o.length===0)return;const n=G(s,o,e);E=n,W(n),B()}function G(e,s,o){const n=[];let a=new Date(e+"T00:00:00");const r={Viernes:5,Sábado:6},c=s.map(i=>r[i]).sort();let l=0;for(;l<o;){const i=a.getDay();if(c.includes(i)){const t=Object.keys(r).find(m=>r[m]===i);n.push({fecha:a.toISOString().split("T")[0],dia:t,numero:l+1}),l++}a.setDate(a.getDate()+1)}return n}function W(e){var r,c,l;const s=document.getElementById("sesiones-container");if(!s)return;const o=((r=document.getElementById("room_id"))==null?void 0:r.value)||"",n=((c=document.getElementById("url_zoom"))==null?void 0:c.value)||"",a=((l=document.getElementById("period_id"))==null?void 0:l.value)||"";s.innerHTML=e.map((i,t)=>`
        <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg p-6" data-sesion-index="${t}">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Sesión #${i.numero} - ${i.dia} ${U(i.fecha)}
                </h4>
                <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                    ${i.dia}
                </span>
            </div>
            
            <input type="hidden" name="sesiones[${t}][fecha]" value="${i.fecha}">
            <input type="hidden" name="sesiones[${t}][dia]" value="${i.dia}">
            <input type="hidden" name="sesiones[${t}][estado]" value="pendiente">
            <input type="hidden" name="sesiones[${t}][numero_sesion]" value="${i.numero}">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="hci-field">
                    <label class="hci-label" for="sesiones_${t}_hora_inicio">
                        Hora Inicio <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="sesiones[${t}][hora_inicio]" id="sesiones_${t}_hora_inicio" 
                           class="hci-input" required
                           value="${i.dia==="Viernes"?"18:30":"09:00"}">
                </div>
                
                <div class="hci-field">
                    <label class="hci-label" for="sesiones_${t}_hora_fin">
                        Hora Fin <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="sesiones[${t}][hora_fin]" id="sesiones_${t}_hora_fin" 
                           class="hci-input" required
                           value="${i.dia==="Viernes"?"21:30":"14:00"}">
                </div>
                
                <div class="hci-field">
                    <label class="hci-label" for="sesiones_${t}_modalidad">
                        Modalidad <span class="text-red-500">*</span>
                    </label>
                    <select name="sesiones[${t}][modalidad]" id="sesiones_${t}_modalidad" 
                            class="hci-select sesion-modalidad" data-index="${t}" required>
                        <option value="">-- Seleccionar --</option>
                        <option value="presencial">Presencial</option>
                        <option value="hibrida" ${i.dia==="Sábado"?"selected":""}>Híbrida</option>
                        <option value="online" ${i.dia==="Viernes"?"selected":""}>Online</option>
                    </select>
                </div>
                
                <div class="hci-field sesion-room-field" data-index="${t}" style="display: ${i.dia==="Viernes"?"none":"block"};">
                    <label class="hci-label" for="sesiones_${t}_room_id">
                        Sala ${i.dia==="Sábado"||i.dia==="Viernes"?"":'<span class="text-red-500">*</span>'}
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
                        URL Zoom ${i.dia==="Viernes"?'<span class="text-red-500">*</span>':""}
                    </label>
                    <input type="url" name="sesiones[${t}][url_zoom]" id="sesiones_${t}_url_zoom" 
                           class="hci-input sesion-zoom-input" placeholder="https://zoom.us/j/..."
                           value="${n}">
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
    `).join(""),s.querySelectorAll(".sesion-modalidad").forEach(i=>{i.addEventListener("change",t=>{const m=t.target.dataset.index;j(m),L(m,a,o)})}),e.forEach((i,t)=>{const m=document.getElementById(`sesiones_${t}_hora_inicio`),f=document.getElementById(`sesiones_${t}_hora_fin`),v=document.getElementById(`sesiones_${t}_room_id`);m&&m.addEventListener("change",()=>L(t,a,o)),f&&f.addEventListener("change",()=>L(t,a,o)),v&&v.addEventListener("change",()=>L(t,a,o)),j(t),L(t,a,o)}),s.querySelectorAll(".btn-ver-horarios").forEach(i=>{i.addEventListener("click",()=>{const t=parseInt(i.dataset.index);J(t,a,o)})}),s.querySelectorAll(".btn-ver-salas").forEach(i=>{i.addEventListener("click",()=>{const t=parseInt(i.dataset.index);Q(t,a,o)})})}function K(e){const s=document.getElementById("room_id");return s?Array.from(s.options).filter(o=>o.value!=="").map(o=>`<option value="${o.value}" ${o.value===e?"selected":""}>${o.text}</option>`).join(""):""}function j(e){var c,l,i;const s=document.getElementById(`sesiones_${e}_modalidad`),o=s==null?void 0:s.value,n=document.querySelector(`.sesion-room-field[data-index="${e}"]`),a=document.getElementById(`sesiones_${e}_room_id`),r=document.getElementById(`sesiones_${e}_url_zoom`);if(o==="online"){if(n&&(n.style.display="none"),a&&a.removeAttribute("required"),r){r.setAttribute("required","required");const t=(c=r.closest(".hci-field"))==null?void 0:c.querySelector("label");t&&(t.innerHTML='URL Zoom <span class="text-red-500">*</span>')}}else if(o==="hibrida"){if(n&&(n.style.display="block"),a&&a.removeAttribute("required"),r){r.removeAttribute("required");const t=(l=r.closest(".hci-field"))==null?void 0:l.querySelector("label");t&&(t.innerHTML="URL Zoom")}}else if(o==="presencial"&&(n&&(n.style.display="block"),a&&a.removeAttribute("required"),r)){r.removeAttribute("required");const t=(i=r.closest(".hci-field"))==null?void 0:i.querySelector("label");t&&(t.innerHTML="URL Zoom")}}function X(){let e=!0;return E.forEach((s,o)=>{var c,l,i;const n=(c=document.getElementById(`sesiones_${o}_hora_inicio`))==null?void 0:c.value,a=(l=document.getElementById(`sesiones_${o}_hora_fin`))==null?void 0:l.value,r=(i=document.getElementById(`sesiones_${o}_modalidad`))==null?void 0:i.value;(!n||!a||!r)&&(e=!1),n&&a&&n>=a&&(e=!1,H(`Sesión ${o+1}: La hora fin debe ser posterior a la hora inicio`))}),e}function U(e){return new Date(e+"T00:00:00").toLocaleDateString("es-CL",{day:"2-digit",month:"2-digit",year:"numeric"})}function B(){var v,y,_,u;const e=d=>{var p,h,b;return((b=(h=(p=d==null?void 0:d.options)==null?void 0:p[d.selectedIndex])==null?void 0:h.textContent)==null?void 0:b.trim())||""},s=e(document.getElementById("magister")),o=e(document.getElementById("course_id")),n=((v=document.getElementById("anio"))==null?void 0:v.value)||"",a=((y=document.getElementById("trimestre"))==null?void 0:y.value)||"",r=n&&a?`${n} - Trimestre ${a}`:"—",c=((_=document.getElementById("encargado"))==null?void 0:_.value)||"",l=e(document.getElementById("room_id"))||"Sin sala asignada",i=((u=document.getElementById("url_zoom"))==null?void 0:u.value)||"No asignado",t=d=>document.getElementById(d);t("resumen-programa")&&(t("resumen-programa").textContent=s||"—"),t("resumen-curso")&&(t("resumen-curso").textContent=o||"—"),t("resumen-periodo")&&(t("resumen-periodo").textContent=r),t("resumen-encargado")&&(t("resumen-encargado").textContent=c||"—"),t("resumen-sala-principal")&&(t("resumen-sala-principal").textContent=l),t("resumen-zoom-principal")&&(t("resumen-zoom-principal").textContent=i);const m=t("resumen-total-sesiones");m&&(m.textContent=E.length);const f=t("resumen-sesiones-lista");f&&E.length>0&&(f.innerHTML=E.map((d,p)=>{var M,S,I;const h=((M=document.getElementById(`sesiones_${p}_hora_inicio`))==null?void 0:M.value)||"—",b=((S=document.getElementById(`sesiones_${p}_hora_fin`))==null?void 0:S.value)||"—",x=((I=document.getElementById(`sesiones_${p}_modalidad`))==null?void 0:I.value)||"—",w={online:"bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200",presencial:"bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200",hibrida:"bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-200"}[x]||"bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200";return`
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/30 rounded border border-gray-200 dark:border-gray-600">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            Sesión ${d.numero} - ${d.dia} ${U(d.fecha)}
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            ${h} - ${b}
                        </p>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded ${w}">
                        ${x.charAt(0).toUpperCase()+x.slice(1)}
                    </span>
                </div>
            `}).join(""))}let z={};async function L(e,s,o){var u,d,p,h,b,x;const n=(u=document.getElementById(`sesiones_${e}_modalidad`))==null?void 0:u.value,a=(d=document.getElementById(`sesiones_${e}_hora_inicio`))==null?void 0:d.value,r=(p=document.getElementById(`sesiones_${e}_hora_fin`))==null?void 0:p.value,l=((h=document.getElementById(`sesiones_${e}_room_id`))==null?void 0:h.value)||o,i=(b=E[e])==null?void 0:b.fecha,t=(x=E[e])==null?void 0:x.dia,m=document.getElementById(`disponibilidad_${e}`);if(!m)return;const f=m.querySelector(".disponibilidad-loading"),v=m.querySelector(".disponibilidad-disponible"),y=m.querySelector(".disponibilidad-ocupada"),_=()=>{m.classList.add("hidden"),f.classList.add("hidden"),v.classList.add("hidden"),y.classList.add("hidden")};if(n==="online"||!a||!r||!l||!s||!i){_();return}z[e]&&clearTimeout(z[e]),z[e]=setTimeout(async()=>{try{m.classList.remove("hidden"),_(),m.classList.remove("hidden"),f.classList.remove("hidden");const w=new URLSearchParams({period_id:s,room_id:l,dia:t,hora_inicio:a,hora_fin:r,modality:n}),S=await(await fetch(`/salas/disponibilidad?${w}`,{headers:{Accept:"application/json"}})).json();if(f.classList.add("hidden"),S.available)v.classList.remove("hidden");else{y.classList.remove("hidden");const I=y.querySelector(".disponibilidad-conflictos");if(I&&S.conflicts&&S.conflicts.length>0){const R=S.conflicts.map($=>{const O=$.modalidad==="online"?"🌐":$.modalidad==="hibrida"?"🔀":"🏢";let V=$.fecha;try{V=new Date($.fecha).toLocaleDateString("es-CL",{year:"numeric",month:"2-digit",day:"2-digit"})}catch(D){console.error("Error formateando fecha:",D)}return`
                            <div class="mt-2 text-xs">
                                ${O} <strong>${$.course_nombre}</strong><br>
                                👤 ${$.encargado}<br>
                                🕐 ${$.hora_inicio} - ${$.hora_fin} (${$.dia})<br>
                                📅 Sesión del ${V}
                            </div>
                        `}).join('<hr class="my-2 border-red-200 dark:border-red-700">');I.innerHTML=`<strong>⚠️ Conflicto de horario detectado:</strong>${R}
                        <p class="mt-2 text-xs italic">Esta sala ya tiene una clase programada en el mismo día y horario.</p>`}else I.textContent="Hay un conflicto de horario en esta sala"}}catch(w){console.error("Error al verificar disponibilidad:",w),_()}},500)}async function J(e,s,o){var t,m,f;const n=(t=document.getElementById(`sesiones_${e}_modalidad`))==null?void 0:t.value,r=((m=document.getElementById(`sesiones_${e}_room_id`))==null?void 0:m.value)||o,c=(f=E[e])==null?void 0:f.dia,l=document.getElementById(`horarios_${e}`),i=document.getElementById(`slots_${e}`);if(!(!l||!i)){if(n==="online"||!r||!c){l.classList.add("hidden");return}try{i.innerHTML='<span class="text-sm text-gray-600 dark:text-gray-400">Buscando horarios disponibles...</span>',l.classList.remove("hidden");const v=new URLSearchParams({period_id:s,room_id:r,dia:c,modality:n,desde:"08:00",hasta:"22:00",min_block:60,blocks:1}),_=await(await fetch(`/salas/horarios?${v}`,{headers:{Accept:"application/json"}})).json();_.slots&&_.slots.length>0?(i.innerHTML=_.slots.map(u=>{const d=[];let p=u.start;for(;T(p,60)<=u.end;){const h=p,b=T(p,180);b<=u.end&&d.push({inicio:h,fin:b}),p=T(p,15)}return d.map(h=>`
                    <button type="button"
                            class="slot-btn px-3 py-2 text-sm font-medium text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/40 transition-colors"
                            data-index="${e}"
                            data-inicio="${h.inicio}"
                            data-fin="${h.fin}"
                            title="Haz clic para usar este horario">
                        🕐 ${h.inicio} - ${h.fin}
                    </button>
                `).join("")}).join(""),i.querySelectorAll(".slot-btn").forEach(u=>{u.addEventListener("click",()=>{const d=parseInt(u.dataset.index),p=u.dataset.inicio,h=u.dataset.fin;document.getElementById(`sesiones_${d}_hora_inicio`).value=p,document.getElementById(`sesiones_${d}_hora_fin`).value=h,l.classList.add("hidden"),L(d,s,o),u.classList.add("ring-2","ring-green-500"),setTimeout(()=>u.classList.remove("ring-2","ring-green-500"),1e3)})})):i.innerHTML='<span class="text-sm text-yellow-600 dark:text-yellow-400">⚠️ No hay horarios disponibles con estas características</span>'}catch(v){console.error("Error al obtener horarios disponibles:",v),i.innerHTML='<span class="text-sm text-red-600 dark:text-red-400">❌ Error al cargar horarios</span>'}}}async function Q(e,s,o){var t,m,f,v;const n=(t=document.getElementById(`sesiones_${e}_modalidad`))==null?void 0:t.value,a=(m=document.getElementById(`sesiones_${e}_hora_inicio`))==null?void 0:m.value,r=(f=document.getElementById(`sesiones_${e}_hora_fin`))==null?void 0:f.value,c=(v=E[e])==null?void 0:v.dia,l=document.getElementById(`salas_${e}`),i=document.getElementById(`salas_list_${e}`);if(!(!l||!i)){if(n==="online"||!a||!r||!c){l.classList.add("hidden"),n==="online"&&(i.innerHTML='<span class="text-sm text-gray-600 dark:text-gray-400">Las clases online no requieren sala física</span>',l.classList.remove("hidden"));return}try{i.innerHTML='<span class="text-sm text-gray-600 dark:text-gray-400">Buscando salas disponibles...</span>',l.classList.remove("hidden");const y=new URLSearchParams({dia:c,hora_inicio:a,hora_fin:r,modalidad:n}),u=await(await fetch(`/salas/disponibles?${y}`,{headers:{Accept:"application/json"}})).json();u.salas&&u.salas.length>0?(i.innerHTML=u.salas.map(d=>`
                <button type="button"
                        class="sala-btn text-left p-3 bg-white dark:bg-gray-800 border-2 border-purple-200 dark:border-purple-700 rounded-lg hover:border-purple-400 dark:hover:border-purple-500 hover:shadow-md transition-all"
                        data-index="${e}"
                        data-sala-id="${d.id}"
                        data-sala-name="${d.name}"
                        title="Haz clic para seleccionar esta sala">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="font-semibold text-purple-900 dark:text-purple-100">${d.name}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                📍 ${d.location}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                👥 Capacidad: ${d.capacity} personas
                            </p>
                        </div>
                        <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </button>
            `).join(""),i.querySelectorAll(".sala-btn").forEach(d=>{d.addEventListener("click",()=>{const p=parseInt(d.dataset.index),h=d.dataset.salaId,b=d.dataset.salaName,x=document.getElementById(`sesiones_${p}_room_id`);x&&(x.value=h),l.classList.add("hidden"),L(p,s,o),d.classList.add("ring-2","ring-purple-500"),setTimeout(()=>d.classList.remove("ring-2","ring-purple-500"),1e3),console.log(`✅ Sala seleccionada: ${b}`)})})):i.innerHTML=`
                <div class="col-span-2 text-center p-6">
                    <svg class="w-12 h-12 mx-auto text-yellow-500 mb-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">No hay salas disponibles</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Todas las salas están ocupadas en este horario (${a} - ${r})</p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-2">💡 Prueba cambiando el horario o día</p>
                </div>
            `}catch(y){console.error("Error al obtener salas disponibles:",y),i.innerHTML='<span class="text-sm text-red-600 dark:text-red-400">❌ Error al cargar salas</span>'}}}function T(e,s){const[o,n]=(e||"00:00").split(":").map(Number),a=new Date(2e3,1,1,o,n,0);a.setMinutes(a.getMinutes()+s);const r=c=>c<10?"0"+c:""+c;return`${r(a.getHours())}:${r(a.getMinutes())}`}document.addEventListener("DOMContentLoaded",()=>{const e=document.querySelector('[data-editing="true"]')!==null;k=e?2:4;const s=document.querySelector(".hci-form");s&&s.addEventListener("keydown",a=>{if(a.key==="Enter"&&a.target.tagName!=="TEXTAREA")return a.preventDefault(),!1});const o=document.querySelector(".hci-form-section .hci-field-error, .hci-form-section .text-red-600, .hci-form-section .border-red-500");if(o){const a=o.closest(".hci-form-section");a&&a.dataset.step&&(g=parseInt(a.dataset.step)||1)}if(C(g),q(g),document.querySelectorAll(".hci-input, .hci-select, .hci-textarea, select, input").forEach(a=>a.addEventListener("input",B)),document.addEventListener("change",B),!e){const a=document.getElementById("num_sesiones"),r=document.getElementById("fecha_inicio"),c=document.querySelectorAll('input[name="dias_semana[]"]');[a,r,...c].forEach(l=>{l&&l.addEventListener("change",P)})}B()});
