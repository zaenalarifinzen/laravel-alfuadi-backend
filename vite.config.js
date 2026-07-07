import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/page/words/create-new.js',
                'resources/js/page/exercise/exercise.js',
                'resources/js/utils/storage-helper.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
