const mix = require('laravel-mix');
mix.webpackConfig({
    stats: {
        children: true,
    },});

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');
mix.copyDirectory('resources/public', 'public');
mix.copyDirectory('resources/img', 'public/img');
mix.copyDirectory('resources/css', 'public/css');
mix.copyDirectory('node_modules/tom-select/dist/css', 'public/plugins/tom-select/css' )
mix.copyDirectory('node_modules/tom-select/dist/js', 'public/plugins/tom-select/js' )
mix.copy('node_modules/vanilla-datetimerange-picker/dist/vanilla-datetimerange-picker.css', 'public/plugins/vanilla-datetimerange-picker/vanilla-datetimerange-picker.css');
mix.copy('node_modules/vanilla-datetimerange-picker/dist/vanilla-datetimerange-picker.js', 'public/plugins/vanilla-datetimerange-picker/vanilla-datetimerange-picker.js');
mix.copy('node_modules/chart.js/dist/chart.umd.js', 'public/plugins/chart.js/chart.umd.js');
mix.copy('node_modules/moment/moment.js', 'public/plugins/moment/moment.js');
mix.copy('node_modules/moment/min/moment.min.js', 'public/plugins/moment/moment.min.js');
mix.copy('node_modules/alpinejs/dist/cdn.min.js', 'public/plugins/alpinejs/cdn.min.js');
mix.copyDirectory('node_modules/bootstrap-icons', 'public/plugins/bootstrap-icons');
