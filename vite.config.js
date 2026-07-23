import { sentryVitePlugin } from "@sentry/vite-plugin";
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [laravel({
        input: [
            'resources/css/app.css',
            'resources/css/custom.css',
            'resources/js/app.js',
            'resources/js/page/words/create-new.js',
            'resources/js/page/exercise/exercise.js',
            'resources/js/page/auth/auth-form.js',
            'resources/js/page/auth-register.js',
            'resources/js/page/features-post-create.js',
            'resources/js/page/features-posts.js',
            'resources/js/page/modules-toastr.js',
            'resources/js/page/wordgroups/grouping-page.js',
            'resources/js/utils/storage-helper.js',
        ],
        refresh: true,
    }), tailwindcss(), sentryVitePlugin({
        org: "al-fuadi-learning-center",
        project: "al-fuadi-web"
    })],

    build: {
        sourcemap: true
    }
});
