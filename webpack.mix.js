const process = require('process');
const mix = require('laravel-mix');
require('laravel-mix-tailwind');
require('laravel-mix-purgecss');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .tailwind('tailwind.config.js')
    .purgeCss()
    .browserSync(process.env.APP_URL);
