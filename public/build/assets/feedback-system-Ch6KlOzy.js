class n{constructor(){this.init()}init(){this.setupGlobalNotifications(),this.setupFormValidation(),this.setupLoadingStates(),this.setupConfirmations()}setupGlobalNotifications(){window.showNotification=t=>{const e={type:t.type||"info",title:t.title||"",message:t.message||"",duration:t.duration||5e3,dismissible:t.dismissible!==!1,...t};window.dispatchEvent(new CustomEvent("notification",{detail:e}))},window.showSuccess=(t,e="Éxito")=>{this.showNotification({type:"success",title:e,message:t})},window.showError=(t,e="Error")=>{this.showNotification({type:"error",title:e,message:t})},window.showWarning=(t,e="Advertencia")=>{this.showNotification({type:"warning",title:e,message:t})},window.showInfo=(t,e="Información")=>{this.showNotification({type:"info",title:e,message:t})}}setupFormValidation(){document.addEventListener("submit",t=>{const e=t.target;e.classList.contains("hci-form")&&this.validateForm(e)}),document.addEventListener("blur",t=>{const e=t.target;e.classList.contains("hci-field")&&this.validateField(e)},!0)}setupLoadingStates(){document.addEventListener("submit",t=>{const e=t.target;e.classList.contains("hci-form")&&this.showFormLoading(e)}),document.addEventListener("click",t=>{const e=t.target.closest(".hci-loading-button");e&&this.showButtonLoading(e)})}setupConfirmations(){document.addEventListener("click",t=>{const e=t.target.closest(".hci-confirm-button");e&&(t.preventDefault(),this.showConfirmation(e))})}showNotification(t){window.showNotification(t)}validateForm(t){const e=t.querySelectorAll(".hci-field[required]");let a=!0;const i=[];return e.forEach(s=>{this.validateField(s)||(a=!1,i.push(`${s.name||"Campo"} es obligatorio`))}),a||this.showError("Por favor, completa todos los campos obligatorios","Formulario incompleto"),a}validateField(t){const e=t.value.trim(),a=t.dataset.rules?t.dataset.rules.split(","):[];let i=!0;const s=[];return a.forEach(o=>{o==="required"&&!e?(s.push("Este campo es obligatorio"),i=!1):o.startsWith("min:")&&e.length<parseInt(o.split(":")[1])?(s.push(`Mínimo ${o.split(":")[1]} caracteres`),i=!1):o.startsWith("max:")&&e.length>parseInt(o.split(":")[1])?(s.push(`Máximo ${o.split(":")[1]} caracteres`),i=!1):o==="email"&&e&&!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e)&&(s.push("Formato de email inválido"),i=!1)}),this.showFieldErrors(t,s),i}showFieldErrors(t,e){if(t.parentNode.querySelectorAll(".hci-field-error").forEach(i=>i.remove()),e.length>0){const i=document.createElement("div");i.className="hci-field-error mt-1 space-y-1",e.forEach(s=>{const o=document.createElement("div");o.className="flex items-center text-sm text-red-600 dark:text-red-400",o.innerHTML=`
                    <svg class="h-4 w-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>${s}</span>
                `,i.appendChild(o)}),t.parentNode.appendChild(i)}}showFormLoading(t){const e=t.querySelector('button[type="submit"]');e&&this.showButtonLoading(e),t.querySelectorAll("input, select, textarea, button").forEach(i=>{i.disabled=!0})}showButtonLoading(t){t.textContent;const e=t.innerHTML;t.innerHTML=`
            <div class="flex items-center">
                <div class="hci-spinner w-4 h-4 mr-2"></div>
                <span>Procesando...</span>
            </div>
        `,t.disabled=!0,setTimeout(()=>{t.innerHTML=e,t.disabled=!1},3e3)}showConfirmation(t){const e=t.dataset.action||"realizar esta acción";t.dataset.confirmType;const a=t.dataset.confirmTitle||"Confirmar acción",i=t.dataset.confirmMessage||`¿Estás seguro de que quieres ${e}?`,s=document.createElement("div");s.className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50",s.innerHTML=`
            <div class="hci-lift bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-500 hci-rotate" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">${a}</h3>
                        <div class="mt-2 text-gray-600 dark:text-gray-400">${i}</div>
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
        `,document.body.appendChild(s)}static showToast(t,e="info",a=3e3){window.showNotification({type:e,message:t,duration:a})}static showSuccess(t,e="Éxito"){window.showSuccess(t,e)}static showError(t,e="Error"){window.showError(t,e)}static showWarning(t,e="Advertencia"){window.showWarning(t,e)}static showInfo(t,e="Información"){window.showInfo(t,e)}}document.addEventListener("DOMContentLoaded",()=>{new n});window.FeedbackSystem=n;
