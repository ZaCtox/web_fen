@props([
    'field' => '',
    'rules' => [],
    'realTime' => true,
    'showIcon' => true
])

<div 
    x-data="{
        field: @js($field),
        rules: @js($rules),
        realTime: @js($realTime),
        errors: [],
        isValid: true,
        isDirty: false,
        
        init() {
            if (this.realTime) {
                this.$watch('$store.form.data.' + this.field, () => {
                    this.validate();
                });
            }
        },
        
        validate() {
            this.errors = [];
            const value = this.$store?.form?.data?.[this.field] || '';
            
            this.rules.forEach(rule => {
                if (rule === 'required' && !value.trim()) {
                    this.errors.push('Este campo es obligatorio');
                } else if (rule.startsWith('min:') && value.length < parseInt(rule.split(':')[1])) {
                    this.errors.push(`Mínimo ${rule.split(':')[1]} caracteres`);
                } else if (rule.startsWith('max:') && value.length > parseInt(rule.split(':')[1])) {
                    this.errors.push(`Máximo ${rule.split(':')[1]} caracteres`);
                } else if (rule === 'email' && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    this.errors.push('Formato de email inválido');
                } else if (rule.startsWith('regex:') && value && !new RegExp(rule.split(':')[1]).test(value)) {
                    this.errors.push('Formato inválido');
                }
            });
            
            this.isValid = this.errors.length === 0;
            this.isDirty = true;
        }
    }"
    class="hci-validation"
>
    <!-- Error messages -->
    <div 
        x-show="isDirty && !isValid" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-1"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        class="mt-1 space-y-1"
    >
        <template x-for="error in errors" :key="error">
            <div class="flex items-center text-sm text-red-600 dark:text-red-400">
                @if($showIcon)
                    <svg class="h-4 w-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                @endif
                <span x-text="error"></span>
            </div>
        </template>
    </div>

    <!-- Success indicator -->
    <div 
        x-show="isDirty && isValid" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-1"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        class="mt-1 flex items-center text-sm text-green-600 dark:text-green-400"
    >
        @if($showIcon)
            <svg class="h-4 w-4 mr-1 flex-shrink-0 hci-rotate" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        @endif
        <span>Campo válido</span>
    </div>
</div>
