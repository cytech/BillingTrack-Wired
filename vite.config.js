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
                    dest: '../plugins/tom-select/css'
                },
                {
                    src: 'node_modules/tom-select/dist/js/*',
                    dest: '../plugins/tom-select/js'
                },
                {
                    src: 'node_modules/vanilla-datetimerange-picker/dist/vanilla-datetimerange-picker.css',
                    dest: '../plugins/vanilla-datetimerange-picker'
                },
                {
                    src: 'node_modules/vanilla-datetimerange-picker/dist/vanilla-datetimerange-picker.js',
                    dest: '../plugins/vanilla-datetimerange-picker'
                },
                {
                    src: 'node_modules/chart.js/dist/chart.umd.js',
                    dest: '../plugins/chart.js'
                },
                {
                    src: 'node_modules/moment/moment.js',
                    dest: '../plugins/moment'
                },
                {
                    src: 'node_modules/moment/min/moment.min.js',
                    dest: '../plugins/moment'
                },
                {
                    src: 'node_modules/alpinejs/dist/cdn.min.js',
                    dest: '../plugins/alpinejs'
                },
                {
                    src: 'node_modules/bootstrap-icons/*',
                    dest: '../plugins/bootstrap-icons'
                },
                {
                    src: 'resources/img',
                    dest: '../'
                },
                {
                    src: 'resources/public/*',
                    dest: '../'
                },

            ]
        })
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '~@fortawesome': path.resolve(__dirname, 'node_modules/@fortawesome'),
            '~admin-lte-v4': path.resolve(__dirname, 'node_modules/admin-lte-v4'),
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
    }
})
