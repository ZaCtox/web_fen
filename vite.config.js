import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/hci-principles.css',
                'resources/css/microinteractions.css',
                'resources/js/app.js',
                'resources/js/calendar-public.js',
                'resources/js/calendar-admin.js',
                'resources/js/courses/form.js',
                'resources/js/alerts.js',
                'resources/js/clases/form.js',
                'resources/js/hci-principles.js',
                'resources/js/staff-form-wizard.js',
                'resources/js/emergency-form-wizard.js',
                'resources/js/courses-form-wizard.js',
                'resources/js/magisters-form-wizard.js',
                'resources/js/rooms-form-wizard.js',
                'resources/js/clases-form-wizard.js',
                'resources/js/periods-form-wizard.js',
                'resources/js/informes-form-wizard.js',
                'resources/js/incidencias-form-wizard.js',
                'resources/js/usuarios-form-wizard.js',
                'resources/js/novedades-form-wizard.js',
                'resources/js/daily-reports-form-wizard.js',
                'resources/js/microinteractions.js',
                'resources/js/feedback-system.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
            'cropperjs': '/node_modules/cropperjs', // ✅ alias agregado para que Vite lo resuelva correctamente
        },
    },
})
