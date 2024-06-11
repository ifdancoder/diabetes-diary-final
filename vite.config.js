import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/tabler.min.css', 'resources/css/tabler-flags.min.css', 'resources/css/tabler-payments.min.css', 'resources/css/tabler-vendors.min.css', 'resources/css/demo.min.css', 'resources/css/dropzone.min.css', 'resources/css/tabler.min.css', 'resources/css/tabler.min.css', 'resources/css/tabler.min.css', 'resources/css/tabler.min.css', 'resources/js/tabler.min.js', 'resources/js/demo.min.js', 'resources/js/autosize.js', 'resources/libs/apexcharts/dist/apexcharts.min.js', 'resources/libs/jsvectormap/dist/js/jsvectormap.min.js', 'resources/libs/jsvectormap/dist/maps/world.js', 'resources/libs/jsvectormap/dist/maps/world-merc.js', 'resources/libs/nouislider/dist/nouislider.min.js', 'resources/libs/litepicker/dist/litepicker.js', 'resources/libs/fslightbox/index.js', 'resources/libs/fslightbox/index.js', 'resources/libs/tom-select/dist/js/tom-select.base.min.js', 'resources/css/app.css', 'resources/js/app.js', 'resources/js/demo-theme.min.js'],
            refresh: true,
        }),
    ],
});
