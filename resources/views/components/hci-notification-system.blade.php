@props([])

<div 
    x-data="{
        notifications: [],
        addNotification(notification) {
            const id = Date.now() + Math.random();
            const newNotification = {
                id,
                type: notification.type || 'info',
                title: notification.title || '',
                message: notification.message || '',
                duration: notification.duration || 5000,
                dismissible: notification.dismissible !== false,
                ...notification
            };
            
            this.notifications.push(newNotification);
            
            if (newNotification.duration > 0) {
                setTimeout(() => {
                    this.removeNotification(id);
                }, newNotification.duration);
            }
        },
        
        removeNotification(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        },
        
        clearAll() {
            this.notifications = [];
        }
    }"
    @notification.window="addNotification($event.detail)"
    class="fixed top-4 right-4 z-50 space-y-2 max-w-sm w-full"
>
    <template x-for="notification in notifications" :key="notification.id">
        <div 
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-full"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-full"
            class="hci-lift bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-4"
        >
            <div class="flex items-start">
                <!-- Icon -->
                <div class="flex-shrink-0">
                    <svg 
                        x-show="notification.type === 'success'"
                        class="h-5 w-5 text-green-500 hci-rotate" 
                        fill="currentColor" 
                        viewBox="0 0 20 20"
                    >
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <svg 
                        x-show="notification.type === 'error'"
                        class="h-5 w-5 text-red-500 hci-rotate" 
                        fill="currentColor" 
                        viewBox="0 0 20 20"
                    >
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <svg 
                        x-show="notification.type === 'warning'"
                        class="h-5 w-5 text-yellow-500 hci-rotate" 
                        fill="currentColor" 
                        viewBox="0 0 20 20"
                    >
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <svg 
                        x-show="notification.type === 'info'"
                        class="h-5 w-5 text-blue-500 hci-rotate" 
                        fill="currentColor" 
                        viewBox="0 0 20 20"
                    >
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>

                <!-- Content -->
                <div class="ml-3 flex-1">
                    <h4 x-show="notification.title" class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="notification.title"></h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400" x-text="notification.message"></p>
                </div>

                <!-- Close button -->
                <div x-show="notification.dismissible" class="ml-3 flex-shrink-0">
                    <button 
                        @click="removeNotification(notification.id)"
                        class="hci-button hci-touch text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 rounded-md p-1"
                    >
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- Clear all button (when there are notifications) -->
    <div 
        x-show="notifications.length > 1" 
        class="flex justify-end"
    >
        <button 
            @click="clearAll()"
            class="hci-button hci-touch text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 rounded-md px-2 py-1"
        >
            Limpiar todo
        </button>
    </div>
</div>



