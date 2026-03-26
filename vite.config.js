import {defineConfig} from 'vite'
import laravel from 'laravel-vite-plugin'
import path from 'path'
import {viteStaticCopy} from 'vite-plugin-static-copy'

export default defineConfig({
    plugins: [
        laravel([
            'resources/js/app.js',
        ]),
        viteStaticCopy({
            targets: [
                {
                    src: 'node_modules/tom-select/dist/css/*',
                    dest: '../plugins/tom-select/css',
                    rename: { stripBase: true },
                },
                {
                    src: 'node_modules/tom-select/dist/js/*',
                    dest: '../plugins/tom-select/js',
                    rename: { stripBase: true },
                },
                {
                    src: 'node_modules/vanilla-datetimerange-picker/dist/vanilla-datetimerange-picker.css',
                    dest: '../plugins/vanilla-datetimerange-picker',
                    rename: { stripBase: true },
                },
                {
                    src: 'node_modules/vanilla-datetimerange-picker/dist/vanilla-datetimerange-picker.js',
                    dest: '../plugins/vanilla-datetimerange-picker',
                    rename: { stripBase: true },
                },
                {
                    src: 'node_modules/chart.js/dist/chart.umd.js',
                    dest: '../plugins/chart.js',
                    rename: { stripBase: true },
                },
                {
                    src: 'node_modules/moment/moment.js',
                    dest: '../plugins/moment',
                    rename: { stripBase: true },
                },
                {
                    src: 'node_modules/moment/min/moment.min.js',
                    dest: '../plugins/moment',
                    rename: { stripBase: true },
                },
                {
                    src: 'node_modules/bootstrap-icons/*',
                    dest: '../plugins/bootstrap-icons',
                    rename: { stripBase: true },
                },
                {
                    src: 'resources/img/credit/*',
                    dest: '../img/credit',
                    rename: { stripBase: true },
                },
                {
                    src: 'resources/img/documentation/*',
                    dest: '../img/documentation',
                    rename: { stripBase: true },
                },
                {
                    src: 'resources/img/*.png',
                    dest: '../img',
                    rename: { stripBase: true },
                },
                {
                    src: 'resources/img/*.svg',
                    dest: '../img',
                    rename: { stripBase: true },
                },
                {
                    src: 'resources/public/*',
                    dest: '../',
                    rename: { stripBase: true },
                },
                {
                    src: 'node_modules/@fortawesome/fontawesome-free/webfonts/*',
                    dest: '../build/webfonts/',
                    rename: { stripBase: true },
                },

            ]
        })
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '~@fortawesome': path.resolve(__dirname, 'node_modules/@fortawesome'),
            '~admin-lte': path.resolve(__dirname, 'node_modules/admin-lte'),
            '~flatpickr': path.resolve(__dirname, 'node_modules/flatpickr'),
            '~tippy.js': path.resolve(__dirname, 'node_modules/tippy.js'),
        }
    },
    build: {
        rollupOptions: {
            output: {
                entryFileNames: `assets/[name].js`,
                chunkFileNames: `assets/[name].js`,
                assetFileNames: `assets/[name].[ext]`
            }
        }
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
})
