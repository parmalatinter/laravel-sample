const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js').vue()
    .js('resources/js/views/main.js', 'public/js/views/main.js').vue()
    .js('resources/js/views/login.js', 'public/js/views/login.js').vue()
    .js('resources/js/views/products.js', 'public/js/views/products.js').vue()
    .js('resources/js/views/items.js', 'public/js/views/items.js').vue()
    .sass('resources/sass/app.scss', 'public/css')

mix.webpackConfig({
    module: {

    }
})
