import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/calendar-public.js',
                'resources/js/calendar-admin.js',
                'resources/js/courses/form.js',
                'resources/js/alerts.js',
                'resources/js/clases/form.js',
            ],
            refresh: true,
        }),
    ],
})
