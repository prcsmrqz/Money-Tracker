import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    base: process.env.VITE_URL, // ensures assets point to your deployed URL
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
