import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js', 'resources/sass/app.scss'],
            refresh: true,
        }),
        vue(),
    ],
    build: {
        manifest: true,
        outDir: 'public/build', // Ensure the manifest is in public/build
        emptyOutDir: true, // Clear the output directory before building
        rollupOptions: {
            output: {
                assetFileNames: 'assets/[name]-[hash][extname]',
                entryFileNames: 'assets/[name]-[hash].js',
            },
        },
    },
    server: {
        origin: 'http://localhost:3000', // Ensure correct dev server origin
    },
});