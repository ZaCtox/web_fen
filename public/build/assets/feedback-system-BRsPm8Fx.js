class a{constructor(){this.init()}init(){this.setupGlobalNotifications(),this.setupFormValidation(),this.setupLoadingStates(),this.setupConfirmations()}setupGlobalNotifications(){window.showNotification=t=>{const i=t.type||"info",s=t.title||"",e=t.message||"",o=t.actions||[];if(window.toast)return window.toast.show(i,s,e,{duration:t.duration||5e3,actions:o});window.dispatchEvent(new CustomEvent("notification",{detail:{type:i,title:s,message:e,...t}}))},window.showSuccess=(t,i="¡Éxito!")=>{if(window.toast)return window.toast.success(i,t);window.showNotification({type:"success",title:i,message:t})},window.showError=(t,i="Error")=>{if(window.toast)return window.toast.error(i,t);window.showNotification({type:"error",title:i,message:t})},window.showWarning=(t,i="Advertencia")=>{if(window.toast)return window.toast.warning(i,t);window.showNotification({type:"warning",title:i,message:t})},window.showInfo=(t,i="Información")=>{if(window.toast)return window.toast.info(i,t);window.showNotification({type:"info",title:i,message:t})}}setupFormValidation(){document.addEventListener("submit",t=>{const i=t.target;i.classList.contains("hci-form")&&this.validateForm(i)}),document.addEventListener("blur",t=>{const i=t.target;i.classList.contains("hci-field")&&this.validateField(i)},!0)}setupLoadingStates(){document.addEventListener("submit",t=>{const i=t.target;i.classList.contains("hci-form")&&this.showFormLoading(i)}),document.addEventListener("click",t=>{const i=t.target.closest(".hci-loading-button");i&&this.showButtonLoading(i)})}setupConfirmations(){document.addEventListener("click",t=>{const i=t.target.closest(".hci-confirm-button");i&&(t.preventDefault(),this.showConfirmation(i))})}showNotification(t){window.showNotification(t)}validateForm(t){const i=t.querySelectorAll(".hci-field[required]");let s=!0;const e=[];return i.forEach(o=>{this.validateField(o)||(s=!1,e.push(`${o.name||"Campo"} es obligatorio`))}),s||this.showError("Por favor, completa todos los campos obligatorios","Formulario incompleto"),s}validateField(t){const i=t.value.trim(),s=t.dataset.rules?t.dataset.rules.split(","):[];let e=!0;const o=[];return s.forEach(n=>{n==="required"&&!i?(o.push("Este campo es obligatorio"),e=!1):n.startsWith("min:")&&i.length<parseInt(n.split(":")[1])?(o.push(`Mínimo ${n.split(":")[1]} caracteres`),e=!1):n.startsWith("max:")&&i.length>parseInt(n.split(":")[1])?(o.push(`Máximo ${n.split(":")[1]} caracteres`),e=!1):n==="email"&&i&&!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(i)&&(o.push("Formato de email inválido"),e=!1)}),this.showFieldErrors(t,o),e}showFieldErrors(t,i){if(t.parentNode.querySelectorAll(".hci-field-error").forEach(e=>e.remove()),i.length>0){const e=document.createElement("div");e.className="hci-field-error mt-1 space-y-1",i.forEach(o=>{const n=document.createElement("div");n.className="flex items-center text-sm text-red-600 dark:text-red-400",n.innerHTML=`
                    <svg class="h-4 w-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>${o}</span>
                `,e.appendChild(n)}),t.parentNode.appendChild(e)}}showFormLoading(t){const i=t.querySelector('button[type="submit"]');i&&this.showButtonLoading(i),t.querySelectorAll("input, select, textarea, button").forEach(e=>{e.disabled=!0})}showButtonLoading(t){t.textContent;const i=t.innerHTML;t.innerHTML=`
            <div class="flex items-center">
                <div class="hci-spinner w-4 h-4 mr-2"></div>
                <span>Procesando...</span>
            </div>
        `,t.disabled=!0,setTimeout(()=>{t.innerHTML=i,t.disabled=!1},3e3)}showConfirmation(t){const i=t.dataset.action||"realizar esta acción";t.dataset.confirmType;const s=t.dataset.confirmTitle||"Confirmar acción",e=t.dataset.confirmMessage||`¿Estás seguro de que quieres ${i}?`,o=document.createElement("div");o.className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50",o.innerHTML=`
            <div class="hci-lift bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-500 hci-rotate" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">${s}</h3>
                        <div class="mt-2 text-gray-600 dark:text-gray-400">${e}</div>
                    </div>
                </div>
                <div class="mt-6 flex space-x-3 justify-end">
                    <button class="hci-button hci-touch px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500" onclick="this.closest('.fixed').remove()">
                        Cancelar
                    </button>
                    <button class="hci-button hci-touch px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 rounded-md" onclick="
                        this.closest('.fixed').remove();
                        ${t.onclick?t.onclick.toString():"button.click()"}
                    ">
                        Confirmar
                    </button>
                </div>
            </div>
        `,document.body.appendChild(o)}static showToast(t,i="info",s=3e3){window.showNotification({type:i,message:t,duration:s})}static showSuccess(t,i="Éxito"){window.showSuccess(t,i)}static showError(t,i="Error"){window.showError(t,i)}static showWarning(t,i="Advertencia"){window.showWarning(t,i)}static showInfo(t,i="Información"){window.showInfo(t,i)}}document.addEventListener("DOMContentLoaded",()=>{new a});window.FeedbackSystem=a;
